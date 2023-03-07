<?php

namespace App\Http\Controllers\Maps;

use App\Datagrids\Filters\MapFilter;
use App\Http\Controllers\CrudController;
use App\Http\Requests\StoreMap;
use App\Models\Campaign;
use App\Models\Map;
use App\Models\MapMarker;
use App\Traits\TreeControllerTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MapController extends CrudController
{
    use TreeControllerTrait;

    /**
     * @var string
     */
    protected string $view = 'maps';
    protected string $route = 'maps';
    protected $module = 'maps';

    /**
     * Crud models
     */
    protected $model = \App\Models\Map::class;

    /** @var string Filter */
    protected $filter = MapFilter::class;

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMap $request, Campaign $campaign)
    {
        return $this->campaign($campaign)->crudStore($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign, Map $map)
    {
        return $this->campaign($campaign)->crudShow($map);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign, Map $map)
    {
        // Can't edit a map being chunked
        if ($map->isChunked() && $map->chunkingRunning()) {
            return response()
                ->redirectTo($map->getLink())
                ->with('error', __('maps.errors.chunking.running.edit') . ' ' . __('maps.errors.chunking.running.time'));
        }
        return $this->campaign($campaign)->crudEdit($map);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreMap $request, Campaign $campaign, Map $map)
    {
        return $this->campaign($campaign)->crudUpdate($request, $map);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign, Map $map)
    {
        return $this->campaign($campaign)->crudDestroy($map);
    }

    /**
     * Exploration view for a map
     */
    public function explore(Campaign $campaign, Map $map)
    {
        // Policies will always fail if they can't resolve the user.
        if (auth()->check()) {
            $this->authorize('view', $map);
        } else {
            $this->authorizeForGuest(\App\Models\CampaignPermission::ACTION_READ, $map);
        }
        if (empty($map->image) && !$map->isReal()) {
            return redirect()->back()->withError(__('maps.errors.explore.missing'));
        }
        if ($map->isChunked()) {
            if ($map->chunkingError()) {
                return redirect()
                    ->route('maps.show', $map->id)
                ;
            } elseif (!$map->chunkingReady()) {
                return redirect()
                    ->route('maps.show', $map->id)
                ;
            }
        }
        return view('maps.explore', compact('campaign', 'map'));
    }

    /**
     * Map ticker for updates to pointers
     */
    public function ticker(Campaign $campaign, Map $map)
    {
        // Policies will always fail if they can't resolve the user.
        if (Auth::check()) {
            $this->authorize('view', $map);
        } else {
            $this->authorizeForGuest(\App\Models\CampaignPermission::ACTION_READ, $map);
        }

        $timestamp = request()->get('ts', time());
        /** @var MapMarker[] $markers */
        $markers = $map->markers()->where('updated_at', '>=', $timestamp)->get();
        $data = [];
        foreach ($markers as $marker) {
            $data[] = [
                'id' => $marker->id,
                'longitude' => $marker->longitude,
                'latitude' => $marker->latitude,
            ];
        }

        return response()->json([
            'ts' => Carbon::now(),
            'markers' => $data,
        ]);
    }

    /**
     * Load only a chunk of the map and cache it for the user
     */
    public function chunks(Campaign $campaign, Map $map)
    {
        $headers = ['Expires', Carbon::now()->addDays(1)->toDateTimeString()];
        if (!request()->has(['z', 'x', 'y'])) {
            return response()
                ->file(public_path('/images/map_chunks/transparent.png'), $headers);
        }

        $path = 'maps/' . $map->id . '/chunks/' . request()->get('z')
            . '/' . request()->get('x') . '_' . request()->get('y')
            . '.png'
        ;

        if (!Storage::exists($path)) {
            return response()
                ->file(public_path('/images/map_chunks/transparent.png'), $headers);
        }

        return redirect()->to(Storage::url($path));
        //return response()
        //    ->file(Storage::path($path), $headers);
    }
}
