<?php
use Illuminate\Support\Arr;
/**
 * We want to pre-load the data from the model, or what has been sent with the form.
 */
$selectedOption = [];

$model = Arr::get($options, 'model', null);
$source = Arr::get($options, 'source', null);

// Try to load what was sent with the form first, in case there was a form validation error
$previous = old('organisations[]');
if (!empty($previous)) {
    //dd($previous);
}
// If we didn't get anything, and there is a model sent, use that
elseif(!empty($model)) {
    /** @var \App\Models\OrganisationMember $member */
    foreach ($model->organisations as $organisation) {
        $selectedOption[$organisation->id] = strip_tags($organisation->name);
    }
}
?>
<label>{{ __('characters.show.tabs.organisations') }}</label>

<select multiple="multiple" name="organisations[]" class="form-control select2" data-tags="true" style="width: 100%" data-url="{{ route('organisations.find', $campaign) }}" data-allow-clear="true" data-allow-new="false" data-placeholder="">
    @foreach ($selectedOption as $key => $val)
        <option value="{{ $key }}" selected="selected">{{ $val }}</option>
    @endforeach
</select>
