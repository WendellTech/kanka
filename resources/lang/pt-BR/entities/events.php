<?php

return [
    'fields'    => [
        'type'  => 'Tipo de Evento',
    ],
    'helpers'   => [
        'characters'    => 'Definir o tipo como data de nascimento ou morte para este personagem irá calcular automaticamente sua idade. more.',
    ],
    'show'      => [
        'actions'   => [
            'add'   => 'Adicionar lembrete',
        ],
        'title'     => 'Lembretes :name',
    ],
    'types'     => [
        'birth'     => 'Nascimento',
        'death'     => 'Morte',
        'primary'   => 'Primário',
    ],
];
