<?php /**
 * @var \App\Models\Journal $model
 */
?>
@if ($model->journal || $model->date)
    <div class="entity-header-sub pull-left">
        @if($model->journal)
        <span data-title="{{ __('crud.fields.parent') }}" data-toggle="tooltip" class="mr-2">
            <x-icon :class="config('entities.icons.journal')"></x-icon>
            {!! $model->journal->tooltipedLink() !!}
        </span>
        @endif

        @if($model->date)
            <span data-title="{{ __('journals.fields.date') }}" data-toggle="tooltip">
                <x-icon class="fa-solid fa-calendar-day"></x-icon>
                {{ $model->date }}
            </span>
        @endif
    </div>
@endif
