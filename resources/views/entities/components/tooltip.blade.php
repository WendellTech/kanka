<?php
/**
 * @var \App\Models\Entity $entity
 * @var \App\Models\Campaign $campaign
 * @var \App\Models\Tag $tag
 */
?>
<div class="tooltip-content {{ implode(' ', $tagClasses) }}">
    <div class="entity-tooltip-avatar">
        @if ($campaign->boosted() && $campaign->tooltip_image && $entity->hasImage($campaign->superboosted()))
        <div>
            <div class="entity-image" style="background-image: url('{{ $entity->avatar(true) }} ');"></div>
        </div>
        @endif
        <div class="entity-names">
            <span class="entity-name">{!! $entity->child->name !!}</span>
            @if (method_exists($entity->child, 'tooltipSubtitle'))
                <span class="entity-subtitle">{!! $entity->child->tooltipSubtitle() !!}</span>
            @endif
        </div>
    </div>
    @if ($tags->isNotEmpty())<div class="tooltip-tags">
        @foreach ($tags as $tag)
            @if (!$tag->entity) @continue @endif
            <span class="tooltip-tag" data-id="{{ $tag->entity->id }}" data-tag-slug="{{ $tag->slug }}">
                {!! $tag->html() !!}
            </span>
        @endforeach
    </div>@endif
    <div class="tooltip-text">
    {!! $entity->ajaxTooltip($campaign) !!}
    </div>
</div>
