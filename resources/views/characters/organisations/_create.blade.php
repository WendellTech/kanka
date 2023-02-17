{!! Form::open([
    'route' => ['characters.character_organisations.store', [$model->campaign_id, $model->id]],
    'method'=>'POST',
    'data-shortcut' => '1'
]) !!}

@include('partials.forms.form', [
    'title' => __('characters.organisations.create.title', ['name' => $model->name]),
    'content' => 'characters.organisations._form',
    'submit' => __('characters.organisations.actions.submit')
])

{!! Form::hidden('character_id', $model->id) !!}
{!! Form::close() !!}
