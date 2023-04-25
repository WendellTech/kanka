<?php

return [
    'actions'           => [
        'mode-map'      => 'Wizualizacja relacji',
        'mode-table'    => 'Tabela relacji i powiązań',
    ],
    'bulk'              => [
        'delete'            => '{1} Usunięto :count relację.|[2,3,4] Usunięto :count relacje.|[5,*] Usunięto :count relacji.',
        'delete_mirrored'   => 'Usuń również drugą stronę relacji.',
        'success'           => [
            'editing'           => '{1} Zmienono :count relację.|[2,3,4] Zmienono :count relacje.|[5,*] Zmienono :count relacji.',
            'editing_partial'   => '{1} Zmienono :count/:total relację.|[2,3,4] Zmienono :count/:total relacje.|[5,*] Zmienono :count/:total relacji.',
        ],
        'unmirror'          => 'Rozłącz relację dwustronną',
        'update_mirrored'   => 'Zmień również drugą stronę relacji.',
    ],
    'call-to-action'    => 'Zobacz rozkład rozmaitych relacji, łączących elementy kampanii.',
    'connections'       => [
        'map_point'         => 'Punkt na mapie',
        'mention'           => 'Wzmianka',
        'quest_element'     => 'Część zadania',
        'timeline_element'  => 'Część historii',
    ],
    'create'            => [
        'new_title' => 'Nowa relacja',
        'success'   => 'Dodano relację :target do elementu :entity.',
        'title'     => 'Nowa relacja elementu :name.',
    ],
    'delete_mirrored'   => [
        'helper'    => 'Te elementy łączy relacja obustronna. Wybór tej opcji usunie obydwie strony relacji.',
        'option'    => 'Usuń relację obustronną.',
    ],
    'destroy'           => [
        'mirrored'  => 'Usunie również drugą stronę relacji. Tej akcji nie można cofnąć.',
        'success'   => 'Usunięto relację :target elementu :entity.',
    ],
    'fields'            => [
        'attitude'          => 'Nastawienie',
        'connection'        => 'Powiązanie',
        'is_star'           => 'Przypięta',
        'owner'             => 'Źródło',
        'relation'          => 'Relacja',
        'target'            => 'Obiekt',
        'target_relation'   => 'Relacje obiektu',
        'two_way'           => 'Stwórz relację obustronną',
        'unmirror'          => 'Zmień w relację jednostronną',
    ],
    'helper'            => 'Ustalaj relacje między elementami, określając ich rodzaj i widoczność. Relacje można przypinać do opisu elementów.',
    'helpers'           => [
        'no_relations'  => 'Element nie jest obecnie związany z żadnym innym elementem tej kampanii.',
        'popup'         => 'Elementy można łączyć z pomocą relacji. Mogą one posiadać opis, wartość, ograniczoną widoczność dla różnych użytkowników i tak dalej.',
    ],
    'hints'             => [
        'attitude'          => 'Pole opcjonalne, pozwalająca określić kolejność wyświetlania relacji, w porządku malejącym.',
        'mirrored'          => [
            'text'  => 'To obustronna relacja z :link.',
            'title' => 'Obustronna',
        ],
        'target_relation'   => 'Opis relacji dla jej obiektu. Jeżeli ma być taki sam, zostaw to pole puste.',
        'two_way'           => 'Jeżeli wybierzesz relację obustronną, taka sama relacja zostanie stworzona dla obiektu. Jeżeli potem zmodyfikujesz relację dla jednej strony, druga nie zostanie zaktualizowana.',
    ],
    'index'             => [
        'title' => 'Relacje',
    ],
    'options'           => [
        'mentions'          => 'Relacje + związki + wzmianki',
        'only_relations'    => 'Tylko relacje bezpośrednie',
        'related'           => 'Relacje + związki',
        'relations'         => 'Relacje',
        'show'              => 'Pokaż',
    ],
    'panels'            => [
        'related'   => 'Związki',
    ],
    'placeholders'      => [
        'attitude'          => '-100 do 100, gdzie 100 to bardzo pozytywny stosunek',
        'relation'          => 'Rywal, Przyjaciółka od serca, Rodzeństwo',
        'target'            => 'Wybierz element',
        'target_relation'   => 'Nie wypełniaj, by użyć opisu',
    ],
    'show'              => [
        'title' => 'Relacje elementu :name',
    ],
    'types'             => [
        'family_member'         => 'Członek rodziny',
        'organisation_member'   => 'Członek organizacji',
    ],
    'update'            => [
        'success'   => 'Zaktualizowano relację :target z elementem :entity.',
        'title'     => 'Zaktualizuj relacje dla :name',
    ],
];
