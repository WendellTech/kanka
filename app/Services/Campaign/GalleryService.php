<?php

namespace App\Services\Campaign;

use App\Enums\Visibility;
use App\Http\Requests\Campaigns\GalleryImageStore;
use App\Http\Requests\StoreImageFocus;
use App\Models\Image;
use App\Observers\PurifiableTrait;
use App\Traits\CampaignAware;
use App\Traits\UserAware;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GalleryService
{
    use CampaignAware;
    use PurifiableTrait;
    use UserAware;

    protected Image $image;

    protected array $folders = [];
    protected int $used;
    protected int $total;
    protected bool $readable = false;
    protected float $quota;

    public function readable(): self
    {
        $this->readable = true;
        return $this;
    }

    public function image(Image $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function usedQuota(): string
    {
        if (isset($this->quota)) {
            return $this->quota;
        }
        return $this->quota = round($this->usedSpace() / $this->totalSpace(), 3) * 100;
    }

    public function usedBarClasses(): string
    {
        $classes = 'h-2 transition-all duration-300 ';
        if ($this->usedQuota() < 50) {
            return $classes . 'bg-green-500';
        } elseif ($this->usedQuota() < 80) {
            return $classes . 'bg-orange-400';
        }
        return $classes . 'bg-red-500';
    }

    /**
     * Size in mb
     * @return int
     */
    public function usedSpace(): int
    {
        if (isset($this->used)) {
            return $this->used;
        }
        return $this->used = Image::sum('size');
    }

    /**
     * Available space in KB
     * @return int
     */
    public function available(): int
    {
        return $this->totalSpace() - $this->usedSpace();
    }

    public function human(int $value): string
    {
        if ($value > 100000) {
            return floor($value / (1024 * 1024)) . ' GB';
        } elseif ($value > 1000) {
            return floor($value / 1024) . ' MB';
        }
        return $value . ' KB';
    }

    public function storageInfo(): array
    {
        return [
            'percentage' => $this->usedQuota(),
            'used' => $this->human($this->usedSpace()),
            'progress' => $this->usedBarClasses()
            //'total' => $this->human($this->totalSpace())
        ];
    }


    /**
     * Total size in mb
     * @return int
     */
    public function totalSpace(): int
    {
        if ($this->campaign->premium()) {
            return config('limits.gallery.premium');
        }
        return config('limits.gallery.standard');
    }

    public function store(GalleryImageStore $request, string $field = 'file'): array
    {
        $images = [];
        $files = $request->file($field);
        if (!is_array($files)) {
            $files = [$files];
        }
        foreach ($files as $source) {
            // Prepare the name as sent by the user. It gets purified in the observer
            if (empty($source)) {
                continue;
            }
            $name = $source->getClientOriginalName();
            $name = Str::beforeLast($name, '.');

            $image = new Image();
            $image->campaign_id = $this->campaign->id;
            $image->created_by = $request->user()->id;
            $image->id = Str::uuid()->toString();
            $image->ext = $source->extension();
            $image->size = (int) ceil($source->getSize() / 1024); // kb
            $image->name = mb_substr($name, 0, 45);
            $image->folder_id = $request->post('folder_id');
            $image->visibility_id = $this->campaign->defaultVisibilityID();
            $image->save();

            $source
                ->storePubliclyAs(
                    $image->folder,
                    $image->file,
                    ['disk' => 's3']
                );

            $images[] = $image;
        }

        return $images;
    }

    /**
     * @param StoreImageFocus $request
     * @return bool
     */
    public function saveFocusPoint(StoreImageFocus $request): bool
    {
        $this->image->focus_x = (int) $request->post('focus_x');
        $this->image->focus_y = (int) $request->post('focus_y');
        $this->image->save();

        foreach ($this->image->inEntities() as $entity) {
            $entity->clearAvatarCache();
        }

        return $request->filled('focus_x');
    }

    /**
     * @param array $options
     * @return Image
     */
    public function update(array $options): Image
    {
        $this->image->update([
            'name' => Arr::get($options, 'name'),
            'folder_id' => Arr::get($options, 'folder_id', null),
            'visibility_id' => Arr::get($options, 'visibility_id', Visibility::All),
        ]);

        return $this->image;
    }

    /**
     * Create a folder (virtual image)
     * @param Request $request
     */
    public function createFolder(Request $request)
    {
        $folder = new Image();
        $folder->id = Str::uuid();
        $folder->campaign_id = $this->campaign->id;
        $folder->name = $this->purify($request->post('name'));
        $folder->folder_id = $request->post('folder_id');
        $folder->is_folder = true;
        $folder->created_by = $request->user()->id;
        $folder->visibility_id = (int) $request->post('visibility_id');
        $folder->save();

        return $folder;
    }

    /**
     * @return array
     */
    public function folderList(): array
    {
        $this->folders = ['' => __('campaigns/gallery.no_folder')];

        /** @var Image[] $rootFolders */
        $rootFolders = $this->campaign->images()->folders()->whereNull('folder_id')
            ->with('folders')
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
        foreach ($rootFolders as $folder) {
            $this->folders[$folder->id] = $folder->name;
            $this->loopSubfolder($folder, 1);
        }

        return $this->folders;
    }

    /**
     * @param Image $folder
     * @param int $level
     */
    protected function loopSubfolder(Image $folder, int $level)
    {
        $subfolders = $folder->folders;
        foreach ($subfolders as $subfolder) {
            $this->folders[$subfolder->id] = str_repeat('-', $level) . ' ' . $subfolder->name;
            $this->loopSubfolder($subfolder, $level + 1);
        }
    }
}
