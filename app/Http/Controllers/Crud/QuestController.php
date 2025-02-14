<?php

namespace App\Http\Controllers\Crud;

use App\Datagrids\Filters\QuestFilter;
use App\Http\Controllers\CrudController;
use App\Http\Requests\StoreQuest;
use App\Models\Campaign;
use App\Models\Quest;
use App\Traits\TreeControllerTrait;

class QuestController extends CrudController
{
    use TreeControllerTrait;

    /**
     */
    protected string $view = 'quests';
    protected string $route = 'quests';
    protected $module = 'quests';

    /** @var string Model */
    protected $model = \App\Models\Quest::class;

    /** @var string Filter */
    protected string $filter = QuestFilter::class;

    /**
     */
    public function store(StoreQuest $request, Campaign $campaign)
    {
        return $this->campaign($campaign)->crudStore($request);
    }

    /**
     */
    public function show(Campaign $campaign, Quest $quest)
    {
        return $this->campaign($campaign)->crudShow($quest);
    }

    /**
     */
    public function edit(Campaign $campaign, Quest $quest)
    {
        return $this->campaign($campaign)->crudEdit($quest);
    }

    /**
     */
    public function update(StoreQuest $request, Campaign $campaign, Quest $quest)
    {
        return $this->campaign($campaign)->crudUpdate($request, $quest);
    }

    /**
     */
    public function destroy(Campaign $campaign, Quest $quest)
    {
        return $this->campaign($campaign)->crudDestroy($quest);
    }
}
