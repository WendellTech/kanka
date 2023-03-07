@inject ('datagrid', 'App\Renderers\DatagridRenderer')
<?php /** @var \App\Models\Timeline $model */?>
{!! $datagrid
    ->campaign($campaign)
    ->service($filterService)
    ->columns([
        // Avatar
        [
            'type' => 'avatar'
        ],
        // Name
        'name',
        'type',
        [
            'label' => trans('timelines.fields.timeline'),
            'field' => 'timeline.name',
            'render' => function($model) {
                if ($model->timeline) {
                    return $model->timeline->entity->tooltipedLink();
                }
            }
        ],
        [
            'label' => __('timelines.fields.eras'),
            'render' => function($model) {
                return $model->eras->count();
            },
            'disableSort' => true,
        ],
        [
            'type' => 'is_private',
        ]
    ])
    ->models($models)
    ->options([
        'route' => 'timelines.index',
        'baseRoute' => 'timelines',
        'trans' => 'timelines.fields.',
    ])
; !!}
