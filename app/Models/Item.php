<?php

namespace App\Models;

use App\Models\Concerns\Acl;
use App\Models\Concerns\SortableTrait;
use App\Traits\CampaignTrait;
use App\Traits\ExportableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    use HasFactory;
    use SoftDeletes;
    use SortableTrait;

    /** @var string[]  */
    protected $fillable = [
        'name',
        'campaign_id',
        'slug',
        'type',
        'entry',
        'price',
        'size',
        'item_id',
        'character_id',
        'location_id',
        'is_private',
    ];
    protected array $sortable = [
        'name',
        'type',
        'price',
        'size',
        'item_id',
    ];

    /**
     * Entity type
     */
    protected string $entityType = 'item';

    /**
     * Fields that can be sorted on
     */
    protected array $sortableColumns = [
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
    public array $nullableForeignKeys = [
        'location_id',
        'character_id',
        'item_id',
    ];


    /**
     * Foreign relations to add to export
     */
    protected array $foreignExport = [

    ];

    /**
     * Tooltip subtitle (item price/size)
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


    public function getParentIdName(): string
    {
        return 'item_id';
    }

    /**
     * Performance with for datagrids
     * @return Builder mixed
     */
    public function scopePreparedWith(Builder $query): Builder
    {
        return $query->with([
            'entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id', 'image_path', 'image_uuid', 'focus_x', 'focus_y');
            },
            'entity.image' => function ($sub) {
                $sub->select('campaign_id', 'id', 'ext', 'focus_x', 'focus_y');
            },
            'location' => function ($sub) {
                $sub->select('id', 'name');
            },
            'location.entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id');
            },
            'character' => function ($sub) {
                $sub->select('id', 'name');
            },
            'character.entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id');
            },
            'items' => function ($sub) {
                $sub->select('id', 'name', 'item_id');
            },
            'children' => function ($sub) {
                $sub->select('id', 'item_id');
            }
        ]);
    }

    /**
     * Only select used fields in datagrids
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
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function entities()
    {
        return $this->hasManyThrough(
            'App\Models\Entity',
            'App\Models\Inventory',
            'item_id',
            'id',
            'id',
            'entity_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\Item', 'item_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->items();
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
     */
    public function menuItems(array $items = []): array
    {
        $inventoryCount = $this->inventories()->with('item')->has('entity')->count();
        if ($inventoryCount > 0) {
            $items['second']['inventories'] = [
                'name' => 'items.show.tabs.inventories',
                'route' => 'items.inventories',
                'count' => $inventoryCount
            ];
        }

        return parent::menuItems($items);
    }

    /**
     * Get the entity_type id from the entity_types table
     */
    public function entityTypeId(): int
    {
        return (int) config('entities.ids.item');
    }

    /**
     * Determine if the model has profile data to be displayed
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

    /**
     * Grid mode sortable fields
     */
    public function datagridSortableColumns(): array
    {
        $columns = [
            'name' => __('crud.fields.name'),
            'type' => __('crud.fields.type'),
            'price' => __('items.fields.price'),
            'size' => __('items.fields.size'),
        ];

        if (auth()->check() && auth()->user()->isAdmin()) {
            $columns['is_private'] = __('crud.fields.is_private');
        }
        return $columns;
    }
}
