<?php

return [
    'settings' => [
        'categoryies' => [
            [
                "id" => 1,
                "name" => "Account Settings",
                "description" => ""
            ],
            [
                "id" => 2,
                "name" => "Notifications",
                "description" => ""
            ],
            [
                "id" => 3,
                "name" => "Theme",
                "description" => ""
            ],
            [
                "id" => 4,
                "name" => "Security Settings",
                "description" => ""
            ],
            [
                "id" => 5,
                "name" => "App Modules",
                "description" => ""
            ],
        ],
        'default_settings' => [
            [
                'title' => 'Task Notifications',
                'key' => 'task_notification',
                'value' => 'on',
                'description' => 'A new notificaiton arrise when anyone assign you a task!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Module Notifications',
                'key' => 'module_notification',
                'value' => 'on',
                'description' => 'A new notificaiton arrise when anyone join you a on any project module!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Comment Notifications',
                'key' => 'comment_notification',
                'value' => 'on',
                'description' => 'A notification arrise when anyone comment to your post!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Comment Reply Notifications',
                'key' => 'comment_reply_notification',
                'value' => 'on',
                'description' => 'A notification arrise when anyone reply to your comment!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Unlisted Visit Notifications',
                'key' => 'unlisted_visit_notification',
                'value' => 'on',
                'description' => 'A notification arrise when anyone visited to your unlisted post!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Master Password',
                'key' => 'is_master_password_set',
                'value' => false,
                'description' => 'Set your master password. With this single password you can manage all authorized tasks. Buf often risky to get anyone know steal this, make sure to change master key after few uses!',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Lock Screen Password',
                'key' => 'lock_screen_password_set',
                'value' => false,
                'description' => 'You can lock unlock screen, according to you handy then logout!',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Pem key Setup',
                'key' => 'is_pem_key_set',
                'value' => false,
                'description' => 'Setup you private file athorization.',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Change Password',
                'key' => 'enable_change_password',
                'value' => true,
                'description' => 'Update your password.',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'App Based Authentication',
                'key' => 'is_app_auth_set',
                'value' => false,
                'description' => 'Download and set the authentication via our authentication app.',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'App Theme',
                'key' => 'app_theme',
                'value' => 1,
                'description' => 'Choose to apply a theme',
                'settings_category_id' => 3,
            ]
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
