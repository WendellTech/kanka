<?php

namespace App\Http\Controllers;

use App\Datagrids\Filters\JournalFilter;
use App\Models\Campaign;
use App\Models\Journal;
use App\Http\Requests\StoreJournal;
use App\Traits\TreeControllerTrait;

class JournalController extends CrudController
{
    /**
     * Tree / Nested Mode
     */
    use TreeControllerTrait;
    protected $treeControllerParentKey = 'journal_id';

    /**
     * @var string
     */
    protected string $view = 'journals';
    protected string $route = 'journals';
    protected $module = 'journals';

    /** @var string Model*/
    protected $model = \App\Models\Journal::class;

    /** @var string Filter */
    protected $filter = JournalFilter::class;

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJournal $request, Campaign $campaign)
    {
        return $this->campaign($campaign)->crudStore($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign, Journal $journal)
    {
        return $this->campaign($campaign)->crudShow($journal);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign, Journal $journal)
    {
        return $this->campaign($campaign)->crudEdit($journal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreJournal $request, Campaign $campaign, Journal $journal)
    {
        return $this->campaign($campaign)->crudUpdate($request, $journal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign, Journal $journal)
    {
        return $this->campaign($campaign)->crudDestroy($journal);
    }
}
