<?php

namespace App\Services\Campaign;

use App\Facades\CampaignCache;
use App\Jobs\Campaigns\Export;
use App\Models\Image;
use App\Notifications\Header;
use App\Traits\CampaignAware;
use App\Traits\UserAware;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Zip;

class ExportService
{
    use CampaignAware;
    use UserAware;

    protected string $exportPath;

    protected string $path;
    protected string $file;

    protected $archive;

    protected bool $assets = false;

    protected int $files = 0;

    public function exportPath(): string
    {
        return $this->exportPath;
    }

    public function assets(bool $assets): self
    {
        $this->assets = $assets;
        return $this;
    }

    public function queue(): self
    {
        $this->campaign->export_date = date('Y-m-d');
        $this->campaign->saveQuietly();

        Export::dispatch($this->campaign, $this->user, false);
        Export::dispatch($this->campaign, $this->user, true);

        return $this;
    }

    public function export(): self
    {
        $this
            ->prepare()
            ->campaignJson()
            ->entities()
            ->gallery()
            ->finish()
            ->notify()
        ;

        return $this;
    }

    protected function prepare(): self
    {
        $this->exportPath = '/exports/campaigns/' . $this->campaign->id . '/';
        $saveFolder = storage_path($this->exportPath);
        File::ensureDirectoryExists($saveFolder);

        // We want the full path for jobs running in the queue.
        $this->file = $this->campaign->id . '_' . date('Ymd_His') . ($this->assets ? '_assets' : null) . '.zip';
        CampaignCache::campaign($this->campaign);
        $this->path = $saveFolder . $this->file;
        $this->archive = Zip::create($this->file);

        return $this;
    }

    protected function campaignJson(): self
    {
        $this->archive->addRaw($this->campaign->toJson(), 'campaign.json');
        $this->files++;
        if (!empty($this->campaign->image) && Storage::exists($this->campaign->image)) {
            $this->archive->add('s3://' . env('AWS_BUCKET') . '/' . Storage::path($this->campaign->image), $this->campaign->image);
            $this->files++;
        }

        return $this;
    }

    protected function entities(): self
    {
        $entityWith = [
            'entity',
            'entity.tags', 'entity.relationships',
            'entity.posts', 'entity.abilities',
            'entity.events',
            'entity.image',
            'entity.header',
            'entity.assets',
            'entity.entityAttributes',
        ];
        if ($this->assets) {
            $entityWith = ['entity'];
        }
        $entities = config('entities.classes-plural');
        foreach ($entities as $entity => $class) {
            if (!$this->campaign->enabled($entity) || !method_exists($class, 'export')) {
                continue;
            }
            try {
                $property = Str::camel($entity);
                foreach ($this->campaign->$property()->with($entityWith)->get() as $model) {
                    $this->process($entity, $model);
                }
            } catch (Exception $e) {
                $saveFolder = storage_path($this->exportPath);
                $this->archive->saveTo($saveFolder);
                unlink($this->path);
                throw new Exception(
                    'Missing campaign entity relation: ' . $entity . '-' . $class . '? '
                    . $e->getMessage()
                );
            }
        }
        return $this;
    }

    protected function gallery(): self
    {
        foreach ($this->campaign->images()->with('imageFolder')->get() as $image) {
            try {
                $this->processImage($image);
            } catch (Exception $e) {
                $saveFolder = storage_path($this->exportPath);
                $this->archive->saveTo($saveFolder);
                unlink($this->path);
                throw new Exception(
                    $e->getMessage()
                );
            }
        }
        return $this;
    }

    protected function processImage(Image $image): self
    {
        if (!$this->assets) {
            $this->archive->add($image->export(), 'gallery/' . $image->id . '.json');
            $this->files++;
            return $this;
        }

        if (!$image->isFolder()) {
            $this->archive->add('s3://' . env('AWS_BUCKET') . '/' . Storage::path($image->path), 'gallery/' . $image->id . '.' . $image->ext);
            $this->files++;
        }
        return $this;
    }

    protected function process(string $entity, $model): self
    {
        if (!$this->assets) {
            $this->archive->add($model->export(), $entity . '/' . Str::slug($model->name) . '.json', );
            $this->files++;
            return $this;
        }

        $path = $model->entity->image_path;
        if (!empty($path) && !Str::contains($path, '?') && Storage::exists($path)) {
            $this->archive->add('s3://' . env('AWS_BUCKET') . '/' . Storage::path($path), $path);
            $this->files++;
        }
        $path = $model->entity->header_image;
        if (!empty($path) && !Str::contains($path, '?') && Storage::exists($path)) {
            $this->archive->add('s3://' . env('AWS_BUCKET') . '/' . Storage::path($path), $path);
            $this->files++;
        }
        return $this;
    }

    protected function notify(): self
    {
        $this->user->notify(new Header(
            'campaign.' . ($this->assets ? 'asset_export' : 'export'),
            'download',
            'green',
            [
                'link' => Storage::url($this->exportPath),
                'time' => 60,
                'campaign' => $this->campaign->name,
            ]
        ));
        return $this;
    }

    protected function finish(): self
    {
        // Save all the content.
        try {
            $saveFolder = storage_path($this->exportPath);
            $this->archive->saveTo($saveFolder);
        } catch (Exception $e) {
            // The export might fail if the zip is too big.
            $this->files = 0;
        }
        if ($this->files === 0) {
            return $this;
        }

        // Move to ?
        $this->exportPath = Storage::putFileAs('exports/campaigns/' . $this->campaign->id, $this->path, $this->file, 'public');
        unlink($this->path);

        return $this;
    }

    /**
     * Track that the export had an issue
     * @return $this
     */
    public function fail(): self
    {
        if (!$this->assets) {
            $this->campaign->updateQuietly([
                'export_date' => null
            ]);
        }

        // Notify the user that something went wrong
        $this->user->notify(new Header(
            $this->assets ? 'campaign.asset_export_error' : 'campaign.export_error',
            'circle-exclamation',
            'red',
            [
                'campaign' => $this->campaign->name,
            ]
        ));

        return $this;
    }
}
