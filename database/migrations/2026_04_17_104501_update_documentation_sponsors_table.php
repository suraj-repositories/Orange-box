<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentation_sponsors', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_document_id')
                ->nullable()
                ->after('uuid');
        });

        DB::statement("
            UPDATE documentation_sponsors ds
            JOIN documentation_documents dd
                ON dd.documentation_id = ds.documentation_id
            SET ds.documentation_document_id = dd.id
        ");

        Schema::table('documentation_sponsors', function (Blueprint $table) {
            $table->dropForeign(['documentation_id']);
            $table->dropForeign(['release_id']);

            $table->dropColumn(['documentation_id', 'release_id']);
        });

        Schema::table('documentation_sponsors', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_document_id')->nullable(false)->change();

            $table->index('documentation_document_id');

            $table->foreign('documentation_document_id')
                ->references('id')
                ->on('documentation_documents')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('documentation_sponsors', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_id')->nullable();
            $table->unsignedBigInteger('release_id')->nullable();
        });

        DB::statement("
            UPDATE documentation_sponsors ds
            JOIN documentation_documents dd
                ON dd.id = ds.documentation_document_id
            SET ds.documentation_id = dd.documentation_id
        ");

        Schema::table('documentation_sponsors', function (Blueprint $table) {
            $table->foreign('documentation_id')
                ->references('id')
                ->on('documentations')
                ->onDelete('cascade');

            $table->foreign('release_id')
                ->references('id')
                ->on('documentation_releases')
                ->onDelete('cascade');

            $table->dropForeign(['documentation_document_id']);
            $table->dropIndex(['documentation_document_id']);
            $table->dropColumn('documentation_document_id');
        });
    }
};
