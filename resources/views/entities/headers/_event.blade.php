<?php /**
 * @var \App\Models\Event $model
 */
?>
@if ($model->date || $model->event)
    <div class="entity-header-sub pull-left">
        @if($model->event)
        <span title="{{ __('events.fields.event') }}" data-toggle="tooltip" class="mr-2">
        <i class="fa-solid fa-bolt"></i>
        {!! $model->event->tooltipedLink() !!}
        </span>
        @endif

        @if($model->date)
            <span title="{{ __('events.fields.date') }}" data-toggle="tooltip">
                <i class="fa-solid fa-calendar-day"></i> {{ $model->date }}
            </span>
        @endif
    </div>
@endif
