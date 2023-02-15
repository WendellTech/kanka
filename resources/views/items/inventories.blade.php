@extends('layouts.app', [
    'title' => __('items.inventories.title', ['name' => $model->name]),
    'breadcrumbs' => false,
    'mainTitle' => false,
    'miscModel' => $model,
])



@section('content')
    @include('partials.errors')

    <div class="entity-grid">
        @include('entities.components.header', [
            'model' => $model,
            'breadcrumb' => [
                ['url' => Breadcrumb::index('items'), 'label' => __('entities.items')],
                null
            ]
        ])

        @include('items._menu', ['active' => 'inventories'])

        <div class="entity-main-block">
            @include('items.panels.inventories')
        </div>
    </div>
@endsection
