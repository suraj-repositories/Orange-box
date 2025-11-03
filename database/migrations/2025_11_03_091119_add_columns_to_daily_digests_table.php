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
        Schema::table('daily_digests', function (Blueprint $table) {
            //
            $table->enum('visibility', ['private','protected', 'unlisted', 'public'])->after('uuid')->default('private');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_digests', function (Blueprint $table) {
            //
            $table->dropColumn('visibility');
        });
    }
};
