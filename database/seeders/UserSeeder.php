<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = User::firstOrCreate([
            'username' => 'Admin@123',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $admin->assignRole('admin');
        $user = User::firstOrCreate([
            'username' => 'Editor@123',
            'email' => 'editor@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole('editor');

    }
}