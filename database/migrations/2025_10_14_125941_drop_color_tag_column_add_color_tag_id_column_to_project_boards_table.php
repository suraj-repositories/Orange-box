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
        Schema::table('project_boards', function (Blueprint $table) {
            //
            $table->dropColumn('color_tag');
            $table->foreignId('color_tag_id')
                ->nullable()
                ->after('thumbnail')
                ->constrained('color_tags')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_boards', function (Blueprint $table) {
            //
            $table->dropForeign(['color_tag_id']);
            $table->dropColumn('color_tag_id');
            $table->string('color_tag')->nullable();
        });
    }
};
