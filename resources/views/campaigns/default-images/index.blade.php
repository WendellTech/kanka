<?php /** @var \App\Models\Campaign $campaign */?>
@extends('layouts.app', [
    'title' => __('campaigns.show.tabs.default-images') . ' - ' . $campaign->name,
    'breadcrumbs' => [
        __('campaigns.show.tabs.default-images')
    ],
    'mainTitle' => false,
    'sidebar' => 'campaign',
    'centered' => true,
])

@section('content')

    <div class="flex gap-5 flex-col">
        @include('partials.errors')
        <div class="flex gap-2 items-center">
            <h3 class="inline-block grow">
                {{ __('campaigns.show.tabs.default-images') }}
            </h3>
            @if ($campaign->boosted())
                <a href="https://docs.kanka.io/en/latest/features/campaigns/default-thumbnails.html" class="btn2 btn-sm btn-ghost" target="_blank">
                    <x-icon class="question"></x-icon>
                    {{ __('crud.actions.help') }}
                </a>

                @can('recover', $campaign)
                <a href="{{ route('campaign.default-images.create', $campaign) }}" class="btn2 btn-primary btn-sm"
                   data-toggle="dialog-ajax" data-target="new-thumbnail"
                   data-url="{{ route('campaign.default-images.create', $campaign) }}">
                    <x-icon class="plus"></x-icon>
                    {{ __('campaigns/default-images.actions.add') }}
                </a>
                @endif
            @endif
        </div>
        @if ($campaign->boosted())
            @if (empty($campaign->defaultImages()))
                <x-box>
                    <a href="{{ route('campaign.default-images.create', $campaign) }}" class="btn2 btn-primary"
                       data-toggle="dialog-ajax" data-target="new-thumbnail"
                       data-url="{{ route('campaign.default-images.create', $campaign) }}">
                        <x-icon class="plus"></x-icon>
                        {{ __('campaigns/default-images.actions.add') }}
                    </a>
                </x-box>
            @endif
            <div class="grid grid-cols-2 sm:grid-cols-1 gap-4 md:grid-cols-2 md:gap-5">

                @foreach ($campaign->defaultImages() as $image)
                    <div class="rounded overflow-hidden border flex gap-2 items-center bg-box">
                        <div class="flex-initial w-24 h-24 cover-background" style="background-image: url('{{ Img::crop(96, 96)->url($image['path']) }}')">
                        </div>
                        <div class="grow">
                            {!! \App\Facades\Module::singular($image['type'], __('entities.' . $image['type'])) !!}
                        </div>
                        @can('recover', $campaign)
                        <div class="mr-2">
                        <x-button.delete-confirm size="sm" target="#delete-thumb-{{ $image['uuid'] }}" />
                        </div>
                        {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => [
                                        'campaign.default-images.delete',
                                        $campaign
                                    ],
                                    'class' => 'hidden',
                                    'id' => 'delete-thumb-' . $image['uuid']
                                ]) !!}
                        {!! Form::hidden('entity_type', $image['type']) !!}
                        {!! Form::close() !!}
                        @endcan
                    </div>

                @endforeach

            </div>
        @else
            <x-cta :campaign="$campaign">
                <p>{{ __('campaigns/default-images.call-to-action') }}</p>
            </x-cta>
        @endif
    </div>
@endsection

@section('modals')
    @parent
    <x-dialog id="new-thumbnail" :loading="true" />
@endsection
