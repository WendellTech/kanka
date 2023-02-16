<?php

namespace App\Models;

use App\Models\Concerns\Acl;
use App\Models\Concerns\Nested;
use App\Services\AttributeService;
use App\Traits\CampaignTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AttributeTemplate
 * @package App\Models
 *
 * @property integer|null $attribute_template_id
 * @property integer|null $entity_type_id
 * @property AttributeTemplate|null $attributeTemplate
 * @property AttributeTemplate[] $attributeTemplates
 * @property EntityType $entityType
 */
class AttributeTemplate extends MiscModel
{
    use Acl
    ;
    use CampaignTrait;
    use Nested;
    use SoftDeletes;

    /**
     * Fields that can be mass-assigned
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'campaign_id',
        'attribute_template_id',
        'entity_type_id',
        'is_private',
    ];

    /**
     * Entity type
     * @var string
     */
    protected $entityType = 'attribute_template';

    /**
     * Searchable fields
     * @var array
     */
    protected array $searchableColumns  = ['name'];

    /**
     * Fields that can be sorted on
     * @var array
     */
    protected $sortableColumns = [
        'attributeTemplate.name',
        'entity_type_id',
    ];

    /**
     * Fields that can be set to null (foreign keys)
     * @var string[]
     */
    public $nullableForeignKeys = [
        'attribute_template_id',
        'entity_type_id'
    ];

    /** @var bool Attribute templates don't have inventory, relations or abilities */
    public $hasRelations = false;

    /**
     * Parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attributeTemplate()
    {
        return $this->belongsTo('App\Models\AttributeTemplate', 'attribute_template_id', 'id');
    }

    /**
     * Parent
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entityType()
    {
        return $this->belongsTo('App\Models\EntityType', 'entity_type_id', 'id');
    }

    /**
     * Children
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeTemplates()
    {
        return $this->hasMany('App\Models\AttributeTemplate', 'attribute_template_id', 'id');
    }

    /**
     * Parent ID field for the Node trait
     * @return string
     */
    public function getParentIdName()
    {
        return 'attribute_template_id';
    }

    /**
     * Specify parent id attribute mutator
     * @param int $value
     */
    public function setAttributeTemplateIdAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }

    /**
     * Performance with for datagrids
     * @param Builder $query
     * @return Builder
     */
    public function scopePreparedSelect(Builder $query): Builder
    {
        return $query
            ->select([$this->getTable() . '.id', $this->getTable() . '.name', $this->getTable() . '.is_private', $this->getTable() . '.campaign_id', 'attribute_template_id', 'entity_type_id']);
    }


    /**
     * Apply a template to an entity
     * @param Entity $entity
     * @param int $startingOrder
     * @return int
     */
    public function apply(Entity $entity, $startingOrder = 0)
    {
        $order = $startingOrder;
        $existing = array_values($entity->attributes()->pluck('name')->toArray());

        // If adding to an entity that already has attributes, we need to add the new ones after the existing ones
        $lastOrder = 0;
        if ($startingOrder == 0) {
            $lastExisting = $entity->attributes()->orderByDesc('default_order')->first();
            if (!empty($lastExisting)) {
                $lastOrder = $lastExisting->default_order + 1;
            }
        }

        /** @var AttributeService $attributeService */
        $attributeService = app()->make(AttributeService::class);

        /** @var Attribute $attribute */
        foreach ($this->entity->attributes()->orderBy('default_order', 'ASC')->get() as $attribute) {
            // Don't re-create existing attributes.
            if (in_array($attribute->name, $existing)) {
                continue;
            }


            list($type, $value) = $attributeService->randomAttribute($attribute->type_id, $attribute->value);

            Attribute::create([
                'entity_id' => $entity->id,
                'name' => $attribute->name,
                'value' => $value,
                'default_order' => $lastOrder + $order,
                'is_private' => $attribute->is_private,
                'is_star' => $attribute->is_star,
                'type_id' => $type,
            ]);
            $order++;
        }

        // Loop through parents
        /** @var Tag $children */
        foreach ($this->ancestors()->with('entity')->get() as $children) {
            foreach ($children->entity->attributes()->orderBy('default_order', 'ASC')->get() as $attribute) {
                // Don't re-create existing attributes.
                if (in_array($attribute->name, $existing)) {
                    continue;
                }
                list($type, $value) = $attributeService->randomAttribute($attribute->type_id, $attribute->value);

                Attribute::create([
                    'entity_id' => $entity->id,
                    'name' => $attribute->name,
                    'value' => $value,
                    'default_order' => $order,
                    'is_private' => $attribute->is_private,
                    'is_star' => $attribute->is_star,
                    'type_id' => $type,
                ]);
                $order++;
            }
        }

        return $order;
    }

    /**
     * Get the entity_type id from the entity_types table
     * @return int
     */
    public function entityTypeId(): int
    {
        return (int) config('entities.ids.attribute_template');
    }

    /**
     * Determine if the attribute templates has visible (to show on the entity creation _attributes tab) attributes
     * @param array $names
     * @return bool
     */
    public function hasVisibleAttributes($names = []): bool
    {
        if (!$this->entity) {
            return false;
        }
        $visible = false;
        foreach ($this->entity->attributes()->get() as $attribute) {
            if (!in_array($attribute->name, $names)) {
                $visible = true;
            }
        }
        return $visible;
    }

    /**
     * Attribute Templates have no entry field
     * @return mixed|string
     */
    public function entry()
    {
        return '';
    }

    /**
     * Determine if the model has profile data to be displayed
     * @return bool
     */
    public function showProfileInfo(): bool
    {
        return (bool) ($this->entityType);
    }

    /**
     * Define the fields unique to this model that can be used on filters
     * @return string[]
     */
    public function filterableColumns(): array
    {
        return [
            'attribute_template_id',
        ];
    }
}
