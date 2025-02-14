<?php

namespace App\Models;

use App\Facades\CampaignLocalization;
use App\Facades\EntityCache;
use App\Facades\Img;
use App\Facades\Mentions;
use App\Models\Concerns\Acl;
use App\Models\Concerns\EntityLogs;
use App\Models\Concerns\LastSync;
use App\Models\Concerns\Paginatable;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\SortableTrait;
use App\Models\Concerns\EntityType;
use App\Models\Relations\EntityRelations;
use App\Models\Scopes\EntityScopes;
use App\Traits\CampaignTrait;
use App\Traits\TooltipTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * Class Entity
 * @package App\Models
 *
 * @property integer $id
 * @property integer $entity_id
 * @property integer $campaign_id
 * @property string $name
 * @property string $type
 * @property integer $type_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property boolean $is_private
 * @property boolean $is_attributes_private
 * @property string $tooltip
 * @property string $header_image
 * @property string|null $image_uuid
 * @property string|null $header_uuid
 * @property boolean $is_template
 * @property string|null $marketplace_uuid
 * @property integer|null $focus_x
 * @property integer|null $focus_y
 * @property string|null $image_path
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Entity extends Model
{
    use Acl;
    use BlameableTrait;
    use CampaignTrait;
    use EntityLogs;
    use EntityRelations;
    use EntityScopes;
    use EntityType;
    use LastSync;
    use Paginatable;
    use Searchable;
    use SoftDeletes;
    use SortableTrait;
    use TooltipTrait;

    /** @var string[]  */
    protected $fillable = [
        'campaign_id',
        'entity_id',
        'name',
        'type_id',
        'is_private',
        'is_attributes_private',
        'header_image',
        'image_uuid',
        'header_uuid',
        'is_template',
    ];

    /** @var array Searchable fields */
    protected array $searchableColumns = [
        'name',
    ];

    /** @var string[] Fields that can be used to order by */
    protected array $sortable = [
        'name',
        'type_id',
        'deleted_at',
    ];

    /**
     * Array of our custom model events declared under model property $observables
     * @var array
     */
    protected $observables = [
        'crudSaved',
    ];

    /**
     * True if the user granted themselves permission to read/write when creating the entity
     * @var bool
     */
    public $permissionGrantSelf = false;

    /** @var bool|string */
    protected $cachedPluralName = false;

    /** @var bool|string the entity type string */
    protected $cachedType = false;

    /**
     * Get the child entity
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Relations\HasOne|MiscModel
     */
    public function child()
    {
        if ($this->isAttributeTemplate()) {
            return $this->attributeTemplate();
        } elseif ($this->isDiceRoll()) {
            return $this->diceRoll();
        }
        // @phpstan-ignore-next-line
        return $this->{$this->type()}();
    }

    /**
     * Child attribute
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Relations\HasOne|MiscModel
     */
    public function getChildAttribute()
    {
        return EntityCache::child($this);
    }

    /**
     * @return Entity
     */
    public function reloadChild()
    {
        if ($this->isAttributeTemplate()) {
            return $this->load('attributeTemplate');
        } elseif ($this->isDiceRoll()) {
            return $this->load('diceRoll');
        }
        // @phpstan-ignore-next-line
        return $this->load($this->type());
    }

    /**
     * Fire an event to the observer to know that the entity was saved from the crud
     */
    public function crudSaved()
    {
        $this->fireModelEvent('crudSaved', false);
    }

    /**
     * Create a short name for the interface
     * @return mixed|string
     */
    public function shortName()
    {
        if (mb_strlen($this->name) > 30) {
            return '<span title="' . e($this->name) . '">' . mb_substr(e($this->name), 0, 28) . '...</span>';
        }
        return $this->name;
    }



    /**
     * Preview of the entity with mapped mentions. For map markers
     */
    public function mappedPreview(): string
    {
        if (empty($this->child)) {
            return '';
        }
        $campaign = CampaignLocalization::getCampaign();
        if ($campaign->boosted()) {
            $boostedTooltip = strip_tags($this->tooltip);
            if (!empty(trim($boostedTooltip))) {
                $text = Mentions::mapEntity($this);
                return (string)strip_tags($text);
            }
        }
        $text = Str::limit($this->child->entry(), 500);
        return (string)strip_tags($text);
    }


    /**
     * @return string
     */
    public function url(string $action = 'show', array $options = [])
    {
        $campaign = CampaignLocalization::getCampaign();
        try {
            if ($action == 'index') {
                return route($this->pluralType() . '.index', $campaign);
            } elseif ($action === 'show') {
                return route('entities.show', [$campaign, $this]);
            }
            $routeOptions = array_merge([$campaign, $this->entity_id], $options);
            return route($this->pluralType() . '.' . $action, $routeOptions);
        } catch (Exception $e) {
            return route('dashboard', $campaign);
        }
    }

    /**
     * Get the plural name of the entity for routes
     */
    public function pluralType(): string
    {
        if ($this->cachedPluralName !== false) {
            return $this->cachedPluralName;
        }
        // @phpstan-ignore-next-line
        return $this->cachedPluralName = Str::plural($this->type());
    }

    /**
     * Get the entity's type id
     */
    public function typeId()
    {
        return $this->type_id;
    }

    public function entityType(): string
    {
        // @phpstan-ignore-next-line
        return __('entities.' . $this->type());
    }

    /**
     * @param array|int $types
     */
    public function isType($types): bool
    {
        if (!is_array($types)) {
            $types = [$types];
        }

        return in_array($this->type_id, $types);
    }

    /**
     */
    public function type(): string
    {
        if ($this->cachedType !== false) {
            return $this->cachedType;
        }
        $type = array_search($this->type_id, config('entities.ids'));
        return $this->cachedType = $type;
    }

    public function cleanCache(): self
    {
        $this->cachedType = false;
        $this->cachedPluralName = false;
        return $this;
    }

    /**
     * Get the image (or default image) of an entity
     * @param int $width = 200
     */
    public function thumbnail(int $width = 400, int $height = null, $field = 'header_image'): string
    {
        if (empty($this->$field)) {
            return '';
        }

        return Img::resetCrop()->crop($width, $height ?? $width)->url($this->$field);
    }

    /**
     * If an entity has entity files
     */
    public function hasFiles(): bool
    {
        return $this->type_id != config('entities.ids.bookmark');
    }

    /**
     * Touch a model (update the timestamps) without any observers/events
     */
    public function touchSilently()
    {
        return static::withoutEvents(function () {
            // Still logg who edited the entity
            $this->updated_by = auth()->user()->id;
            return $this->touch();
        });
    }

    /**
     */
    public function hasHeaderImage(bool $superboosted = false): bool
    {
        if (!empty($this->header_image)) {
            return true;
        }

        return (bool) ($superboosted && !empty($this->header_uuid) && !empty($this->header));
    }

    /**
     * Determine if an entity has an image that can be shown
     */
    public function hasImage(bool $boosted = false): bool
    {
        // Most basic setup, the child has an image
        if (!empty($this->image_path)) {
            return true;
        }
        // Otherwise, might have a gallery image, which needs a boosted campaign
        return $boosted && $this->image;
    }

    /**
     * Get the entity's image url (local or gallery)
     */
    public function getEntityImageUrl(bool $boosted = false, int $width = 200, int $height = 200): string
    {
        if ($boosted && $this->image) {
            return Img::crop($width, $height)->url($this->image->path);
        }
        return $this->child->thumbnail($width, $height);
    }

    /**
     */
    public function hasLinks(): bool
    {
        return $this->links()->count() > 0;
    }

    /**
     */
    public function getHeaderUrl(bool $superboosted = false): string
    {
        if (!empty($this->header_image)) {
            return $this->thumbnail(1200, 400, 'header_image');
        }

        if (!$superboosted) {
            return '';
        }

        if (empty($this->header)) {
            return '';
        }

        return $this->header->getUrl(1200, 400);
    }

    /**
     */
    public function accessAttributes(): bool
    {
        $campaign = CampaignLocalization::getCampaign();

        if (!$campaign->enabled('entity_attributes')) {
            return false;
        }

        if (!$this->is_attributes_private) {
            return true;
        }
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Count the number of mentions this entity has
     */
    public function mentionsCount(): int
    {
        return $this->targetMentions()
            ->prepareCount()
            ->count();
    }

    /**
     * Determine if an entity has pinned elements to display
     */
    public function hasPins(): bool
    {
        if ($this->pinnedRelations->isNotEmpty()) {
            return true;
        }
        if ($this->accessAttributes() && $this->starredAttributes()->isNotEmpty()) {
            return true;
        }
        return (bool) ($this->pinnedFiles->isNotEmpty());
    }

    /**
     * @return array|string[]
     */
    public function postPositionOptions($position = null): array
    {
        $options = $position ? [
            null => __('posts.position.dont_change'),
        ] : [];

        $layers = $this->posts->sortBy('position');
        $hasFirst = false;
        foreach ($layers as $layer) {
            if (!$hasFirst) {
                $hasFirst = true;
                $options[$layer->position < 0 ? $layer->position - 1 : 1] = __('posts.position.first');
            }
            $key = $layer->position > 0 ? $layer->position + 1 : $layer->position;
            $lang = __('maps/layers.placeholders.position_list', ['name' => $layer->name]);
            if (app()->isLocal()) {
                $lang .= ' (' . $key . ')';
            }
            $options[$key] = $lang;
        }

        // Didn't have a first option added, add one now
        if (!$hasFirst) {
            $options[1] = __('posts.position.first');
        }

        //If is the last position remove last+1 position from the options array
        /*if ($position == array_key_last($options) - 1 && count($options) > 1) {
            array_pop($options);
        }*/
        return $options;
    }
}
