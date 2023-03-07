<?php

return [
    'actions'       => [
        'back'      => 'Voltar para :name',
        'edit'      => 'Editar mapa',
        'explore'   => 'Explorar',
    ],
    'create'        => [
        'title' => 'Novo mapa',
    ],
    'destroy'       => [],
    'edit'          => [],
    'errors'        => [
        'chunking'  => [
            'error'     => 'Ocorreu um erro ao fragmentar o mapa. Entre em contato com a equipe em :discord para obter suporte.',
            'running'   => [
                'edit'      => 'O mapa não pode ser editado enquanto estiver em partes.',
                'explore'   => 'O mapa não pode ser exibido enquanto estiver em partes.',
                'time'      => 'Isso pode levar de vários minutos a várias horas, dependendo do tamanho do mapa.',
            ],
        ],
        'dashboard' => [
            'missing'   => 'Este mapa precisa de uma imagem para poder aparecer no dashboard',
        ],
        'explore'   => [
            'missing'   => 'Por favor, adicione uma imagem ao mapa para poder explorá-lo',
        ],
    ],
    'fields'        => [
        'center_marker'     => 'Marcador',
        'center_x'          => 'Posição de longitude padrão',
        'center_y'          => 'Posição de latitude padrão',
        'centering'         => 'Centralizando',
        'distance_measure'  => 'Medição de distância',
        'distance_name'     => 'Rótulo da unidade de distância',
        'grid'              => 'Grid',
        'has_clustering'    => 'Agrupar marcadores',
        'initial_zoom'      => 'Zoom inicial',
        'is_real'           => 'Usar OpenStreetMaps',
        'map'               => 'Mapa primário',
        'maps'              => 'Mapas',
        'max_zoom'          => 'Zoom máximo',
        'min_zoom'          => 'Zoom mínimo',
        'tabs'              => [
            'coordinates'   => 'Coordenadas',
            'marker'        => 'Marcador',
        ],
    ],
    'helpers'       => [
        'center'                => 'Alterar os valores a seguir controlará em qual área do mapa está o foco. Deixar esses valores vazios resultará no centro do mapa ser considerado o foco.',
        'centering'             => 'Centralizar em um marcador terá prioridade sobre as coordenadas padrão.',
        'chunked_zoom'          => 'Agrupe automaticamente os marcadores quando estiverem próximos uns dos outros.',
        'descendants'           => 'Esta lista contém todos os mapas que são relacionados a este mapa, e não apenas aqueles diretamente relacionados a ele.',
        'distance_measure'      => 'Dar ao mapa uma medida de distância habilitará a ferramenta de medição no modo de exploração.',
        'distance_measure_2'    => 'Para que 100 pixels meçam 1 quilômetro, insira um valor de 0,0041.',
        'grid'                  => 'Defina o tamanho do grid que será mostrado no modo exploração.',
        'has_clustering'        => 'Agrupe automaticamente os marcadores quando estiverem próximos uns dos outros.',
        'initial_zoom'          => 'O nível de zoom inicial com o qual um mapa é carregado. O valor padrão é :default, enquanto o maior valor permitido é :max e o menor valor permitido é :min.',
        'is_real'               => 'Selecione esta opção se quiser usar um mapa do mundo real em vez da imagem carregada. Esta opção desativa as camadas.',
        'max_zoom'              => 'O máximo que um mapa pode ser ampliado. O valor padrão é :default, enquanto o maior valor permitido é :max.',
        'min_zoom'              => 'O máximo que um mapa pode ser diminuído. O valor padrão é :default, enquanto o menor valor permitido é :max.',
        'missing_image'         => 'Você precisa salvar o mapa com uma imagem antes de poder adicionar camadas e marcadores.',
        'nested_without'        => 'Mostrando todos os mapas que não tem um mapa-pai. Clique em uma linha para ver os mapas-filhos.',
    ],
    'index'         => [],
    'maps'          => [
        'title' => 'Mapas de :name',
    ],
    'panels'        => [
        'groups'    => 'Grupos',
        'layers'    => 'Camadas',
        'legend'    => 'Lenda',
        'markers'   => 'Marcadores',
        'settings'  => 'Configurações',
    ],
    'placeholders'  => [
        'center_marker' => 'Deixe vazio para carregar o mapa centralizado.',
        'center_x'      => 'Deixe vazio para carregar o mapa centralizado.',
        'center_y'      => 'Deixe vazio para carregar o mapa centralizado.',
        'distance_name' => 'Km, milhas, pés, hambúrgueres',
        'grid'          => 'Distância em pixels entre os elementos da grid. Deixe vazio para esconder a grid.',
        'name'          => 'Nome do mapa',
        'type'          => 'Masmorra, Cidade, Galáxia',
    ],
    'show'          => [
        'tabs'  => [
            'maps'  => 'Mapas',
        ],
    ],
    'tooltips'      => [
        'chunking'  => [
            'running'   => 'Mapa está sendo fragmentado. Esse processo pode levar vários minutos ou horas.',
        ],
    ],
];
