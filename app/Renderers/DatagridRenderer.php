<?php

namespace App\Renderers;

use App\Facades\CampaignLocalization;
use App\Models\Campaign;
use App\Models\Entity;
use App\Models\Journal;
use App\Models\Location;
use App\Models\MiscModel;
use App\Models\Relation;
use App\Services\FilterService;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Collective\Html\FormFacade as Form;
use Illuminate\Support\Str;

class DatagridRenderer
{
    protected array $columns = [];

    protected LengthAwarePaginator|Collection|array $data = [];

    protected array $options = [];
    protected Collection|LengthAwarePaginator $models;
    protected User|null $user;

    protected FilterService|null $filterService = null;

    /** @var Campaign|bool */
    protected $campaign;

    /**
     * @var null|string
     */
    protected null|string $nestedFilter = null;

    /**
     *
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }


    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function models(Collection|LengthAwarePaginator $models): self
    {
        $this->models = $models;
        return $this;
    }

    public function service($service): self
    {
        $this->filterService = $service;
        return $this;
    }

    /**
     * @param FilterService $filterService
     * @param array $columns
     * @param array $data
     * @param array $options
     * @return string
     */
    public function render(
        FilterService $filterService,
        $columns = [],
        $data = [],
        $options = []
    ): self {
        $this->columns = $columns;
        $this->models = $data;
        $this->options = $options;

        $this->filterService = $filterService;

        return $this;
    }

    public function __toString(): string
    {
        $html = '<table id="' . $this->getOption('baseRoute') . '" class="table table-striped' .
            ($this->nestedFilter ? ' table-nested' : null) . '">';
        $html .= '<thead><tr>';
        $html .= $this->renderColumns();
        $html .=  '</tr></thead>';
        $html .=  '<tbody>';
        $html .= $this->renderRows();
        $html .=  '</tbody></table>';

        return $html;
    }

    /**
     * Render the header columns
     */
    private function renderColumns()
    {
        $html = '';
        // Checkbox for delete
        if (auth()->check()) {
            $html .= '<th>' . Form::checkbox('all', 1, false, ['id' => 'datagrid-select-all']) . '</th>';
        }

        foreach ($this->columns as $column) {
            $html .= $this->renderHeadColumn($column);
        }
        // Admin column

        $html .= '<th class="text-right">' . $this->renderFilters() . '</th>';
        return $html;
    }

    /**
     * @param string|array $column
     */
    private function renderHeadColumn($column)
    {
        // Easy mode: A string. We want to return it directly since it's so easy.
        if (is_string($column)) {
            if ($column == 'name') {
                return "<th>" . $this->route($column) . "</th>\n";
            } else {
                return "<th class='hidden-xs hidden-sm'>" . $this->route($column) . "</th>\n";
            }
        }

        // Check visibility
        if (isset($column['visible']) && $column['visible'] == false) {
            return null;
        }

        $html = null;
        $class = null;

        if (!empty($column['type'])) {
            // We have a type so we know what to do
            $type = $column['type'];
            $class = $column['type'];
            if ($type == 'avatar') {
                $class = !empty($column['parent']) ? 'hidden-xs hidden-sm' : $class . ' w-14';
            //$html = null;
            } elseif ($type == 'location') {
                $class .= '  hidden-xs hidden-sm';
                $html = $this->route('location.name', __('entities.location'));
            } elseif ($type == 'organisation') {
                $class .= '  hidden-xs hidden-sm';
                $html = $this->route('organisation.name', __('entities.organisation'));
            } elseif ($type == 'character') {
                $class .= '  hidden-xs hidden-sm';
                $html = $this->route(
                    'character.name',
                    !empty($column['label']) ? $column['label'] : __('entities.character')
                );
            } elseif ($type == 'entity') {
                $class .= '  hidden-xs hidden-sm';
                $html = $this->route(
                    'entity.name',
                    !empty($column['label']) ? $column['label'] : __('crud.fields.entity')
                );
            } elseif ($type == 'is_private') {
                // Viewers can't see private
                if (!$this->user || !$this->user->isAdmin()) {
                    return null;
                }
                $html = $this->route(
                    'is_private',
                    '<i class="fa-solid fa-lock" title="' . __('crud.fields.is_private') . '"></i>'
                );
                $class = 'min-w-12';
            } elseif ($type == 'calendar_date') {
                $class .= ' hidden-xs hidden-sm';
                $html = $this->route('calendar_date', __('crud.fields.calendar_date'));
            } else {
                // No idea what is expected
                $html = null;
            }
        } else {
            // Now the 'fun' starts
            $class .= Arr::get($column, 'class', '  hidden-xs hidden-sm');
            if (!empty($column['label'])) {
                $label = $column['label'];

                // We have a label, that's nice. If we have a custom render, we probably can't orderBy
                if (!empty($column['disableSort'])) {
                    $html = $label;
                } else {
                    // So we have a label and no renderer, so we can order by. We just need a field
                    $html = $this->route($column['field'], $label);
                }
            } else {
                // No label? Sure, we can do this
                $html = null;
            }
        }

        return "<th" . (!empty($class) ? " class=\"{$class}\"" : null) . ">{$html}</th>\n";
    }

