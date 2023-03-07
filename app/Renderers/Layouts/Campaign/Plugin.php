<?php

namespace App\Renderers\Layouts\Campaign;

use App\Facades\CampaignLocalization;
use App\Renderers\Layouts\Layout;

class Plugin extends Layout
{
    /**
     * Available columns
     * @return array[]
     */
    public function columns(): array
    {
        $columns = [
            'name' => [
                'key' => 'name',
                'label' => 'campaigns/plugins.fields.name',
                'render' => function ($model) {
                    return '<a href="' . config('marketplace.url') . '/plugins/' . $model->uuid . '" target="_blank">'
                            . $model->name
                            . '</a>';
                },
            ],
            'update' => [
                'key' => 'has_update',
                'label' => 'Has update',
                'render' => function ($model) {
                    $base = '';
                    if ($model->obsolete()) {
                        $base = '<i class="fa-solid fa-exclamation-triangle" aria-hidden="true" data-toggle="tooltip" title="'
                            . __('campaigns/plugins.fields.obsolete')
                            . '"></i>';
                    }
                    if (!$model->has_update) {
                        return $base;
                    }

                    if (!auth()->check() || !auth()->user()->can('update', $model)) {
                        return $base;
                    }

                    return '<a href="' . route('campaign_plugins.update-info', ['plugin' => $model, 'campaign' => $model->pivot->campaign_id])
                            . '" class="btn btn-xs btn-info" data-toggle="ajax-modal" '
                            . 'data-target="#entity-modal" data-url="'
                            . route('campaign_plugins.update-info', ['plugin' => $model, 'campaign' => $model->pivot->campaign_id]) . '">'
                            . __('campaigns/plugins.actions.update_available')
                            . '</a> ' . $base
                    ;
                }
            ],
            'type' => [
                'key' => 'type_id',
                'label' => 'campaigns/plugins.fields.type',
                'render' => function ($model) {
                    return __('campaigns/plugins.types.' . $model->type());
                },
            ],
            'status' => [
                'key' => 'pivot_is_active',
                'label' => 'campaigns/plugins.fields.status',
                'render' => function ($model) {
                    if (!$model->isTheme()) {
                        return '';
                    }
                    if ($model->pivot->is_active) {
                        return
                            '<i class="fa-solid fa-check-circle" title="' .
                            __('campaigns/plugins.status.enabled') .
                            '" data-toggle="tooltip"></i>';
                    }

                    return
                        '<i class="fa-solid fa-ban" title="' .
                        __('campaigns/plugins.status.disabled') .
                        '" data-toggle="tooltip"></i>';
                }
            ],
        ];

        return $columns;
    }

    /**
     * Available actions on each row
     * @return array
     */
    public function actions(): array
    {
        return [
            'update' => [
                'label' => 'campaigns/plugins.actions.update',
                'icon' => 'fa-solid fa-download',
                'can' => 'update',
                'type' => 'ajax-modal',
                'route' => 'campaign_plugins.update-info',
            ],
            'changelog' => [
                'label' => 'campaigns/plugins.actions.changelog',
                'icon' => 'fa-solid fa-list',
                'can' => 'changelog',
                'type' => 'ajax-modal',
                'route' => 'campaign_plugins.update-info',
            ],
            'disable' => [
                'can' => 'disable',
                'route' => 'campaign_plugins.disable',
                'label' => 'campaigns/plugins.actions.disable',
                'icon' => 'fa-solid fa-ban',
            ],
            'enable' => [
                'can' => 'enable',
                'route' => 'campaign_plugins.enable',
                'label' => 'campaigns/plugins.actions.enable',
                'icon' => 'fa-solid fa-check-circle',
            ],
            'import' => [
                'can' => 'import',
                'route' => 'campaign_plugins.confirm-import',
                'type' => 'ajax-modal',
                'label' => 'campaigns/plugins.actions.import',
                'icon' => 'fa-solid fa-check-circle',
            ],
            Layout::ACTION_DELETE,
        ];
    }

    public function bulks(): array
    {
        return [
            [
                'action' => 'enable',
                'label' => 'campaigns/plugins.actions.bulks.enable',
                'icon' => 'fa-solid fa-check',
                'can' => 'campaign:recover',
            ],
            [
                'action' => 'disable',
                'label' => 'campaigns/plugins.actions.bulks.disable',
                'icon' => 'fa-solid fa-ban',
                'can' => 'campaign:recover',
            ],
            [
                'action' => 'update',
                'label' => 'campaigns/plugins.actions.bulks.update',
                'icon' => 'fa-solid fa-download',
                'can' => 'campaign:recover',
            ],
            self::ACTION_DELETE,
        ];
    }
}
