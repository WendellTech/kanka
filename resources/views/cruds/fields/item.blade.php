@if (!$campaign->enabled('items'))
    <?php return ?>
@endif

@php
$preset = null;
if (isset($model) && $model->item) {
    $preset = $model->item;
} elseif (isset($parent) && $parent) {
    $preset = FormCopy::field('item')->select(true, \App\Models\Item::class);
} else {
    $preset = FormCopy::field('item')->select();
}

$data = [
    'preset' => $preset,
    'class' => App\Models\Item::class,
];
if (isset($enableNew)) {
    $data['allowNew'] = $enableNew;
}
if (isset($parent) && $parent) {
    $data['labelKey'] = 'items.fields.item';
}
if (isset($dropdownParent)) {
    $data['dropdownParent'] = $dropdownParent;
}
if (isset($from)) {
    $data['from'] = $from;
}
if (isset($quickCreator)) {
    $data['quickCreator'] = $quickCreator;
}
@endphp
<div class="form-group">
    {!! Form::foreignSelect(
        'item_id',
        $data
    ) !!}
</div>
