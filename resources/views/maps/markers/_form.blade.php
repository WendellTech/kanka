<?php

$iconOptions = [
    1 => __('maps/markers.icons.marker'),
    2 => __('maps/markers.icons.question'),
    3 => __('maps/markers.icons.exclamation'),
    4 => __('maps/markers.icons.entity'),
];
//unset($iconOptions['pin']);
//unset($iconOptions['entity']);

$sizeOptions = [
    1 => __('locations.map.points.sizes.tiny'),
    2 => __('locations.map.points.sizes.small'),
    3 => __('locations.map.points.sizes.standard'),
    4 => __('locations.map.points.sizes.large'),
    5 => __('locations.map.points.sizes.huge'),
];
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li role="presentation" @if(!isset($model) || $model->shape_id == 1) class="active" @endif>
            <a href="#marker-pin" data-nohash="true" data-toggle="tooltip" class="text-center" title="{{ __('maps/markers.tabs.marker') }}">
                <i class="fa fa-2x fa-map-pin"></i><br />
                {{ __('maps/markers.tabs.marker') }}
            </a>
        </li>
        <li role="presentation" @if(isset($model) && $model->shape_id == 2) class="active" @endif>
            <a href="#marker-label" data-nohash="true"  data-toggle="tooltip" class="text-center" title="{{ __('maps/markers.tabs.label') }}">
                <i class="fa fa-2x fa-font"></i><br />
                {{ __('maps/markers.tabs.label') }}
            </a>
        </li>
        <li role="presentation" @if(isset($model) && $model->shape_id == 3) class="active" @endif>
            <a href="#marker-circle" data-nohash="true"  data-toggle="tooltip" class="text-center" title="{{ __('maps/markers.tabs.circle') }}">
                <i class="fa fa-2x fa-circle-o"></i><br />
                {{ __('maps/markers.tabs.circle') }}
            </a>
        </li>
        <li role="presentation" @if(isset($model) && $model->shape_id == 5) class="active" @endif>
            <a href="#marker-poly" data-nohash="true"  data-toggle="tooltip" class="text-center" title="{{ __('maps/markers.tabs.polygon') }}">
                <i class="fa fa-2x fa-draw-polygon"></i><br />
                {{ __('maps/markers.tabs.polygon') }}
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane @if(!isset($model) || $model->shape_id == 1) active @endif" id="marker-pin">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label>{{ __('locations.map.points.fields.icon') }}</label>
                        {!! Form::select('icon', $iconOptions, null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label>{{ __('maps/markers.fields.custom_icon') }}</label>
                        @if ($campaign->campaign()->boosted())
                            {!! Form::text('custom_icon', null, ['class' => 'form-control', 'placeholder' => __('maps/markers.placeholders.custom_icon')]) !!}
                            <p class="help-block">{!! __('maps/markers.helpers.custom_icon', ['rpgawesome' => '<a href="https://nagoshiashumari.github.io/Rpg-Awesome/" target="_blank">RPG Awesome</a>', 'fontawesome' => '<a href="https://fontawesome.com/icons?d=gallery" target="_blank">Font Awesome</a>']) !!}</p>
                        @else
                            <p class="help-block">{{ __('crud.errors.boosted') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane @if(isset($model) && $model->shape_id == 2) active @endif" id="marker-label">
            <div class="form-group">
                <label>{{ __('crud.fields.entry') }}</label>
                {!! Form::textarea('entry', null, ['class' => 'form-control html-editor', 'id' => 'marker-entry', 'name' => 'entry']) !!}
            </div>
        </div>
        <div class="tab-pane @if(isset($model) && $model->shape_id == 3) active @endif" id="marker-circle">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label>{{ __('locations.map.points.fields.size') }}</label>
                        {!! Form::select('size_id', $sizeOptions, null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane @if(isset($model) && $model->shape_id == 5) active @endif" id="marker-poly">
            <div class="form-group">
                <label>{{ __('maps/markers.fields.custom_shape') }}</label>
                @if ($campaign->campaign()->boosted())
                    {!! Form::textarea('custom_shape', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('maps/markers.placeholders.custom_shape')]) !!}
                @else
                    <p class="help-block">{{ __('crud.errors.boosted') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="form-group required">
    <label>{{ __('crud.fields.name') }}</label>
    {!! Form::text('name', null, ['placeholder' => __('maps/markers.placeholders.name'), 'class' => 'form-control', 'maxlength' => 191]) !!}
</div>

<div class="form-group">
    <label>{{ __('locations.map.points.fields.colour') }}</label><br />
    {!! Form::text('colour', null, ['class' => 'form-control spectrum', 'maxlength' => 20] ) !!}
</div>

<div class="form-group">
{!! Form::select2(
    'entity_id',
    (isset($model) && $model->entity ? $model->entity : null),
    App\Models\Entity::class,
    false,
    'crud.fields.entity',
    'search.entities-with-reminders',
    'crud.placeholders.entity'
) !!}
</div>


<div class="form-group">
@include('cruds.fields.visibility')
</div>

<div class="row @if (!$model) hidden @endif">
    <div class="col-xs-6">
        <div class="form-group">
            <label>{{ __('maps/markers.fields.latitude') }}</label>
            {!! Form::number('latitude', null, ['placeholder' => __('maps/markers.placeholders.latitude'), 'class' => 'form-control', 'maxlength' => 191, 'id' => 'marker-latitude']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            <label>{{ __('maps/markers.fields.longitude') }}</label>
            {!! Form::number('longitude', null, ['placeholder' => __('maps/markers.placeholders.longitude'), 'class' => 'form-control', 'maxlength' => 191, 'id' => 'marker-longitude']) !!}
        </div>
    </div>
</div>

{!! Form::hidden('shape_id') !!}

@include('editors.editor')
