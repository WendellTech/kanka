@extends('layouts.' . (request()->ajax() ? 'ajax' : 'app'), [
    'title' => __('entities/notes.create.title', ['name' => $entity->name]),
    'description' => '',
    'breadcrumbs' => [
        ['url' => route($parentRoute . '.index'), 'label' => __('entities.' . $parentRoute)],
        ['url' => route($parentRoute . '.show', $entity->child->id), 'label' => $entity->name],
        __('entities/notes.actions.add')
    ]
])

@section('fullpage-form')
    {!! Form::open([
    'route' => ['entities.posts.store', $entity->id],
    'method'=>'POST',
    'data-shortcut' => '1',
    'id' => 'entity-form',
    'class' => 'entity-form post-form entity-note-form',
    'data-maintenance' => 1,
    'data-unload' => 1,
    ]) !!}
@endsection

@section('content')
    @include('entities.pages.posts._form')
@endsection

@include('editors.editor')

@section('fullpage-form-end')
    {!! Form::close() !!}
@endsection
