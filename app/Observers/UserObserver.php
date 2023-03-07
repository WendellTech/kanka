<?php

namespace App\Observers;

use App\Facades\SingleUserCache;
use App\Jobs\Emails\MailSettingsChangeJob;
use App\Jobs\Emails\WelcomeEmailJob;
use App\Jobs\Users\UnsubscribeUser;
use App\Jobs\Users\UpdateEmail;
use App\Models\CampaignUser;
use App\Models\CampaignFollower;
use App\Services\ImageService;
use App\User;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserObserver
{
    use PurifiableTrait;

    /**
     * @param User $user
     */
    public function saving(User $user)
    {
        // Setting a new password
        $new = request()->post('password_new');
        if (!empty($new)) {
            $user->password = Hash::make(request()->post('password_new'));
        }

        // Purify the bio
        if (!empty($user->profile['bio'])) {
            $profile = $user->profile;
            $profile['bio'] = mb_substr(strip_tags($profile['bio']), 0, 301);
            try {
                $user->profile = $profile;
            } catch (\Exception $e) {
                // An invalid profile, like emojis in text
                $profile['bio'] = '';
                $user->profile = $profile;
            }
        }

        // Handle image. Let's use a service for this.
        $folderno = (int) floor($user->id / 1000);
        ImageService::handle($user, $user->getTable() . '/' . $folderno, 60, 'avatar');
    }

    /**
     * @param User $user
     */
    public function saved(User $user)
    {
        // Only clear the cache if the name changed
        if ($user->isDirty('name')) {
            SingleUserCache::user($user)->clearName();
        }
    }

    /**
     * @param User $user
     */
    public function updated(User $user)
    {
        // Tell mailchimp about the user's new email
        if (!$user->wasRecentlyCreated && $user->isDirty('email') && $user->hasNewsletter()) {
            UpdateEmail::dispatch($user->getOriginal('email'), $user->email);
        }
    }

    public function creating(User $user)
    {
        $user->locale = LaravelLocalization::getCurrentLocale();
        $settings = [];

        if (session()->has('tracking')) {
            $settings['tracking'] = session()->get('tracking');
            session()->remove('tracking');
        }

        if (session()->has('invite_token')) {
            $settings['invited'] = true;
        }

        if (count($settings) > 0) {
            $user->settings = $settings;
        }
    }

    /**
     * @param User $user
     */
    public function created(User $user)
    {
        WelcomeEmailJob::dispatch($user, app()->getLocale());
        session()->put('user_registered', true);

        if (request()->filled('newsletter')) {
            $user
                ->updateSettings(['mail_release' => 1])
                ->save();

            MailSettingsChangeJob::dispatch($user);
        }
    }

    /**
     * @param User $user
     */
    public function deleted(User $user)
    {
        // If the user has an avatar, delete it from the disk to free up some space.
        if (!empty($user->avatar)) {
            ImageService::cleanup($user, 'avatar');
        }

        SingleUserCache::user($user)
            ->clearName()
            ->clearCampaigns()
            ->clearRoles()
        ;

        // If the user was subscribed to the newsletter, unsubscribe them
        if (!empty($user->hasNewsletter())) {
            UnsubscribeUser::dispatch($user->email);
        }
    }

    /**
     * @param User $user
     */
    public function deleting(User $user)
    {
        // Campaign user
        $members = CampaignUser::where('user_id', $user->id)->with('campaign')->get();
        foreach ($members as $member) {
            $member->delete();

            // Delete a campaign if no one is left in it
            if ($member->campaign->members()->count() == 0) {
                $member->campaign->delete();
            }
        }
        $followers = CampaignFollower::where('user_id', $user->id)->with('campaign')->get();
        foreach ($followers as $follower) {
            $follower->delete();
        }
    }
}
