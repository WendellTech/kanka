<?php

namespace App\Traits;

use App\Models\Campaign;
use App\Models\Scopes\CampaignScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait CampaignTrait
 * @package App\Traits
 *
 * @property int $campaign_id
 * @property Campaign $campaign
 *
 * @method static Builder|self allCampaigns()
 * @method static Builder|self inCampaign(Campaign $campaign)
 */
trait CampaignTrait
{
    /** @var bool Determine if the query context is limited to the current campaign */
    protected bool $withCampaignLimit = true;

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeAllCampaigns(Builder $builder): Builder
    {
        $this->withCampaignLimit = false;
        return $builder;
    }

    /**
     * @param Builder $builder
     * @param Campaign $campaign
     * @return Builder
     */
    public function scopeInCampaign(Builder $builder, Campaign $campaign): Builder
    {
        return $builder->where('campaign_id', $campaign->id);
    }

    /**
     * Check if limited to the current campaign context
     * @return bool
     */
    public function withCampaignLimit(): bool
    {
        return $this->withCampaignLimit;
    }

    /**
     * @return void
     */
    public static function bootCampaignTrait()
    {
        static::addGlobalScope(new CampaignScope());
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
