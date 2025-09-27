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
        Schema::table('files', function (Blueprint $table) {
            $table->string('fileable_type', 255)
                ->collation('utf8mb4_unicode_ci')
                ->nullable()
                ->change();

            $table->unsignedBigInteger('fileable_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('fileable_type', 255)
                ->collation('utf8mb4_unicode_ci')
                ->nullable(false)
                ->change();

            $table->unsignedBigInteger('fileable_id')
                ->nullable(false)
                ->change();
        });
    }
};