    /**
     * @param string|null $label
     * @param string|null $field
     * @return string
     */
    private function route(string $field = null, string $label = null)
    {
        // Field is label
        if (empty($label)) {
            $label = $this->trans($field);
        }

        // If we are in public mode (bots) don't make this links
        if (!auth()->check()) {
            return $label;
        }

        $routeOptions = [
            'campaign' => $this->getCampaign(),
            'order' => $field ,
            'page' => request()->get('page')
        ];
        if (request()->get('_from', false) == 'quicklink') {
            $routeOptions['_from'] = 'quicklink';
        }

        if (!empty($this->nestedFilter)) {
            $val = request()->get($this->nestedFilter, null);
            if (!empty($val)) {
                $routeOptions[$this->nestedFilter] = $val;
            }
        }

        // Order by
        $order = $this->filterService->order();
        $orderImg = ' <i class="fa-solid fa-sort"></i>';
        if (!empty($order) && isset($order[$field])) {
            $direction = 'down';
            if ($order[$field] != 'DESC') {
                $routeOptions['desc'] = true;
                $direction = 'up';
            }
            $orderImg = ' <i class="fa-solid fa-sort-' . $direction . '"></i>';
        }

        return "<a href='" .
            url()->route($this->getOption('route'), $routeOptions) . "'>" . $label . $orderImg . "</a>";
    }

    /**
     *
     */
    private function renderRows()
    {
        $html = '';
        $rows = 0;
        foreach ($this->models as $model) {
            $rows++;
            $html .= $this->renderRow($model);
        }

        // Render an empty row
        if ($rows == 0) {
            $html .= '<tr><td colspan="' . (count($this->columns)+2) . '"><i>'
                . __('crud.datagrid.empty') . '</i></td>';
        }
        return $html;
    }

    /**
     * @param MiscModel|Relation $model
     * @return string
     */
    private function renderRow(MiscModel|Relation $model): string
    {
        $useEntity = $this->getOption('disableEntity') !== true;
        // Should never happen...
        if ($useEntity && empty($model->entity)) {
            return '';
        }

        $html = '<tr data-id="' . $model->id . '" '
            . (!empty($model->type) ? 'data-type="' . Str::slug($model->type) . '" ' : null)
            . ($useEntity ? 'data-entity-id="' . $model->entity->id . '" data-entity-type="' . $model->entity->type() . '"' : null);
        if (!empty($this->options['row']) && !empty($this->options['row']['data'])) {
            foreach ($this->options['row']['data'] as $name => $data) {
                $html .= ' ' . $name . '="' . $data($model) . '"';
            }
        }
        $html .= '>';

        // Delete
        if (auth()->check()) {
            $html .= '<td>' . Form::checkbox('model[]', $model->id, false) . '</td>';
        }

        foreach ($this->columns as $column) {
            $html .= $this->renderColumn($column, $model);
        }
        $html .= $model instanceof MiscModel ? $this->renderEntityActionRow($model) : $this->renderActionRow($model);

        return $html . '</tr>';
    }

