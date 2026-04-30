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
        $links = config('seedable.quick_links', []);

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
