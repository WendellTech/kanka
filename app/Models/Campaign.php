<?php

namespace App\Models;

use App\Facades\CampaignCache;
use App\Facades\Img;
use App\Facades\Mentions;
use App\Models\Concerns\Boosted;
use App\Models\Concerns\LastSync;
use App\Models\Relations\CampaignRelations;
use App\Models\Scopes\CampaignScopes;
use App\User;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class Campaign
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $locale
 * @property string $entry
 * @property string $image
 * @property string $export_path
 * @property Carbon $export_date
 * @property int $visibility_id
 * @property bool $entity_visibility
 * @property bool $entity_personality_visibility
 * @property string $header_image
 * @property string $system
 * @property string $excerpt
 * @property string $css
 * @property string $theme
 * @property int $boost_count
 * @property integer $visible_entity_count
 * @property array $ui_settings
 * @property boolean $is_open
 * @property boolean $is_featured
 * @property Carbon $featured_until
 * @property string $featured_reason
 * @property array|null $default_images
 * @property array|null $settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $follower
 *
 * UI virtual Settings
 * @property bool $tooltip_family
 * @property bool $tooltip_image
 * @property bool $hide_members
 * @property bool $hide_history
 *
 */
class Campaign extends MiscModel
{
    use CampaignScopes,
        CampaignRelations,
        Boosted,
        LastSync;

    /**
     * Visibility of a campaign
     */
    public const VISIBILITY_PRIVATE = 1;
    public const VISIBILITY_REVIEW = 2;
    public const VISIBILITY_PUBLIC = 3;

