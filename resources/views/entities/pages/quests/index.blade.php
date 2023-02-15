<?php
/**
 * @var \App\Models\Entity $entity
 * @var \App\Models\QuestElement $element
 */?>
@extends('layouts.' . ($ajax ? 'ajax' : 'app'), [
    'title' => __('entities/quests.title', ['name' => $entity->name]),
    'description' => '',
    'breadcrumbs' => [
        ['url' => Breadcrumb::index($entity->pluralType()), 'label' => __('entities.' . $entity->pluralType())],
        ['url' => $entity->url('show'), 'label' => $entity->name],
        __('entities.quests')
    ],
    'mainTitle' => false,
    'canonical' => true,
    'miscModel' => $entity->child,
    'bodyClass' => 'entity-quests'
])



@include('entities.components.header', ['model' => $entity->child, 'entity' => $entity])

@section('content')
    @include('partials.errors')


    <div class="entity-grid">
        @include('entities.components.header', [
            'model' => $entity->child,
            'entity' => $entity,
            'breadcrumb' => [
                ['url' => Breadcrumb::index($entity->pluralType()), 'label' => __('entities.' . $entity->pluralType())],
                __('entities.quests')
            ]
        ])

        @include($entity->pluralType() . '._menu', [
            'active' => 'quests',
            'model' => $entity->child,
            'name' => $entity->pluralType()
        ])

        <div class="entity-main-block">
            <div class="box box-solid box-entity-quests">
                <div class="box-header">
                    <h3 class="box-title">
                        {{ __('entities/quests.title', ['name' => $entity->name]) }}
                    </h3>
                </div>
                <div class="box-body">

                    <p class="help-block">{{ __('entities/quests.helper') }}</p>

                    <table id="entity-quests" class="table table-hover">
                        <tbody><tr>
                            <th class="w-14"></th>
                            <th>{{ __('quests.elements.fields.quest') }}</th>
                            <th>{{ __('quests.fields.role') }}</th>
                            <th>{{ __('quests.fields.type') }}</th>
                            <th>{{ __('quests.fields.is_completed') }}</th>
                        </tr>
                        @foreach ($quests as $element)
                            <tr>
                                <td>
                                    <a class="entity-image" style="background-image: url('{{ $element->quest->thumbnail() }}');" title="{{ $element->quest->name }}" href="{{ route('quests.show', $element->quest_id) }}"></a>
                                </td>
                                <td>
                                    {!! $element->quest->tooltipedLink() !!}
                                </td>
                                <td>
                                    {{ $element->role }}
                                </td>
                                <td>
                                    {{ $element->quest->type }}
                                </td>
                                <td>
                                    @if($element->quest->is_completed)
                                        <i class="fa-solid fa-check-circle"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($quests->hasPages())
                    <div class="box-footer text-right">
                        {{ $quests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
