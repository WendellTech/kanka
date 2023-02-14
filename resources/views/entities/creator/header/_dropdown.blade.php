<li class="@if ($dropType == $type) disabled @endif">
    @if ($dropType == $type)
        <a href="#">
            <i class="fa-solid fa-check" aria-hidden="true"></i>
            {{ $trans }}
        </a>
    @else
    <a href="#" class="" data-toggle="entity-creator" data-url="{{ route('entity-creator.form', ['campaign' => $campaign, 'type' => $dropType, 'mode' => $mode ?? null]) }}" data-entity-type="character" data-type="inline">
        <i class="fa-solid" aria-hidden="true"></i>
        {{ $trans }}
    </a>
    @endif
</li>
