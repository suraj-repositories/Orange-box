<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = config('icons');

        $data = [];
        foreach ($categories as $category => $categoryItem) {
            $counter = 0;
            foreach ($categoryItem as $name => $file_path) {
                $data[] = [
                    'name' => $name,
                    'category' => $category,
                    'file_path' => $file_path,
                    'order' => $counter++,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        DB::table('icons')->insert($data);
    }
}
