<?php
$role = \App\Facades\CampaignCache::adminRole();
?>
<div class="row">
    <div class="col-md-6">
        <label>{{ __('characters.sections.appearance') }}</label>
        <div class="character-appearance sortable-elements" data-handle=".input-group-addon">
            @foreach ((isset($model) ? $model->characterTraits()->appearance()->orderBy('default_order', 'ASC')->get() : FormCopy::characterAppearance()) as $trait)
                <div class="form-group parent-delete-row">
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="fa-solid fa-arrows-alt-v"></span>
                                </span>
                                {!! Form::text('appearance_name[' . $trait->id . ']', $trait->name, [
                                    'class' => 'form-control',
                                    'maxlength' => 191,
                                    'placeholder' => __('characters.placeholders.appearance_name'),
                                    'spellcheck' => 'true'
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-8 col-xs-8">
                            <div class="input-group">
                                {!! Form::text('appearance_entry[' . $trait->id . ']', $trait->entry, [
                                    'class' => 'form-control',
                                    'placeholder' => __('characters.placeholders.appearance_entry'),
                                    'spellcheck' => 'true'
                                ]) !!}
                                <span class="input-group-btn">
                                    <span class="dynamic-row-delete btn btn-danger" title="{{ __('crud.remove') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="btn btn-default btn-block dynamic-row-add" data-template="template_appearance" data-target="character-appearance" href="#" title="{{ __('characters.actions.add_appearance') }}">
            <i class="fa-solid fa-plus"></i> {{ __('characters.actions.add_appearance') }}
        </a>

        <div class="form-group checkbox">
            {!! Form::hidden('is_appearance_pinned', 0) !!}
            <label>
                {!! Form::checkbox('is_appearance_pinned', 1, (!empty($model) ? $model->is_appearance_pinned : (!empty($source) ? FormCopy::field('is_appearance_pinned')->boolean() : null))) !!}
                {{ __('characters.fields.is_appearance_pinned') }}
            </label>
            <p class="help-block">
                {{ __('characters.hints.is_appearance_pinned') }}
            </p>
        </div>

        <div class="form-group"><br /></div>
    </div>
    <div class="col-md-6">
        <label>{{ __('characters.sections.personality') }}</label>
        @if (!isset($model) || auth()->user()->can('personality', $model))
            <div class="character-personality sortable-elements" data-handle=".input-group-addon">
                @foreach ((isset($model) ? $model->characterTraits()->personality()->orderBy('default_order', 'ASC')->get() : FormCopy::characterPersonality()) as $trait)
                    <div class="parent-delete-row mb-5">
                        <div class="form-group">
                            <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa-solid fa-arrows-alt-v"></span>
                            </span>
                                {!! Form::text('personality_name[' . $trait->id . ']', $trait->name, [
                                    'class' => 'form-control',
                                    'placeholder' => __('characters.placeholders.personality_name'),
                                    'spellcheck' => 'true'
                                ]) !!}
                                <span class="input-group-btn">
                                <span class="dynamic-row-delete btn btn-danger" title="{{ __('crud.remove') }}">
                                    <i class="fa-solid fa-trash"></i>
                                </span>
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::textarea('personality_entry[' . $trait->id . ']', $trait->entry, [
                                'class' => 'form-control',
                                'placeholder' => __('characters.placeholders.personality_entry'),
                                'spellcheck' => 'true',
                                'rows' => 3
                            ]) !!}
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="btn btn-default btn-block dynamic-row-add" data-template="template_personality" data-target="character-personality" href="#" title="{{ __('characters.actions.add_personality') }}">
                <i class="fa-solid fa-plus"></i> {{ __('characters.actions.add_personality') }}
            </a>


            <div class="form-group checkbox">
                {!! Form::hidden('is_personality_pinned', 0) !!}
                <label>
                    {!! Form::checkbox('is_personality_pinned', 1, (!empty($model) ? $model->is_personality_pinned : (!empty($source) ? FormCopy::field('is_personality_pinned')->boolean() : null))) !!}
                    {{ __('characters.fields.is_personality_pinned') }}
                </label>
                <p class="help-block">
                    {{ __('characters.hints.is_personality_pinned') }}
                </p>
            </div>

        @if (auth()->user()->isAdmin())
                <hr>
                {!! Form::hidden('is_personality_visible', 0) !!}
                <div class="form-group checkbox">
                    <label>{!! Form::checkbox('is_personality_visible', 1, (!empty($model) ? $model->is_personality_visible : (!empty($source) ? FormCopy::field('is_personality_visible')->boolean() : !$campaign->entity_personality_visibility))) !!}
                        {{ __('characters.fields.is_personality_visible') }}
                    </label>
                    <p class="help-block">
                        {!! __('characters.hints.is_personality_visible', [
        'admin' => link_to_route('campaign_roles.admin', \Illuminate\Support\Arr::get($role, 'name', __('campaigns.roles.admin_role')), $campaign, ['target' => '_blank'])
]) !!}
                    </p>
                </div>
            @else
                {!! Form::hidden('is_personality_visible', 1) !!}
            @endif
        @else
            <p class="alert alert-warning">{{ __('characters.warnings.personality_hidden') }}</p>
        @endif
    </div>
</div>


@section('modals')
    @parent
    <div class="hidden">
        <div id="template_appearance">
            <div class="form-group parent-delete-row">
                <div class="row">
                    <div class="col-md-4 col-xs-4">
                        <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="fa-solid fa-arrows-alt-v"></span>
                                </span>
                            {!! Form::text('appearance_name[]', null, [
                                'class' => 'form-control',
                                'placeholder' => __('characters.placeholders.appearance_name'),
                                'spellcheck' => 'true'
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-8 col-xs-8">
                        <div class="input-group">
                            {!! Form::text('appearance_entry[]', null, [
                                'class' => 'form-control',
                                'placeholder' => __('characters.placeholders.appearance_entry'),
                                'spellcheck' => 'true'
                            ]) !!}
                            <span class="input-group-btn">
                                    <span class="dynamic-row-delete btn btn-danger" title="{{ __('crud.remove') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </span>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="template_personality">
            <div class="parent-delete-row mb-5">
                <div class="form-group">
                    <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="fa-solid fa-arrows-alt-v"></span>
                                </span>
                        {!! Form::text('personality_name[]', null, [
                            'class' => 'form-control',
                            'placeholder' => __('characters.placeholders.personality_name'),
                            'spellcheck' => 'true'
                        ]) !!}
                        <span class="input-group-btn">
                                    <span class="dynamic-row-delete btn btn-danger" title="{{ __('crud.remove') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </span>
                                </span>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::textarea('personality_entry[]', null, [
                        'class' => 'form-control',
                        'placeholder' => __('characters.placeholders.personality_entry'),
                        'spellcheck' => 'true',
                        'rows' => 3
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

@endsection
