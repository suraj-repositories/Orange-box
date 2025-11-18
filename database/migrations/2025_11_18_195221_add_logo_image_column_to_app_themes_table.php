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
            $table->renameColumn('image', 'theme_image');
            $table->string('logo_image')->after('theme_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_themes', function (Blueprint $table) {
            //
            $table->renameColumn('theme_image', 'image');
            $table->dropColumn('logo_image');
        });
    }
};
