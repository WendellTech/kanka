<?php /** @var \App\Models\Inventory $inventory */?>
{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <input type="hidden" name="item_id" value="" />
            {!! Form::select2(
                'item_id',
                (!empty($inventory) && $inventory->item ? $inventory->item: null),
                App\Models\Item::class,
                false,
                'entities.item',
                'items.find',
                'crud.placeholders.item',
                null,
                request()->ajax() ? '#entity-modal' : null
            ) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            <label>{{ __('entities/inventories.fields.name') }}</label>
            {!! Form::text(
                'name',
                null,
                [
                    'placeholder' => __('entities/inventories.placeholders.name'),
                    'class' => 'form-control',
                    'max-length' => 45
                ]
            ) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label>{{ __('entities/inventories.fields.amount') }}</label>
            {!! Form::number('amount', (empty($inventory) ? 1 : null), ['class' => 'form-control', 'max' => 1000000000, 'min' => 0, 'required']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ __('entities/inventories.fields.position') }}</label>
            {!! Form::text('position', null, [
                'placeholder' => __('entities/inventories.placeholders.position'),
                'class' => 'form-control',
                'maxlength' => 191,
                'list' => 'position-list',
                'autocomplete' => 'off'
            ]) !!}
        </div>
    </div>
</div>
<div class="hidden">
    <datalist id="position-list">
        @foreach (\App\Models\Inventory::positionList($campaign) as $name)
            <option value="{{ e($name) }}">{{ e($name) }}</option>
        @endforeach
    </datalist>
</div>

<div class="form-group">
    <label>{{ __('entities/inventories.fields.description') }}</label>
    {!! Form::text('description', null, ['placeholder' => __('entities/inventories.placeholders.description'), 'class' => 'form-control', 'maxlength' => 191]) !!}
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group checkbox">
            <label>
                {!! Form::hidden('copy_item_entry', 0) !!}
                {!! Form::checkbox('copy_item_entry') !!}
                {{ __('entities/inventories.fields.copy_entity_entry') }}
                <i class="fa-solid fa-question-circle hidden-xs hidden-sm" title="{{ __('entities/inventories.helpers.copy_entity_entry') }}" data-toggle="tooltip"></i>
            </label>
            <p class="help-block visible-xs visible-sm">
                {{ __('entities/inventories.helpers.copy_entity_entry') }}
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group checkbox">
            {!! Form::hidden('is_equipped', 0) !!}
            <label>
                {!! Form::checkbox('is_equipped', 1, isset($inventory) ? $inventory->is_equipped : null) !!}
                {{ __('entities/inventories.fields.is_equipped') }}
            </label>
        </div>
    </div>
</div>

@include('cruds.fields.visibility_id', ['model' => $inventory ?? null])

