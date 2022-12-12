<?php

return [
    'actions'       => [
        'entry'             => 'Написать отдельную статью для этой метки',
        'remove'            => 'Удалить метку',
        'reset-polygon'     => 'Очистить точки',
        'save_and_explore'  => 'Сохранить и Исследовать',
        'start-drawing'     => 'Начать рисовать',
        'update'            => 'Редактировать метку',
    ],
    'bulks'         => [
        'delete'    => '{1} Удалена :count метка.|[2,4] Удалено :count метки.|[5,*] Удалено :count меток.',
        'patch'     => '{1} Обновлена :count метка.|[2,4] Обновлено :count метки.|[5,*] Обновлено :count меток.',
    ],
    'create'        => [
        'success'   => 'Метка ":name" создана.',
        'title'     => 'Новая метка',
    ],
    'delete'        => [
        'success'   => 'Метка ":name" удалена.',
    ],
    'edit'          => [
        'success'   => 'Метка ":name" обновлена.',
        'title'     => 'Редактирование метки :name',
    ],
    'fields'        => [
        'circle_radius' => 'Радиус круга',
        'copy_elements' => 'Копировать элементы',
        'custom_icon'   => 'Другая иконка',
        'custom_shape'  => 'Настройка формы фигуры',
        'font_colour'   => 'Цвет иконки',
        'group'         => 'Группа меток',
        'icon'          => 'Иконка',
        'is_draggable'  => 'Подвижная',
        'latitude'      => 'Широта',
        'longitude'     => 'Долгота',
        'opacity'       => 'Непрозрачность',
        'pin_size'      => 'Размер метки',
        'polygon_style' => [
            'stroke'            => 'Цвет линий',
            'stroke-opacity'    => 'Непрозрачность линий',
            'stroke-width'      => 'Толщина линий',
        ],
    ],
    'helpers'       => [
        'base'                      => 'Чтобы добавить метку, нажмите в любое место на карте.',
        'copy_elements'             => 'Копировать группы, слои и метки.',
        'copy_elements_to_campaign' => 'Копировать группы, слои и метки. Метки, связанные с объектами, станут обычными метками.',
        'custom_icon_v2'            => 'Используйте иконки с :fontawesome, :rpgawesome или собственную SVG иконку. Подробнее расскажет :docs.',
        'custom_radius'             => 'Выберите вариант "Другой" в списке размеров, чтобы задать радиус круга.',
        'draggable'                 => 'Подвижные метки можно двигать в режиме исследования.',
        'label'                     => 'Надпись отображается на карте в виде текста. Его содержание определяется названием метки или объекта.',
        'polygon'                   => [
            'edit'  => 'Нажмите на карту, чтобы добавить место нажатия к координатам этой фигуры.',
        ],
    ],
    'icons'         => [
        'custom'        => 'Особая',
        'entity'        => 'Объект',
        'exclamation'   => 'Восклицание',
        'marker'        => 'Метка',
        'question'      => 'Вопрос',
    ],
    'index'         => [
        'title' => 'Метки карты :name',
    ],
    'pitches'       => [
        'poly'  => 'Рисуйте какие угодно многоугольники для отображения границ и других фигур.',
    ],
    'placeholders'  => [
        'custom_icon'   => 'Попробуйте :example1 или :example2',
        'custom_shape'  => '100,100 200,240 340,110',
        'name'          => 'Обязательно, если не выбран объект.',
    ],
    'presets'       => [
        'helper'    => 'Нажмите на заготовку, чтобы применить ее, или создайте новую.',
    ],
    'shapes'        => [
        '0' => 'Круг',
        '1' => 'Квадрат',
        '2' => 'Треугольник',
        '3' => 'Особая',
    ],
    'sizes'         => [
        '0' => 'Крошечная',
        '1' => 'Обычная',
        '2' => 'Маленькая',
        '3' => 'Большая',
        '4' => 'Огромная',
    ],
    'tabs'          => [
        'circle'    => 'Круг',
        'label'     => 'Надпись',
        'marker'    => 'Метка',
        'polygon'   => 'Фигура',
        'preset'    => 'Заготовка',
    ],
];
