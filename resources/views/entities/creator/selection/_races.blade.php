<div class="option flex">

    @include('entities.creator.selection._main', [
        'singular' => 'race',
        'plural' => 'races',
        'icon' => config('entities.icons.race'),
        'id' => config('entities.ids.race'),
    ])
    @include('entities.creator.selection._full', ['key' => 'races'])
</div>
