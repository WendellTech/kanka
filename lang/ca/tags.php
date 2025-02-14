<?php

return [
    'children'      => [
        'actions'   => [
            'add'   => 'Afegeix a l\'etiqueta',
        ],
        'create'    => [
            'success'   => 'S\'ha afegit l\'etiqueta :name a l\'entitat.',
            'title'     => 'Afegeix una etiqueta a :name',
        ],
    ],
    'create'        => [
        'title' => 'Nova etiqueta',
    ],
    'destroy'       => [],
    'edit'          => [],
    'fields'        => [
        'children'  => 'Entitats niades',
    ],
    'helpers'       => [
        'nested_without'    => 'S\'estan mostrant les etiquetes sense pare. Feu clic a la fila d\'un mapa per a mostrar-ne les descendents.',
    ],
    'hints'         => [
        'children'  => 'Aquí es mostren totes les entitats que pertanyen directament a aquesta etiqueta i a totes les etiquetes niades.',
        'tag'       => 'Aquí es mostren totes les etiquetes que estan directament sota aquesta etiqueta.',
    ],
    'index'         => [],
    'placeholders'  => [
        'type'  => 'Tradicions, guerres, història, religió...',
    ],
    'show'          => [
        'tabs'  => [
            'children'  => 'Entitats niades',
        ],
    ],
    'tags'          => [],
];
