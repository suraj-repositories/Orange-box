<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentation_sponsors', function (Blueprint $table) {


            $table->dropForeign(['documentation_id']);
            $table->dropForeign(['release_id']);


            $table->dropColumn(['documentation_id', 'release_id']);


            $table->unsignedBigInteger('documentation_document_id')->after('uuid');


            $table->index('documentation_document_id');

            $table->foreign('documentation_document_id')
                ->references('id')
                ->on('documentation_documents')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_sponsors', function (Blueprint $table) {

            $table->dropForeign(['documentation_document_id']);

            $table->dropIndex(['documentation_document_id']);

            $table->dropColumn('documentation_document_id');

            $table->unsignedBigInteger('documentation_id');
            $table->unsignedBigInteger('release_id')->nullable();

            $table->foreign('documentation_id')
                ->references('id')
                ->on('documentations')
                ->onDelete('cascade');

            $table->foreign('release_id')
                ->references('id')
                ->on('documentation_releases')
                ->onDelete('cascade');
        });
    }
};
