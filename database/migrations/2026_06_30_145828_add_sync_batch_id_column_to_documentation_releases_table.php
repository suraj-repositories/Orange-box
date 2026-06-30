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
            $table->string('sync_batch_id')->nullable()->after('load_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_releases', function (Blueprint $table) {
            //
            $table->dropColumn('sync_batch_id');
        });
    }
};
