<?php
/**
* @var \App\Models\Image $image
*/
?>

<li tabindex="0" class="pull-left h-36 w-32 p-2 m-2 rounded drop-shadow cursor-pointer flex flex-col items-center justify-center text-center select-none" role="checkbox" aria-label="{{ $image->name }}" aria-checked="false" data-id="{{ $image->id }}"
    data-url="{{ route('images.edit', [$campaign, $image]) }}" @if ($image->is_folder) data-folder="{{ route('gallery.index', [$campaign, 'folder_id' => $image->id]) }}" @endif title="{{ $image->name }}">
    @if ($image->is_folder)
        @if ($image->visibility_id == \App\Models\Visibility::VISIBILITY_ALL)
            <i class="fa-solid fa-folder text-3xl mb-3" aria-hidden="true"></i>
        @else
            {!! $image->visibilityIcon('fa-2x') !!}
        @endif
        <div class="truncate w-full">
            {{ $image->name }}
        </div>
    @else
    <div class="w-full image-preview ">
        <div class="w-25 h-25 inline-block rounded cover-background"
            style="background-image: url('{{ Img::crop(100, 100)->url($image->path) }}')">
        </div>
        <div class="w-full thumbnail-text text-left inline-block truncate">
            {!! $image->visibilityIcon() !!} {{ $image->name }}
        </div>
    </div>
        @endif
</li>
