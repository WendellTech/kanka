<?php /** @var \App\Models\Relation $model */?>
@inject ('datagrid', 'App\Renderers\DatagridRenderer')

{!! $datagrid
    ->service($filterService)
    ->models($models)
    ->columns([
        [
            'field' => 'owner_id',
            'label' => __('entities/relations.fields.owner'),
            'class' => null,
            'render' => function($model) {
                return $model->owner->tooltipedLink();
                //return '<a href="' . $model->owner->url() . '">' . $model->owner->name . '</a>';
            }
        ],
        [
            'field' => 'target_id',
            'label' => __('entities/relations.fields.target'),
            'class' => null,
            'render' => function($model) {
                return $model->target->tooltipedLink();
                //return '<a href="' . $model->target->url() . '">' . $model->target->name . '</a>';
            }
        ],
        [
            'field' => 'relation',
            'label' => __('entities/relations.fields.relation'),
            'render' => function($model) {
                $colour = null;
                if (!empty($model->colour)) {
                    $colour = '<div class="label-tag-bubble" style="background-color: ' . $model->colour . '; "></div> ';
                }
                return $colour . $model->relation;
            }
        ],
        [
            'field' => 'mirror_id',
            'label' => '<i class="fa-solid fa-sync-alt" title="' . __('entities/relations.hints.mirrored.title') . '"></i>',
            'render' => function ($model) {
                return $model->isMirrored() ? '<i class="fa-solid fa-sync-alt"></i>' : null;
            }
        ],
        [
            'field' => 'is_star',
            'label' => '<i class="fa-solid fa-star" title="' . __('entities/relations.fields.is_star') . '"></i>',
            'render' => function ($model) {
                return $model->is_star ? '<i class="fa-solid fa-star"></i>' : null;
            }
        ],
        [
            'field' => 'attitude',
            'label' => __('entities/relations.fields.attitude'),
        ],
        [
            'field' => 'visibility_id',
            'label' => __('crud.fields.visibility'),
            'render' => function ($model) {
                return $model->visibilityIcon();
            }
        ],
    ])
    ->options(    [
        'route' => 'relations.index',
        'baseRoute' => 'relations',
        'trans' => 'relations.fields.',
        'disableEntity' => true,
    ]
) !!}
