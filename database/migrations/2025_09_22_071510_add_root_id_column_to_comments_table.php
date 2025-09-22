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
        Schema::table('comments', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('root_id')->nullable()->after('parent_id');

            $table->foreign('root_id', 'comments_root_id_foreign')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');

            $table->index('root_id', 'comments_root_id_index');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            //
            $table->dropForeign('comments_root_id_foreign');
            $table->dropIndex('comments_root_id_index');
            $table->dropColumn('root_id');

        });
    }
};
