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
            [
                'title' => 'Change Username',
                'key' => 'username_update',
                'value' =>  'on',
                'description' => 'User can change their usernames!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Change Primary Email',
                'key' => 'primary_email_update',
                'value' =>  'on',
                'description' => 'User can change their primary email!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Delete Account',
                'key' => 'delete_account',
                'value' =>  'on',
                'description' => 'Danger! - Once your account has been deleted it is very difficult to get it again!!!',
                'settings_category_id' => 1,
            ],
            [
                'title' => 'Task Notifications',
                'key' => 'task_notification',
                'value' => 'on',
                'description' => 'A new notificaiton arrise when anyone assign you a task!',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Module Notifications',
                'key' => 'module_notification',
                'value' => 'on',
                'description' => 'A new notificaiton arrise when anyone join you a on any project module!',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Comment Notifications',
                'key' => 'comment_notification',
                'value' => 'on',
                'description' => 'A notification arrise when anyone comment to your post!',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Comment Reply Notifications',
                'key' => 'comment_reply_notification',
                'value' => 'on',
                'description' => 'A notification arrise when anyone reply to your comment!',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Unlisted Visit Notifications',
                'key' => 'unlisted_visit_notification',
                'value' => 'on',
                'description' => 'A notification arrise when anyone visited to your unlisted post!',
                'settings_category_id' => 2,
            ],
            [
                'title' => 'Master Password',
                'key' => 'is_master_password_set',
                'value' => false,
                'description' => 'Set your master password. With this single password you can manage all authorized tasks. Buf often risky to get anyone know steal this, make sure to change master key after few uses!',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'Lock Screen Password',
                'key' => 'lock_screen_password_set',
                'value' => false,
                'description' => 'You can lock unlock screen, according to you handy then logout!',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'Pem key Setup',
                'key' => 'is_pem_key_set',
                'value' => false,
                'description' => 'Setup you private file athorization.',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'Change Password',
                'key' => 'enable_change_password',
                'value' => true,
                'description' => 'Update your password.',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'App Based Authentication',
                'key' => 'is_app_auth_set',
                'value' => false,
                'description' => 'Download and set the authentication via our authentication app.',
                'settings_category_id' => 3,
            ],
            [
                'title' => 'App Theme',
                'key' => 'app_theme',
                'value' => 1,
                'description' => 'Choose to apply a theme',
                'settings_category_id' => 4,
            ],
            [
                'title' => 'Enable Project Module',
                'key' => 'enable_project_module',
                'value' => true,
                'description' => 'Enable project, module, task and subtask creation.',
                'settings_category_id' => 5,
            ],
            [
                'title' => 'Enable Project Contribution Module',
                'key' => 'enable_contribution_module',
                'value' => true,
                'description' => 'Enable mangement of project assigned by someone else!',
                'settings_category_id' => 5,
            ],
            [
                'title' => 'Enable Git Module',
                'key' => 'enable_git_module',
                'value' => true,
                'description' => 'Enable your git',
                'settings_category_id' => 5,
            ],
            [
                'title' => 'Enable Orbit Zone Module',
                'key' => 'enable_orbit_zone_module',
                'value' => true,
                'description' => 'Enable your git',
                'settings_category_id' => 5,
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