    /**
     * @param string|array $column
     * @param MiscModel|Journal|Location $model
     * @return string|null
     */
    private function renderColumn(string|array $column, $model)
    {
        $class = null;
        $content = null;

        // Easy mode: String. Just return it, no need to make it complicated.
        if (is_string($column)) {
            // Just for name, a link to the view
            if ($column == 'name') {
                $content = $model->tooltipedLink();
            } else {
                // Handle boolean values (has, is)
                if ($this->isBoolean($column)) {
                    $icon = $column == 'is_dead' ? 'ra ra-skull' : 'fa-solid fa-check-circle';
                    $content = $model->{$column} ? '<i class="' . $icon . '"></i>' : '';
                } else {
                    $content = e($model->{$column});
                }
                $class = 'hidden-xs hidden-sm';
            }
            return '<td' . (!empty($class) ? ' class="' . $class . '"' : null) . '>' . $content . '</td>';
        }

        // Check visibility
        if (isset($column['visible']) && $column['visible'] == false) {
            return null;
        }

        // Start with a pre-defined "type"
        if (!empty($column['type'])) {
            $type = $column['type'];
            if ($type == 'avatar') {
                $who = !empty($column['parent']) ? $model->{$column['parent']} : $model;
                if ($who instanceof Entity) {
                    $who = $who->child;
                }
                $who = !empty($column['parent']) ? $model->{$column['parent']} : $model;
                $class = !empty($column['parent']) ? 'hidden-xs hidden-sm' : $class;
                if (!empty($who)) {
                    $whoRoute = !empty($column['parent_route'])
                        ? (is_string($column['parent_route'])
                            ? $column['parent_route']
                            : $column['parent_route']($model))
                        : $this->getOption('baseRoute');
                    $route = route($whoRoute . '.show', ['campaign' => $this->getCampaign()->id, $who]);
                    $content = '<a class="entity-image" style="background-image: url(\'' . $who->thumbnail() .
                        '\');" title="' . e($who->name) . '" href="' . $route . '"></a>';
                }
            } elseif ($type == 'location') {
                $class = 'hidden-xs hidden-sm';
                if (method_exists($model, 'location')) {
                    // @phpstan-ignore-next-line
                    $content = $model->location?->tooltipedLink();
                } elseif (method_exists($model, 'parentLocation')) {
                    // @phpstan-ignore-next-line
                    $content = $model->parentLocation?->tooltipedLink();
                }
            } elseif ($type == 'character') {
                $class = 'hidden-xs hidden-sm';
                if (method_exists($model, 'character')) {
                    // @phpstan-ignore-next-line
                    $content = $model->character?->tooltipedLink();
                }
            } elseif ($type == 'organisation') {
                $class = 'hidden-xs hidden-sm';
                if (method_exists($model, 'organisation')) {
                    // @phpstan-ignore-next-line
                    $content = $model->organisation?->tooltipedLink();
                }
            } elseif ($type == 'entity') {
                $class = 'hidden-xs hidden-sm';
                if ($model->entity) {
                    $content = $model->entity->tooltipedLink();
                }
            } elseif ($type == 'is_private') {
                // Viewer can't see private
                if (!$this->user || !$this->user->isAdmin()) {
                    return null;
                }
                $content = $model->is_private ?
                    '<i class="fa-solid fa-lock" title="' . __('crud.is_private') . '"></i>' :
                    '<br />';
            } elseif ($type == 'calendar_date') {
                $class = 'hidden-xs hidden-sm';
                /** @var Journal $model */
                if ($model->hasCalendar()) {
                    $reminder = $model->calendarReminder();
                    $content = link_to_route(
                        'calendars.show',
                        $reminder->readableDate(),
                        [$this->getCampaign(), $reminder->calendar_id, 'month' => $reminder->month, 'year' => $reminder->year]
                    );
                }
            } else {
                // Exception
                $content = 'ERR_UNKNOWN_TYPE';
            }
        } elseif (!empty($column['render'])) {
            // If it's not a type, do we have a renderer?
            $content = $column['render']($model, $column);
            $class = Arr::get($column, 'class', 'hidden-xs hidden-sm');
        } elseif (!empty($column['field'])) {
            // A field was given? This could be when a field needs another label than anticipated.
            $content = $model->{$column['field']};
            $class = 'hidden-xs hidden-sm';
        } else {
            // I have no idea.
            $content = 'ERR_UNKNOWN';
        }

        return '<td' . (!empty($class) ? ' class="' . $class . '"' : null) . '>' . $content . '</td>';
    }

    /**
     * @param string $option
     * @return mixed|null
     */
    private function getOption($option)
    {
        if (!empty($this->options[$option])) {
            return $this->options[$option];
        }
        return null;
    }

    /**
     * @param string $field
     * @return string
     */
    private function trans(string $field = '')
    {
        $crudFields = ['name', 'type'];
        if (in_array($field, $crudFields)) {
            return __('crud.fields.' . $field);
        }
        $trans = $this->getOption('trans');
        if (!empty($trans)) {
            return  __(rtrim($trans, '.') . '.' . $field);
        }
        // No idea what to do!
        return $field;
    }

    /**
     * @param MiscModel $model
     * @return string
     */
    private function renderEntityActionRow(MiscModel $model): string
    {
        $content = '';
        $actions = $model->datagridActions($this->getCampaign());
        if (!empty($actions)) {
            $content = '
        <div class="dropdown">
            <a class="dropdown-toggle cursor-pointer" data-toggle="dropdown" aria-expanded="false" data-placement="right" data-tree="escape">
                <i class="fa-solid fa-ellipsis-v" data-tree="escape"></i>
                <span class="sr-only">' . __('crud.actions.actions') . '</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                ' . implode("\n", $actions) . '
            </ul>
        </div>
        ';
        }

        return '<td class="text-center table-actions">' . $content . '</td>';
    }

    private function renderActionRow($model): string
    {
        $actions = '';
        if ($this->user && $this->user->can('update', $model)) {
            $actions .= ' <a href="'
                . $model->getLink('edit')
                . '" title="' . __('crud.edit') . '">
                <i class="fa-solid fa-edit" aria-hidden="true"></i>
            </a>';
        }
        return '<td class="text-center table-actions">' . $actions . '</td>';
    }

    /**
     * Determin if a column is a boolean column
     * @param string $column
     * @return bool
     */
    private function isBoolean(string $column)
    {
        return Str::startsWith($column, ['is_', 'has_']);
    }

    /**
     * Render the filter
     * @return string
     */
    protected function renderFilters()
    {
        return '';
    }

    /**
     * Tell the rendered that this is a nested view
     * @param string $key
     * @return $this
     */
    public function nested(string $key = 'parent_id'): self
    {
        $this->nestedFilter = $key;
        return $this;
    }

    /**
     * @param Campaign $campaign
     * @return $this
     */
    public function campaign(Campaign $campaign): self
    {
        $this->campaign = $campaign;
        return $this;
    }

    /**
     * @return Campaign
     */
    protected function getCampaign(): Campaign
    {
        if ($this->campaign === null) {
            $this->campaign = CampaignLocalization::getCampaign();
        }
        return $this->campaign;
    }
}
