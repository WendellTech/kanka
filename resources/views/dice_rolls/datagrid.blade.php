@inject ('datagrid', 'App\Renderers\DatagridRenderer')

{!! $datagrid
    ->service($filterService)
    ->models($models)
    ->columns([
        // Avatar
        [
            'type' => 'avatar'
        ],
        // Name
        'name',
        'parameters',
        [
            'label' => __('entities.character'),
            'field' => 'character.name',
            'render' => function($model) {
                if ($model->character) {
                    return $model->character->tooltipedLink();
                }
            }
        ],
        [
            'label' => __('dice_rolls.fields.rolls'),
            'render' => function($model) {
                return $model->diceRollResults()->count();
            },
            'disableSort' => true,
        ],
        [
            'type' => 'is_private',
        ],
    ])
    ->options([
        'route' => 'dice_rolls.index',
        'baseRoute' => 'dice_rolls',
        'trans' => 'dice_rolls.fields.',
    ]
) !!}
