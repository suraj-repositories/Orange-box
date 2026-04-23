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
        Schema::table('documentation_faqs', function (Blueprint $table) {
            //
            if (Schema::hasColumn('documentation_faqs', 'documentation_id')) {
                $table->dropForeign(['documentation_id']);
                $table->dropColumn('documentation_id');
            }

            if (Schema::hasColumn('documentation_faqs', 'release_id')) {
                $table->dropForeign(['release_id']);
                $table->dropColumn('release_id');
            }

            $table->unsignedBigInteger('documentation_document_id')->after('id');

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
        Schema::table('documentation_faqs', function (Blueprint $table) {
            //
            $table->dropForeign(['documentation_document_id']);
            $table->dropColumn('documentation_document_id');

            $table->unsignedBigInteger('documentation_id')->after('id');
            $table->unsignedBigInteger('release_id')->after('documentation_id');

            $table->foreign('documentation_id')
                ->references('id')
                ->on('documentations')
                ->onDelete('cascade');

            $table->foreign('release_id')
                ->references('id')
                ->on('releases')
                ->onDelete('cascade');
        });
    }
};
