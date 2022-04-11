<?php

return [
    'age'               => [
        'description'   => 'Персонажа можно связать с календарем кампании, перейдя во вкладку "Напоминания" на странице персонажа. Попав туда, нужно добавить новое напоминание и задать тип "Рождение" или "Смерть", чтобы возраст персонажа вычислялся автоматически. Если заданы даты и для рождения, и для смерти, то на странице персонажа будут показаны обе даты, а также возраст на момент смерти. Если задана только дата смерти, то будет показана эта дата и количество лет, прошедших с ее момента.',
        'title'         => 'Возраст и смерть персонажей',
    ],
    'api-filters'       => [
        'description'   => 'Для конечной точки API с названием :name доступны следующие фильтры.',
        'title'         => 'Фильтры API',
    ],
    'attributes'        => [
        'con'               => 'Тел',
        'description'       => 'Используйте атрибуты, чтобы показать нетекстовые характеристики объекта. В атрибутах можно ссылаться на различные объекты с помощью продвинутых упоминаний вида :mention. Также можно ссылаться на другие атрибуты, указывая их названия в фигурных скобках, например :attribute.',
        'level'             => 'Уровень',
        'link'              => 'Функции атрибутов',
        'math'              => 'Также можно поэкспериментировать с некоторыми основными математическими действиями. Например, <code>{Уровень}*{ХАР}</code> перемножит атрибуты <code>Уровень</code> и <code>ХАР</code> этого объекта. Если хотите округлить результат в большую или меньшую сторону, можно использовать синтаксис вида <code>ceil(({ХАР}*{Уровень})/2)</code> и :floor соответственно.',
        'name'              => 'На название объекта-обладателя атрибута можно ссылаться так: <code>{Название}</code>. Если существует атрибут с таким названием, то будет использован он, а не название объекта.',
        'pinned'            => 'Закрепляйте атрибуты с помощью :icon, чтобы он отображались в меню объекта ниже его изображения.',
        'private'           => 'Скрывайте атрибуты с помощью :icon, чтобы они были видны только участникам кампании с ролью админа.',
        'random'            => 'При создании или изменении шаблона атрибутов можно создавать случайные атрибуты. Это может быть либо случайное число, находящееся в диапазоне между двумя числами, разделенными :dash (дефисом), либо случайное значение из списка возможных, разделенных :comma (запятой). Значение атрибута определяется при применении шаблона на объект и при сохранении объекта.',
        'random_examples'   => 'Например, если нужно число от 1 до 100, используйте :number. Если нужно значение из списка, используйте <code>Лондон, Берлин, Рим, Цюрих</code>.',
        'title'             => 'Атрибуты',
    ],
    'dice'              => [
        'description'               => 'Для создания кости (дайса) напишите d, затем число ее сторон. Прямо перед d можно указать число бросков, сумму результатов которых вам нужно получить. Например, d20 для броска кости с 20 гранями, 4d4+4 для сложения результатов бросков четырех костей с четырьмя сторонами и прибавления 4 к результату. Используйте d% для "процентника" и df для FUDGE кубика.',
        'description_attributes'    => 'Также можно использовать значения атрибутов персонажа, указанного в поле "Персонаж", с помощью синтаксиса {character.Атрибут}. Например, {character.Уровень}d6+{character.Мудрость}.',
        'more'                      => 'Больше функций описано и объяснено на странице плагина Dice Roller.',
        'title'                     => 'Броски костей',
    ],
    'entity_templates'  => [
        'description'   => 'При создании новых объектов, вы можете использовать шаблоны, а не начинать с пустой формы. Чтобы сделать объект шаблоном, откройте его и нажмите :link в меню :action справа вверху. При просмотре списка объектов шаблон соответствующего типа объектов можно выбрать рядом с кнопкой :new. Для каждого типа объектов можно создавать несколько шаблонов.',
        'link'          => 'Как создавать шаблоны',
        'remove'        => 'Чтобы снять с объекта статус шаблона, нажмите :remove на месте действия :link, описанного выше.',
        'title'         => 'Объекты-шаблоны',
    ],
    'filters'           => [
        'attributes'    => [
            'exclude'   => '!Уровень',
            'first'     => 'Объекты можно фильтровать по их атрибутам. Специальные поля позволяют найти объекты, и название и значение атрибута которых совпадает полностью с указанным. Если оставить значение пустым, будут найдены объекты с атрибутом с названием, совпадающим с указанным. Если ввести :exclude, то все объекты с атрибутом Уровень не будут выданы.',
            'second'    => 'Этот фильтр не поддерживает вычисляемые значения атрибутов. Если в значении атрибута указано <code>Хиты * Уровень</code>, то результат вычисления не возможно будет найти фильтром.',
        ],
        'clipboard'     => 'После применения фильтров активируется кнопка "Копировать фильтры". Она копирует фильтры в ваш буфер обмена, что позволяет использовать их для фильтров в виджетах обзоров и для фильтров быстрых ссылок.',
        'description'   => 'Вы можете использовать фильтры, чтобы ограничить количество результатов, показываемых в списках. Текстовые поля поддерживают различные функции для более детального управления фильтрованием.',
        'empty'         => 'Если в поле написать :tag, то будут найдены все объекты, у которых это поле пустое.',
        'ending_with'   => 'Если поставить в конце текста :tag, то будут найдены все объекты с абсолютно таким же текстом в этом поле.',
        'multiple'      => 'Вы можете объединять несколько условий поиска с помощью :syntax. Например, <code>Алекс;!Смит</code>.',
        'session'       => 'Фильтры и сортировка списков объектов сохраняются в вашей сессии, таким образом, пока вы подключены, вам не нужно задавать их заново для каждой страницы.',
        'starting_with' => 'Если поставить в начале текста :tag, то будут найдены все объекты, у которых нет такого текста в этом поле.',
        'title'         => 'Как использовать фильтры',
    ],
    'link'              => [
        'anchor'            => 'В продвинутом упоминании также можно указать целевой HTML якорь ссылки вот так: :example.',
        'auto_update'       => 'Ссылки на другие объекты будут автоматически обновлены при изменении названия или описания этих объектов.',
        'description'       => 'На другие объекты вашей кампании можно ссылаться с помощью следующих символов.',
        'formatting'        => [
            'text'  => 'Список разрешенных HTML тэгов и атрибутов можно найти на нашем :github.',
            'title' => 'Форматирование',
        ],
        'friendly_mentions' => 'Ссылайтесь на другие объекты, вводя :code и первые несколько символов названия объекта, чтобы найти его. Это вставит в текстовый редактор <code>Название_объекта</code>, которое при просмотре статьи будет работать как ссылка.',
        'mention_helpers'   => 'Если в названии объекта есть пробел, вместо пробела поставьте :example. Если вы хотите найти объект с именно таким названием как указанное, укажите его так: <code>=Название_объекта</code>.',
        'mentions'          => 'Ссылайтесь на другие объекты, вводя :code и первые несколько символов названия объекта, чтобы найти его. Это вставит в текстовый редактор ссылку вида :example. Отображаемый текст ссылки можно изменить вот так: <code>[entity:123|Алекс]</code>. Также можно задать подстраницу объекта, например :example_page.',
        'mentions_field'    => 'Вы также можете показать в ссылке не название объекта, а значение одного из его полей, вот так: :code.',
        'months'            => 'Введите :code, чтобы посмотреть список месяцев ваших календарей.',
        'options'           => 'Вот некоторые варианты значений: :options.',
        'title'             => 'Ссылки на другие объекты и специальные символы',
    ],
    'map'               => [
        'description'   => 'Загрузка карты для локации добавит меню "Карта" на странице просмотра локации и прямую ссылку на карту со страницы локаций кампании. При просмотре карты пользователи, которым разрешено редактировать локацию, могут включить "Режим редактора", который позволяет размещать точки на карте. Они могут быть ссылками на объекты или подписями. Также они могут иметь разную форму и размер.',
        'private'       => 'Участники кампании с ролью "Админ" могут скрыть карту. Это позволит пользователям просматривать локацию, а админам хранить карту в тайне.',
        'title'         => 'Карты локаций',
    ],
    'pins'              => [
        'description'   => 'Связи и атрибуты объектов можно закреплять в правой части страницы истории объекта. Чтобы закрепить связь, выберите "Закрепить" при ее редактировании, а чтобы закрепить атрибут, нажмите на звездочку возле него в режиме управления атрибутами.',
        'title'         => 'Закрепленные элементы',
    ],
    'public'            => 'Посмотрите видео на YouTube, объясняющее публичные кампании.',
    'title'             => 'Справка',
    'troubleshooting'   => [
        'description'       => 'На эту страницу вас направил член команды Kanka. Выберите кампанию из списка, чтобы сгенерировать токен, позволяющий нам временно присоединиться к вашей кампании в роли админа.',
        'errors'            => [
            'token_exists'  => 'Токен для кампании :campaign уже существует.',
        ],
        'save_btn'          => 'Сгенерировать токен',
        'select_campaign'   => 'Выберите кампанию',
        'subtitle'          => 'Спасите, помогите!',
        'success'           => 'Пожалуйста, скопируйте следующий токен и отправьте его кому-нибудь из членов команды Kanka.',
        'title'             => 'Исправление неполадок',
    ],
    'widget-filters'    => [
        'description'   => 'В некоторых виджетах можно фильтровать список объектов, выбрав тип искомых объектов и предоставив их "значения". Например, фильтр :example покажет только мертвых персонажей типа "NPC".',
        'link'          => 'фильтрах виджетов',
        'more'          => '"Значения" можно скопировать из URL страницы списка объектов. Например, можно создать нужные фильтры на странице персонажей кампании и скопировать текст URL, стоящий после знака :question.',
        'title'         => 'Фильтры в виджетах обзоров',
    ],
];
