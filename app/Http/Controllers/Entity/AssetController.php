<?php

namespace App\Http\Controllers\Entity;

use App\Exceptions\EntityFileException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntityAsset;
use App\Models\Campaign;
use App\Models\Entity;
use App\Models\EntityAsset;
use App\Services\EntityFileService;
use App\Traits\CampaignAware;
use App\Traits\GuestAuthTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    use CampaignAware;
    /**
     * Guest Auth Trait
     */
    use GuestAuthTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Entity $entity
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Campaign $campaign, Entity $entity)
    {
        // Policies will always fail if they can't resolve the user.
        if (auth()->check()) {
            $this->authorize('view', $entity->child);
        } else {
            $this->authorizeEntityForGuest(\App\Models\CampaignPermission::ACTION_READ, $entity->child);
        }

        $assets = $entity->assets;

        return view('entities.pages.assets.index', compact(
            'campaign',
            'entity',
            'assets'
        ));
    }

    /**
     * No unique "show", redirect
     * @param Entity $entity
     * @param EntityAsset $entityAsset
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Campaign $campaign, Entity $entity, EntityAsset $entityAsset)
    {
        return redirect()->route('entities.entity_assets.index', [$campaign, $entity]);
    }

    public function create(Campaign $campaign, Entity $entity)
    {
        $this->authorize('update', $entity->child);
        $this->campaign($campaign);

        $typeID = (int) request()->get('type');
        if ($typeID == EntityAsset::TYPE_FILE) {
            return $this->createFile($entity);
        } elseif ($typeID == EntityAsset::TYPE_LINK) {
            return $this->createLink($entity);
        } elseif ($typeID == EntityAsset::TYPE_ALIAS) {
            return $this->createAlias($entity);
        }
        abort(404);
    }

    public function store(StoreEntityAsset $request, Campaign $campaign, Entity $entity)
    {
        $this->authorize('update', $entity->child);

        $this->campaign($campaign);
        $data = [];
        $type = '';
        $typeId = null;
        if (request()->get('type_id') == EntityAsset::TYPE_FILE) {
            return $this->storeFile($request, $entity);
        } elseif (request()->get('type_id') == EntityAsset::TYPE_LINK) {
            $data = $request->only(['name', 'position', 'visibility_id', 'metadata']);
            $type = 'links';
            $typeId = EntityAsset::TYPE_LINK;
        } elseif (request()->get('type_id') == EntityAsset::TYPE_ALIAS) {
            $typeId = EntityAsset::TYPE_ALIAS;
            $data = $request->only(['name', 'visibility_id']);
            $type = 'aliases';
        }
        $data['entity_id'] = $entity->id;
        $data['type_id'] = $typeId;

        $asset = EntityAsset::create($data);

        return redirect()
            ->route('entities.entity_assets.index', [$campaign, $entity])
            ->with('success', __(
                'entities/' . $type . '.create.success',
                ['name' => $asset->name, 'entity' => $entity->name]
            ));
    }

    protected function storeFile(StoreEntityAsset $request, Entity $entity)
    {
        /** @var EntityFileService $service */
        $service = app()->make(EntityFileService::class);

        try {
            $file = $service
                ->entity($entity)
                ->campaign($this->campaign)
                ->upload($request);

            return redirect()
                ->route('entities.entity_assets.index', [$this->campaign, $entity])
                ->with('success', __('entities/files.create.success', ['file' => $file->name]));
        } catch (EntityFileException $e) {
            return redirect()
                ->route('entities.entity_assets.index', [$this->campaign, $entity])
                ->with('error', __('crud.files.errors.' . $e->getMessage(), ['max' => $this->campaign->maxEntityFiles()]));
        } catch (\Exception $e) {
            return redirect()
                ->route('entities.entity_assets.index', [$this->campaign, $entity])
                ->with('error', $e->getMessage());
        }
    }

    public function edit(Campaign $campaign, Entity $entity, EntityAsset $entityAsset)
    {
        $this->authorize('update', $entity->child);

        $file = 'files';
        if ($entityAsset->isLink()) {
            $file = 'links';
        } elseif ($entityAsset->isAlias()) {
            $file = 'aliases';
        }

        return view('entities.pages.' . $file . '.update')
            ->with('campaign', $campaign)
            ->with('entity', $entity)
            ->with('entityAsset', $entityAsset);
    }

    public function update(StoreEntityAsset $request, Campaign $campaign, Entity $entity, EntityAsset $entityAsset)
    {
        $this->authorize('update', $entity->child);
        $this->campaign($campaign);

        $type = 'files';
        if ($entityAsset->isAlias()) {
            $data = $request->only(['name', 'visibility_id']);
            $entityAsset->update($data);
            $type = 'aliases';
        } elseif ($entityAsset->isLink()) {
            $data = $request->only(['name', 'metadata.url', 'metadata.icon', 'visibility_id']);
            $entityAsset->update($data);
            $type = 'links';
        } elseif ($entityAsset->isFile()) {
            $data = $request->only(['name', 'visibility_id', 'is_pinned']);
            $entityAsset->update($data);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }
        return redirect()
            ->route('entities.entity_assets.index', [$campaign, $entity])
            ->with('success', __('entities/' . $type . '.update.success', ['name' => $entityAsset->name, 'entity' => $entity->name]));
    }


    public function destroy(Request $request, Campaign $campaign, Entity $entity, EntityAsset $entityAsset)
    {
        $this->authorize('update', $entity->child);

        if (!$entityAsset->delete()) {
            abort(500);
        }
        $type = 'files';
        if ($entityAsset->isLink()) {
            $type = 'links';
        } elseif ($entityAsset->isAlias()) {
            $type = 'aliases';
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }
        return redirect()
            ->route('entities.entity_assets.index', [$campaign, $entity])
            ->with('success', __('entities/' . $type . '.destroy.success', ['name' => $entityAsset->name, 'entity' => $entity->name]));
    }

    /**
     * Create a new file
     * @param Entity $entity
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    protected function createFile(Entity $entity)
    {
        $max = $this->campaign->maxEntityFiles();
        if ($entity->files->count() >= $max) {
            return view('entities.pages.files.max')
                ->with('campaign', $this->campaign)
                ->with('max', $max);
        }

        return view('entities.pages.files.create')
            ->with('campaign', $this->campaign)
            ->with('entity', $entity);
    }

    /**
     * Create a new link
     * @param Entity $entity
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    protected function createLink(Entity $entity)
    {
        if (!$this->campaign->boosted()) {
            return view('entities.pages.links.unboosted')
                ->with('campaign', $this->campaign);
        }

        return view('entities.pages.links.create', compact(
            'entity'
        ))
            ->with('campaign', $this->campaign);
    }

    /**
     * Create a new alias
     * @param Entity $entity
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    protected function createAlias(Entity $entity)
    {
        if (!$this->campaign->boosted()) {
            return view('entities.pages.aliases.unboosted')
                ->with('campaign', $this->campaign);
        }

        return view('entities.pages.aliases.create', compact(
            'entity'
        ))
            ->with('campaign', $this->campaign);
    }


    /**
     * @param Entity $entity
     * @param EntityAsset $entityAsset
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function go(Campaign $campaign, Entity $entity, EntityAsset $entityAsset)
    {
        // Policies will always fail if they can't resolve the user.
        if (auth()->check()) {
            $this->authorize('view', $entity->child);
        } else {
            $this->authorizeEntityForGuest(\App\Models\CampaignPermission::ACTION_READ, $entity->child);
        }

        if ($entityAsset->entity_id !== $entity->id || !$entityAsset->isLink()) {
            abort(404);
        }

        // If the link goes to the same domain, just go.
        $url = $entityAsset->metadata['url'];
        if (Str::startsWith($url, config('app.url')) && !Str::contains($url, 'entity_links/')) {
            return redirect()->to($url);
        }

        // If the domain is trusted for the user, we don't need the confirmation, just go
        $trusted = Cookie::get('kanka_trusted_domains');
        if ($trusted) {
            $domains = explode('|', $trusted);
            if (in_array($entityAsset->urlDomain(), $domains)) {
                return redirect()->to($url);
            }
        }

        return view('entities.pages.links.go', compact(
            'campaign',
            'entity',
            'entityAsset'
        ));
    }
}
