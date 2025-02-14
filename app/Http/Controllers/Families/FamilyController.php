<?php

namespace App\Http\Controllers\Families;

use App\Facades\Datagrid;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Family;
use App\Traits\CampaignAware;
use App\Traits\Controllers\HasDatagrid;
use App\Traits\Controllers\HasSubview;
use App\Traits\GuestAuthTrait;

class FamilyController extends Controller
{
    use CampaignAware;
    use GuestAuthTrait;
    use HasDatagrid;
    use HasSubview;

    public function index(Campaign $campaign, Family $family)
    {
        $this->campaign($campaign)->authView($family);

        $options = ['campaign' => $campaign, 'family' => $family];
        $filters = [];
        if (request()->has('parent_id')) {
            $options['parent_id'] = $family->id;
            $filters['parent'] = $family->id;
        }
        Datagrid::layout(\App\Renderers\Layouts\Family\Family::class)
            ->route('families.families', $options)
        ;

        // @phpstan-ignore-next-line
        $this->rows = $family
            ->descendants()
            ->sort(request()->only(['o', 'k']), ['name' => 'asc'])
            ->filter($filters)
            ->with([
                'family', 'family.entity',
                'location', 'location.entity',
                'entity', 'entity.tags', 'entity.tags.entity', 'entity.image'
            ])
            ->paginate(15);

        if (request()->ajax()) {
            return $this->campaign($campaign)->datagridAjax();
        }

        return $this
            ->campaign($campaign)
            ->subview('families.families', $family);
    }
}
