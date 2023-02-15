<?php /**
 * @var \App\Models\Map $model
 */
?>
@if ($model->map || $model->location)
    <div class="entity-header-sub pull-left">
        @if ($model->map)
            <span  class="mr-2">
                <i class="fa-solid fa-map" title="{{ __('entities.map') }}"></i>
                {!! $model->map->tooltipedLink() !!}
            </span>
        @endif

        @if ($model->location)
            <i class="ra ra-tower" title="{{ __('entities.location') }}"></i>
            {!! $model->location->tooltipedLink() !!}
        @endif
    </div>
@endif

