<?php
use Illuminate\Support\Arr;
/**
 * We want to pre-load the data from the model, or what has been sent with the form.
 */
$selectedOption = [];

$model = Arr::get($options, 'model');
$enableNew = Arr::get($options, 'enableNew', true);
$enableAutoTags = Arr::get($options, 'enableAutoTags', true);
$label = Arr::get($options, 'label', true);
$filterOptions = Arr::get($options, 'filterOptions', []);
$dropdownParent = Arr::get($options, 'dropdownParent', '#app');
$helper = Arr::get($options, 'helper');
$campaign = Arr::get($options, 'campaign');
if (!is_array($filterOptions)) {
    $filterOptions = [$filterOptions];
}

// Try to load what was sent with the form first, in case there was a form validation error
$previous = old($fieldId);
if (!empty($previous)) {
    //dd($previous);
}
// If we didn't get anything, and there is a model sent, use that
elseif(!empty($model) && !empty($model->entity)) {
    foreach ($model->entity->tags()->with('entity')->has('entity')->get() as $tag) {
        if ($tag->entity) {
            $selectedOption[$tag->id] = $tag;
        }
    }
} elseif(!empty($model) && ($model instanceof \App\Models\CampaignDashboardWidget || $model instanceof \App\Models\MenuLink)) {
    foreach ($model->tags()->get() as $tag) {
        $selectedOption[$tag->id] = $tag;
    }
} elseif (!empty($filterOptions)) {
    foreach ($filterOptions as $tagId) {
        if (!empty($tagId) && is_numeric($tagId)) {
            $tag = \App\Models\Tag::find($tagId);
            if ($tag && $tag->entity) {
                $selectedOption[$tag->id] = $tag;
            }
        }
    }
} elseif (empty($model) && $enableAutoTags) {
    $tags = \App\Models\Tag::autoApplied()->with('entity')->get();
    foreach ($tags as $tag) {
        if ($tag && $tag->entity) {
            $selectedOption[$tag->id] = $tag;
        }
    }
}

?>
@if ($label)
<label>{{ __('entities.tags') }}
@if(!empty($helper))
    <i class="fa-solid fa-question-circle hidden-xs hidden-sm" data-toggle="tooltip" title="{{ $helper }}"></i>
@endif
</label>
@endif

<select multiple="multiple" name="tags[]" id="{{ Arr::get($options, 'id', 'tags_' . uniqid() . '') }}"
        class="form-control form-tags" style="width: 100%"
        data-url="{{ route('tags.find', $campaign) }}" data-allow-new="{{ $enableNew ? 'true' : 'false' }}"
        data-allow-clear="{{ Arr::get($options, 'allowClear', 'true') }}" data-new-tag="{{ __('tags.new_tag') }}"
        data-placeholder="" @if (!empty($dropdownParent)) data-dropdown-parent="{{ $dropdownParent }}" @endif
>
    @foreach ($selectedOption as $key => $tag)
        <option value="{{ $key }}" data-colour="{{ $tag->colourClass() }}" selected="selected">{{ $tag->name }}</option>
    @endforeach
</select>
