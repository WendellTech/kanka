<?php
/**
 * View used in Quest and Journals form "calendar" tab
 * @var \App\Models\Journal $model
 */
$calendars = \App\Models\Calendar::get();
$onlyOneCalendar = count($calendars) == 1;
$oldCalendarID = old('calendar_id');
// Make sure the user has access to the source's calendar
if (!empty($source) && $source->calendarReminder()) {
    $oldCalendarID = $source->calendarReminder()->calendar_id;
}
$calendar = null;
if (!empty($oldCalendarID)) {
    $calendar = \App\Models\Calendar::find($oldCalendarID);
}
?>

@if (isset($model) && $model->hasCalendarButNoAccess())
    {!! Form::hidden('calendar_id', $model->calendarReminder()->calendar_id) !!}
    {!! Form::hidden('calendar_skip', true) !!}
    @php return; @endphp
@endif
<div class="form-group">
    <p class="help-block">{{ __('crud.hints.calendar_date') }}</p>

    <a href="#" id="entity-calendar-form-add" class="btn btn-default"
       style="<?=(!empty($model) && $model->hasCalendar() || !empty($oldCalendarID) ? "display: none" : null)?>" data-default-calendar="{{ ($onlyOneCalendar ? $calendars->first()->id : null) }}">
        <i class="ra ra-moon-sun"></i> {{ __('crud.forms.actions.calendar') }}
    </a>

    <div class="entity-calendar-form" style="<?=((!isset($model) || !$model->hasCalendar()) && empty($oldCalendarID) ? "display: none" : null)?>">
        @if (count($calendars) == 1)
            {!! Form::hidden('calendar_id', isset($model) && $model->hasCalendar() ? $model->calendarReminder()->calendar_id : FormCopy::field('calendar_id')->string(), ['id' => 'calendar_id']) !!}
        @else
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group entity-calendar-selector">
                        {!! Form::select2(
                            'calendar_id',
                            (isset($model) && $model->calendarReminder() && $model->calendarReminder()->calendar ? $model->calendarReminder()->calendar : FormCopy::field('calendar')->select()),
                            App\Models\Calendar::class,
                            false
                        ) !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="entity-calendar-subform" style="<?=((!isset($model) || !$model->hasCalendar()) && empty($oldCalendarID) ? "display: none" : null)?>">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label>{{ __('calendars.fields.year') }}</label>
                        {!! Form::number('calendar_year', FormCopy::field('calendar_year')->string(), ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">

                    <div class="form-group">
                        <label>{{ __('calendars.fields.month') }}</label>
                        {!! Form::select(
                            'calendar_month',
                            (!empty($model) && $model->hasCalendar() ? $model->calendarReminder()->calendar->monthList(): (!empty($calendar) ? $calendar->monthList() : [])),
                            FormCopy::field('calendar_month')->string(),
                            ['class' => 'form-control'],
                            (!empty($model) && $model->hasCalendar() ? $model->calendarReminder()->calendar->monthDataProperties(): (!empty($calendar) ? $calendar->monthDataProperties() : []))
                            ) !!}
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label>{{ __('calendars.fields.day') }}</label>
                        {!! Form::select(
                                'calendar_day',
                                (!empty($model) && $model->hasCalendar() ? $model->calendarReminder()->calendar->dayList($model->calendarReminder()->month) : (!empty($calendar) ? $calendar->dayList() : [])),
                                FormCopy::field('calendar_day')->string(),
                                ['class' => 'form-control']
                            ) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label>{{ __('calendars.fields.length') }}</label>
                        {!! Form::number('calendar_length', FormCopy::field('calendar_length')->string(), ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label>{{ __('calendars.fields.colour') }}</label><br />
                        {!! Form::text('calendar_colour', FormCopy::field('calendar_colour')->string(), ['class' => 'form-control spectrum', 'maxlength' => 7]) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label>{{ __('calendars.fields.recurring_periodicity') }}</label>
                         {!! Form::select('calendar_recurring_periodicity', (!empty($model) && $model->hasCalendar() ? $model->calendarReminder()->calendar->recurringOptions(): (!empty($calendar) ? $calendar->recurringOptions() : [])), null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="entity-calendar-loading" style="display: none">
            <p class="text-center">
                <i class="fa-solid fa-spin fa-spinner"></i>
            </p>
        </div>
    </div>

    <a href="#" id="entity-calendar-form-cancel" class="pull-right" style="@if ((((isset($model) && $model->hasCalendar()) || empty($model))) && $onlyOneCalendar) @else display:none @endif">
        {{ __('crud.remove') }}
    </a>
</div>

<input type="hidden" name="calendar-data-url" data-url="{{ route('calendars.month-list', [$campaign, 'calendar' => 0]) }}">
