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
        Schema::table('documentation_releases', function (Blueprint $table) {
            //
            $table->enum('sync_status', ['pending', 'syncing', 'completed', 'failed'])->default('pending')->after('sync_batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_releases', function (Blueprint $table) {
            //
            $table->dropColumn('sync_status');
        });
    }
};
