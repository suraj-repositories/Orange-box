<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            DB::statement("ALTER TABLE documentation_documents
            MODIFY content LONGTEXT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_documents', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE documentation_documents
            MODIFY content LONGTEXT NOT NULL");
        });
    }
};
