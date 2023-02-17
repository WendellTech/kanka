@if (!request()->ajax())<div id="datagrid-delete-forms">@endif
@foreach($models as $model)
    {!! Form::open(['method' => 'DELETE', 'route' => [$model->url('destroy'), method_exists($model, 'routeParams') ? $model->routeParams($params ?? []) : [$model]], 'style '=> 'display:inline', 'id' => 'delete-form-' . $model->id]) !!}
        @if ($model instanceof \App\Models\Relation)
            {!! Form::hidden('remove_mirrored', 0) !!}
        @endif
        {!! Form::close() !!}
@endforeach
@if (!request()->ajax())</div>@endif
