<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define roles
        $roles = ['admin', 'editor', 'user'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Define permissions
        $permissions = [
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $editorRole = Role::findByName('editor');

        $adminRole->syncPermissions($permissions);
        $editorRole->syncPermissions(['create_posts', 'edit_posts']);
        // User role doesn't need any permissions at this stage
    }
}
