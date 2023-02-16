<?php /** @var \App\Models\Entity $entity
 * @var \App\Models\EntityMention $mention */?>
@extends('layouts.' . (request()->ajax() ? 'ajax' : 'app'), [
    'title' => trans('entities/mentions.show.title', ['name' => $entity->name]),
    'description' => '',
    'breadcrumbs' => [
        ['url' => $entity->url('index'), 'label' => __('entities.' . $entity->pluralType())],
        ['url' => $entity->url('show'), 'label' => $entity->name]
    ]
])
@section('content')
    @if (!request()->ajax()) <div class="box box-default">@endif
    <div class="pagination-ajax-body">
        @if (request()->ajax())
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('crud.delete_modal.close') }}"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                {{ $entity->name }}
            </h4>
        </div>
        @endif
        <div class="modal-body">
            <div class="loading text-center" style="display: none">
                <i class="fa-solid fa-spinner fa-spin fa-4x"></i>
            </div>
            <div class="pagination-ajax-content">
                <p class="help-block">
                    {{ __('entities/mentions.helper') }}
                </p>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __('entities/mentions.fields.element') }}</th>
                        <th>{{ __('entities/mentions.fields.type') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($mentions as $mention)
                        @if ($mention->isCampaign())
                            <tr>
                                <td>
                                    <a href="{{ route('overview', $mention->campaign_id) }}">
                                        {{ $mention->campaign->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ __('entities.campaign') }}
                                </td>
                            </tr>
                        @elseif ($mention->isPost() && $mention->post)
                            @if($mention->post->entity)
                            <tr>
                                <td>
                                    @if ($mention->post->entity->is_private)
                                        <i class="fa-solid fa-lock" data-toggle="tooltip" title="{{ __('crud.is_private') }}"></i>
                                    @endif
                                    <a href="{{ $mention->post->entity->url() }}">{{ $mention->post->entity->name }}</a>
                                        -
                                    {!! $mention->post->visibilityIcon(null, true) !!}
                                    <a href="{{ $mention->post->entity->url('show', ['#post-' . $mention->post->id]) }}">
                                        {{ $mention->post->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ __('entities.post') }}
                                </td>
                            </tr>
                            @endif
                        @elseif ($mention->entity)
                            <tr>
                                <td>
                                    @if ($mention->entity->is_private)
                                        <i class="fa-solid fa-lock" data-toggle="tooltip" title="{{ __('crud.is_private') }}"></i>
                                    @endif
                                    <a href="{{ $mention->entity->url() }}">{{ $mention->entity->name }}</a>
                                </td>
                                <td>
                                    {{ __('entities.' . $mention->entity->type()) }}
                                </td>
                            </tr>
                        @elseif ($mention->isQuestElement() && $mention->questElement)
                            @if(!$mention->questElement->quest || !$mention->questElement->quest->entity)
                                @continue
                            @endif
                            @if ($mention->questElement->entity_id && empty($mention->questElement->entity))
                                @continue
                            @endif
                            <tr>
                                <td>
                                    @if ($mention->questElement->quest->entity->is_private)
                                        <i class="fa-solid fa-lock" data-toggle="tooltip" title="{{ __('crud.is_private') }}"></i>
                                    @endif
                                    <a href="{{ $mention->questElement->entity->url() }}">{{ $mention->questElement->quest->entity->name }}</a>
                                        -
                                    {!! $mention->questElement->visibilityIcon(null, true) !!}
                                    <a href="{{ $mention->questElement->quest->entity->url('quest_elements.index', ['/#quest-element-' . $mention->questElement->id]) }}">
                                        {{ $mention->questElement->name() }}
                                    </a>
                                </td>
                                <td>
                                    {{ __('entities.quest_element') }}
                                </td>
                            </tr>
                        @elseif ($mention->isTimelineElement() && $mention->timelineElement)
                            @if(!$mention->timelineElement->timeline || !$mention->timelineElement->timeline->entity)
                                @continue
                            @endif
                            @if ($mention->timelineElement->entity_id && empty($mention->timelineElement->entity))
                                @continue
                            @endif
                            <tr>
                                <td>
                                    @if ($mention->timelineElement->timeline->entity->is_private)
                                        <i class="fa-solid fa-lock" data-toggle="tooltip" title="{{ __('crud.is_private') }}"></i>
                                    @endif
                                    <a href="{{ $mention->timelineElement->timeline->entity->url() }}">{{ $mention->timelineElement->timeline->entity->name }}</a>
                                        -
                                    {!! $mention->timelineElement->visibilityIcon(null, true) !!}
                                    <a href="{{ $mention->timelineElement->timeline->entity->url('show', ['timeline-element-' . $mention->timelineElement->id]) }}">
                                        {{ $mention->timelineElement->elementName() }}
                                    </a>
                                </td>
                                <td>
                                    {{ __('entities.timeline_element') }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>

                @if (request()->ajax())
                    <div class="pagination-ajax-links">
                        {{ $mentions->links() }}
                    </div>
                @else
                    {{ $mentions->links() }}
                @endif
            </div>
        </div>
    </div>
    @if (!request()->ajax()) </div>@endif
@endsection
