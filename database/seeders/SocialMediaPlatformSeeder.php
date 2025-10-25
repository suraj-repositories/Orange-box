<?php

namespace Database\Seeders;

use App\Models\SocialMediaPlatform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = config('seedable.social_media_platforms');

        if (empty($data) || !is_array($data)) {
            echo "empyt";
            return;
        }

        foreach ($data as $item) {
            SocialMediaPlatform::create($item);
        }
    }
}
