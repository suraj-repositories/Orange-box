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
            $table->index('documentation_id');

            $table->dropUnique('documentation_documents_documentation_id_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_documents', function (Blueprint $table) {
            //
            $table->dropIndex(['documentation_id']);
            $table->unique(['documentation_id', 'slug']);
        });
    }
};
