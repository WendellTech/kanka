<?php
use Illuminate\Support\Arr;
/**
 * We want to pre-load the data from the model, or what has been sent with the form.
 */
$selectedOption = [];

$model = Arr::get($options, 'model');
$quickCreator = Arr::get($options, 'quickCreator', false);

// Try to load what was sent with the form first, in case there was a form validation error
$previous = old('families[]');
$fieldUniqIdentifier = 'families_' . uniqid();

if (!empty($previous)) {
    //dd($previous);
}
// If we didn't get anything, and there is a model sent, use that
elseif(!empty($model)) {
    /** @var \App\Models\Family $family */
    foreach ($model->families as $family) {
        $selectedOption[$family->id] = strip_tags($family->name);
    }
}

if ($quickCreator) {
    $quickCreator = auth()->user()->can('create', new \App\Models\Family());
}
?>
<label>{{ __('entities.families') }}</label>

@if ($quickCreator)<div class="input-group input-group-sm">@endif

<select multiple="multiple" name="families[]" class="form-control select2" data-tags="true" style="width: 100%" data-url="{{ route('families.find') }}" data-allow-clear="true" data-allow-new="false" data-placeholder="" id="{{ $fieldUniqIdentifier }}">
    @foreach ($selectedOption as $key => $val)
        <option value="{{ $key }}" selected="selected">{{ $val }}</option>
    @endforeach
</select>

@if ($quickCreator)
        <div class="input-group-btn">
            <a class="quick-creator-subform btn btn-tab-form" data-url="{{ route('entity-creator.form', ['campaign' => $campaign, 'type' => 'families', 'origin' => 'entity-form', 'target' => $fieldUniqIdentifier, 'multi' => true]) }}">
                <span class="glyphicon glyphicon-plus"></span>
            </a>
        </div>
    </div>
@endif
