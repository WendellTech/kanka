<?php

return [
    'actions'                           => [
        'boost' => 'Boost :name',
    ],
    'create'                            => [
        'description'           => 'Create a new campaign',
        'helper'                => [
            'title'     => 'Welcome to :name',
            'welcome'   => <<<'TEXT'
Before going any further, you need to pick a campaign name. This is the name of your world. If you don't have a good name yet, don't worry, you can always change it later, or create more campaigns.

Thanks for joining Kanka, and welcome to our thriving community!
TEXT
,
        ],
        'success'               => 'Campaign created.',
        'success_first_time'    => 'Your campaign has been created! Since it\'s your first campaign, we\'ve created a few things to help you get started and hopefully provide a bit of inspiration on what you can do.',
        'title'                 => 'New Campaign',
    ],
    'destroy'                           => [
        'action'            => 'Delete campaign',
        'confirm'           => 'Are you sure you want to delete :campaign? This action is permanent and can\'t be recovered.',
        'confirm-button'    => 'Permanently delete the campaign',
        'helper-v2'         => 'This campaign can\'t be deleted while there are other members in it. Remove the other members first and try again.',
        'hint'              => 'If so, please write :code on the box below.',
        'success'           => 'Campaign deleted.',
        'title'             => 'Delete a campaign',
    ],
    'edit'                              => [
        'success'   => 'Campaign updated.',
        'title'     => 'Edit Campaign :campaign',
    ],
    'entity_personality_visibilities'   => [
        'private'   => 'New characters have their personality private by default.',
    ],
    'entity_visibilities'               => [
        'private'   => 'New entities are private',
    ],
    'errors'                            => [
        'access'        => 'You don\'t have access to this campaign.',
        'superboosted'  => 'This feature is only available to superboosted campaigns.',
        'unknown_id'    => 'Unknown Campaign.',
    ],
    'fields'                            => [
        'boosted'                           => 'Boosted by',
        'character_personality_visibility'  => 'Default character personality visibility',
        'connections'                       => 'Show an entity\'s connection table by default (instead of relation explorer for boosted campaigns)',
        'css'                               => 'CSS',
        'description'                       => 'Description',
        'entity_count'                      => 'Entity Count',
        'entity_privacy'                    => 'Default new entity privacy',
        'entry'                             => 'Campaign description',
        'excerpt'                           => 'Campaign dashboard text',
        'featured'                          => 'Featured campaign',
        'followers'                         => 'Followers',
        'header_image'                      => 'Campaign dashboard background image',
        'image'                             => 'Sidebar image',
        'locale'                            => 'Locale',
        'name'                              => 'Name',
        'nested'                            => 'Default entity lists to nested when available',
        'open'                              => 'Open to applications',
        'past_featured'                     => 'Previously featured campaign',
        'post_collapsed'                    => 'New posts on entities are collapsed by default.',
        'public'                            => 'Campaign visibility',
        'public_campaign_filters'           => 'Public Campaign Filters',
        'related_visibility'                => 'Related Elements Visibility',
        'rpg_system'                        => 'RPG Systems',
        'superboosted'                      => 'Superboosted by',
        'system'                            => 'System',
        'theme'                             => 'Theme',
        'visibility'                        => 'Visibility',
    ],
    'following'                         => 'Following',
    'helpers'                           => [
        'boost_required'                    => 'This feature requires the campaign to be boosted. More info on the :settings page.',
        'boost_required_multi'              => 'These features require the campaign to be boosted. More info on the :settings page.',
        'boosted'                           => 'Some features are unlocked because this campaign is being boosted. Find out more on the :settings page.',
        'character_personality_visibility'  => 'When creating a new character as an admin, select the default privacy setting for its personality traits.',
        'css'                               => 'Write your own CSS that will be loaded into the pages of your campaign. Please note that any abuse of this feature can lead to a removal of your custom CSS. Repeated or grave offenses can lead to a removal of your campaign.',
        'dashboard'                         => 'Customise the way the campaign dashboard widget is displayed by filling out the following fields.',
        'entity_count_infinity'             => 'This campaign has no limit to the number of entities.',
        'entity_count_v2'                   => 'This number is recalculated every six hours and ignores tags.',
        'entity_privacy'                    => 'When creating a new entity as an admin, select the default privacy setting of the new entity.',
        'excerpt'                           => 'The contents of this field will be displayed on the dashboard in the campaign header widget, so write a few sentences introducing your world. If this field is empty, the first 1000 characters of the campaign\'s entry field will be used instead.',
        'header_image'                      => 'Image displayed as a background in the campaign header dashboard widget.',
        'hide_history'                      => 'If enabled, only members of the campaign\'s :admin role will have access to an entity\'s history (log of changes).',
        'hide_members'                      => 'If enabled, only members of the campaign\'s :admin role will have access to the list of the campaign\'s members.',
        'locale'                            => 'The language your campaign is written in. This is used for generating content and grouping public campaigns.',
        'name'                              => 'Your campaign/world can have any name as long as it contains at least 4 letters or numbers.',
        'permissions_tab'                   => 'Control the default privacy and visibility settings of new elements with the following options.',
        'public_campaign_filters'           => 'Help others find the campaign among other public campaigns by providing the following information.',
        'public_no_visibility'              => 'Heads up! The campaign is public, but the campaign\'s public role can\'t access anything. :fix.',
        'related_visibility'                => 'Default Visibility value when creating a new element with this field (posts, relations, abilities, etc)',
        'system'                            => 'If your campaign is publicly visible, the system is shown in the :link page.',
        'systems'                           => 'To avoid cluttering users with options, some features of Kanka are only available with specific RPG systems (ie the D&D 5e monster stat block). Adding supported systems here will enable those features.',
        'theme'                             => 'Force the theme for the campaign, overriding a user\'s preference.',
        'view_public'                       => 'To view your campaign as a public viewer would, open :link in an incognito window.',
        'visibility'                        => 'Making a campaign public will mean anyone with a link to it will be able to see it.',
    ],
    'index'                             => [
        'actions'   => [
            'new'   => [
                'title' => 'New Campaign',
            ],
        ],
    ],
    'invites'                           => [
        'actions'               => [
            'copy'  => 'Copy the link to your clipboard',
            'link'  => 'Invite people',
        ],
        'create'                => [
            'buttons'       => [
                'create'    => 'Generate link',
            ],
            'success_link'  => 'Link created: :link',
            'title'         => 'Invite friends to :campaign',
        ],
        'destroy'               => [
            'success'   => 'Invitation removed.',
        ],
        'error'                 => [
            'already_member'    => 'You are already a member of that campaign.',
            'inactive_token'    => 'This token has already been used, or the campaign no longer exists.',
            'invalid_token'     => 'This token is no longer valid.',
            'login'             => 'Please log in or register to join the campaign.',
        ],
        'fields'                => [
            'created'   => 'Created',
            'role'      => 'Role',
            'token'     => 'Token',
            'type'      => 'Type',
            'usage'     => 'Expires after',
        ],
        'unlimited_validity'    => 'Unlimited',
        'usages'                => [
            'five'      => '5 uses',
            'no_limit'  => 'Never',
            'once'      => '1 use',
            'ten'       => '10 uses',
        ],
    ],
    'leave'                             => [
        'confirm'           => 'Are you sure you want to leave the :name campaign? You won\'t be able to access it anymore, unless an admin of the campaign invites you again.',
        'confirm-button'    => 'Yes, leave the campaign',
        'error'             => 'Can\'t leave the campaign.',
        'fix'               => 'Go to the campaign members',
        'no-admin-left'     => 'Leaving the campaign isn\'t possible because doing so would leave it without any admins. Add another member to the admin role first.',
        'success'           => 'You have left the campaign.',
        'title'             => 'Leaving the campaign',
    ],
    'members'                           => [
        'actions'               => [
            'help'          => 'Help',
            'remove'        => 'Remove from campaign',
            'switch'        => 'View campaign as user',
            'switch-back'   => 'Back to my user',
            'switch-entity' => 'View as',
        ],
        'create'                => [
            'title' => 'Add a member to your campaign',
        ],
        'edit'                  => [
            'title' => 'Edit member :name',
        ],
        'fields'                => [
            'banned'        => 'User is banned',
            'joined'        => 'Joined',
            'last_login'    => 'Last Login',
            'name'          => 'User',
            'role'          => 'Role',
            'roles'         => 'Roles',
        ],
        'help'                  => 'Campaigns can have an unlimited amount of members in them.',
        'helpers'               => [
            'admin' => 'As a member of the campaign\'s admin role, you can invite new users, remove inactive one, and change their permissions. To test the permissions of a member, use the :button button. You can read more about this feature in the :link.',
            'switch'=> 'View the campaign as this user',
        ],
        'impersonating'         => [
            'message'   => 'You are viewing the campaign as another user. Some features have been disabled, but the rest acts exactly as the user would see it.',
            'title'     => 'Impersonating :name',
        ],
        'invite'                => [
            'description'   => 'Invite your friends and players to the campaign by creating an invitation link and sending them the generated URL! Upon accepting their invitation, they will be added as a member in the invitation\'s requested role.',
            'more'          => 'You can add more roles on the :link.',
            'roles_page'    => 'Roles page',
            'title'         => 'Invites',
        ],
        'manage_roles'          => 'Manage user roles',
        'removal'               => 'You are removing ":member" from the campaign.',
        'roles'                 => [
            'member'    => 'Member',
            'owner'     => 'Admin',
            'player'    => 'Player',
            'public'    => 'Public',
            'viewer'    => 'Viewer',
        ],
        'switch_back_success'   => 'Switched back to your account.',
        'title'                 => 'Members - :name',
        'updates'               => [
            'added'     => 'Role :role added to :user.',
            'removed'   => 'Role :role removed from :user.',
        ],
        'your_role'             => 'Your role: <i>:role</i>',
    ],
    'modules'                           => [
        'permission-disabled'   => 'This module is disabled.',
    ],
    'panels'                            => [
        'boosted'   => 'Boosted',
        'dashboard' => 'Dashboard',
        'permission'=> 'Permission',
        'setup'     => 'Setup',
        'sharing'   => 'Sharing',
        'systems'   => 'Systems',
        'ui'        => 'Interface',
    ],
    'placeholders'                      => [
        'description'   => 'A short summary of your campaign',
        'locale'        => 'Language code',
        'name'          => 'Your campaign name',
        'system'        => 'D&D, Pathfinder, Fate, DSA',
    ],
    'privacy'                           => [
        'hidden'    => 'Hidden',
        'private'   => 'Private',
        'visible'   => 'Visible',
    ],
    'public'                            => [
        'helpers'   => [
            'introduction'  => 'Campaigns are private by default, and can be made public. This allows anyone to access them, and makes them available in the :public-campaigns page if they have entities visible to the :public-role role. A public campaign is visible to all, but for its content to be visible, the :public-role role needs adequate permissions.',
        ],
    ],
    'roles'                             => [
        'actions'       => [
            'add'           => 'Create role',
            'permissions'   => 'Manage permissions',
            'rename'        => 'Rename role',
            'save'          => 'Save role',
        ],
        'admin_role'    => 'admin role',
        'bulks'         => [
            'delete'    => '{1} Removed :count role.|[2,*] Removed :count roles.',
            'edit'      => '{1} Updated :count role.|[2,*] Updated :count roles.',
        ],
        'create'        => [
            'success'   => 'Role :name created.',
            'title'     => 'New role',
        ],
        'destroy'       => [
            'success'   => 'Role :name removed.',
        ],
        'edit'          => [
            'success'   => 'Role :name updated.',
            'title'     => 'Edit role :name',
        ],
        'fields'        => [
            'name'          => 'Name',
            'permissions'   => 'Permissions',
            'type'          => 'Type',
            'users'         => 'Users',
        ],
        'helper'        => [
            '1' => 'A campaign is split up into several roles. The :admin role automatically has access to everything in a campaign, but every other role can have specific permissions on different types of entities (character, location, etc).',
            '2' => 'Entities can have more fine-tuned permissions by viewing the "Permissions" tab of an entity. This tab appears once your campaign has several roles or members.',
            '3' => 'One can either go with an "opt-out" system, where roles are given access to viewing all of the entities, and use the "Private" checkbox on entities to hide them. Or one can not give roles many permissions, but set each entity to be visible individually.',
            '4' => 'Boosted campaigns can have an unlimited amount of roles.',
        ],
        'hints'         => [
            'campaign_not_public'   => 'The public role has permissions but the campaign is private. You can change this setting on the Sharing tab when editing the campaign.',
            'empty_role'            => 'The role doesn\'t have any members in it yet.',
            'role_admin'            => 'The :name role automatically grants access to everything in the campaign to its members.',
            'role_permissions'      => 'Enable the :name role to do the following actions on all entities',
        ],
        'members'       => 'Members',
        'modals'        => [
            'details'   => [
                'campaign'  => 'Campaign permissions allow the following.',
                'entities'  => 'Here is a quick recap of what members of this role get when a permission is set.',
                'more'      => 'For more details, view our tutorial video on Youtube',
                'title'     => 'Permission details',
            ],
        ],
        'permissions'   => [
            'actions'   => [
                'add'           => 'Create',
                'dashboard'     => 'Dashboard',
                'delete'        => 'Delete',
                'edit'          => 'Edit',
                'entity-note'   => 'Post',
                'gallery'       => 'Gallery',
                'manage'        => 'Manage',
                'members'       => 'Members',
                'permission'    => 'Permissions',
                'read'          => 'View',
                'toggle'        => 'Change for all',
            ],
            'helpers'   => [
                'add'           => 'Allow creating entities of this type. They will automatically be allowed to view and edit entities they create if they don\'t have the view or edit permission.',
                'dashboard'     => 'Allow editing the dashboards and dashboard widgets.',
                'delete'        => 'Allow removing all entities of this type.',
                'edit'          => 'Allow editing all entities of this type.',
                'entity_note'   => 'Allows adding and editing posts even if the member can\'t edit the entity.',
                'gallery'       => 'Allow managing the superboosted campaign\'s gallery.',
                'manage'        => 'Allow editing the campaign as a campaign admin would, without allowing the members to delete the campaign.',
                'members'       => 'Allow inviting new members to the campaign.',
                'permission'    => 'Allow setting permissions on entities of this type they can edit.',
                'read'          => 'Allow viewing all entities of this type that aren\'t private.',
            ],
        ],
        'placeholders'  => [
            'name'  => 'Name of the role',
        ],
        'show'          => [
            'title' => 'Campaign Role \':role\'',
        ],
        'title'         => 'Roles - :name',
        'types'         => [
            'owner'     => 'Admin',
            'public'    => 'Public',
            'standard'  => 'Standard',
        ],
        'users'         => [
            'actions'   => [
                'add'           => 'Add member',
                'remove'        => ':user from the :role role',
                'remove_user'   => 'Remove user from role',
            ],
            'create'    => [
                'success'   => ':user added to the role :role.',
                'title'     => 'Add a member to the :name role',
            ],
            'destroy'   => [
                'success'   => ':user removed from the role :role.',
            ],
            'errors'    => [
                'cant_kick_admins'  => 'To avoid abuse, it is not possible to remove other members from the campaign\'s :admin role. In case of issues, contact us on :discord or at :email.',
                'needs_more_roles'  => 'You need to add yourself to another role in the campaign before being able to remove yourself from the :admin role.',
            ],
            'fields'    => [
                'name'  => 'Name',
            ],
        ],
    ],
    'settings'                          => [
        'actions'       => [
            'enable'    => 'Enable',
        ],
        'boosted'       => 'This feature is in early access and currently only available for :boosted.',
        'deprecated'    => [
            'help'  => 'This module is deprecated, meaning it is no longer maintained, and that bugs aren\'t tested with each new update. Use this module with the knowledge that it will eventually get removed from Kanka.',
            'title' => 'Deprecated',
        ],
        'disabled'      => 'The :module module is disabled.',
        'enabled'       => 'The :module module is enabled.',
        'errors'        => [
            'module-disabled'   => 'The requested module is currently disabled in the campaign settings. :fix.',
        ],
        'helpers'       => [
            'abilities'     => 'Create abilities, be it feats, spells, or powers that can be assigned to entities.',
            'calendars'     => 'A place to define the calendars of your world.',
            'characters'    => 'Create and keep track of the people inhabiting the world with characters.',
            'conversations' => 'Fictional conversations between characters or between campaign users.',
            'creatures'     => 'Build your world\'s creatures, animals, and monsters with the creatures module.',
            'dice_rolls'    => 'For those who use Kanka for RPG campaigns, a way to handle dice rolls.',
            'events'        => 'Holidays, festivals, disasters, birthdays, wars.',
            'families'      => 'Clans or families, their relations and their members.',
            'inventories'   => 'Manage inventories on your entities.',
            'items'         => 'Weapons, vehicles, relics, potions.',
            'journals'      => 'Observations written by characters, or session prep for the dungeon master.',
            'locations'     => 'Planets, planes, continents, rivers, states, settlements, temples, taverns.',
            'maps'          => 'Upload maps with layers and markers pointing to other entities in the campaign.',
            'menu_links'    => 'Custom menu links in the side bar.',
            'notes'         => 'Lore, nature, history, magic, cultures.',
            'organisations' => 'Cults, religions, factions, guilds.',
            'quests'        => 'To keep track of various quests with characters and locations.',
            'races'         => 'Track the origins, ethnicities, and racial traits of the world\'s characters with the race module.',
            'tags'          => 'Each entity can have several tags. Tags can belong to other tags, and entries can be filtered by tag.',
            'timelines'     => 'Represent the history of your world with timelines.',
        ],
    ],
    'show'                              => [
        'actions'   => [
            'boost' => 'Boost campaign',
            'edit'  => 'Edit Campaign',
            'leave' => 'Leave campaign',
        ],
        'menus'     => [
            'configuration'     => 'Configuration',
            'overview'          => 'Overview',
            'user_management'   => 'User management',
        ],
        'tabs'      => [
            'achievements'      => 'Achievements',
            'applications'      => 'Applications',
            'campaign'          => 'Campaign',
            'default-images'    => 'Default thumbnails',
            'export'            => 'Export',
            'information'       => 'Information',
            'members'           => 'Members',
            'plugins'           => 'Plugins',
            'recovery'          => 'Recovery',
            'roles'             => 'Roles',
            'settings'          => 'Modules',
            'sidebar'           => 'Sidebar setup',
            'styles'            => 'Theming',
        ],
        'title'     => 'Overview - :name',
    ],
    'themes'                            => [
        'none'  => 'None (defaults to user settings)',
    ],
    'ui'                                => [
        'boosted'           => 'Boosted',
        'collapsed'         => [
            'collapsed' => 'Collapsed',
            'default'   => 'Default',
        ],
        'connections'       => [
            'explorer'  => 'Relations explorer (if available, for boosted campaigns)',
            'list'      => 'List interface',
        ],
        'entity_history'    => [
            'hidden'    => 'Only visible to campaign admins',
            'visible'   => 'Visible to members',
        ],
        'fields'            => [
            'connections'       => 'Default entity\'s connections interface',
            'connections_mode'  => 'Default relations explorer mode',
            'entity_history'    => 'Entity\'s history logs',
            'entity_image'      => 'Entity\'s image',
            'family_toolip'     => 'Character\'s family',
            'member_list'       => 'Campaign\'s member list',
            'nested'            => 'Default lists layout',
            'post_collapsed'    => 'New post default collapsed value',
        ],
        'helpers'           => [
            'connections'       => 'When clicking on the connections subpage of an entity, select the default interface showed.',
            'connections_mode'  => 'When viewing the relation explorer of an entity, define the default mode that is selected.',
            'other'             => 'Other visual options for the campaign.',
            'post_collapsed'    => 'When creating a new post on an entity, select the collapsed field\'s default value.',
            'tooltip'           => 'Control which information is visibile when hovering an entity\'s name in their tooltip.',
        ],
        'members'           => [
            'hidden'    => 'Only visible to campaign admins',
            'visible'   => 'Visible to members',
        ],
        'nested'            => [
            'nested'    => 'Nested',
            'user'      => 'User\'s default',
        ],
        'other'             => 'Other',
    ],
    'visibilities'                      => [
        'private'   => 'Private',
        'public'    => 'Public',
        'review'    => 'Awaiting Review',
    ],
    'warning'                           => [
        'editing'   => [
            'description'   => 'Looks like someone else is currently editing this campaign! Do you want to go back or ignore this warning, at the risk of losing data? Members currently editing this campaign:',
        ],
    ],
];
