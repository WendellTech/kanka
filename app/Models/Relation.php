<?php

namespace App\Models;

use App\Models\Concerns\Blameable;
use App\Models\Concerns\HasFilters;
use App\Models\Concerns\Orderable;
use App\Models\Concerns\Paginatable;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\Sortable;
use App\Models\Concerns\SortableTrait;
use App\Models\Scopes\Pinnable;
use App\Traits\VisibilityIDTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Relation
 * @package App\Models
 * @property int $id
 * @property string $relation
 * @property int $attitude
 * @property int|null $mirror_id
 * @property int $owner_id
 * @property int $campaign_id
 * @property int $target_id
 * @property bool $is_pinned
 * @property string $colour
 * @property string $marketplace_uuid
 *
 * @property Relation|null $mirror
 * @property Entity|null $target
 * @property Entity $owner
 * @property int $created_at
 * @property int $updated_at
 */
class Relation extends Model
{
    use Blameable;
    use HasFactory;
    use HasFilters;
    use Orderable;
    use Paginatable;
    use Pinnable;
    use Searchable;
    use Sortable;
    use SortableTrait
    ;
    /**
     * Traits
     */
    use VisibilityIDTrait;

    /** @var string[]  */
    protected $fillable = [
        'campaign_id',
        'owner_id',
        'target_id',
        'relation',
        'visibility_id',
        'mirror_id',
        'attitude',
        'is_pinned',
        'colour',
    ];

    protected array $sortable = [
        'relation',
        'target.name',
        'attitude',
        'visibility_id',
    ];

    /**
     * Fields that can be sorted on
     */
    public array $sortableColumns = [
        'owner_id',
        'target_id',
        'relation',
        'attitude',
        'is_pinned',
        'mirror_id',
        'visibility_id',
    ];

    public $defaultOrderField = 'relation';

    /**
     * @param string $order
     */
    public function scopeOrdered(Builder $query, $order = 'asc'): Builder
    {
        return $query
            ->orderBy('relation', $order)
            ->orderBy('attitude', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\Entity', 'owner_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo('App\Models\Entity', 'target_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mirror()
    {
        return $this->belongsTo('App\Models\Relation', 'mirror_id', 'id');
    }

    /**
     * Check if a relation is mirrored
     */
    public function isMirrored(): bool
    {
        return !empty($this->mirror_id);
    }

    /**
     * Create a mirror of the relation
     */
    public function createMirror(): void
    {
        $target = request()->get('target_relation');
        $mirror = Relation::create([
            'owner_id' => $this->target_id,
            'target_id' => $this->owner_id,
            'campaign_id' => $this->campaign_id,
            'relation' => !empty($target) ? $target : $this->relation,
            'attitude' => $this->attitude,
            'colour' => $this->colour,
            'visibility_id' => $this->visibility_id,
            'is_pinned' => $this->isPinned(),
            'mirror_id' => $this->id,
        ]);

        // Update this relation to keep track of everything
        $this->update(['mirror_id' => $mirror->id]);
    }

    /**
     * Performance with for datagrids
     */
    public function scopePreparedWith(Builder $query): Builder
    {
        return $query
            ->with([
                'owner',
                'target',
            ])
            ->has('owner')
            ->has('target')
        ;
    }

    /**
     * Performance with for datagrids
     */
    public function scopePreparedSelect(Builder $query): Builder
    {
        return $query
            ->select(['id', 'target_id', 'owner_id', 'relation', 'mirror_id', 'is_pinned', 'attitude', 'visibility_id', 'colour'])
        ;
    }

    /**
     * When setting the colour, remove the '#' from the db
     * @param string $colour
     */
    public function setColourAttribute($colour)
    {
        $this->attributes['colour'] = ltrim($colour, '#');
    }

    /**
     * When getting the colour, remove the '#' from the db
     */
    public function getColourAttribute(): string
    {
        if (empty($this->attributes['colour'])) {
            return '';
        }
        return '#' . $this->attributes['colour'];
    }

    public function getEntityType()
    {
        return 'relation';
    }

    /**
     * Faker event
     */
    public function crudSaved()
    {
        return $this;
    }

    /** Fake entity type ID */
    public function entityTypeID(): int
    {
        return 0;
    }

    /**
     * Functions for the datagrid2
     */
    public function deleteName(): string
    {
        return (string) $this->relation;
    }
    public function url(string $where): string
    {
        return 'entities.relations.' . $where;
    }
    public function routeParams(array $options = []): array
    {
        return $options + ['entity' => $this->owner_id, 'relation' => $this->id, 'mode' => 'table'];
    }
    public function actionDeleteConfirmOptions(): array
    {
        return ['mirrored' => $this->isMirrored()];
    }

    /**
     * Relations don't use the default filterable columns available to entities
     */
    protected function defaultFilterableColumns(): array
    {
        return [];
    }

    /**
     * Define the fields unique to this model that can be used on filters
     * @return string[]
     */
    public function filterableColumns(): array
    {
        return [
            'name',
            'attitude',
            'relation',
            'owner_id',
            'target_id',
            'is_pinned',
            'is_mirrored',
        ];
    }

    public function hasSearchableFields(): bool
    {
        return false;
    }

    public function hasEntityType(): bool
    {
        return false;
    }
}
