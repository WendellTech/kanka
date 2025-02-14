<?php /** @var \App\Models\Entity $entity
 * @var \App\Models\Inventory $item */?>
<div class="table-responsive">
<table class="table table-striped table-entity-inventory mb-0">
    <thead>
    <tr>
        <th colspan="2">{{ __('entities.item') }}</th>
        <th>{{ __('entities/inventories.fields.qty') }}</th>
        @if (auth()->check())
            <th>
                <i class="fa-solid fa-user-lock" data-title="{{ __('crud.fields.visibility') }}" data-toggle="tooltip" aria-hidden="true"></i>
                <span class="sr-only">{{ __('crud.fields.visibility') }}</span>
            </th>
            <th><br /></th>
        @endif
    </tr>
    </thead>
    <tbody>
    <?php $previousPosition = null; ?>
    @foreach ($inventory as $item)
        @if (!empty($item->item_id) && empty($item->item))
            @continue
        @endif
        @if ($previousPosition != $item->position)
            <tr class="active cursor-pointer" data-animate="collapse" data-target=".inventory-group-{{ \Illuminate\Support\Str::kebab($item->position) }}">
                <th colspan="@if (auth()->check())5 @else 4 @endif" class="text-neutral-content text-left">
                    {!! $item->position ?: '<i>' . __('entities/inventories.show.unsorted') . '</i>' !!}
                </th>
            </tr>
            <?php $previousPosition = $item->position; ?>
        @endif
        <tr class="overflow-hidden inventory-group-{{ \Illuminate\Support\Str::kebab($item->position) }}">
            <td style="width: 50px">
                @if ($item->is_equipped)
                    <i class="fa-solid fa-check" data-title="{{ __('entities/inventories.fields.is_equipped') }}" data-animate="collapse" aria-hidden="true"></i>
                    <span class="sr-only">{{ __('entities/inventories.fields.is_equipped') }}</span>
                @endif
            </td>
            <td>
                @if ($item->item)
                    {!! $item->item->tooltipedLink($item->name) !!}
                @else
                    {!! $item->name !!}
                @endif<br />
                <span class="text-sm text-muted">
                    @if ($item->item && $item->copy_item_entry)
                        {!! $item->item->entry() !!}
                    @else
                    {{ $item->description }}
                    @endif
                </span>
            </td>
            <td>
                {{ number_format($item->amount) }}
            </td>
            @if (auth()->check())
                <td>
                    {!! $item->visibilityIcon() !!}
                </td>
                @can('inventory', $entity->child)
                    <td class="text-right">
                        <a href="{{ route('entities.inventories.edit', [$campaign, $entity, $item]) }}"
                           class="btn2 btn-outline btn-xs"
                           data-toggle="dialog" data-target="inventory-dialog"
                           data-url="{{ route('entities.inventories.edit', [$campaign, $entity, $item]) }}"
                           title="{{ __('crud.edit') }}">
                            <x-icon class="edit"></x-icon> {{ __('crud.edit') }}
                        </a>
                    </td>
                @endcan
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</div>
