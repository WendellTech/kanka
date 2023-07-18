<?php
/**
 * @var \App\Models\CampaignDashboardWidget $widget
 * @var \App\Models\MiscModel $model
 * @var \App\Models\Entity $entity
 */
?>
@if(!$campaign->boosted() || !$widget->showMembers($entity))
    @php return @endphp
@endif

@php
$child = null;
if (isset($model)) {
    $child = $model;
} else {
    $child = $entity->child;
}
$members = $entity->isFamily()
    ? $child->members()->orderBy('name')->get()
    : $child->members()->with(['character', 'character.entity'])->get()
;
@endphp

<div class="widget-advanced-members">

@if($entity->isFamily())
    <div class="grid grid-cols-1 gap-2 members">
            <?php /** @var \App\Models\CharacterFamily $member */?>
        @foreach ($members as $member)
            <div class="">{!! $member->tooltipedLink() !!}</div>
        @endforeach
    </div>
@else
    <div class="grid grid-cols-2 gap-2 members">
        <?php /** @var \App\Models\OrganisationMember $member */?>
        @foreach ($members as $member)
            @if (empty($member->character))
                @continue
            @endif
            <div class="font-extrabold" data-role="{{ Illuminate\Support\Str::slug($member->role) }}">{{ $member->role }}</div>
            <div data-status="{{ $member->status_id }}">{!! $member->character->tooltipedLink() !!}</div>
        @endforeach
    </div>
@endif

</div>
