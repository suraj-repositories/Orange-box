<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(EmojiSeeder::class);
        $this->call(ColorTagSeeder::class);
        $this->call(IconSeeder::class);
        $this->call(ProjectModuleTypeSeeder::class);
        $this->call(SocialMediaPlatformSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
