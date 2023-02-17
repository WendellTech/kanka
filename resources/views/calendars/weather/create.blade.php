@extends('layouts.' . (request()->ajax() ? 'ajax' : 'app'), [
    'title' => __('calendars/weather.create.title', ['name' => $calendar->name]),
    'breadcrumbs' => [
        ['url' => route('calendars.index', [$campaign]), 'label' => __('entities.calendars')],
        ['url' => $calendar->getLink(), 'label' => $calendar->name],
        __('calendars.show.tabs.weather'),
    ],
    'canonical' => true,
])

@section('content')
    {!! Form::open(['route' => ['calendars.calendar_weather.store', [$campaign->id, $calendar->id]], 'method' => 'POST', 'data-shortcut' => '1']) !!}

    @if (request()->ajax())
        <div class="modal-body">
            @include('partials.errors')
            @include('calendars.weather._form')
        </div>
        <div class="modal-footer">
            <button class="btn btn-success">{{ __('crud.save') }}</button>
            <div class="pull-left">
                @include('partials.footer_cancel')
            </div>
        </div>
    @else
        <div class="panel panel-default">
            <div class="panel-body">
                @include('partials.errors')

                @include('calendars.weather._form')
            </div>
            <div class="panel-footer">
                <div class="pull-right">
                    <button class="btn btn-success">{{ __('crud.save') }}</button>
                </div>
                @include('partials.footer_cancel')
            </div>
        </div>
    @endif

    {!! Form::hidden('year', $year) !!}
    {!! Form::hidden('month', $month) !!}
    {!! Form::hidden('day', $day) !!}
    @if (request()->has('layout'))
        {!! Form::hidden('layout', request()->get('layout')) !!}
    @endif

    {!! Form::close() !!}
@endsection
