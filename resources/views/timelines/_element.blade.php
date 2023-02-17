<?php
/**
 * @var \App\Models\Timeline $timeline
 * @var \App\Models\TimelineEra $era
 * @var \App\Models\TimelineElement $element
 */
?>
<li id="timeline-element-{{ $element->id }}">
    {!! $element->htmlIcon() !!}

    <div class="timeline-item">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title cursor-pointer element-toggle {{ $element->collapsed() ? 'collapsed' : null }}" data-toggle="collapse" data-target="#timeline-element-body-{{ $element->id }}">

                    <i class="fa-solid fa-chevron-up icon-show"></i>
                    <i class="fa-solid fa-chevron-down icon-hide"></i>
                    {!! $element->htmlName() !!}
                    @if (isset($element->date) || $element->use_event_date && isset($element->entity->event->date))
                        <span class="text-muted">{{isset($element->entity->event->date) && $element->use_event_date ? $element->entity->event->date : $element->date}}</span>
                    @endif
                    @if($element->entity && $element->entity->is_private)
                        <i class="fa-solid fa-lock" title="{{ __('timelines/elements.helpers.entity_is_private') }}" data-toggle="tooltip" ></i>
                    @endif
                </h3>
                <div class="box-tools">
                    @if (auth()->check()) {!! $element->visibilityIcon('btn-box-tool') !!}@endif

                    @can('update', $timeline)
                        <a class="dropdown-toggle btn btn-box-tool" data-toggle="dropdown" aria-expanded="false" data-placement="right" data-tree="escape">
                            <i class="fa-solid fa-ellipsis-v"></i>
                            <span class="sr-only">{{__('crud.actions.actions') }}'</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li>
                                <a href="{{ route('timelines.timeline_elements.edit', [$campaign, $timeline, $element, 'from' => 'view']) }}" title="{{ __('crud.edit') }}"
                                >
                                    <i class="fa-solid fa-edit"></i> {{ __('crud.edit') }}
                                </a>
                            </li>
                            <li class="text-red">
                                <a href="#" class="delete-confirm" data-toggle="modal" data-name="{{ $element->elementName() }}"
                                   data-target="#delete-confirm" data-delete-target="delete-form-timeline-element-{{ $element->id }}"
                                   title="{{ __('crud.remove') }}">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i> {{ __('crud.remove') }}
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#" title="[timeline:{{ $timeline->entity->id }}|anchor:timeline-element-{{ $element->id }}]" data-toggle="tooltip"
                                   data-clipboard="[timeline:{{ $timeline->entity->id }}|anchor:timeline-element-{{ $element->id }}]" data-toast="{{ __('timelines/elements.copy_mention.success') }}">
                                    <i class="fa-solid fa-link"></i> {{ __('entities/notes.copy_mention.copy') }}
                                </a>
                            </li>
                            @php $mentionName = $element->mentionName() @endphp
                            <li>
                                <a href="#" title="[timeline:{{ $timeline->entity->id }}|anchor:timeline-element-{{ $element->id }}]|{{ $mentionName }}" data-toggle="tooltip"
                                   data-clipboard="[timeline:{{ $timeline->entity->id }}|anchor:timeline-element-{{ $element->id }}|{{ $mentionName }}]" data-toast="{{ __('timelines/elements.copy_mention.success') }}">
                                    <i class="fa-solid fa-link"></i> {{ __('timelines/elements.copy_mention.copy_with_name') }}
                                </a>
                            </li>
                        </ul>
                    @endcan
                </div>
            </div>
            <div class="box-body entity-content collapse {{ $element->collapsed() ? 'out' : 'in' }}" id="timeline-element-body-{{ $element->id }}">
                {!! \App\Facades\Mentions::mapAny($element) !!}

                @if ($element->use_entity_entry && $element->entity && $element->entity->child->hasEntry())
                    <div class="timeline-entity-content">
                        {!! $element->entity->child->entry() !!}
                    </div>
                @endif
            </div>
        </div>
        {!! Form::hidden('element_ids[]', $element->id) !!}
    </div>
</li>
