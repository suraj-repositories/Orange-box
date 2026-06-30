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
        Schema::table('documentations', function (Blueprint $table) {
            //
            $table->string('load_url')->nullable()->after('documentation_template_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentations', function (Blueprint $table) {
            //
            $table->dropColumn('load_url');
        });
    }
};
