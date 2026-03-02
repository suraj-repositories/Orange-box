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
        Schema::table('documentation_documents', function (Blueprint $table) {
            //
            $table->foreignId('release_id')
                ->nullable()
                ->after('documentation_id')
                ->constrained('documentation_releases')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_documents', function (Blueprint $table) {
            $table->dropForeign(['release_id']);
            $table->dropColumn('release_id');
        });
    }
};
