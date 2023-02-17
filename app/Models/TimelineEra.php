<?php

namespace App\Models;

use App\Models\Concerns\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TimelineEra
 * @package App\Models
 *
 * @property int $id
 * @property int|null $timeline_id
 * @property string $name
 * @property string $entry
 * @property string $abbreviation
 * @property string|int $start_year
 * @property string|int $end_year
 * @property bool $is_collapsed
 * @property int|null $position
 *
 * @property Timeline $timeline
 * @property TimelineElement[] $elements
 * @property TimelineElement[] $orderedElements
 *
 * @method static self|Builder ordered()
 */
class TimelineEra extends Model
{
    use SortableTrait;

    /** @var string[]  */
    protected $fillable = [
        'timeline_id',
        'name',
        'abbreviation',
        'entry',
        'start_year',
        'end_year',
        'is_collapsed',
    ];

    protected $sortable = [
        'name',
        'position',
        'abbreviation',
        'start_year',
        'end_year',
        'is_collapsed',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeline()
    {
        return $this->belongsTo(Timeline::class, 'timeline_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function elements()
    {
        return $this->hasMany(TimelineElement::class, 'era_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderedElements()
    {
        return $this->elements()
            ->ordered()
        ;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query)
    {
        return $query
            ->orderBy('position')
            ->orderBy('start_year')
            ->orderBy('end_year')
            ->orderBy('name');
    }

    /**
     * @return bool
     */
    public function collapsed(): bool
    {
        return $this->is_collapsed;
    }

    /**
     * Get the age header of the era
     * @return string
     */
    public function ages(): string
    {
        $from = mb_strlen($this->start_year);
        $to = mb_strlen($this->end_year);

        if ($from == 0 && $to == 0) {
            return '';
        }

        if ($from == 0) {
            return '< ' . $this->end_year;
        } elseif ($to == 0) {
            return '> ' . $this->start_year;
        }

        return $this->start_year . ' &mdash; ' . $this->end_year;
    }

    /**
     * @return bool
     */
    public function hasEntity(): bool
    {
        return false;
    }

    /**
     * Functions for the datagrid2
     * @return string
     */
    public function url(string $where): string
    {
        return 'timelines.timeline_eras.' . $where;
    }
    public function routeParams(array $options = []): array
    {
        return [$this->timeline->campaign_id, $this->timeline_id, $this->id];
    }

    /**
     * Override the get link
     * @return string
     */
    public function getLink(): string
    {
        return route('timelines.timeline_eras.edit', ['campaign' => $this->timeline->campaign_id, 'timeline' => $this->timeline_id, $this->id]);
    }

    /**
     * Override the tooltiped link for the datagrid
     * @param string|null $displayName
     * @return string
     */
    public function tooltipedLink(string $displayName = null): string
    {
        return '<a href="' . $this->getLink() . '">' .
            (!empty($displayName) ? $displayName : e($this->name)) .
            '</a>';
    }
}
