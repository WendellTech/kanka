<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaigns\DefaultImageDestroy;
use App\Http\Requests\Campaigns\DefaultImageStore;
use App\Models\Campaign;
use App\Services\Campaign\DefaultImageService;
use App\Services\EntityService;

class DefaultImageController extends Controller
{
    protected DefaultImageService $service;

    protected EntityService $entityService;

    public function __construct(EntityService $entityService, DefaultImageService $service)
    {
        $this->middleware('auth', ['except' => 'index']);
        $this->middleware('campaign.boosted', ['except' => 'index']);

        $this->service = $service;
        $this->entityService = $entityService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Campaign $campaign)
    {
        $this->authorize('recover', $campaign);


        return view('campaigns.default-images.index', compact('campaign'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Campaign $campaign)
    {
        $this->authorize('recover', $campaign);
        $ajax = request()->ajax();

        $entities = $this
            ->entityService
            ->labelledEntities(false, $campaign->existingDefaultImages());

        return view('campaigns.default-images.create', compact(
            'campaign',
            'ajax',
            'entities'
        ));
    }

    /**
     * @param DefaultImageStore $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(DefaultImageStore $request, Campaign $campaign)
    {
        $this->authorize('recover', $campaign);

        if ($this->service->campaign($campaign)->type($request->post('entity_type'))->save($request)) {
            return redirect()->route('default-images', $campaign)
                ->with(
                    'success',
                    __('campaigns/default-images.create.success', ['type' => __('entities.' . $request->post('entity_type'))])
                );
        }
        return redirect()->route('default-images', $campaign)
            ->with(
                'error',
                __('campaigns/default-images.create.error', ['type' => __('entities.' . $request->post('entity_type'))])
            );
    }

    /**
     * @param DefaultImageDestroy $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(DefaultImageDestroy $request, Campaign $campaign)
    {
        $this->authorize('recover', $campaign);
        $this->service
            ->campaign($campaign)
            ->type($request->post('entity_type'))
            ->destroy();

        return redirect()->route('default-images', $campaign)
            ->with(
                'success',
                __('campaigns/default-images.destroy.success', ['type' => __('entities.' . $request->post('entity_type'))])
            );
    }
}
