<?php

namespace App\Http\Controllers;

use App\Datagrids\Filters\EventFilter;
use App\Http\Requests\StoreEvent;
use App\Models\Campaign;
use App\Models\Event;
use App\Traits\TreeControllerTrait;

class EventController extends CrudController
{
    use TreeControllerTrait;

    /**
     * @var string
     */
    protected string $view = 'events';
    protected string $route = 'events';
    protected $module = 'events';

    /** @var string Model */
    protected $model = \App\Models\Event::class;

    /** @var string Filter */
    protected $filter = EventFilter::class;

    public function store(StoreEvent $request, Campaign $campaign)
    {
        return $this->campaign($campaign)->crudStore($request);
    }

    public function show(Campaign $campaign, Event $event)
    {
        return $this->campaign($campaign)->crudShow($event);
    }

    public function edit(Campaign $campaign, Event $event)
    {
        return $this->campaign($campaign)->crudEdit($event);
    }

    public function update(StoreEvent $request, Campaign $campaign, Event $event)
    {
        return $this->campaign($campaign)->crudUpdate($request, $event);
    }

    public function destroy(Campaign $campaign, Event $event)
    {
        return $this->campaign($campaign)->crudDestroy($event);
    }
}
