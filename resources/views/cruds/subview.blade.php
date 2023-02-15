@section('og')
    @if ($tooltip = $model->entity->mappedPreview())<meta property="og:description" content="{{ $tooltip }}" />@endif
    @if ($model->image)<meta property="og:image" content="{{ $model->thumbnail(200)  }}" />@endif

    <meta property="og:url" content="{{ $model->getLink()  }}" />
@endsection

@include($fullview)
