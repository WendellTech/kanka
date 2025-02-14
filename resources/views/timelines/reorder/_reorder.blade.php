<?php /** @var \App\Models\TimelineEra[] $eras */?>

@if ($hasNothing)
    <x-alert type="warning">
        <p>{{ __('timelines.reorder.empty') }}</p>
    </x-alert>
    <?php return; ?>
@endif
{!! Form::open([
        'route' => ['timelines.reorder-save', $campaign, $timeline],
        'method' => 'POST',
    ]) !!}
<div class="max-w-4xl box-timeline-reorder flex flex-col gap-5">
    <div class="element-live-reorder sortable-elements flex flex-col gap-5">
        @foreach($eras as $era)
            <div class="element bg-base-200 rounded flex flex-col gap-2 p-2" data-id="{{ $era->id }}">
                {!! Form::hidden('timeline_era[]', $era->id) !!}
                <div class="dragger pr-3">
                    <span class="fa-solid fa-sort"></span>
                </div>
                <div class="name overflow-hidden flex-grow">
                    {!! $era->name !!}
                    <span class="text-xs text-neutral-content">
                        {!! $era->ages()!!}
                    </span>
                </div>

                @if (!$era->orderedElements->isEmpty())
                    <div class="children sortable-elements flex flex-col gap-1">
                    @foreach ($era->orderedElements as $element)
                        @if ($element->invisibleEntity())
                            @continue
                        @endif
                            <x-reorder.child id="element-{{ $element->id }}">
                                {!! Form::hidden('timeline_element[' . $era->id . '][]', $element->id) !!}
                                <div class="dragger relative dragger pr-3 rounded-icon">
                                    {!! $element->htmlIcon(false) !!}
                                </div>
                                <div class="name overflow-hidden flex-grow">
                                    {!! $element->htmlName(false) !!}
                                    @if (isset($element->date))<span class="text-xs text-neutral-content">({{ $element->date }})</span>@endif
                                </div>
                            </x-reorder.child>
                    @endforeach
                </div>
                @endif
            </div>
        @endforeach
    </div>

    <button class="btn2 btn-primary btn-block">
        {{ __('crud.save') }}
    </button>
</div>
{!! Form::close() !!}
