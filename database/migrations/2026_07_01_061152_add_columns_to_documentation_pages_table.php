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
        Schema::table('documentation_pages', function (Blueprint $table) {
            //
            $table->string('git_path')->nullable()->after('git_link');
            $table->string('git_sha')->nullable()->after('git_path');

            $table->unique([
                'documentation_id',
                'release_id',
                'git_path'
            ], 'documentation_git_path_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_pages', function (Blueprint $table) {
            //
            $table->dropUnique('documentation_git_path_unique');

            $table->dropColumn([
                'git_path',
                'git_sha',
            ]);
        });
    }
};
