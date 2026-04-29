<?php

namespace Database\Seeders;

use App\Models\QuickLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuickLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [

            // Dashboard
            [
                'title' => 'Dashboard Home',
                'icon' => 'bx bx-home',
                'route_name' => 'user.dashboard',
                'color' => 'primary',
                'sort_order' => 1,
            ],
            [
                'title' => 'Analytics',
                'icon' => 'bx bx-bar-chart',
                'route_name' => 'user.dashboard.analytics',
                'color' => 'info',
                'sort_order' => 2,
            ],

            // Daily Digest
            [
                'title' => 'Public Digestions',
                'icon' => 'bx bx-news',
                'route_name' => 'user.daily-digest',
                'color' => 'success',
            ],
            [
                'title' => 'My Digestions',
                'icon' => 'bx bx-user',
                'route_name' => 'user.daily-digest.me',
                'color' => 'secondary',
            ],
            [
                'title' => 'Add Digestion',
                'icon' => 'bx bx-plus',
                'route_name' => 'user.daily-digest.create',
                'color' => 'primary',
            ],

            // Think Pad
            [
                'title' => 'Public Think Pads',
                'icon' => 'bx bx-book',
                'route_name' => 'user.think-pad',
                'color' => 'success',
            ],
            [
                'title' => 'My Think Pads',
                'icon' => 'bx bx-user-pin',
                'route_name' => 'user.think-pad.me',
                'color' => 'secondary',
            ],
            [
                'title' => 'Create Think Pad',
                'icon' => 'bx bx-edit',
                'route_name' => 'user.think-pad.create',
                'color' => 'primary',
            ],

            // Syntax Store
            [
                'title' => 'Syntax Store',
                'icon' => 'bx bx-code',
                'route_name' => 'user.syntax-store',
                'color' => 'dark',
            ],
            [
                'title' => 'My Syntax',
                'icon' => 'bx bx-user-circle',
                'route_name' => 'user.syntax-store.me',
                'color' => 'secondary',
            ],
            [
                'title' => 'Write Syntax',
                'icon' => 'bx bx-pencil',
                'route_name' => 'user.syntax-store.create',
                'color' => 'primary',
            ],

            // Documentation
            [
                'title' => 'New Documentation',
                'icon' => 'bx bx-file',
                'route_name' => 'user.documentation.create',
                'color' => 'primary',
            ],
            [
                'title' => 'Docs List',
                'icon' => 'bx bx-list-ul',
                'route_name' => 'user.documentation.index',
                'color' => 'info',
            ],

            // Folder Factory
            [
                'title' => 'Upload File',
                'icon' => 'bx bx-upload',
                'route_name' => 'user.folder-factory.files.create',
                'color' => 'primary',
            ],
            [
                'title' => 'Folders',
                'icon' => 'bx bx-folder',
                'route_name' => 'user.folder-factory',
                'color' => 'warning',
            ],

            // Project Board
            [
                'title' => 'Create Project',
                'icon' => 'bx bx-plus-circle',
                'route_name' => 'user.project-board.create',
                'color' => 'primary',
            ],
            [
                'title' => 'Projects',
                'icon' => 'bx bx-collection',
                'route_name' => 'user.project-board',
                'color' => 'info',
            ],

            // Tasks
            [
                'title' => 'Create Task',
                'icon' => 'bx bx-task',
                'route_name' => 'user.tasks.create',
                'color' => 'primary',
            ],
            [
                'title' => 'Task List',
                'icon' => 'bx bx-list-check',
                'route_name' => 'user.tasks.index',
                'color' => 'success',
            ],

            // Account
            [
                'title' => 'My Profile',
                'icon' => 'bx bx-user',
                'route_name' => 'user.profile.index',
                'color' => 'secondary',
            ],
            [
                'title' => 'Password Locker',
                'icon' => 'bx bx-lock',
                'route_name' => 'user.password_locker.index',
                'color' => 'danger',
            ],
            [
                'title' => 'Settings',
                'icon' => 'bx bx-cog',
                'route_name' => 'user.settings.index',
                'color' => 'dark',
            ],
        ];

        foreach ($links as $index => $link) {
            QuickLink::updateOrCreate(
                ['title' => $link['title']],
                array_merge($link, [
                    'sort_order' => $link['sort_order'] ?? $index,
                    'is_active' => true,
                ])
            );
        }
    }
}
