<?php

namespace App\Http\Controllers\Families;

use App\Facades\Datagrid;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCharacterFamily;
use App\Models\Campaign;
use App\Models\Family;
use App\Traits\CampaignAware;
use App\Traits\Controllers\HasDatagrid;
use App\Traits\Controllers\HasSubview;
use App\Traits\GuestAuthTrait;

class MemberController extends Controller
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
        $relation = 'allMembers';
        if (request()->has('family_id')) {
            $options['family_id'] = $family->id;
            $filters['family_id'] = $options['family_id'];
            $relation = 'members';
        }
        Datagrid::layout(\App\Renderers\Layouts\Family\Character::class)
            ->route('families.members', $options)
        ;

        $this->rows = $family
            ->{$relation}()
            ->sort(request()->only(['o', 'k']), ['name' => 'asc'])
            ->filter($filters)
            ->with([
                'location', 'location.entity',
                'families', 'families.entity',
                'races', 'races.entity',
                'entity', 'entity.tags', 'entity.tags.entity', 'entity.image'
            ])
            ->has('entity')
            ->paginate(15);

        // Ajax Datagrid
        if (request()->ajax()) {
            return $this->campaign($campaign)->datagridAjax();
        }
        return $this
            ->campaign($campaign)
            ->subview('families.members', $family);
    }

    public function create(Campaign $campaign, Family $family)
    {
        $this->authorize('update', $family);

        return view('families.members.create', [
            'campaign' => $campaign,
            'model' => $family,
        ]);
    }

    public function store(StoreCharacterFamily $request, Campaign $campaign, Family $family)
    {
        $this->authorize('update', $family);

        $newMembers = $family->members()->syncWithoutDetaching($request->members);

        return redirect()->route('entities.show', [$campaign, $family->entity])
            ->with('success', trans_choice('families.members.create.success', count($newMembers['attached']), ['count' => count($newMembers['attached'])]));
    }
}
