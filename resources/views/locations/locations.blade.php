@extends('layouts.app', [
    'title' => trans('locations.locations.title', ['name' => $model->name]),
    'description' => trans('locations.locations.description'),
    'breadcrumbs' => [
        ['url' => Breadcrumb::index('locations'), 'label' => __('locations.index.title')],
        ['url' => route('locations.show', $model), 'label' => $model->name],
        trans('locations.show.tabs.locations')
    ],
    'canonical' => true,
    'mainTitle' => false,
    'miscModel' => $model,
])

@section('content')
    @include('partials.errors')
    <div class="row entity-grid">
        <div class="col-md-2 entity-sidebar-submenu">
            @include('locations._menu', ['active' => 'locations'])
        </div>
        <div class="col-md-10 entity-main-block">
            @include('locations.panels.locations')
        </div>
    </div>
@endsection
