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
            $table->text('preview_text')->nullable()->after('description');

            $table->index('user_id');
            $table->index('status');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_boards', function (Blueprint $table) {
            //
            $table->dropColumn('preview_text');

            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['slug']);
        });
    }
};
