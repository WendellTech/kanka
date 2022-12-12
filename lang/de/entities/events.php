<?php

return [
    'fields'    => [
        'type'  => 'Ereignistyp',
    ],
    'helpers'   => [
        'characters'    => 'Wenn Sie den Typ entweder als Geburts- oder als Todesdatum für diesen Charakter festlegen, wird automatisch dessen Alter berechnet. :more.',
        'founding'      => 'Wenn su den Typ als :type festlegst, wird das Alter des Objekts seit der Gründung automatisch berechnet.',
    ],
    'show'      => [
        'actions'   => [
            'add'   => 'Erinnerung hinzufügen',
        ],
        'title'     => ':name Erinnerung',
    ],
    'types'     => [
        'birth'     => 'Geburt',
        'death'     => 'Tod',
        'founded'   => 'Gegründet',
        'primary'   => 'Primär',
    ],
    'years-ago' => '{1} :Jahr vor zählen|[2,*] :Jahr vor zählen',
];
