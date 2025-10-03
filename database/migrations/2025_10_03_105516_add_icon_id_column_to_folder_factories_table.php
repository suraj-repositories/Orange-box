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
        Schema::table('folder_factories', function (Blueprint $table) {
            //
            $table->foreignId('icon_id')
                ->nullable()
                ->after('id')
                ->constrained('icons')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folder_factories', function (Blueprint $table) {
            //
            $table->dropForeign(['icon_id']);
            $table->dropColumn('icon_id');
        });
    }
};
