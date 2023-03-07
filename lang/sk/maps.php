<?php

return [
    'actions'       => [
        'back'      => 'Späť na :name',
        'edit'      => 'Upraviť mapu',
        'explore'   => 'Prieskumník',
    ],
    'create'        => [
        'title' => 'Nová mapa',
    ],
    'destroy'       => [],
    'edit'          => [],
    'errors'        => [
        'chunking'  => [
            'error'     => 'Pri rozdeľovaní mapy na bloky nastala chyba. Prosím, obráť sa ohľadom pomoci na náš tím na :discord.',
            'running'   => [
                'edit'      => 'Mapa počas rozdeľovania na bloky nemôže byť upravovaná.',
                'explore'   => 'Mapa počas rozdeľovania na bloky nemôže byť zobrazená.',
                'time'      => 'Môže to teraz trvať niekoľko minút až hodín, v závislosti od veľkosti mapy.',
            ],
        ],
        'dashboard' => [
            'missing'   => 'Táto mapa vyžaduje obrázok, aby mohla byť zobrazená na nástenke.',
        ],
        'explore'   => [
            'missing'   => 'Na použitie Prieskumníka budeš musieť najprv pridať obrázok mapy.',
        ],
    ],
    'fields'        => [
        'center_marker'     => 'Značka',
        'center_x'          => 'Štandardná zemepisná dĺžka',
        'center_y'          => 'Štandardná zemepisná šírka',
        'centering'         => 'Vystredniť',
        'distance_measure'  => 'Meranie vzdialenosti',
        'distance_name'     => 'Označenie mierky vzdialenosti',
        'grid'              => 'Mriežka',
        'has_clustering'    => 'Značka zhluku',
        'initial_zoom'      => 'Prvotné priblíženie',
        'is_real'           => 'Použiť OpenStreetMaps',
        'map'               => 'Nadradená mapa',
        'maps'              => 'Mapy',
        'max_zoom'          => 'Maximálne priblíženie',
        'min_zoom'          => 'Minimálne priblíženie',
        'tabs'              => [
            'coordinates'   => 'Koordináty',
            'marker'        => 'Značka',
        ],
    ],
    'helpers'       => [
        'center'                => 'Zmenou týchto hodnôt vieš kontrolovať, na ktorú oblasť mapy bude zameraný náhľad. Ak hodnoty ponecháš prázdne, bude zameranie na stred mapy.',
        'centering'             => 'Vystrednenie značky bude prioritou pred štandardnými koordinátmi.',
        'chunked_zoom'          => 'Automaticky zhlukuj značky, keď sa nachádzajú blízko seba.',
        'descendants'           => 'Tento zoznam obsahuje všetky mapy, ktoré sú podradené tejto mape, ale nielen priamo pod ňou.',
        'distance_measure'      => 'Pridaním merania vzdialenosti sa aktivuje nástroj merania v Prieskumníkovi.',
        'distance_measure_2'    => 'Aby zodpovedalo 100 pixelov 1 km, zadaj hodnotu 0.0041.',
        'grid'                  => 'Definuj veľkosť mriežky, ktorá sa zobrazí v Prieskumníkovi.',
        'has_clustering'        => 'Automaticky zhlukuj značky, keď sa nachádzajú blízko seba.',
        'initial_zoom'          => 'Úroveň prvotného priblíženia mapy, s ktorou sa zobrazí na začiatku. Štandardná hodnota je :default, pričom najvyššia povolená hodnota je :max a najnižšia :min.',
        'is_real'               => 'Použi toto nastavenie, ak chceš použiť mapu reálneho sveta namiesto nahraného obrázku mapy. Toto nastavenie deaktivuje vrstvy.',
        'max_zoom'              => 'Mapa môže byť priblížená maximálne na túto hodnotu. Štandardná hodnota je :default, najvyššia povolená hodnota je :max.',
        'min_zoom'              => 'Mapa môže byť oddialená maximálne na túto hodnotu. Štandardná hodnota je :default, najnižšia povolená hodnota je :max.',
        'missing_image'         => 'Na použitie vrstiev a značiek budeš musieť najprv pridať obrázok mapy.',
        'nested_without'        => 'Zobraziť všetky mapy, ktoré nemajú nadradenú mapu. Kliknutím na riadok zobrazíš podradené mapy.',
    ],
    'index'         => [],
    'maps'          => [
        'title' => 'Mapy objektu :name',
    ],
    'panels'        => [
        'groups'    => 'Skupiny',
        'layers'    => 'Vrstvy',
        'legend'    => 'Legenda',
        'markers'   => 'Značky',
        'settings'  => 'Nastavenia',
    ],
    'placeholders'  => [
        'center_marker' => 'Ponechaj prázdne, ak sa má mapa zobraziť nastred',
        'center_x'      => 'Ponechaj prázdne, ak sa má mapa zobraziť nastred',
        'center_y'      => 'Ponechaj prázdne, ak sa má mapa zobraziť nastred',
        'distance_name' => 'km, míle, stopy, hamburgery',
        'grid'          => 'Vzdialenosť v pixloch medzi prvkami mriežky. Ponechaj prázdne, ak chceš mriežku vypnúť.',
        'name'          => 'Názov mapy',
        'type'          => 'Jaskyňa, Mesto, Galaxia',
    ],
    'show'          => [
        'tabs'  => [
            'maps'  => 'Mapy',
        ],
    ],
    'tooltips'      => [
        'chunking'  => [
            'running'   => 'Mapa sa rozdeľuje na bloky. Tento proces môže trvať niekoľko minút až hodín.',
        ],
    ],
];
