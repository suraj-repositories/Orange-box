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
        Schema::table('syntax_stores', function (Blueprint $table) {
            //
            $table->foreignId('emoji_id')
                ->nullable()
                ->after('status')
                ->constrained('emojis')
                ->nullOnDelete();

            $table->foreignId('file_id')
                ->nullable()
                ->after('emoji_id')
                ->constrained('files')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syntax_stores', function (Blueprint $table) {
            //

            $table->dropForeign(['emoji_id']);
            $table->dropForeign(['file_id']);

            $table->dropColumn([
                'emoji_id',
                'file_id',
            ]);
        });
    }
};
