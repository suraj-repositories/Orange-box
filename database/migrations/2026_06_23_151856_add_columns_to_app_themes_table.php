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
        Schema::table('app_themes', function (Blueprint $table) {
            //
            $table->dropColumn('logo_image');

            $table->string('logo_light')->nullable()->after('theme_image');
            $table->string('logo_dark')->nullable()->after('logo_light');
            $table->string('logo_sm')->nullable()->after('logo_dark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_themes', function (Blueprint $table) {
            //
            $table->dropColumn([
                'logo_light',
                'logo_dark',
                'logo_sm',
            ]);

            $table->string('logo_image')->nullable()->after('theme_image');
        });
    }
};
