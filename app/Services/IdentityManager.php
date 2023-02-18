<?php

namespace App\Services;

use App\Models\CampaignUser;
use App\Models\UserLog;
use App\Traits\CampaignAware;
use App\User;
use Illuminate\Foundation\Application;

class IdentityManager
{
    use CampaignAware;

    /**
     * @var Application
     */
    private $app;

    /**
     * IdentityManager constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param CampaignUser $campaignUser
     * @return bool
     */
    public function switch(CampaignUser $campaignUser): bool
    {
        try {
            // Save the current user in the session to know we have limitation on the current user.
            session()->put($this->getSessionKey(), $this->app['auth']->user()->id);
            session()->put($this->getSessionCampaignKey(), $this->campaign->id);

            // Log this action
            auth()->user()->log(UserLog::TYPE_USER_SWITCH);
            session()->put('kanka.userLog', UserLog::TYPE_USER_SWITCH_LOGIN);
            $this->app['auth']->loginUsingId($campaignUser->user->id);
        } catch (\Exception $e) {
            return false;
        }

        // Dispatch a log for the user?

        return true;
    }

    /**
     * @return bool
     */
    public function back(): bool
    {
        // Not actually impersonating anyone? Sure.
        if (!$this->isImpersonating()) {
            return false;
        }

        try {
            $impersonator = $this->findUserById($this->getImpersonatorId());

            session()->put('kanka.userLog', UserLog::TYPE_USER_REVERT);

            $this->app['auth']->loginUsingId($impersonator->id);
            $this->clear();
        } catch (\Exception $e) {
            return false;
        }

        // Dispatch a log for the user?

        return true;
    }

    /**
     * Determine if we are someone else that we usually are.
     * @return bool
     */
    public function isImpersonating(): bool
    {
        return session()->has($this->getSessionKey());
    }

    /**
     * @param int $id
     * @return User
     */
    protected function findUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * The Key used to determine where our original user is stored
     * @return string
     */
    public function getSessionKey(): string
    {
        return 'kanka.originalUserID';
    }

    /**
     * The Key used to determine where our original campaign is stored
     * @return string
     */
    public function getSessionCampaignKey(): string
    {
        return 'kanka.originalCampaignID';
    }

    /**
     * @return mixed
     */
    public function getImpersonatorId()
    {
        return session($this->getSessionKey());
    }

    /**
     * @return mixed
     */
    public function getCampaignId()
    {
        return session($this->getSessionCampaignKey());
    }

    /**
     * Forget the saved user identity.
     * @return bool
     */
    protected function clear(): bool
    {
        session()->forget($this->getSessionKey());
        session()->forget($this->getSessionCampaignKey());
        return true;
    }
}
