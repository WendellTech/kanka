<?php

namespace App\Http\Controllers\Entity;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntityAbility;
use App\Models\Ability;
use App\Models\Campaign;
use App\Models\Entity;
use App\Models\EntityAbility;
use App\Traits\GuestAuthTrait;

class AbilityController extends Controller
{
    use GuestAuthTrait;

    public function index(Campaign $campaign, Entity $entity)
    {
        $this->authEntityView($entity);

        $translations = [
            'all' => __('crud.visibilities.all'),
            'members' => __('crud.visibilities.members'),
            'admin-self' => __('crud.visibilities.admin-self'),
            'admin' => __('crud.visibilities.admin'),
            'self' => __('crud.visibilities.self'),
            'update' => __('crud.update'),
            'remove' => __('crud.remove'),
        ];
        $translations = json_encode($translations);

        return view('entities.pages.abilities.index', compact(
            'campaign',
            'entity',
            'translations'
        ));
    }

    public function create(Campaign $campaign, Entity $entity)
    {
        $this->authorize('update', $entity->child);

        return view('entities.pages.abilities.create', compact(
            'campaign',
            'entity'
        ));
    }

    public function store(StoreEntityAbility $request, Campaign $campaign, Entity $entity)
    {
        $this->authorize('update', $entity->child);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        $data = $request->only(['abilities', 'ability_id', 'visibility_id']);
        $data['entity_id'] = $entity->id;

        $success = '';
        if (is_array($data['abilities'])) {
            $abilities = [];
            foreach ($data['abilities'] as $abilityId) {
                /** @var Ability|null $ability */
                $ability = Ability::find($abilityId);
                if ($ability) {
                    $entityAbility = EntityAbility::create([
                        'entity_id' => $entity->id,
                        'ability_id' => $abilityId,
                        'visibility_id' => $data['visibility_id'],
                    ]);
                    $abilities[] = $ability->name;
                }
            }
            $success = __('entities/abilities.create.success_multiple', [
                'abilities' => implode(', ', $abilities),
                'entity' => $entity->name
            ]);
        } elseif (!empty($data['ability_id'])) {
            // Allow adding a single ability through the API
            $entityAbility = new EntityAbility();
            unset($data['abilities']);
            $entityAbility = $entityAbility->create($data);

            $success = trans('entities/abilities.create.success', [
                'ability' => $entityAbility->ability->name,
                'entity' => $entity->name
            ]);
        }

        return redirect()
            ->route('entities.entity_abilities.index', [$campaign, $entity])
            ->with('success', $success);
    }

    public function show(Campaign $campaign, Entity $entity, EntityAbility $entityAbility)
    {
        return redirect()
            ->route('entities.entity_abilities.index', [$campaign, $entity->id]);
    }

    public function edit(Campaign $campaign, Entity $entity, EntityAbility $entityAbility)
    {
        $this->authorize('update', $entity->child);
        $ability = $entityAbility;

        return view('entities.pages.abilities.update', compact(
            'campaign',
            'entity',
            'ability'
        ));
    }

    public function update(StoreEntityAbility $request, Campaign $campaign, Entity $entity, EntityAbility $entityAbility)
    {
        $this->authorize('update', $entity->child);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        $data = $request->only(['ability_id', 'visibility_id', 'note']);

        $entityAbility->update($data);

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
            ->route('entities.entity_abilities.index', [$campaign, $entity->id]);
    }

    public function destroy(Campaign $campaign, Entity $entity, EntityAbility $entityAbility)
    {
        $this->authorize('update', $entity->child);

        if (!$entityAbility->delete()) {
            abort(500);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()
            ->route('entities.entity_abilities.index', [$campaign, $entity]);
    }
}
