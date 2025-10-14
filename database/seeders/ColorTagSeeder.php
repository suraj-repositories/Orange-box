<?php

namespace Database\Seeders;

use App\Models\ColorTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tagColors = collect(config('constants.TAG_COLORS'))
            ->map(fn($tag) => (object) $tag);;

        foreach($tagColors as $tag){
            ColorTag::create([
                'name' => $tag->label,
                'color_code' => $tag->code,
                'emoji' => $tag->emoji,
            ]);
        }
    }
}