    public const LAYER_COUNT_MIN = 1;
    public const LAYER_COUNT_MAX = 10;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'locale',
        'entry',
        'excerpt',
        'image',
        'export_path',
        'export_date',
        'visibility_id',
        'entity_visibility',
        'entity_personality_visibility',
        'header_image',
        'system',
        'theme_id',
        'css',
        'ui_settings',
        'settings',
        'is_open',
    ];

    protected $casts = [
        'ui_settings' => 'array',
        'default_images' => 'array',
        'settings' => 'array',
        'featured_until' => 'date',
        'export_date' => 'date',
    ];

    /**
     * If set to false, skip many of the observers
     * @var bool
     */
    public $withObservers = true;


    /**
     * Helper function to know if a campaign has permissions. This is true as soon as the campaign has several roles
     * @return bool
     */
    public function hasPermissions(): bool
    {
        return $this->roles()->count() > 1;
    }

    /**
     * Does the campaign has a preview text that can be displayed
     * @return bool
     */
    public function hasPreview(): bool
    {
        return !empty($this->preview());
    }

    /**
     * Preview text for the dashboard
     * @return string
     */
    public function preview(): string
    {
        if (!empty(strip_tags($this->excerpt))) {
            return $this->excerpt();
        }
        if (!empty(strip_tags($this->entry))) {
            return strip_tags(substr($this->entry(), 0, 1000)) . ' ...';
        }
        return '';
    }

    /**
     * @return array
     */
    public function membersList($removedIds = []): array
    {
        $members = [];

        foreach ($this->members()->with('user')->get() as $m) {
            if (!in_array($m->user->id, $removedIds)) {
                $members[$m->user->id] = $m->user->name;
            }
        }

        return $members;
    }

    /**
     * @return mixed
     */
    public function invites()
    {
        return $this->hasMany('App\Models\CampaignInvite');
    }

    /**
     * @return mixed
     */
    public function unusedInvites()
    {
        return $this->invites()->where('is_active', true);
    }

    /**
     * @return bool
     */
    public function owner()
    {
        return $this->owners()->where('user_id', Auth::user()->id)->count() == 1;
    }

    /**
     * Get a list of users who are admins of the campaign
     * @return User[]|array|Collection
     */
    public function admins()
    {
        $users = [];
        foreach ($this->roles()->with(['users', 'users.user'])->where('is_admin', '1')->get() as $role) {
            foreach ($role->users as $user) {
                if (!isset($users[$user->id])) {
                    $users[$user->user->id] = $user->user;
                }
            }
        }
        return $users;
    }

    /**
     * Count the number of admins in a campaign. Used by the CampaignPolicy
     * @return int
     */
    public function adminCount(): int
    {
        return count(CampaignCache::campaign($this)->admins());
    }

    /**
     * Determine if the user is in the campaign
     * @return bool
     */
    public function userIsMember(): bool
    {
        return CampaignCache::members()->where('user_id', auth()->user()->id)->count() == 1;
    }

    /**
     * @return int
     */
    public function role()
    {
        $member = $this->members()
            ->where('user_id', Auth::user()->id)
            ->first();
        if ($member) {
            return $member->role;
        }
        return 0;
    }

    /**
     * Determine if a campaign has an entity enabled or not
     *
     * @param string $entity
     * @return bool
     */
    public function enabled(string $entity): bool
    {
        // Can't disable attribute templates
        if ($entity == 'attribute_templates') {
            return true;
        }

        $settings = CampaignCache::settings();
        return (bool) $settings->$entity;
    }

    /**
     * Get the is public checkbox for the campaign form.
     */
    public function getIsPublicAttribute()
    {
        return $this->visibility_id == self::VISIBILITY_PUBLIC;
    }

    /**
     * @return string
     */
    public function getMiddlewareLink(): string
    {
        return 'campaign/' . $this->id;
    }

    /**
     * Determine if the user is currently following the campaign
     * @return bool
     */
    public function isFollowing(): bool
    {
        return $this->followers()->where('user_id', Auth::user()->id)->count() === 1;
    }

    /**
     * Determine if a campaign is public
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->visibility_id == self::VISIBILITY_PUBLIC;
    }
    /**
     *
     * Determine if a campaign is open to submissions
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->is_open;
    }

    /**
     * Determine if a campaign is featured or was featured in the past
     * @param bool $past
     * @return bool
     */
    public function isFeatured(bool $past = false): bool
    {
        return (bool) $this->is_featured && (
            empty($this->featured_until) || (
                $past ?
                    $this->featured_until->isBefore(Carbon::today()) :
                    $this->featured_until->isAfter(Carbon::today())
            )
        );
    }

    /**
     * @return mixed
     */
    public function entry()
    {
        return Mentions::mapAny($this);
    }

    /**
     * @return mixed
     */
    public function getEntryForEditionAttribute()
    {
        return Mentions::parseForEdit($this);
    }

    /**
     * @return mixed
     */
    public function excerpt()
    {
        return Mentions::mapAny($this, 'excerpt');
    }
    /**
     * @return mixed
     */
    public function getExcerptForEditionAttribute()
    {
        return Mentions::parseForEdit($this, 'excerpt');
    }

    /**
     * Determine if the campaign has images in tooltips.
     * @return mixed
     */
    public function getTooltipImageAttribute()
    {
        return Arr::get($this->ui_settings, 'tooltip_image', false);
    }

    /**
     * @return mixed
     */
    public function defaultToNested(): bool
    {
        return (bool) Arr::get($this->ui_settings, 'nested', false);
    }

    /**
     * @return mixed
     */
    public function defaultToConnection(): bool
    {
        return (bool) Arr::get($this->ui_settings, 'connections', false);
    }

    /**
     * @return mixed
     */
    public function getHideMembersAttribute()
    {
        return Arr::get($this->ui_settings, 'hide_members', false);
    }


    /**
     * @return mixed
     */
    public function getHideHistoryAttribute()
    {
        return Arr::get($this->ui_settings, 'hide_history', false);
    }

    /**
     * Number of layers a map of a campaign can have
     * @return int
     */
    public function maxMapLayers(): int
    {
        if ($this->boosted()) {
            return self::LAYER_COUNT_MAX;
        }
        return self::LAYER_COUNT_MIN;
    }

    /**
     * @return int
     */
    public function maxEntityFiles(): int
    {
        if ($this->superboosted()) {
            return config('entities.max_entity_files_superboosted');
        }
        if ($this->boosted()) {
            return config('entities.max_entity_files_boosted');
        }
        return config('entities.max_entity_files');
    }

    /**
     * @return array
     */
    public function existingDefaultImages(): array
    {
        if (empty($this->default_images)) {
            return [];
        }

        return array_keys($this->default_images);
    }

    /**
     * Prepare the default entity images
     * @return array
     */
    public function defaultImages(): array
    {
        if (empty($this->default_images)) {
            return [];
        }

        $imageIds = array_values($this->default_images);
        $images = Image::whereIn('id', $imageIds)->get();

        $data = [];
        foreach ($this->default_images as $type => $uuid) {
            /** @var Image $image */
            $image = $images->where('id', $uuid)->first();
            if (empty($image)) {
                continue;
            }

            $data[] = [
                'type' => $type,
                'uuid' => $uuid,
                'path' => $image->path,
            ];
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasPluginTheme(): bool
    {
        return !empty(CampaignCache::themes());
    }


    /**
     * @return array|\ArrayAccess|mixed
     */
    public function getDefaultVisibilityAttribute()
    {
        return Arr::get($this->settings, 'default_visibility', 'all');
    }

    public function defaultVisibilityID(): int
    {
        $visibility = $this->default_visibility;

        if ($visibility == 'admin') {
            return Visibility::VISIBILITY_ADMIN;
        }
        elseif ($visibility == 'admin-self') {
            return Visibility::VISIBILITY_ADMIN_SELF;
        }
        elseif ($visibility == 'members') {
            return Visibility::VISIBILITY_MEMBERS;
        }
        elseif ($visibility == 'self') {
            return Visibility::VISIBILITY_SELF;
        }

        return Visibility::VISIBILITY_ALL;
    }

    /**
     * Checks if the campaign's public role has no read permissions
     * @return bool
     */
    public function publicHasNoVisibility(): bool
    {
        /** @var CampaignRole $publicRole */
        $publicRole = $this->roles()->public()->first();
        $permissionCount = $publicRole->permissions()
            ->where('action', CampaignPermission::ACTION_READ)
            ->where('access', 1)
            ->count();
        return $permissionCount == 0;
    }

    /**
     * Determine if a campaign has editing warnings (when multiple people are trying to edit
     * the same entity). This is enabled if the campaign has several members.
     * @return bool
     */
    public function hasEditingWarning(): bool
    {
        $members = CampaignCache::members();
        return !empty($members) && $members->count() > 1;
    }

    /**
     * Send a notification to the campaign's admins
     * @param Notification $notification
     * @return $this
     */
    public function notifyAdmins(Notification $notification): self
    {
        foreach ($this->admins() as $user) {
            $user->notify($notification);
        }
        return $this;
    }


    public function getImageUrl(int $width = 400, int $height = null, string $field = 'image')
    {
        if (empty($this->$field)) {
            return '';
        }
        return Img::resetCrop()
            ->crop($width, (!empty($height) ? $height : $width))
            ->url($this->$field);
    }

    /**
     * Determine if a campaign can be exported, or if it already hit the daily maximum
     * @return bool
     */
    public function exportable(): bool
    {
        if (!app()->environment('prod')) {
            return true;
        }

        return empty($this->export_date) || !$this->export_date->isToday();
    }

    /**
     * Get the value of the follower variable
     * @return int
     */
    public function follower(): int
    {
        return (int) $this->follower;
    }

    /**
     * Determine if a campaign is grandfathered, aka has access to features before feature changes in
     * the summer of 2022.
     * @return bool
     */
    public function isGrandfathered(): bool
    {
        $grandfathered = config('kanka.campaigns.grandfathered');
        return $this->id <= $grandfathered;
    }

    /**
     * Get the member limit for the campaign
     * @return int|null
     */
    public function memberLimit(): null|int
    {
        if ($this->isGrandfathered() || $this->boosted()) {
            return null;
        }
        return config('kanka.campaigns.member_limit');
    }

    /**
     * Get the role limit for the campaign
     * @return int|null
     */
    public function roleLimit(): null|int
    {
        if ($this->isGrandfathered() || $this->boosted()) {
            return null;
        }
        return config('kanka.campaigns.role_limit');
    }

    /**
     * Get the quick link limit for the campaign
     * @return int|null
     */
    public function quickLinkLimit(): null|int
    {
        if ($this->isGrandfathered() || $this->boosted()) {
            return null;
        }
        return config('kanka.campaigns.quick_link_limit');
    }

    /**
     * Get the limit of entities a campaign can have
     * @return int|null
     */
    public function entityLimit(): null|int
    {
        if ($this->isGrandfathered() || $this->boosted()) {
            return null;
        }
        return config('kanka.campaigns.entity_limit');
    }
}
