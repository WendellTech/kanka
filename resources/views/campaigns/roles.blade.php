@extends('layouts.app', [
    'title' => trans('campaigns.roles.title', ['name' => $campaign->name]),
    'breadcrumbs' => [
        ['url' => route('overview', $campaign), 'label' => __('entities.campaign')],
        trans('campaigns.show.tabs.roles')
    ],
    'canonical' => true,
    'mainTitle' => false,
])

@section('content')
    @include('partials.errors')
    <div class="row">
        <div class="col-md-3">
            @include('campaigns._menu', ['active' => 'roles'])
        </div>
        <div class="col-md-9">
            @include('campaigns.roles.index')
        </div>
    </div>
@endsection
