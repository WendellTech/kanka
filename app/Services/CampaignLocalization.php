<?php

namespace App\Services;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CampaignLocalization
{
    /**
     * Current campaign id.
     *
     * @var string|int|bool
     */
    protected $campaignId = false;

    /** @var Campaign|null The current campaign contact */
    protected Campaign|null $campaign;

    /** @var int console campaign id */
    protected int $consoleCampaignId = 0;

    /**
     * Set and return current locale.
     *
     * @param string|int|null $campaignId
     * @return string Returns locale (if route has any) or null (if route does not have a locale)
     */
    public function setCampaign($campaignId = null): string
    {
        if (empty($campaignId)) {
            // If the locale has not been passed through the function
            // it tries to get it from the first segment of the url
            $campaignId = request()->segment(3);

            // Workaround for the API, where we need the 4th segment
            if (request()->segment(1) == 'api') {
                $campaignId = request()->segment(4);
            }
        }

        // Check to make sure the campaign is an id (we don't want to check the db at this point)
        if (!empty($campaignId) && !is_numeric($campaignId)) {
            if (request()->segment(2) == 'campaign') {
                return redirect()->to(app()->getLocale() . '/404');
            }
        }

        if (!empty($campaignId)) {
            $this->campaignId = $campaignId;
        } else {
            // There is no session at this point. No use trying to read it.
            $this->campaignId = 0;
        }
        return 'campaign/' . $this->campaignId;
    }

    /**
     * Get the campaign
     * @return Campaign|null
     */
    public function getCampaign(bool $canAbort = true)
    {
        if (isset($this->campaign)) {
            return $this->campaign;
        }

        // Some pages like helper pages don't have a campaign in the url
        $this->campaign = null;
        if (is_numeric($this->campaignId) && !empty($this->campaignId)) {
            /** @var Campaign|null $campaign */
            $campaign = Campaign::find((int) $this->campaignId);
            $this->campaign = $campaign;
            // If we're looking for a campaign that doesn't exist, just 404
            if (empty($this->campaign) && $canAbort) {
                throw new ModelNotFoundException();
            }
        }
        // Else, in the API, it's in the binding
        $campaignBinding = request()->route('campaign');
        if (empty($this->campaign) && !empty($campaignBinding)) {
            $this->campaign = $campaignBinding;
        }

        return $this->campaign;
    }

    /**
     * Force the campaign. This is use for moving entities between campaigns.
     * @param Campaign $campaign
     */
    public function forceCampaign(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    /**
     * Get the url of the campaign
     * @param int $campaignId
     * @param string|null $with
     * @return string
     */
    public function getUrl(int $campaignId, string $with = null)
    {
        return app()->getLocale() . '/' . $this->setCampaign($campaignId) . (!empty($with) ? "/{$with}" : null);
    }

    /**
     * @return int
     */
    public function getConsoleCampaign(): int
    {
        return $this->consoleCampaignId;
    }

    /**
     * @param int $campaignId
     * @return $this
     */
    public function setConsoleCampaign(int $campaignId): self
    {
        $this->consoleCampaignId = $campaignId;
        return $this;
    }
}
