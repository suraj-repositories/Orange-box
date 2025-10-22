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
        Schema::table('project_modules', function (Blueprint $table) {
            //
            $table->dropUnique(['slug']);
            $table->unique(['slug', 'project_board_id', 'user_id'], 'project_modules_slug_board_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_modules', function (Blueprint $table) {
            //
            $table->dropUnique('project_modules_slug_board_user_unique');
            $table->unique('slug');
        });
    }
};
