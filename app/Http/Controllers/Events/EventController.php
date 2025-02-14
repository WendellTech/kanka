<?php

namespace App\Http\Controllers\Events;

use App\Facades\Datagrid;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Event;
use App\Traits\CampaignAware;
use App\Traits\Controllers\HasDatagrid;
use App\Traits\Controllers\HasSubview;
use App\Traits\GuestAuthTrait;

class EventController extends Controller
{
    use CampaignAware;
    use GuestAuthTrait;
    use HasDatagrid;
    use HasSubview;

    public function index(Campaign $campaign, Event $event)
    {
        $this->campaign($campaign)->authView($event);

        $options = ['campaign' => $campaign, 'event' => $event];
        $filters = [];
        if (request()->has('parent_id')) {
            $options['parent_id'] = $event->id;
            $filters['event_id'] = $event->id;
        }
        Datagrid::layout(\App\Renderers\Layouts\Event\Event::class)
            ->route('events.events', $options);

        // @phpstan-ignore-next-line
        $this->rows = $event
            ->descendants()
            ->sort(request()->only(['o', 'k']), ['name' => 'asc'])
            ->with([
                'entity', 'entity.image', 'entity.tags', 'entity.tags.entity',
                'event', 'event.entity'
            ])
            ->has('entity')
            ->filter($filters)
            ->paginate();

        if (request()->ajax()) {
            return $this->campaign($campaign)->datagridAjax();
        }

        return $this
            ->campaign($campaign)
            ->subview('events.events', $event);
    }
}
