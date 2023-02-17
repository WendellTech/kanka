<?php
/**
* @var \App\Models\Map $map
* @var \App\Models\MapLayer $model
*/
?>
@extends('layouts.' . (request()->ajax() ? 'ajax' : 'app'), [
    'title' => __('maps/layers.create.title', ['name' => $map->name]),
    'description' => '',
    'breadcrumbs' => [
        ['url' => $map->entity->url('index'), 'label' => __('entities.maps')],
        ['url' => $map->entity->url(), 'label' => $map->name],
        ['url' => $map->entity->url('map_layers.index'), 'label' => __('maps.panels.layers')],
        __('maps/layers.create.title')
    ]
])

@section('content')
    {!! Form::open(['route' => ['maps.map_layers.store', $campaign, $map], 'method' => 'POST', 'id' => 'map-layer-form', 'enctype' => 'multipart/form-data', 'data-maintenance' => 1]) !!}

        <div class="panel panel-default">

            <div class="panel-body">
                @include('partials.errors')

                @include('maps.layers._form', ['model' => null])
            </div>

            <div class="panel-footer text-right">
                @include('maps.groups._actions')
                <div class="pull-left">
                    @include('partials.footer_cancel')
                </div>
            </div>
    </div>

    {!! Form::close() !!}
@endsection
