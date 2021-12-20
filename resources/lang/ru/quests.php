<?php

return [
    'characters'    => [],
    'create'        => [
        'success'   => 'Квест ":name" создан.',
        'title'     => 'Новый квест',
    ],
    'destroy'       => [
        'success'   => 'Квест ":name" удален.',
    ],
    'edit'          => [
        'success'   => 'Квест ":name" обновлен.',
        'title'     => 'Редактирование квеста :name',
    ],
    'elements'      => [
        'create'    => [
            'success'   => 'Объект :entity добавлен в квест.',
            'title'     => 'Новый элемент квеста :name',
        ],
        'destroy'   => [
            'success'   => 'Элемент :entity удален из квеста.',
        ],
        'edit'      => [
            'success'   => 'Элемент :entity обновлен.',
            'title'     => 'Обновление элемента квеста :name',
        ],
        'fields'    => [
            'description'       => 'Описание',
            'entity_or_name'    => 'Нужно либо выбрать объект из кампании, либо дать название этому элементу.',
            'name'              => 'Название',
            'quest'             => 'Квест',
        ],
        'title'     => 'Элементы квеста :name',
    ],
    'fields'        => [
        'character'     => 'Предлагающий',
        'copy_elements' => 'Копировать элементы квеста',
        'date'          => 'Дата',
        'description'   => 'Описание',
        'image'         => 'Изображение',
        'is_completed'  => 'Завершен',
        'name'          => 'Название',
        'quest'         => 'Родительский квест',
        'quests'        => 'Подквесты',
        'role'          => 'Роль',
        'type'          => 'Тип',
    ],
    'helpers'       => [
        'nested_parent' => 'Показаны квесты, входящие в квест :parent.',
        'nested_without'=> 'Показаны все квесты, у которых нет родительских квестов. Нажмите на строку квеста, чтобы увидеть список его подквестов.',
    ],
    'hints'         => [
        'quests'    => 'С помощью поля "Родительский квест" можно создать сеть пересекающихся квестов.',
    ],
    'index'         => [
        'add'       => 'Новый квест',
        'header'    => 'Квесты :name',
        'title'     => 'Квесты',
    ],
    'items'         => [],
    'locations'     => [],
    'organisations' => [],
    'placeholders'  => [
        'date'  => 'Дата квеста в реальном мире',
        'name'  => 'Название квеста',
        'quest' => 'Родительский квест',
        'role'  => 'Роль объекта в квесте',
        'type'  => 'Арка персонажа, побочный, основной',
    ],
    'show'          => [
        'actions'   => [
            'add_element'   => 'Добавить элемент',
        ],
        'tabs'      => [
            'elements'  => 'Элементы',
        ],
        'title'     => 'Квест :name',
    ],
];
