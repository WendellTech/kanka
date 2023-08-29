<?php

return [
    'actions'           => [
        'customise' => 'Customise sidebar',
    ],
    'create'            => [
        'title' => 'New bookmark',
    ],
    'edit'              => [
        'title' => 'Bookmark :name',
    ],
    'fields'            => [
        'active'            => 'Active',
        'dashboard'         => 'Dashboard',
        'default_dashboard' => 'Default dashboard',
        'entity'            => 'Entity',
        'filters'           => 'Filters',
        'is_nested'         => 'Nested',
        'menu'              => 'Subpage',
        'position'          => 'Position',
        'random'            => 'Random',
        'random_type'       => 'Random Entity Type',
        'selector'          => 'Bookmark Configuration',
        'target'            => 'Target',
        'type'              => 'Entity List',
    ],
    'helpers'           => [
        'active'            => 'Inactive bookmarks won\'t appear in the interface.',
        'dashboard'         => 'Have the bookmarks target one of the campaign\'s custom dashboards.',
        'default_dashboard' => 'Link to the campaign\'s default dashboard instead. A custom dashboard still needs to be selected.',
        'entity'            => 'Set up this bookmark to go directly to an entity. The :menu field controls which subpage of the entity is opened.',
        'position'          => 'Use this field to control in which ascending order the links appear in the menu.',
        'random'            => 'Use this field to have a bookmark pointing to a random entity. You can filter the link to only go to a specific entity type.',
        'selector'          => 'Configure where this bookmark goes when a user clicks on it in the sidebar.',
        'type'              => 'Set up this bookmark to go directly to a list of entities. To filter the results, copy parts of the url on the filtered entity list after the :? sign into the :filter field.',
    ],
    'placeholders'      => [
        'filters'   => 'location_id=15&type=city',
        'menu'      => 'Menu subpage (use the last text of the url)',
        'tab'       => '(deprecated)',
    ],
    'random_no_entity'  => 'No random entity found.',
    'random_types'      => [
        'any'   => 'Any entity',
    ],
    'reorder'           => [
        'success'   => 'Bookmarks reordered.',
        'title'     => 'Reorder bookmarks',
    ],
    'visibilities'      => [
        'is_active' => 'Show the bookmark in the sidebar',
    ],
];
