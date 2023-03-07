<?php

namespace App\Models\Scopes;

use App\Facades\CampaignLocalization;
use App\Models\Visibility;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VisibilityIDScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // Only apply these scopes in non-console mode.
        if (app()->runningInConsole()) {
            // However, if we are in console mode (exporting), we need a way to avoid people accessing "self" notes.
            // Todo: how to handle this use case properly? Not exporting "self" seems silly
            // $builder->where($model->getTable() . 'visibility', '!=', Visibility::VISIBILITY_SELF);
            return;
        }

        // If we aren't authenticated, just see what is set to all
        if (!auth()->check()) {
            $builder->where($model->getTable() . '.visibility_id', Visibility::VISIBILITY_ALL);
            return;
        }
        // Todo: move to the user knowing about this
        $campaign = CampaignLocalization::getCampaign();
        if (!$campaign->userIsMember()) {
            $builder->where($model->getTable() . '.visibility_id', Visibility::VISIBILITY_ALL);
            return;
        }

        // Either mine (self && created_by = me) or (if admin: !self, else: all)
        $builder->where(function ($sub) use ($model) {
            $visibilities = auth()->user()->isAdmin()
                ? [Visibility::VISIBILITY_ALL, Visibility::VISIBILITY_ADMIN,
                    Visibility::VISIBILITY_ADMIN_SELF, Visibility::VISIBILITY_MEMBERS]
                : [Visibility::VISIBILITY_ALL, Visibility::VISIBILITY_MEMBERS];
            $sub
                ->where(function ($self) use ($model) {
                    $self
                        ->whereIn($model->getTable() . '.visibility_id', [
                            Visibility::VISIBILITY_SELF,
                            Visibility::VISIBILITY_ADMIN_SELF,
                        ])
                        ->where($model->getTable() . '.created_by', auth()->user()->id);
                })
                ->orWhereIn($model->getTable() . '.visibility_id', $visibilities);
        });
    }
}
