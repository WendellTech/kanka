<?php /** @var \App\Models\MiscModel $model */ ?>
@if ($model->hasCalendar() && $model->calendar)
    <p>
        <b><i class="ra ra-moon-sun"></i> {{ __('crud.fields.calendar_date') }}</b><br />

            <a href="{{ route('calendars.show', ['campaign' => $campaign->id, 'calendar' => $model->calendar_id, 'year' => $model->calendar_year, 'month' => $model->calendar_month]) }}">{{ $model->calendar->name }}</a>
            <span class="pull-right">{{ \App\Facades\UserDate::format($model->getDate()) }}</span>
    </p>
@endif
