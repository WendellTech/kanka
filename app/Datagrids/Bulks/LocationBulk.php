<?php

namespace App\Datagrids\Bulks;

class LocationBulk extends Bulk
{
    protected array $fields = [
        'name',
        'type',
        'location_id',
        'tags',
        'private_choice',
    ];
}
