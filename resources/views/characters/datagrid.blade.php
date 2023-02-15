<?php /** @var \App\Models\Character $model */?>
@inject ('datagrid', 'App\Renderers\DatagridRenderer')

{!! $datagrid
    ->service($filterService)
    ->models($models)
    ->columns([
        [
            'type' => 'avatar'
        ],
        'name',
        'title',
        [
            'label' => __('entities.families'),
            'visible' => $campaign->enabled('families'),
            'disableSort' => true,
            'render' => function($model) {
                $families = [];
                foreach ($model->families as $family) {
                    $families[] = $family->tooltipedLink();
                }
                return implode( ', ', $families);
            }
        ],
        [
            'type' => 'location',
            'visible' => $campaign->enabled('locations'),
        ],
        [
            'label' => __('entities.races'),
            'visible' => $campaign->enabled('races'),
            'disableSort' => true,
            'render' => function($model) {
                $races = [];
                foreach ($model->races as $race) {
                    $races[] = $race->tooltipedLink();
                }
                return implode( ', ', $races);
            }
        ],
        'type',
        [
            'label' => '<i class="fa-solid fa-transgender-alt" title="' . __('characters.fields.sex') . '"></i>',
            'field' => 'sex',
        ],
        [
            'label' => '<i class="ra ra-skull" title="' . __('characters.fields.is_dead') . '"></i>',
            'field' => 'is_dead',
            'render' => function($model) {
                if ($model->is_dead) {
                    return '<i class="ra ra-skull" title="' . __('characters.fields.is_dead') . '"></i>';
                }
                return '';
            },
            'class' => 'icon'
        ],
        [
            'type' => 'is_private',
        ]
    ])
    ->options([
        'route' => 'characters.index',
        'baseRoute' => 'characters',
        'trans' => 'characters.fields.',
    ]
) !!}


@tutorial('character_1')
@include('tutorials.modal', [
    'key' => 'character_1',
    'title' => 'characters.character_1.title',
    'contents' => [
        'characters.character_1.first'
    ],
])
@endtutorial
