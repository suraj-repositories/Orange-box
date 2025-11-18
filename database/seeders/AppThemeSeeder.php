<?php

namespace Database\Seeders;

use App\Models\AppTheme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $themes = config('seedable.app_themes');
        if(!$themes){
            return;
        }

        foreach($themes as $theme){
            AppTheme::create(array_merge($theme, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

    }
}
