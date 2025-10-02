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
        Schema::table('syntax_stores', function (Blueprint $table) {
            //
            $table->string('preview_text', 256)->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syntax_stores', function (Blueprint $table) {
            //
            $table->dropColumn('preview_text');
        });
    }
};
