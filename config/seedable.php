<?php

return [
    'settings' => [
        'categoryies' => [
            [
                "id" => 1,
                "name" => "Account Settings",
                "description" => "",
                "icon" => 'assets/icons/ob-svg/setting/account.svg'

            ],
            [
                "id" => 2,
                "name" => "Notifications",
                "description" => "",
                "icon" => 'assets/icons/ob-svg/setting/notification.svg'
            ],
            [
                "id" => 3,
                "name" => "Security Settings",
                "description" => "",
                "icon" => 'assets/icons/ob-svg/setting/security.svg'
            ],
            [
                "id" => 4,
                "name" => "Theme",
                "description" => "",
                "icon" => 'assets/icons/ob-svg/setting/theme.svg'
            ],

            [
                "id" => 5,
                "name" => "App Modules",
                "description" => "Mange different supported module on this app",
                "icon" => 'assets/icons/ob-svg/setting/module.svg'
            ],
        ],
        'default_settings' => [

            // Category: Account
            [
                'title' => 'Change Username',
                'key' => 'username_update',
                'is_enabled' => true,
                'description' => 'User can change their usernames!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Change Primary Email',
                'key' => 'primary_email_update',
                'is_enabled' => true,
                'description' => 'User can change their primary email!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Delete Account',
                'key' => 'delete_account',
                'is_enabled' => true,
                'description' => 'Danger! Account deletion is hard to revert.',
                'settings_category_id' => 1,
            ],


            [
                'title' => 'Task Notifications',
                'key' => 'task_notification',
                'is_enabled' => true,
                'description' => 'Notify when assigned a task.',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Module Notifications',
                'key' => 'module_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone joins your module.',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Comment Notifications',
                'key' => 'comment_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone comments.',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Comment Reply Notifications',
                'key' => 'comment_reply_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone replies.',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Unlisted Visit Notifications',
                'key' => 'unlisted_visit_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone visits your unlisted post.',
                'settings_category_id' => 2,
            ],


            [
                'title' => 'Master Password',
                'key' => 'master_password_enabled',
                'is_enabled' => false,
                'description' => 'Enable master password feature.',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'Lock Screen Password',
                'key' => 'lock_screen_enabled',
                'is_enabled' => false,
                'description' => 'Enable lock-screen.',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'Pem key Setup',
                'key' => 'pem_key_enabled',
                'is_enabled' => false,
                'description' => 'Enable PEM key setup.',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'Change Password',
                'key' => 'enable_change_password',
                'is_enabled' => true,
                'description' => 'Allow password updates.',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'App Based Authentication',
                'key' => 'app_auth_enabled',
                'is_enabled' => false,
                'description' => 'Enable app-based authentication.',
                'settings_category_id' => 3,
            ],


            // Category: Theme (MODEL-BASED)
            [
                'title' => 'App Theme',
                'key' => 'app_theme',
                'value_model' => 'App\Models\AppTheme',
                'value' => 1,
                'description' => 'Choose a theme.',
                'settings_category_id' => 4,
            ],

            [
                'title' => 'Enable Project Module',
                'key' => 'enable_project_module',
                'is_enabled' => true,
                'description' => 'Enable project & task management.',
                'settings_category_id' => 5,
            ],
            [
                'title' => 'Enable Project Contribution Module',
                'key' => 'enable_contribution_module',
                'is_enabled' => true,
                'description' => 'Enable project contribution.',
                'settings_category_id' => 5,
            ],
            [
                'title' => 'Enable Git Module',
                'key' => 'enable_git_module',
                'is_enabled' => true,
                'description' => 'Enable git support.',
                'settings_category_id' => 5,
            ],
            [
                'title' => 'Enable Orbit Zone Module',
                'key' => 'enable_orbit_zone_module',
                'is_enabled' => true,
                'description' => 'Enable Orbit Zone.',
                'settings_category_id' => 5,
            ],

        ]

    ],

    'social_media_platforms' => [
        [
            'name' => 'Facebook',
            'url' => 'https://www.facebook.com',
            'icon' => 'bx bxl-facebook-circle',
            'color' => '#086CFF',
            'bg_color' => '#ffffffff',
        ],
        [
            'name' => 'Instagram',
            'url' => 'https://www.instagram.com',
            'icon' => 'bx bxl-instagram-alt',
            'color' => '#E4405F',
            'bg_color' => '#ffffffff',
        ],
        [
            'name' => 'LinkedIn',
            'url' => 'https://www.linkedin.com',
            'icon' => 'bx bxl-linkedin-square',
            'color' => '#0077B5',
            'bg_color' => '#ffffffff',
        ],
        [
            'name' => 'Twitter (X)',
            'url' => 'https://www.twitter.com',
            'icon' => 'bx bxl-twitter',
            'color' => '#000000',
            'bg_color' => '#ffffffff',
        ],
        [
            'name' => 'YouTube',
            'url' => 'https://www.youtube.com',
            'icon' => 'bx bxl-youtube',
            'color' => '#FF0000',
            'bg_color' => '#ffffffff',
        ],
        [
            'name' => 'GitHub',
            'url' => 'https://www.github.com',
            'icon' => 'bx bxl-github',
            'color' => '#24292e',
            'bg_color' => '#ffffffff',
        ],
        [
            'name' => 'WhatsApp',
            'url' => 'https://www.whatsapp.com',
            'icon' => 'bx bxl-whatsapp',
            'color' => '#25D366',
            'bg_color' => '#ffffffff',
        ],
    ],

];
