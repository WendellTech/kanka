<?php

namespace App\View\Components\Widgets\Previews;

use App\Facades\Avatar;
use App\Facades\Img;
use App\Models\Campaign;
use App\Models\CampaignDashboardWidget;
use App\Models\Entity;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Head extends Component
{
    public Campaign $campaign;
    public CampaignDashboardWidget $widget;
    public Entity $entity;

    /**
     * Create a new component instance.
     */
    public function __construct(
        Campaign $campaign,
        CampaignDashboardWidget $widget,
        Entity $entity,
    ) {
        $this->campaign = $campaign;
        $this->widget = $widget;
        $this->entity = $entity;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.widgets.previews.head')
            ->with('images', $this->headerImage());
    }

    protected function headerImage(): null|array
    {
        if ($this->widget->conf('entity-header') && $this->campaign->boosted() && $this->entity->header_image) {
            return [
                'wide_xl' => $this->entity->thumbnail(800, 450, 'header_image'),
                'wide_sm' => $this->entity->thumbnail(480, 270, 'header_image'),
                'square' => $this->entity->thumbnail(800, 800, 'header_image'),
            ];
        } elseif ($this->widget->conf('entity-header') && $this->campaign->boosted() && $this->widget->entity->header) {
            return [
                'wide_xl' => $this->entity->header->getUrl(800, 450),
                'wide_sm' => $this->entity->header->getUrl(480, 270),
                'square' => $this->entity->header->getUrl(800, 800),
            ];
        } elseif ($this->entity->image) {
            return [
                'wide_xl' => Img::crop(800, 450)->url($this->entity->image->path),
                'wide_sm' => Img::crop(480, 270)->url($this->entity->image->path),
                'square' => Img::crop(800, 800)->url($this->entity->image->path),
            ];
        } elseif ($this->entity->image_path) {
            return [
                'wide_xl' => Avatar::entity($this->entity)->size(800, 450)->thumbnail(),
                'wide_sm' => Avatar::entity($this->entity)->size(480, 270)->thumbnail(),
                'square' => Avatar::entity($this->entity)->size(800, 800)->thumbnail(),
            ];
        }
        return null;
    }
}
