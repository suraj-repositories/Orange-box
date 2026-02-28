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

            $table->renameColumn('logo', 'logo_light');

            $table->string('logo_sm_light')->nullable()->after('logo_light');
            $table->string('logo_dark')->nullable()->after('logo_sm_light');
            $table->string('logo_sm_dark')->nullable()->after('logo_dark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentations', function (Blueprint $table) {
            $table->renameColumn('logo_light', 'logo');
            $table->dropColumn([
                'logo_sm_light',
                'logo_dark',
                'logo_sm_dark',
            ]);
        });
    }
};
