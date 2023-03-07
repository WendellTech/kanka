<?php

namespace App;

use App\Facades\Identity;
use App\Facades\Img;
use App\Facades\PostCache;
use App\Facades\SingleUserCache;
use App\Facades\UserCache;
use App\Models\AppRelease;
use App\Models\Campaign;
use App\Facades\CampaignLocalization;
use App\Models\CampaignRole;
use App\Models\Concerns\Tutorial;
use App\Models\Concerns\UserTokens;
use App\Models\Pledge;
use App\Models\Scopes\UserScope;
use App\Models\UserLog;
use App\Models\UserSetting;
use App\Models\Relations\UserRelations;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use App\Models\Concerns\LastSync;

/**
 * Class User
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property integer|null $last_campaign_id
 * @property string $avatar
 * @property string $provider
 * @property integer $provider_id
 * @property string $last_login_at
 * @property integer $welcome_campaign_id
 * @property boolean $newsletter
 * @property boolean $has_last_login_sharingw
 * @property string|null $pledge
 * @property string|null $timezone
 * @property string|null $currency
 * @property int $booster_count
 * @property int $referral_id
 * @property Carbon|string|null $card_expires_at
 * @property Carbon|string|null $banned_until
 * @property Collection|array $settings
 * @property Collection|array $profile
 *
 * Virtual (from \App\Models\UserSetting)
 * @property bool $advancedMentions
 * @property bool $defaultNested
 * @property string $campaignSwitcherOrderBy
 *
 * @property string $stripe_id
 */
class User extends \Illuminate\Foundation\Auth\User
{
    use Billable;
    use HasApiTokens;
    use LastSync;
    use Notifiable;
    use Tutorial;
    use UserRelations;
    use UserScope;
    use UserSetting;
    use UserTokens
    ;

