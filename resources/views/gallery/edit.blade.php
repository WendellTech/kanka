<?php /** @var \App\Models\Image $image */
$imageCount = 0;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('campaigns/gallery.actions.close') }}"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">{{ __('campaigns/gallery.update.title') }}</h4>
</div>
<div class="modal-body panel-image-edit">

    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
            @if($image->is_folder)
                <i class="fa-solid fa-folder fa-4x"></i>
            @else
                <div class="text-center">
                    <img src="{{ Img::crop(300, 300)->url($image->path) }}" class="max-w-full rounded" alt="{{ $image->name }}" />
                </div>

                <hr />
                <p>
                    {{ trans_choice('campaigns/gallery.fields.image_used_in', $image->inEntitiesCount(), ['count' => $image->inEntitiesCount()]) }}
                </p>
                @if($image->inEntitiesCount() > 0)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($image->inEntities() as $entity)
                            <a href="{{ $entity->url() }}">{{ $entity->name }}</a>
                        @endforeach
                    </div>
                    <hr class="visible-sm visible-xs"/>
                @endif
            @endif
            </div>
            <div class="col-md-6">
                <strong>{{ __('crud.fields.name') }}:</strong> {!! $image->name !!}<br />
                @if(!$image->is_folder)
                <strong>{{ __('campaigns/gallery.fields.ext') }}:</strong> {{ $image->ext }}<br />
                <strong>{{ __('campaigns/gallery.fields.size') }}:</strong> {{ $image->niceSize() }}<br />
                @endif
                <strong>{{ __('campaigns/gallery.fields.created_by') }}:</strong> {{ $image->user ? $image->user->name : __('crud.users.unknown') }}


                <hr />

                {!! Form::model($image, ['route' => ['images.update', [$campaign, $image]], 'method' => 'PUT', 'class' => '']) !!}

                <div class="form-group">
                    <label for="name" class="control-label required">{{ __('crud.fields.name') }}</label>
                    {!! Form::text('name', null, ['maxlength' => 45, 'class' => 'form-control']) !!}
                </div>
                @if(!$image->is_folder)
                <div class="form-group">
                    <label for="folder_id" class="control-label">{{ __('campaigns/gallery.fields.folder') }}</label>
                    {!! Form::select('folder_id', $folders, null, ['class' => 'form-control']) !!}
                </div>

                @endif

                @include('cruds.fields.visibility_id', ['model' => $image])

                <input type="submit" class="btn btn-sm btn-primary" value="{{ __('campaigns/gallery.actions.save') }}">


                {!! Form::close() !!}


                <hr />
                @if(!$image->is_folder)
                <a href="{{ $image->getUrl() }}" target="_blank">
                    <i class="fa-solid fa-link"></i> {{ __('campaigns/gallery.actions.full') }}
                </a>
                @endif

                @if(!$image->is_folder || $image->hasNoFolders())

                <a href="#" class="delete-confirm pull-right text-red" data-name="{{ $image->name }}" data-toggle="modal" data-target="#delete-confirm">
                    <i class="fa-solid fa-trash" aria-hidden="true"></i> {{ trans('crud.remove') }}
                </a>
                {!! Form::open(['method' => 'DELETE','route' => ['images.destroy', [$campaign, $image->id]], 'style'=>'display:inline', 'id' => 'delete-confirm-form']) !!}
                {!! Form::close() !!}
                @endif

            </div>
        </div>
    </div>
</div>
