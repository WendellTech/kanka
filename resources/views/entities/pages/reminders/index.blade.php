<?php
/** @var \App\Models\Entity $entity */
/** @var \App\Models\EntityEvent $relation */
?>
@extends('layouts.app', [
    'title' => __('entities/events.show.title', ['name' => $entity->name]),
    'breadcrumbs' => false,
    'mainTitle' => false,
    'miscModel' => $entity->child,
    'bodyClass' => 'entity-reminders'
])



@section('entity-header-actions')
    @can('events', $entity->child)
        <div class="header-buttons">
            <a href="{{ route('entities.entity_events.create', [$campaign, $entity, 'next' => 'entity.events']) }}" id="entity-calendar-modal-add"
               class="btn btn-sm btn-warning pull-right" data-toggle="ajax-modal" data-target="#entity-modal"
               data-url="{{ route('entities.entity_events.create', [$campaign, $entity, 'next' => 'entity.events']) }}">
                <i class="fa-solid fa-plus"></i> {{ __('entities/events.show.actions.add') }}
            </a>
        </div>
    @endcan
@endsection


@section('content')
    @include('partials.errors')
    @include('partials.ads.top')

    <div class="entity-grid">
        @include('entities.components.header', [
            'model' => $entity->child,
            'entity' => $entity,
            'breadcrumb' => [
                ['url' => Breadcrumb::index($entity->pluralType()), 'label' => __('entities.' . $entity->pluralType())],
                __('crud.tabs.reminders')
            ]
        ])

        @include($entity->pluralType() . '._menu', [
            'active' => 'reminders',
            'model' => $entity->child,
            'name' => $entity->pluralType()
        ])

        <div class="entity-main-block">
            @if (auth()->check() && !auth()->user()->settings()->get('tutorial_events'))
                <div class="alert alert-info tutorial">
                    <span>
                        <button type="button" class="close banner-notification-dismiss" data-dismiss="alert" aria-hidden="true" data-url="{{ route('settings.banner', ['code' => 'events', 'type' => 'tutorial']) }}">×</button>
                        <p>{{ __('entities/events.helpers.no_events_v2') }}</p>
                        <p>{!!  __('crud.helpers.learn_more', ['documentation' => link_to('https://docs.kanka.io/en/latest/features/reminders.html', '<i class="fa-solid fa-external-link" aria-hidden="true"></i> ' . __('front.menu.documentation'), ['target' => '_blank'], null, false)])!!}</p>
                    </span>
                </div>
            @endif
            @if ($reminders->count() > 0)
                <div class="box box-solid box-entity-reminders">
                    <div class="box-body no-padding">
                        @include('entities.pages.reminders._table')
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
