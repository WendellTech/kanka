@extends('layouts.app', [
    'title' => __('timelines/eras.index.title', ['name' => $model->name]),
    'description' => '',
    'breadcrumbs' => false,
    'mainTitle' => false,
    'miscModel' => $model,
])



@section('entity-header-actions')
    @can('update', $model)
        <div class="header-buttons">
            <a href="{{ route('timelines.timeline_eras.create', ['campaign' => $campaign->id, 'timeline' => $model]) }}" class="btn btn-warning btn-sm"
            >
                <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('timelines/eras.actions.add') }}
            </a>
        </div>
    @endcan
@endsection

@section('content')
    @include('partials.errors')

    <div class="entity-grid">
        @include('entities.components.header', [
            'model' => $model,
            'breadcrumb' => [
                ['url' => Breadcrumb::index('timelines'), 'label' => __('entities.timelines')],
                __('timelines.fields.eras')
            ]
        ])
        @include('timelines._menu', ['active' => 'eras'])
        <div class="entity-main-block">
            @include('timelines.panels.eras')
            @includeWhen(false && $rows->count() > 1, 'timelines.eras._reorder')
        </div>
    </div>
@endsection
