<?php

return [
    'create'                            => [
        'description'           => 'Создание новой кампании',
        'helper'                => [
            'title'     => 'Добро пожаловать в :name!',
            'welcome'   => <<<'TEXT'
Прежде чем продолжить, нужно дать название вашей кампании. Это будет названием вашего мира. Если у вас пока нет подходящего имени, не волнуйтесь, у вас всегда будет возможность его изменить, или создать еще несколько кампаний.

Спасибо, что присоединились к Kanka и добро пожаловать в наше быстро растущее сообщество!
TEXT
,
        ],
        'success'               => 'Кампания создана.',
        'success_first_time'    => 'Ваша кампания создана! Так как это ваша первая кампания, мы кое-что приготовили, чтобы помочь вам начать и дать вам представление о том, что можно создать с помощью Kanka.',
        'title'                 => 'Новая кампания',
    ],
    'destroy'                           => [
        'action'    => 'Удалить кампанию',
        'helper'    => 'Вы можете удалить кампанию, только если вы единственный ее участник.',
        'success'   => 'Кампания удалена.',
    ],
    'edit'                              => [
        'success'   => 'Кампания обновлена.',
        'title'     => 'Редактирование кампании :campaign',
    ],
    'entity_note_visibility'            => [],
    'entity_personality_visibilities'   => [
        'private'   => 'Скрывать личные качества новых персонажей',
    ],
    'entity_visibilities'               => [
        'private'   => 'Скрывать новые объекты',
    ],
    'errors'                            => [
        'access'        => 'У вас нет доступа к этой кампании.',
        'superboosted'  => 'Эта функция доступна только для супер-усиленных кампаний.',
        'unknown_id'    => 'Неизвестная кампания.',
    ],
    'export'                            => [
        'errors'            => [
            'limit' => 'Вы не можете делать больше одного экспорта в день. Пожалуйста, попробуйте снова завтра.',
        ],
        'helper'            => 'Экспортируйте вашу кампанию. Вы получите уведомление с ссылкой на скачивание.',
        'helper_secondary'  => 'Вы получите два файла - один с экспортом объектов в формате JSON, а другой с изображениями, загруженными на объекты. Учтите, что в достаточно больших кампаниях экспорт изображений часто повреждается и может быть восстановлен только с помощью :api.',
        'success'           => 'Идет подготовка к экспорту вашей кампании. Вы получите уведомление Kanka со скачиваемым ZIP файлом, как только он будет готов.',
        'title'             => 'Экспорт кампании :name',
    ],
    'fields'                            => [
        'boosted'                   => 'Усилена',
        'connections'               => 'Показывать по умолчанию таблицу связей объекта (а не инструмент исследования связей для усиленных кампаний)',
        'css'                       => 'CSS',
        'description'               => 'Описание',
        'entity_count'              => 'Объекты',
        'entry'                     => 'Описание кампании',
        'excerpt'                   => 'Подпись заголовка кампании',
        'followers'                 => 'Отслеживают',
        'header_image'              => 'Изображение заголовка кампании',
        'hide_history'              => 'Скрыть историю объектов',
        'hide_members'              => 'Скрыть участников кампании',
        'image'                     => 'Изображение',
        'locale'                    => 'Язык',
        'name'                      => 'Название',
        'nested'                    => 'Сворачивать списки объектов по умолчанию, если возможно',
        'open'                      => 'Принимает заявки на вступление',
        'public_campaign_filters'   => 'Фильтры для публичной кампании',
        'related_visibility'        => 'Доступ элементов объектов',
        'rpg_system'                => 'Ролевые системы',
        'superboosted'              => 'Супер-усилена',
        'system'                    => 'Система',
        'theme'                     => 'Тема',
        'tooltip_family'            => 'Не показывать в подсказках названия семей',
        'tooltip_image'             => 'Показывать в подсказках изображения объектов',
        'visibility'                => 'Доступ',
    ],
    'following'                         => 'Следующий',
    'helpers'                           => [
        'boost_required'            => 'Эта функция требует усиления кампании. Больше информации здесь: :settings.',
        'boosted'                   => 'Так как эта кампания усилена, ей открылись некоторые функции. Подробнее здесь: :settings.',
        'css'                       => 'Напишите здесь собственный код на CSS, и он будет использоваться на страницах вашей кампании. Пожалуйста, обратите внимание, что любое злоупотребление этой функцией может привести к удалению вашего CSS. Повторные или серьезные нарушения могут привести к удалению вашей кампании.',
        'dashboard'                 => 'Настройте вид виджета заголовка кампании в обзоре с помощью следующих полей.',
        'excerpt'                   => 'Содержание этого поля будет отображено в заголовке кампании в обзоре. Напишите пару строк, знакомящих с вашим миром. Если это поле не заполнять, то будет использоваться первая 1000 символов статьи кампании.',
        'header_image'              => 'Изображение для фона заголовка кампании в обзоре.',
        'hide_history'              => 'Включите это, чтобы скрыть историю объектов для всех участников кампании кроме админов.',
        'hide_members'              => 'Включите это, чтобы скрыть список участников кампании для всех участников кроме админов.',
        'locale'                    => 'Язык, на котором написана ваша кампания. Это нужно для генерации контента и группирования публичных кампаний.',
        'name'                      => 'Названии кампании или мира может быть любым, но должно содержать не меньше 4 букв или цифр.',
        'public_campaign_filters'   => 'Помогите другим найти эту кампанию среди других публичных кампаний, заполнив следующие поля.',
        'public_no_visibility'      => 'Внимание! Ваша кампания является публичной, но у роли "Посетитель" нет никаких разрешений. :fix.',
        'related_visibility'        => 'Настройка доступа по умолчанию для новых элементов с полем "Доступ" (посты, связи, способности и т.д.)',
        'system'                    => 'Если ваша кампания публичная, то ее система будет указана здесь: :link.',
        'systems'                   => 'Чтобы вы не запутались в разделах кампании, некоторые функции Kanka (статблок монстра D&D 5 ред.) доступны только определенным ролевым системам. Добавьте сюда поддерживаемые системы, чтобы открыть эти функции.',
        'theme'                     => 'Установите тему кампании, не зависящую от предпочтений пользователя.',
        'view_public'               => 'Чтобы увидеть вашу кампанию, как ее видит посетитель, откройте следующую ссылку в режиме инкогнито: :link.',
        'visibility'                => 'Публичную кампанию может просматривать любой, у кого есть на нее ссылка.',
    ],
    'index'                             => [
        'actions'   => [
            'new'   => [
                'title' => 'Новая кампания',
            ],
        ],
        'title'     => 'Кампания',
    ],
    'invites'                           => [
        'actions'               => [
            'add'   => 'Пригласить',
            'copy'  => 'Скопировать ссылку в буфер обмена',
            'link'  => 'Новая ссылка',
        ],
        'create'                => [
            'buttons'       => [
                'create'    => 'Создать приглашение',
                'send'      => 'Отправить приглашение',
            ],
            'success'       => 'Приглашение отправлено.',
            'success_link'  => 'Ссылка создана: :link',
            'title'         => 'Приглашение в вашу кампанию',
        ],
        'destroy'               => [
            'success'   => 'Приглашение удалено.',
        ],
        'email'                 => [
            'link_text' => 'Вступить в кампанию :name',
            'subject'   => ':name приглашает вас в свою кампанию :campaign на kanka.io! Чтобы принять приглашение, перейдите по следующей ссылке.',
            'title'     => 'Приглашение от пользователя :name',
        ],
        'error'                 => [
            'already_member'    => 'Вы уже являетесь участником этой кампании.',
            'inactive_token'    => 'Этот токен уже использовался или этой кампании не существует.',
            'invalid_token'     => 'Этот токен больше не действителен.',
            'login'             => 'Пожалуйста, войдите или зарегистрируйтесь, чтобы присоединиться к этой кампании.',
        ],
        'fields'                => [
            'created'   => 'Отправлено',
            'email'     => 'Почта',
            'role'      => 'Роль',
            'type'      => 'Тип',
            'usage'     => 'Лимит на число переходов',
        ],
        'helpers'               => [
            'email'     => 'Наши письма часто помечаются как спам. Может пройти несколько часов, прежде чем они появятся во входящих сообщениях.',
            'validity'  => 'Число пользователей, которые могут перейти по вашей ссылке, прежде чем она будет закрыта. Оставьте пустым для неограниченного числа переходов.',
        ],
        'placeholders'          => [
            'email' => 'Адрес электронной почты того, кого вы хотите пригласить',
        ],
        'types'                 => [
            'email' => 'Электронная почта',
            'link'  => 'Ссылка',
        ],
        'unlimited_validity'    => 'Не ограничены',
        'usages'                => [
            'five'      => '5 переходов',
            'no_limit'  => 'Без лимита',
            'once'      => '1 переход',
            'ten'       => '10 переходов',
        ],
    ],
    'leave'                             => [
        'confirm'   => 'Вы уверены, что хотите покинуть кампанию :name? Вы не сможете получить к ней доступ, если вас снова не пригласит ее админ.',
        'error'     => 'Невозможно покинуть кампанию.',
        'success'   => 'Вы покинули кампанию.',
    ],
    'members'                           => [
        'actions'               => [
            'help'          => 'Справка',
            'remove'        => 'Удалить из кампании',
            'switch'        => 'Тестировать',
            'switch-back'   => 'Конец тестирования',
        ],
        'create'                => [
            'title' => 'Добавление участника в кампанию',
        ],
        'edit'                  => [
            'title' => 'Редактирование участника :name',
        ],
        'fields'                => [
            'joined'        => 'Присоединился',
            'last_login'    => 'Заходил(а)',
            'name'          => 'Пользователь',
            'role'          => 'Роль',
            'roles'         => 'Роли',
        ],
        'help'                  => 'Участников в кампании может быть сколько угодно.',
        'helpers'               => [
            'admin' => 'Вы, как участник роли "Админ", можете приглашать новых пользователей, удалять неактивных и изменять их разрешения. Используйте кнопку :button, чтобы проверить разрешения пользователя. Можете прочесть об этом больше в наших :link.',
            'switch'=> 'Тестировать этого пользователя',
        ],
        'impersonating'         => [
            'message'   => 'Вы просматриваете кампанию как другой пользователь. Некоторые функции отключены, но остальные работают именно так, как их видит этот пользователь. Чтобы выйти из тестирования, нажмите кнопку "Конец тестирования" на том месте, где обычно находится кнопка выхода.',
            'title'     => 'Тестирование :name',
        ],
        'invite'                => [
            'description'   => 'Вы можете приглашать друзей в вашу кампанию, отправляя им пригласительную ссылку. После принятия приглашения, они будут добавлены в кампанию как участники указанной роли. Вы также можете отправить приглашение по электронной почте.',
            'more'          => 'Вы можете добавить больше ролей на :link.',
            'roles_page'    => 'странице ролей',
            'title'         => 'Приглашения',
        ],
        'manage_roles'          => 'Настроить роли',
        'roles'                 => [
            'member'    => 'Участник',
            'owner'     => 'Админ',
            'player'    => 'Игрок',
            'public'    => 'Посетитель',
            'viewer'    => 'Наблюдатель',
        ],
        'switch_back_success'   => 'Теперь вы снова просматриваете кампанию от своего лица.',
        'title'                 => 'Участники кампании :name',
        'updates'               => [
            'added'     => 'Пользователь :user лишен роли ":role".',
            'removed'   => 'Пользователь :user получил роль ":role".',
        ],
        'your_role'             => 'Ваша роль: <i>:role</i>',
    ],
    'open_campaign'                     => [
        'helper'    => 'Если сделать публичную кампанию открытой, то пользователи смогут отправлять заявки на вступление в нее. Список заявок расположен на странице :link.',
        'link'      => 'заявок кампании',
        'title'     => 'Открытая кампания',
    ],
    'options'                           => [
        'entity_personality_visibility' => 'Делать личные качества новых персонажей скрытыми по умолчанию.',
        'entity_visibility'             => 'Делать новые объекты скрытыми по умолчанию.',
    ],
    'panels'                            => [
        'boosted'   => 'Усиление',
        'dashboard' => 'Обзор',
        'permission'=> 'Разрешения',
        'setup'     => 'Настройка',
        'sharing'   => 'Доступность',
        'systems'   => 'Системы',
        'ui'        => 'Интерфейс',
    ],
    'placeholders'                      => [
        'description'   => 'Небольшой рассказ о вашей кампании',
        'locale'        => 'Название языка',
        'name'          => 'Название вашей кампании',
        'system'        => 'D&D, Pathfinder, Fate, DSA',
    ],
    'roles'                             => [
        'actions'       => [
            'add'           => 'Добавить роль',
            'permissions'   => 'Настройка разрешений',
            'rename'        => 'Переименовать',
            'save'          => 'Сохранить роль',
        ],
        'admin_role'    => 'ролью админа',
        'create'        => [
            'success'   => 'Роль создана.',
            'title'     => 'Создание новой роли для :name',
        ],
        'destroy'       => [
            'success'   => 'Роль удалена.',
        ],
        'edit'          => [
            'success'   => 'Роль обновлена.',
            'title'     => 'Редактирование роли :name',
        ],
        'fields'        => [
            'name'          => 'Название',
            'permissions'   => 'Разрешения',
            'type'          => 'Тип',
            'users'         => 'Пользователи',
        ],
        'helper'        => [
            '1' => 'Ролей в кампании может быть сколько угодно. Роль "Админ" автоматически дает доступ ко всему, что есть в кампании. Остальным ролям можно задать отдельные разрешения для каждого типа объектов (персонажей, локаций и т. д.).',
            '2' => 'Можно задавать разрешения отдельным объектам, во вкладке "Разрешения" при их редактировании. Эта вкладка отображается, если в кампании есть несколько ролей или участников.',
            '3' => 'Можно либо последовать "принципу исключений", открыв ролям доступ ко всем объектам, и скрывать отдельные объекты от всех кроме админов, либо не давать ролям много разрешений, но сделать все объекты видимыми.',
        ],
        'hints'         => [
            'campaign_not_public'   => 'У роли "Посетитель" есть разрешения, хотя это приватная кампания. Это можно изменить во вкладке "Доступность" при редактировании кампании.',
            'public'                => 'Роль "Посетитель" используется, когда кто-то просматривает вашу публичную кампанию. :more',
            'role_permissions'      => 'Выберите действия, к которым будет иметь доступ роль ":name", для каждого типа объектов.',
        ],
        'members'       => 'Участники',
        'modals'        => [
            'details'   => [
                'button'    => 'Нужна помощь',
                'campaign'  => 'Разрешения раздела "Кампания" позволяют следующее:',
                'entities'  => 'Вот краткое пояснение того, что участники этой роли могут делать при каждом разрешении.',
                'more'      => 'Для более подробного объяснения, смотрите наше видео на YouTube.',
                'title'     => 'Пояснение разрешений',
            ],
        ],
        'permissions'   => [
            'actions'   => [
                'add'           => 'Создать',
                'dashboard'     => 'Обзор',
                'delete'        => 'Удалить',
                'edit'          => 'Изменить',
                'entity-note'   => 'Посты',
                'manage'        => 'Управление',
                'members'       => 'Участники',
                'permission'    => 'Разрешения',
                'read'          => 'Смотреть',
                'toggle'        => 'Изменить для всех',
            ],
            'helpers'   => [
                'add'           => 'Позволяет создавать объекты этого типа. Участникам роли будет автоматически разрешено редактировать созданные ими объекты и просматривать их, если у роли нет разрешений на просмотр и редактирование.',
                'dashboard'     => 'Позволяет редактировать обзоры и виджеты обзоров.',
                'delete'        => 'Позволяет удалять все объекты этого типа.',
                'edit'          => 'Позволяет редактировать все объекты этого типа.',
                'entity_note'   => 'Позволяет пользователям без разрешения на редактирование объекта добавлять к нему посты.',
                'manage'        => 'Позволяет редактировать кампанию как админ, но не позволяет удалять ее.',
                'members'       => 'Позволяет приглашать новых участников в кампанию.',
                'permission'    => 'Позволяет настраивать разрешения объектов этого типа, которые которые участник может редактировать.',
                'read'          => 'Позволяет просматривать все объекты этого типа, кроме скрытых.',
            ],
            'hint'      => 'Эта роль автоматически дает доступ ко всему.',
        ],
        'placeholders'  => [
            'name'  => 'Название роли',
        ],
        'show'          => [
            'title' => 'Роль ":role"',
        ],
        'title'         => 'Роли кампании :name',
        'types'         => [
            'owner'     => 'Админ',
            'public'    => 'Посетитель',
            'standard'  => 'Основной',
        ],
        'users'         => [
            'actions'   => [
                'add'       => 'Добавить участника',
                'remove'    => ':user из роли ":role"',
            ],
            'create'    => [
                'success'   => 'Пользователь добавлен в роль.',
                'title'     => 'Добавление участника в роль ":name"',
            ],
            'destroy'   => [
                'success'   => 'Пользователь удален из роли.',
            ],
            'fields'    => [
                'name'  => 'Имя',
            ],
        ],
    ],
    'settings'                          => [
        'actions'   => [
            'enable'    => 'Подключить',
        ],
        'boosted'   => 'Эта функция находится в раннем доступе и пока доступна только для :boosted.',
        'edit'      => [
            'success'   => 'Настройки кампании обновлены.',
        ],
        'helper'    => 'Каждый модуль кампании можно подключить или отключить по желанию. Элементы интерфейса отключенного модуля будут скрыты, а существующие объекты модуля будут и дальше существовать, на случай если вы передумаете. Эти изменения влияют на всех пользователей кампании, включая админов.',
        'helpers'   => [
            'abilities'     => 'Создавайте способности, будь то навыки, заклинания или силы.',
            'calendars'     => 'Здесь можно создавать календари для вашего мира.',
            'characters'    => 'Населяющие ваш мир личности.',
            'conversations' => 'Выдуманные разговоры между персонажами или пользователями кампании. Этот модуль устарел.',
            'dice_rolls'    => 'Упрощает работу с бросками костей тем, кто использует Kanka для RPG кампаний. Этот модуль устарел.',
            'events'        => 'Праздники, фестивали, катастрофы, дни рожденья, войны.',
            'families'      => 'Кланы и семьи, их члены и связи.',
            'inventories'   => 'Создавайте инвентари для своих объектов.',
            'items'         => 'Оружие, транспорт, реликвии, зелья.',
            'journals'      => 'Наблюдения, записанные персонажами, или подготовка ДМа к сессии.',
            'locations'     => 'Планеты, планы, континенты, реки, страны, поселения, храмы, таверны.',
            'maps'          => 'Создавайте карты со слоями и метками, указывающими на другие объекты кампании.',
            'menu_links'    => 'Добавляйте собственные быстрые ссылки на боковую панель.',
            'notes'         => 'Наука, природа, история, магия, культура.',
            'organisations' => 'Культы, религии, фракции, гильдии.',
            'quests'        => 'Создавайте разнообразные квесты с персонажами и локациями.',
            'races'         => 'Это поможет не запутаться, если в вашей кампании несколько рас.',
            'tags'          => 'У каждого объекта могут быть тэги. У тэгов могут быть свои тэги, а статьи можно фильтровать по тэгам.',
            'timelines'     => 'Расскажите историю вашего мира с помощью хронологий.',
        ],
        'title'     => 'Модули кампании :name',
    ],
    'show'                              => [
        'actions'   => [
            'boost' => 'Усилить кампанию',
            'edit'  => 'Редактировать',
            'leave' => 'Покинуть кампанию',
        ],
        'menus'     => [
            'configuration'     => 'Компоненты',
            'overview'          => 'Основное',
            'user_management'   => 'Пользователи',
        ],
        'tabs'      => [
            'achievements'      => 'Достижения',
            'applications'      => 'Заявки',
            'campaign'          => 'Кампания',
            'default-images'    => 'Иконки по умолчанию',
            'export'            => 'Экспорт',
            'information'       => 'Информация',
            'members'           => 'Участники',
            'plugins'           => 'Плагины',
            'recovery'          => 'Восстановление',
            'roles'             => 'Роли',
            'settings'          => 'Модули',
            'styles'            => 'Стили',
        ],
        'title'     => 'Кампания :name',
    ],
    'superboosted'                      => [
        'gallery'   => [
            'error' => [
                'text'  => 'Загрузка картинок в текстовом редакторе доступна только для :superboosted.',
                'title' => 'Загрузка картинок из галереи кампании',
            ],
        ],
    ],
    'ui'                                => [
        'helper'    => 'Здесь вы можете изменить то, как отображаются некоторые элементы кампании.',
        'other'     => 'Другое',
    ],
    'visibilities'                      => [
        'private'   => 'Приватная',
        'public'    => 'Публичная',
        'review'    => 'На проверке',
    ],
];
