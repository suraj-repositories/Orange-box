<?php

return [
    'settings' => [
        'categoryies' => [
            [
                "id" => 1,
                "name" => "Account Settings",
                "description" => "",
                "icon" => 'bx bx-user-circle'
            ],
            [
                "id" => 2,
                "name" => "Notifications",
                "description" => "",
                "icon" => 'bx bx-bell'
            ],
            [
                "id" => 3,
                "name" => "Security Settings",
                "description" => "",
                "icon" => 'bx bx-shield'
            ],
            [
                "id" => 4,
                "name" => "Theme",
                "description" => "",
                "icon" => 'bx bx-palette'
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
                'icon' => 'bx bx-user-circle'
            ],
            [
                'title' => 'Change Primary Email',
                'key' => 'primary_email_update',
                'is_enabled' => true,
                'description' => 'User can change their primary email!',
                'settings_category_id' => 1,
                'icon' => 'bx bx-envelope'
            ],
            [
                'title' => 'Delete Account',
                'key' => 'delete_account',
                'is_enabled' => true,
                'description' => 'Danger! Account deletion is hard to revert.',
                'settings_category_id' => 1,
                'icon' => 'bx bx-trash'
            ],

            // Category: Notifications
            [
                'title' => 'Task Notifications',
                'key' => 'task_notification',
                'is_enabled' => true,
                'description' => 'Notify when assigned a task.',
                'settings_category_id' => 2,
                'icon' => 'bx bx-task'
            ],
            [
                'title' => 'Module Notifications',
                'key' => 'module_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone joins your module.',
                'settings_category_id' => 2,
                'icon' => 'bx bx-layer'
            ],
            [
                'title' => 'Comment Notifications',
                'key' => 'comment_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone comments.',
                'settings_category_id' => 2,
                'icon' => 'bx bx-comment'
            ],
            [
                'title' => 'Comment Reply Notifications',
                'key' => 'comment_reply_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone replies.',
                'settings_category_id' => 2,
                'icon' => 'bx bx-chat'
            ],
            [
                'title' => 'Unlisted Visit Notifications',
                'key' => 'unlisted_visit_notification',
                'is_enabled' => true,
                'description' => 'Notify when someone visits your unlisted post.',
                'settings_category_id' => 2,
                'icon' => 'bx bx-show'
            ],

            [
                'title' => 'Master Password',
                'key' => 'master_password_enabled',
                'is_enabled' => true,
                'description' => 'Enable master password feature.',
                'settings_category_id' => 3,
                'icon' => 'bx bx-key'
            ],
            [
                'title' => 'Lock Screen Password',
                'key' => 'lock_screen_enabled',
                'is_enabled' => true,
                'description' => 'Enable lock-screen.',
                'settings_category_id' => 3,
                'icon' => 'bx bx-lock'
            ],
            [
                'title' => 'Pem key Setup',
                'key' => 'pem_key_enabled',
                'is_enabled' => true,
                'description' => 'Enable PEM key setup.',
                'settings_category_id' => 3,
                'icon' => 'bx bx-file'
            ],
            [
                'title' => 'Change Password',
                'key' => 'enable_change_password',
                'is_enabled' => true,
                'description' => 'Allow password updates.',
                'settings_category_id' => 3,
                'icon' => 'bx bx-lock-alt'
            ],
            [
                'title' => 'App Based Authentication',
                'key' => 'app_auth_enabled',
                'is_enabled' => false,
                'description' => 'Enable app-based authentication.',
                'settings_category_id' => 3,
                'icon' => 'bx bx-shield'
            ],

            // Category: Theme (MODEL-BASED)
            [
                'title' => 'App Theme',
                'key' => 'app_theme',
                'value_model' => 'App\Models\AppTheme',
                'value' => 1,
                'description' => 'Choose a theme.',
                'settings_category_id' => 4,
                'icon' => 'bx bx-palette'
            ],

        ]

    ],
    'app_themes' => [
        [
            'title' => 'Orange Box',
            'theme_key' => 'orange_box',
            'theme_image' => '/assets/images/themes/orange_box.png',
            'logo_image' => '/assets/images/logo/orange_box.svg'
        ],
        [
            'title' => 'Fresh Fiber',
            'theme_key' => 'fresh_fiber',
            'theme_image' => '/assets/images/themes/fresh_fiber.png',
            'logo_image' => '/assets/images/logo/fresh_fiber.svg'
        ],
        [
            'title' => 'Bright Red',
            'theme_key' => 'bright_red',
            'theme_image' => '/assets/images/themes/bright_red.png',
            'logo_image' => '/assets/images/logo/bright_red.svg'
        ],
        [
            'title' => 'Sweet Blue',
            'theme_key' => 'sweet_blue',
            'theme_image' => '/assets/images/themes/sweet_blue.png',
            'logo_image' => '/assets/images/logo/sweet_blue.svg'
        ],
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
        [
            'name' => 'Discord',
            'url' => 'https://www.discord.com',
            'icon' => 'bx bxl-discord',
            'color' => '#0D0C0C',
            'bg_color' => '#ffffffff',
        ],
    ],

];
