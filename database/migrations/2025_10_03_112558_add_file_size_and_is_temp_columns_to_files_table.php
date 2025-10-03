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
            //
            $table->bigInteger('file_size')->after('mime_type')->nullable()->comment('File size in bytes');
            $table->boolean('is_temp')->after('file_size')->default(false)->comment('Temporary file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            //
            $table->dropColumn(['file_size', 'is_temp']);
        });
    }
};
