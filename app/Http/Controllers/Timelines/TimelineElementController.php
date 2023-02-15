<?php

namespace App\Http\Controllers\Timelines;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimelineElement;
use App\Facades\CampaignLocalization;
use App\Services\MultiEditingService;
use App\Models\Timeline;
use App\Models\TimelineElement;
use App\Services\TimelineService;
use Illuminate\Http\Request;

class TimelineElementController extends Controller
{
    /** @var TimelineService  */
    protected $service;

    /** @var string[] Form fields passed on to the model */
    protected $fields = [
        'era_id',
        'entity_id',
        'name',
        'entry',
        'position',
        'colour',
        'date',
        'visibility_id',
        'icon',
        'is_collapsed',
        'use_entity_entry',
        'use_event_date'
    ];

    /**
     * TimelineElementController constructor.
     * @param TimelineService $timelineService
     */
    public function __construct(TimelineService $timelineService)
    {
        $this->service = $timelineService;
    }

    public function show(Timeline $timeline, TimelineElement $timelineElement)
    {
        return redirect()->route('timelines.show', $timeline);
    }
    public function index(Timeline $timeline)
    {
        return redirect()->route('timelines.show', $timeline);
    }

    /**
     * @param Timeline $timeline
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request, Timeline $timeline)
    {
        $this->authorize('update', $timeline);

        $campaign = CampaignLocalization::getCampaign();
        $eraId = $request->get('era_id');
        $position = $request->get('position', 1);

        return view(
            'timelines.elements.create',
            compact('campaign', 'timeline', 'eraId', 'position')
        );
    }

    /**
     * @param Timeline $timeline
     * @param StoreTimelineElement $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Timeline $timeline, StoreTimelineElement $request)
    {
        $this->authorize('update', $timeline);

        // For ajax requests, send back that the validation succeeded, so we can really send the form to be saved.
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        $model = new TimelineElement();
        $data = $request->only($this->fields);
        $data['timeline_id'] = $timeline->id;
        $new = $model->create($data);
        $this->service->reorderElements($new);

        return redirect()
            ->route('timelines.show', [$timeline->id, '#timeline-element-' . $new->id])
            ->withSuccess(__('timelines/elements.create.success', ['name' => $new->name]));
    }

    /**
     * @param Timeline $timeline
     * @param TimelineElement $timelineElement
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Timeline $timeline, TimelineElement $timelineElement)
    {
        $this->authorize('update', $timeline);

        $campaign = CampaignLocalization::getCampaign();
        $editingUsers = null;
        $model = $timelineElement;

        if ($campaign->hasEditingWarning()) {
            /** @var MultiEditingService $editingService */
            $editingService = app()->make(MultiEditingService::class);
            $editingUsers = $editingService->model($model)->user(auth()->user())->users();
            // If no one is editing the model, we are now editing it
            if (empty($editingUsers)) {
                $editingService->edit();
            }
        }

        $ajax = request()->ajax();

        return view(
            'timelines.elements.edit',
            compact(
                'campaign',
                'timeline',
                'ajax',
                'model',
                'editingUsers',
            )
        );
    }

    /**
     * @param StoreTimelineElement $request
     * @param Timeline $timeline
     * @param TimelineElement $timelineElement
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreTimelineElement $request, Timeline $timeline, TimelineElement $timelineElement)
    {
        $this->authorize('update', $timeline);

        /** @var MultiEditingService $editingService */
        $editingService = app()->make(MultiEditingService::class);
        $editingService->model($timelineElement)
            ->user($request->user())
            ->finish();

        // For ajax requests, send back that the validation succeeded, so we can really send the form to be saved.
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        $data = $request->only($this->fields);
        if (!$request->has('entity_id')) {
            $data['entity_id'] = null;
        }
        $timelineElement->update($data);
        $this->service->reorderElements($timelineElement);

        return redirect()
            ->route('timelines.show', [$timeline->id, '#timeline-element-' . $timelineElement->id])
            ->withSuccess(__('timelines/elements.edit.success', ['name' => $timelineElement->name]));
    }

    /**
     * @param Timeline $timeline
     * @param TimelineElement $timelineElement
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Timeline $timeline, TimelineElement $timelineElement)
    {
        $this->authorize('update', $timeline);

        $timelineElement->delete();
        $this->service->reorderElements($timelineElement, true);

        return redirect()
            ->route('timelines.show', [$timeline])
            ->withSuccess(__('timelines/elements.delete.success', ['name' => $timelineElement->name]));
    }
}
