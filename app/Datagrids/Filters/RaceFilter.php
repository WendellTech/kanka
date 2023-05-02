<?php

namespace App\Datagrids\Filters;

use App\Models\Race;
use App\Models\Location;

class RaceFilter extends DatagridFilter
{
    /**
     * Filters available for races
     */
    public function __construct()
    {
        $this
            ->add('name')
            ->add('type')
            ->add([
                'field' => 'race_id',
                'label' => __('crud.fields.parent'),
                'type' => 'select2',
                'route' => route('races.find'),
                'placeholder' =>  __('crud.placeholders.parent'),
                'model' => Race::class,
            ])
            ->add([
                'field' => 'location_id',
                'label' => __('entities.location'),
                'type' => 'select2',
                'route' => route('locations.find'),
                'placeholder' =>  __('crud.placeholders.location'),
                'model' => Location::class,
                'withChildren' => true,
            ])
            ->isPrivate()
            ->template()
            ->hasImage()
            ->hasPosts()
            ->hasEntityFiles()
            ->hasAttributes()
            ->tags()
            ->attributes()
        ;
    }
}
