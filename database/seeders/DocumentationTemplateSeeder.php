<?php

namespace Database\Seeders;

use App\Models\DocumentationTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates =  config('seedable.documentation_templates', []);

        foreach ($templates as $template) {
            DocumentationTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}
