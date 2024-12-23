<?php

namespace Database\Seeders;

use App\Models\Emoji;
use App\Models\EmojiCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmojiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = config('emojis');

        foreach ($categories as $categoryName => $emojis) {

            $category = EmojiCategory::firstOrCreate(['name' => $categoryName]);

            $emojiData = array_map(
                fn($emoji, $index) => [
                    'emoji_category_id' => $category->id,
                    'name' => $categoryName . 'Emoji' . ($index + 1),
                    'emoji' => $emoji,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                $emojis,
                array_keys($emojis)
            );

            Emoji::insert($emojiData);
        }
    }
}
