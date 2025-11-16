<?php

namespace Database\Seeders;

use App\Models\Settings;
use App\Models\SettingsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingCategories = config('seedable.settings.categoryies');
        $defaultSettings = config('seedable.settings.default_settings');

        try {

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Settings::truncate();
            SettingsCategory::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            if (is_array($settingCategories) && !empty($settingCategories)) {

                foreach ($settingCategories as &$c) {
                    $c['created_at'] = now();
                    $c['updated_at'] = now();
                }
                unset($c);

                SettingsCategory::insert($settingCategories);
            }

            if (is_array($defaultSettings) && !empty($defaultSettings)) {

                foreach ($defaultSettings as &$s) {

                    if (isset($s['is_enabled'])) {
                        $s['value'] = null;
                        $s['value_model'] = null;
                    }

                    if (isset($s['value_model'])) {
                        $s['is_enabled'] = null;
                    }
                    $s['created_at'] = now();
                    $s['updated_at'] = now();
                }
                unset($s);

                Settings::insert($defaultSettings);
            }

            Log::info('Settings seeded successfully!');
        } catch (\Exception $ex) {
            Log::critical('Error seeding settings: ' . $ex->getMessage());
        }
    }
}