    protected static $currentCampaign = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_campaign_id',
        'provider',
        'provider_id',
        'newsletter',
        'timezone',
        'campaign_role',
        'theme',
        'locale', // Keep this for the LocaleChange middleware
        'last_login_at',
        'has_last_login_sharing',
        'pledge',
        'referral_id',
        'profile',
        'settings',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token', 'card_expires_at',
    ];

    /**
     * Casted variables
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
        'tutorial' => 'array',
        'profile' => 'array',
        'card_expires_at' => 'datetime',
        'last_login_at' => 'date',
        'banned_until' => 'date',
    ];

    /**
     * @param int $size = 40
     * @return string
     */
    public function getAvatarUrl(int $size = 40): string
    {
        if (!empty($this->avatar) && $this->avatar != 'users/default.png') {
            return Img::crop($size, $size)->url($this->avatar);
        } else {
            return '/images/defaults/user.svg';
        }
    }

    /**
     * @param int|null $campaignId
     * @return string
     */
    public function rolesList(int $campaignId): string
    {
        /** @var CampaignRole[] $roles */
        $roles = $this->campaignRoles->where('campaign_id', $campaignId);
        $roleLinks = [];
        foreach ($roles as $role) {
            if (auth()->user()->isAdmin()) {
                $roleLinks[] = link_to_route('campaign_roles.show', $role->name, ['campaign' => $campaignId, $role->id]);
            } else {
                $roleLinks[] = $role->name;
            }
        }
        return (string) implode(', ', $roleLinks);
    }

    public function hasCampaignRole(Campaign $campaign, int $roleId)
    {
        $roleIds = $this->campaignRoles->where('campaign_id', $campaign->id)->pluck('id')->toArray();
        return in_array($roleId, $roleIds);
    }

    /**
     * Figure out if the user is an admin of the current campaign
     */
    public function isAdmin(): bool
    {
        return UserCache::user($this)->admin();
    }

    /**
     * Check if a user has campaigns
     * @return bool
     */
    public function hasCampaigns($count = 0): bool
    {
        return SingleUserCache::user($this)->campaigns()->count() > $count;
    }

    /**
     * Check if the user has other campaigns than the current one
     * @param int $campaignId
     * @return bool
     */
    public function hasOtherCampaigns(int $campaignId): bool
    {
        $campaigns = SingleUserCache::campaigns();
        return $campaigns->where('campaign_id', '<>', $campaignId)->count() > 0;
    }

    /**
     * Get max file size of user
     * @param bool $readable
     * @return string|int
     */
    public function maxUploadSize(bool $readable = false): string|int
    {
        $campaign = CampaignLocalization::getCampaign();
        if (!$this->isSubscriber() && (empty($campaign) || !$campaign->boosted())) {
            $min = config('limits.filesize.image');
            return $readable ? $min . 'MB' : ($min * 1024);
        } elseif ($this->isElemental()) {
            // Anders gets higher upload sizes until we handle this in the db.
            if ($this->id === 34122) {
                return $readable ? '100MB' : 102400;
            }
            return $readable ? '25MB' : 25600;
        } elseif ($this->isWyvern()) {
            return $readable ? '15MB' : 15360;
        }
        // Allow kobolds and goblins to have the Owlbear sizes
        return $readable ? '8MB' : 8192;
    }

    /**
     * Determine the max upload size for a map
     * @param bool $readable
     * @return string|int
     */
    public function mapUploadSize(bool $readable = false): string|int
    {
        $campaign = CampaignLocalization::getCampaign();
        // Not a subscriber and not in a boosted campaign get the default
        if (!$this->isSubscriber() && (empty($campaign) || !$campaign->boosted())) {
            return $readable ? '3MB' : 3072;
        } elseif ($this->isElemental()) {
            // Anders gets higher upload sizes until we handle this in the db.
            if ($this->id === 34122) {
                return $readable ? '100MB' : 102400;
            }
            return $readable ? '50MB' : 51200;
        } elseif ($this->isWyvern()) {
            return $readable ? '20mb' : 20480;
        }
        // We allow Kobolds and Goblins to have 10MB
        return $readable ? '10MB' : 10240;
    }

    /**
     * Determine if a user is a subscriber
     * @return bool
     */
    public function isSubscriber(): bool
    {
        return $this->hasRole(Pledge::ROLE) || $this->hasRole('admin');
    }

    /**
     * Determine if a user has a legacy patreon sync set up
     * @return bool
     */
    public function isLegacyPatron(): bool
    {
        return $this->hasRole(Pledge::ROLE) && !empty($this->patreon_email);
    }

    /**
     * Determine if a user is a goblin (deprecated)
     * @return bool
     */
    public function isGoblin(): bool
    {
        return !empty($this->pledge) && $this->pledge !== Pledge::KOBOLD;
    }

    /**
     * Determine if a user is an elemental
     * @return bool
     */
    public function isElemental(): bool
    {
        if (!empty($this->pledge) && $this->pledge == Pledge::ELEMENTAL) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isOwlbear(): bool
    {
        return !empty($this->pledge) && $this->pledge == Pledge::OWLBEAR;
    }

    /**
     * @return bool
     */
    public function isWyvern(): bool
    {
        return !empty($this->pledge) && $this->pledge == Pledge::WYVERN;
    }

    /**
     * Determine if a user has access to campaign boosters to boost a campaign
     * @return bool
     */
    public function hasBoosters(): bool
    {
        return $this->isGoblin();
    }


    /**
     * Get available boosts for the user
     * @return int
     */
    public function availableBoosts(): int
    {
        return $this->maxBoosts() - $this->boosting();
    }

    /**
     * Get amount of campaigns the user is boosting
     * @return int
     */
    public function boosting(): int
    {
        return $this->boosts->count();
    }

    /**
     * Get max number of boosts a user can give
     * @return int
     */
    public function maxBoosts(): int
    {
        // Allows admins to give boosters to members of the community
        $base = 0;
        if (!empty($this->booster_count)) {
            $base += $this->booster_count;
        }

        if (!$this->isSubscriber()) {
            return $base;
        }

        if ($this->hasRole('admin')) {
            return max(3, $base);
        }

        $levels = [
            Pledge::KOBOLD => 0,
            Pledge::GOBLIN => 1,
            Pledge::OWLBEAR => 3,
            Pledge::WYVERN => 6,
            Pledge::ELEMENTAL => 10,
        ];

        // Default 3 for admins and owlbears
        return Arr::get($levels, $this->pledge, 0) + $base;
    }

    /**
     * API throttling is increased for subscribers
     * @return int
     */
    public function getRateLimitAttribute(): int
    {
        return $this->isGoblin() ? 90 : 30;
    }

    /**
     * Currency symbol
     * @return string
     */
    public function currencySymbol(): string
    {
        if ($this->billedInEur()) {
            return '€';
        }
        return 'US$';
    }

    /**
     * Determine if the user is billed in EUR.
     * @return bool
     */
    public function billedInEur(): bool
    {
        return $this->currency() === 'eur';
    }

    /**
     * Determine if ads should be shown for the user or campaign
     * @return bool
     */
    public function showAds(Campaign $campaign = null): bool
    {
        // Subs don't have ads
        if ($this->isSubscriber()) {
            return false;
        }

        // Campaigns that are boosted don't either
        return !empty($campaign) && !$campaign->boosted();
    }

    /**
     * @return array
     */
    public function adminCampaigns(): array
    {
        $campaigns = [];

        $roles = $this
            ->campaignRoles()
            ->where('campaign_roles.is_admin', 1)->with('campaign')
            ->get();
        foreach ($roles as $role) {
            $campaigns[$role->campaign->id] = $role->campaign->name;
        }

        return $campaigns;
    }

    /**
     * Check if User has a Role(s) associated.
     *
     * @param string|array $name The role(s) to check.
     *
     * @return bool
     */
    public function hasRole($name): bool
    {
        $roles = $this->roles->pluck('name')->toArray();

        foreach ((is_array($name) ? $name : [$name]) as $role) {
            if (in_array($role, $roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a user is using a social login
     * @return bool
     */
    public function isSocialLogin(): bool
    {
        return !empty($this->provider);
    }

    /**
     * Number of entities the user has created
     * @return string
     */
    public function createdEntitiesCount(): string
    {
        return (string) number_format(SingleUserCache::user($this)->entitiesCreatedCount());
    }

    public function hasPlugins(): bool
    {
        return config('marketplace.enabled') && $this->plugins->count();
    }

    /**
     * Get the Discord app of the user
     * @return mixed
     */
    public function discord()
    {
        return $this->apps->where('app', 'discord')->first();
    }

    /**
     * Get the user's role IDs based on the campaign
     * @param int $campaignID
     * @return array
     */
    public function campaignRoleIDs(int $campaignID): array
    {
        $roles = UserCache::roles()->where('campaign_id', $campaignID);
        return $roles->pluck('id')->toArray();
    }

    /**
     * Log an event on the user
     * @param int $type
     * @return $this
     */
    public function log(int $type): self
    {
        UserLog::create([
            'user_id' => $this->id,
            'type_id' => $type,
        ]);
        return $this;
    }

    /**
     * Determine if the user is banned
     * @return bool
     */
    public function isBanned(): bool
    {
        return !empty($this->banned_until) && $this->banned_until->isFuture();
    }

    /**
     * Determine if the user has achievements to display on their profile page
     * @return bool
     */
    public function hasAchievements(): bool
    {
        return $this->isWordsmith();
    }

    /**
     * Determine if a user has the Wordsmith role
     * @return bool
     */
    public function isWordsmith(): bool
    {
        return $this->hasRole('wordsmith');
    }

    /**
     * Check if user has 2FA.
     */
    public function passwordSecurity()
    {
        return $this->hasOne('App\Models\PasswordSecurity');
    }

    public function initials(): string
    {
        if (!Str::contains(' ', $this->name)) {
            return Str::limit($this->name, 2, '');
        }
        $explode = explode(' ', $this->name);
        return $explode[0] . $explode[1];
    }

    /**
     * Determine if the user has unread notifications or kanka alerts
     * @return bool
     */
    public function hasUnread(): bool
    {
        if (Identity::isImpersonating()) {
            return false;
        }

        // Unread notifications
        $releases = PostCache::latest();
        /** @var AppRelease $release */
        foreach ($releases as $release) {
            if (!$release->alreadyRead()) {
                return true;
            }
        }

        return $this->unreadNotifications()->count() > 0;
    }
}
