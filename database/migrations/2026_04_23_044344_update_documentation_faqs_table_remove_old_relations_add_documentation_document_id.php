<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentation_faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_document_id')
                ->nullable()
                ->after('id');
        });

        DB::statement("
            UPDATE documentation_faqs df
            JOIN documentation_documents dd
                ON dd.documentation_id = df.documentation_id
            SET df.documentation_document_id = dd.id
        ");

        Schema::table('documentation_faqs', function (Blueprint $table) {
            if (Schema::hasColumn('documentation_faqs', 'documentation_id')) {
                $table->dropForeign(['documentation_id']);
                $table->dropColumn('documentation_id');
            }

            if (Schema::hasColumn('documentation_faqs', 'release_id')) {
                $table->dropForeign(['release_id']);
                $table->dropColumn('release_id');
            }
        });

        Schema::table('documentation_faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_document_id')->nullable(false)->change();

            $table->foreign('documentation_document_id')
                ->references('id')
                ->on('documentation_documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documentation_faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_id')->nullable()->after('id');
            $table->unsignedBigInteger('release_id')->nullable()->after('documentation_id');
        });

        DB::statement("
            UPDATE documentation_faqs df
            JOIN documentation_documents dd
                ON dd.id = df.documentation_document_id
            SET df.documentation_id = dd.documentation_id
        ");

        Schema::table('documentation_faqs', function (Blueprint $table) {
            $table->foreign('documentation_id')
                ->references('id')
                ->on('documentations')
                ->cascadeOnDelete();

            $table->foreign('release_id')
                ->references('id')
                ->on('releases')
                ->cascadeOnDelete();

            $table->dropForeign(['documentation_document_id']);
            $table->dropColumn('documentation_document_id');
        });
    }
};
