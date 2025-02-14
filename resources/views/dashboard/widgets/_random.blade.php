<?php
/**
 * @var \App\Models\Entity $entity
 * @var \App\Models\CampaignDashboardWidget $widget
 * @var \App\Models\Tag $tag
 */
if (!isset($offset)) {
    $offset = 0;
}

$entity = $widget->randomEntity();

if (empty($entity) || empty($entity->child)) {
    return;
}
\App\Facades\Dashboard::add($entity);
$model = $entity->child;

$specificPreview = 'dashboard.widgets.previews.' . $entity->type();
$customName = !empty($widget->conf('text')) ? str_replace('{name}', $model->name, $widget->conf('text')) : null;
$widget->setEntity($entity);
?>
<x-box padding="0" css="widget-calendar widget-list {{ $widget->customClass($campaign) }}" id="dashboard-widget-{{ $widget->id }}">
@if(view()->exists($specificPreview))
    @include($specificPreview, ['entity' => $entity, 'customName' => $customName])
@else
    <x-widgets.previews.head :widget="$widget" :campaign="$campaign" :entity="$entity" />
    <x-widgets.previews.body :widget="$widget" :campaign="$campaign" :entity="$entity" :model="$model" />
@endif
</x-box>
