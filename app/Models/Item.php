<?php

namespace App\Models;

use App\Models\Concerns\Acl;
use App\Models\Concerns\SortableTrait;
use App\Traits\CampaignTrait;
use App\Traits\ExportableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Item
 * @package App\Models
 *
 * @property string $type
 * @property string $price
 * @property string $size
 * @property string $weight
 * @property integer|null $item_id
 * @property integer|null $character_id
 * @property integer|null $location_id
 * @property Character|null $character
 * @property Location|null $location
 * @property Item[] $items
 * @property Item|null $item
 */
class Item extends MiscModel
{
    use Acl
    ;
    use CampaignTrait;
    use ExportableTrait;
    use SoftDeletes;
    use SortableTrait;

    /** @var string[]  */
    protected $fillable = [
        'name',
        'campaign_id',
        'slug',
        'type',
        'image',
        'entry',
        'price',
        'size',
        'item_id',
        'character_id',
        'location_id',
        'is_private',
    ];
    protected $sortable = [
        'name',
        'type',
        'price',
        'size',
        'item_id',
    ];

    /**
     * Entity type
     * @var string
     */
    protected $entityType = 'item';

    /**
     * Fields that can be sorted on
     * @var array
     */
    protected $sortableColumns = [
        'price',
        'size',
        'location.name',
        'character.name',
        'item_id',
    ];

    /**
     * Casting for order by
     * @var array
     */
    protected $orderCasting = [
        'price' => 'unsigned'
    ];

    /**
     * Nullable values (foreign keys)
     * @var string[]
     */
    public $nullableForeignKeys = [
        'location_id',
        'character_id',
        'item_id',
    ];


    /**
     * Foreign relations to add to export
     * @var array
     */
    protected $foreignExport = [

    ];

    /**
     * Tooltip subtitle (item price/size)
     * @return string
     */
    public function tooltipSubtitle(): string
    {
        $extra = [];
        if (!empty($this->price)) {
            $extra[] = __('items.fields.price') . ': ' . e($this->price);
        }
        if (!empty($this->size)) {
            $extra[] = __('items.fields.size') . ': ' . e($this->size);
        }
        if (empty($extra)) {
            return '';
        }
        return implode('<br />', $extra);
    }

    /**
     * Performance with for datagrids
     * @param Builder $query
     * @return Builder mixed
     */
    public function scopePreparedWith(Builder $query): Builder
    {
        return $query->with([
            'entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id', 'image_uuid');
            },
            'entity.image' => function ($sub) {
                $sub->select('campaign_id', 'id', 'ext');
            },
            'location' => function ($sub) {
                $sub->select('id', 'name', 'campaign_id');
            },
            'location.entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id');
            },
            'character' => function ($sub) {
                $sub->select('id', 'name', 'campaign_id');
            },
            'character.entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id');
            },
            'items' => function ($sub) {
                $sub->select('id', 'name', 'item_id');
            },
        ]);
    }

    /**
     * Only select used fields in datagrids
     * @return array
     */
    public function datagridSelectFields(): array
    {
        return ['character_id', 'location_id', 'price', 'size', 'item_id'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character()
    {
        return $this->belongsTo('App\Models\Character', 'character_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemQuests()
    {
        return $this->hasMany('App\Models\QuestItem', 'item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany('App\Models\Inventory', 'item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\Item', 'item_id', 'id');
    }

    /**
     * Parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }
    /**
     * @return array
     */
    public function menuItems(array $items = []): array
    {
        $inventoryCount = $this->inventories()->with('item')->has('entity')->count();
        if ($inventoryCount > 0) {
            $items['second']['inventories'] = [
                'name' => 'items.show.tabs.inventories',
                'route' => 'items.inventories',
                'count' => $inventoryCount,
            ];
        }

        return parent::menuItems($items);
    }

    /**
     * Get the entity_type id from the entity_types table
     * @return int
     */
    public function entityTypeId(): int
    {
        return (int) config('entities.ids.item');
    }

    /**
     * Determine if the model has profile data to be displayed
     * @return bool
     */
    public function showProfileInfo(): bool
    {
        if (!empty($this->type) || !empty($this->price) || !empty($this->size)) {
            return true;
        }

        return (bool) ($this->character || $this->location);
    }

    /**
     * Define the fields unique to this model that can be used on filters
     * @return string[]
     */
    public function filterableColumns(): array
    {
        return [
            'location_id',
            'character_id',
            'price',
            'size',
            'item_id',
        ];
    }
}
