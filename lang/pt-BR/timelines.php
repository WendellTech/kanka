<?php

return [
    'actions'       => [
        'add_element'   => 'Adicionar novo elemento à era :era',
        'back'          => 'Voltar para :name',
        'edit'          => 'Editar linha do tempo',
        'save_order'    => 'Salvar nova ordem',
    ],
    'create'        => [
        'title' => 'Nova linha do tempo',
    ],
    'destroy'       => [],
    'edit'          => [],
    'fields'        => [
        'copy_elements' => 'Copiar elementos',
        'copy_eras'     => 'Copiar eras',
        'eras'          => 'Eras',
        'reverse_order' => 'Era em ordem reversa',
        'timeline'      => 'Linha do Tempo primária',
        'timelines'     => 'Linhas do Tempo',
    ],
    'helpers'       => [
        'nested_without'    => 'Mostrando todas as linhas do tempo que não tem uma linha do tempo pai. Clique em uma linha para ver as linhas do tempo filhos.',
        'no_era_v2'         => 'Esta linha do tempo atualmente não tem nenhuma era. Adicione uma ou várias eras, após o qual você pode adicionar elementos às eras aqui.',
        'reverse_order'     => 'Habilite para mostrar linhas do tempo em ordem cronológica reversa (eras antigas primeiro)',
    ],
    'index'         => [],
    'placeholders'  => [
        'name'  => 'Nome da linha do tempo',
        'type'  => 'Primária, crônica Mundial, legado de um Reino',
    ],
    'reorder'       => [
        'success'   => 'Linha do tempo reordenada com sucesso.',
        'title'     => 'Reordenar a linha do tempo',
    ],
    'show'          => [
        'tabs'  => [
            'reorder'   => 'Reordenar linha do tempo',
            'timelines' => 'Linhas do tempo',
        ],
    ],
    'timelines'     => [
        'title' => 'Linhas do tempo de :name',
    ],
];
