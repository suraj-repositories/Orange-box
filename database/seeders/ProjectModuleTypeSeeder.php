<?php

namespace Database\Seeders;

use App\Models\ProjectModuleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectModuleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $types = config('constants.PROJECT_MODULE_TYPES');

        $data = [];
        foreach($types as $type){
            $data[] = [
                'name' => $type,
                'slug' => Str::slug($type),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        ProjectModuleType::insert($data);

    }
}
