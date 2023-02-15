{{ csrf_field() }}
@if(!isset($entityAsset))

    <div class="form-group required">
        <label>{{ __('entities/files.fields.file') }}</label>
        {!! Form::file('file', array('class' => 'image form-control')) !!}

        <p class="help-block">
            {{ __('crud.files.hints.limitations', ['formats' => 'jpg, jpeg, png, gif, webp, pdf, xls(x), mp3, ogg, json', 'size' => auth()->user()->maxUploadSize(true)]) }}
            @subscriber()
                @if (!$campaign->boosted())
                    <p>
                        <a href="{{ route('settings.boost', ['campaign' => $campaign]) }}">
                            <i class="fa-solid fa-rocket" aria-hidden="true"></i>
                            {!! __('callouts.subscribe.share-booster', ['campaign' => $campaign->name]) !!}
                        </a>
                    </p>
                @endif
            @else
                <a href="{{ route('front.pricing') }}">{{ __('callouts.subscribe.pitch-image', ['max' => 25]) }}</a>
            @endif
        </p>
    </div>
@endif

<div class="form-group @if(isset($entityAsset)) required @endif">
    <label>{{ __('entities/files.fields.name') }}</label>
    {!! Form::text(
        'name',
        null,
        [
            'class' => 'form-control',
            'maxlength' => 45
        ]
    ) !!}
</div>

@include('cruds.fields.visibility_id', ['model' => $entityAsset ?? null])
@include('cruds.fields.is_pinned', ['model' => $entity ?? null])

