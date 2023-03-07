<?php

namespace App\Http\Controllers;

use App\Datagrids\Filters\OrganisationFilter;
use App\Http\Requests\StoreOrganisation;
use App\Models\Campaign;
use App\Models\Organisation;
use App\Traits\TreeControllerTrait;

class OrganisationController extends CrudController
{
    use TreeControllerTrait;

    /**
     * @var string
     */
    protected string $view = 'organisations';
    protected string $route = 'organisations';
    protected $module = 'organisations';

    /** @var string */
    protected $model = \App\Models\Organisation::class;

    /** @var string Filter */
    protected $filter = OrganisationFilter::class;

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganisation $request, Campaign $campaign)
    {
        return $this->campaign($campaign)->crudStore($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign, Organisation $organisation)
    {
        return $this->campaign($campaign)->crudShow($organisation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign, Organisation $organisation)
    {
        return $this->campaign($campaign)->crudEdit($organisation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrganisation $request, Campaign $campaign, Organisation $organisation)
    {
        return $this->campaign($campaign)->crudUpdate($request, $organisation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign, Organisation $organisation)
    {
        return $this->campaign($campaign)->crudDestroy($organisation);
    }
}
