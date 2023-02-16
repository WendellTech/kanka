<?php

namespace App\Models;

use App\Models\Concerns\Acl;
use App\Models\Concerns\Nested;
use App\Models\Concerns\SortableTrait;
use App\Traits\CalendarDateTrait;
use App\Traits\CampaignTrait;
use App\Traits\ExportableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Journal
 * @package App\Models
 *
 * @property int $id
 * @property string $date
 * @property int|null $character_id
 * @property int|null $journal_id
 * @property int|null $author_id
 * @property Character|null $character
 * @property Entity|null $author
 * @property Journal|null $journal
 * @property Journal[] $journals
 * @property Journal[] $descendants
 */
class Journal extends MiscModel
{
    use Acl
    ;
    use CalendarDateTrait;
    use CampaignTrait;
    use ExportableTrait;
    use Nested;
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
        'date',
        'character_id',
        'location_id',
        'is_private',
        'journal_id',
        'author_id',

        // calendar date
        'calendar_id',
        'calendar_year',
        'calendar_month',
        'calendar_day',
    ];

    /**
     * Entity type
     * @var string
     */
    protected $entityType = 'journal';

    /**
     * Fields that can be sorted on
     * @var array
     */
    protected $sortableColumns = [
        'date',
        'calendar_date',
        'author.name',
    ];
    protected $sortable = [
        'name',
        'date',
        'character.name',
        //'character.name',
    ];

    /**
     * Nullable values (foreign keys)
     * @var string[]
     */
    public $nullableForeignKeys = [
        'location_id',
        //'character_id',
        'calendar_id',
        'journal_id',
        'author_id',
    ];

    /**
     * Performance with for datagrids
     * @param Builder $query
     * @return Builder
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
            'entity.calendarDateEvents',
            'author',
            'location' => function ($sub) {
                $sub->select('id', 'name', 'campaign_id');
            },
            'location.entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id');
            },
            'journal' => function ($sub) {
                $sub->select('id', 'name', 'campaign_id');
            },
            'journal.entity' => function ($sub) {
                $sub->select('id', 'name', 'entity_id', 'type_id');
            },
            'journals' => function ($sub) {
                $sub->select('id', 'journal_id');
            },
        ]);
    }

    /**
     * Only select used fields in datagrids
     * @return array
     */
    public function datagridSelectFields(): array
    {
        return ['journal_id', 'author_id', 'date', 'calendar_id', 'calendar_year', 'calendar_month', 'calendar_day'];
    }

    /**
     * @return array
     */
    public function menuItems(array $items = []): array
    {
        $items['second']['journals'] = [
            'name' => 'journals.show.tabs.journals',
            'route' => 'journals.journals',
            'count' => $this->descendants()->count(),
            'world' => true,
        ];
        return parent::menuItems($items);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
    /**
     * Get all journals in the journal and descendants
     */
    public function allJournals()
    {
        $locationIds = [$this->id];
        foreach ($this->descendants as $descendant) {
            $locationIds[] = $descendant->id;
        };

        $table = new Journal();
        return Journal::whereIn($table->getTable() . '.journal_id', $locationIds)->with('journal');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character()
    {
        return $this->belongsTo('App\Models\Character', 'character_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Entity', 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id');
    }

    /**
     * Get the entity_type id from the entity_types table
     * @return int
     */
    public function entityTypeId(): int
    {
        return (int) config('entities.ids.journal');
    }

    /**
     * Parent ID field for the Node trait
     * @return string
     */
    public function getParentIdName()
    {
        return 'journal_id';
    }

    /**
     * Specify parent id attribute mutator
     * @param int $value
     */
    public function setJournalIdAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }

    /**
     * Determine if the model has profile data to be displayed
     * @return bool
     */
    public function showProfileInfo(): bool
    {
        if (!empty($this->type) || !empty($this->date)) {
            return true;
        }

        if (!empty($this->author) || !empty($this->location)) {
            return true;
        }
        return (bool) (!empty($this->calendarReminder()));
    }

    /**
     * Define the fields unique to this model that can be used on filters
     * @return string[]
     */
    public function filterableColumns(): array
    {
        return [
            'date',
            'character_id',
            'location_id',
            'journal_id',
            'author_id',
            'date_start',
            'date_end',
        ];
    }
}
