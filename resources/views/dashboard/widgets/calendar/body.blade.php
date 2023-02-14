<?php
/**
 * @var \App\Models\CampaignDashboardWidget $widget
 * @var \App\Models\Entity $entity
 * @var \App\Models\Calendar $calendar
 * @var \App\Models\EntityEvent $event
 * @var \App\Models\EntityEvent $reminder
 */
$entity = $widget->entity;
if (empty($entity)) {
    return;
}
$calendar = $entity->child;

$upcomingEvents = $calendar->upcomingReminders();
$previousEvents = $calendar->pastReminders();
//$previousEvents = new \Illuminate\Support\Collection();

// Get the current day's weather effect.
// Todo: make it a relation that can be queried "with"?
$weather = $calendar->calendarWeather()
    ->year($calendar->currentYear())
    ->month($calendar->currentMonth())
    ->where('day', $calendar->currentDay())
    ->first();


/** @var \App\Models\EntityEvent $event */
?>
<div class="current-date" id="widget-date-{{ $widget->id }}">
    @can('update', $calendar)
        <a href="#" class="widget-calendar-switch" data-url="{{ route('dashboard.calendar.sub', $widget) }}" data-widget="{{ $widget->id }}">
            <i class="fa-solid fa-chevron-circle-left" data-toggle="tooltip" title="{{ __('dashboard.widgets.calendar.actions.previous') }}" ></i>
        </a>
        <span>{{ $calendar->niceDate() }}</span>

        <a href="#" class="widget-calendar-switch" data-url="{{ route('dashboard.calendar.add', $widget) }}" data-widget="{{ $widget->id }}">
            <i class="fa-solid fa-chevron-circle-right" data-toggle="tooltip" title="{{ __('dashboard.widgets.calendar.actions.next') }}" ></i>
        </a>
    @else
        {{ $calendar->niceDate() }}
    @endcan

</div>

@if ($weather)
    <div class="text-center">
        <div class="weather weather-{{ $weather->weather }}" data-html="true" data-toggle="tooltip" title="{!! $weather->tooltip() !!}">
            <i class="fa-solid fa-{{ $weather->weather }}"></i>
            {{ $weather->weatherName() }}
        </div>
    </div>
@endif

<div class="row">
    @if ($previousEvents->isNotEmpty())
        <div class="col-md-12 col-lg-6">
            <h4>
                {{ __('dashboard.widgets.calendar.previous_events') }}
                <a href="//docs.kanka.io/en/latest/guides/dashboard.html#known-limitations" target="_blank">
                    <i class="fa-solid fa-question-circle" data-toggle="tooltip" title="{{ __('helpers.calendar-widget.info') }}"></i>
                </a>
            </h4>
            <ul class="list-unstyled">
                @foreach ($previousEvents->take(5) as $reminder)
                    @if ($reminder->daysAgo() > -1)
                        @if (!$reminder->entity) @continue @endif
                        <li data-ago="{{ $reminder->daysAgo() }}">
                            <div class="pull-right">
                                @if (!empty($reminder->comment))
                                    <i class="fa-solid fa-comment" title="{{ $reminder->comment }}" data-toggle="tooltip" data-placement="bottom"></i>
                                @endif
                                    @if ($reminder->is_recurring)
                                    <i class="fa-solid fa-arrows-rotate" title="{{ __('calendars.fields.is_recurring') }}" data-toggle="tooltip"></i>
                                @endif
                                <i class="fa-solid fa-calendar" title="{{ $reminder->readableDate() }}" data-toggle="tooltip" data-placement="bottom"></i>
                            </div>
                            {{ link_to($reminder->entity->url(), $reminder->entity->name) }}

                            @if (app()->environment('local'))
                                ({{ $reminder->date() }}, {{ $reminder->daysAgo() }} days ago)
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif

    @if ($upcomingEvents->isNotEmpty())
        <div class="col-lg-6 col-md-12">
            <h4>
                {{ __('dashboard.widgets.calendar.upcoming_events') }}
                <a href="//docs.kanka.io/en/latest/guides/dashboard.html#known-limitations" target="_blank">
                    <i class="fa-solid fa-question-circle" data-toggle="tooltip" title="{{ __('helpers.calendar-widget.info') }}"></i>
                </a>
            </h4>
            <ul class="list-unstyled">
                @foreach ($upcomingEvents->take(5) as $reminder)
                    @if ($reminder->inDays() > -1)
                        @if (!$reminder->entity) @continue @endif
                        <li data-in="{{ $reminder->inDays() }}">
                            <div class="pull-right">
                                @if (!empty($reminder->comment))
                                    <i class="fa-solid fa-comment" title="{{ $reminder->comment }}" data-toggle="tooltip" data-placement="bottom"></i>
                                @endif
                                @if ($reminder->is_recurring)
                                    <i class="fa-solid fa-arrows-rotate" title="{{ __('calendars.fields.is_recurring') }}" data-toggle="tooltip"></i>
                                @endif
                                @if ($reminder->isToday($calendar))
                                    <i class="fa-solid fa-calendar-check" data-toggle="tooltip" title="{{ __('calendars.actions.today') }}"></i>
                                @else
                                    <i class="fa-solid fa-calendar" title="{{ $reminder->readableDate() }}" data-toggle="tooltip" data-placement="bottom"></i>
                                @endif
                            </div>
                            {{ link_to($reminder->entity->url(), $reminder->entity->name, ['title' => $reminder->comment, 'data-toggle' => 'tooltip']) }}
                            @if (app()->environment('local'))
                            ({{ $reminder->date() }}, in {{ $reminder->inDays() }} days)
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
</div>
