<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_module_types', function (Blueprint $table) {
            //
            $table->foreignId('color_tag_id')
                ->nullable()
                ->after('slug')
                ->constrained('color_tags')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_module_types', function (Blueprint $table) {
            //
            $table->dropForeign(['color_tag_id']);
            $table->dropColumn('color_tag_id');
        });
    }
};
