<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentation_partners', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_document_id')
                ->nullable()
                ->after('uuid');
        });

        DB::statement("
            UPDATE documentation_partners dp
            JOIN documentation_documents dd
                ON dd.documentation_id = dp.documentation_id
            SET dp.documentation_document_id = dd.id
        ");

        Schema::table('documentation_partners', function (Blueprint $table) {
            $table->dropForeign(['documentation_id']);
            $table->dropColumn('documentation_id');
        });

        Schema::table('documentation_partners', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_document_id')->nullable(false)->change();

            $table->foreign('documentation_document_id')
                ->references('id')
                ->on('documentation_documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documentation_partners', function (Blueprint $table) {
            $table->unsignedBigInteger('documentation_id')->nullable()->after('uuid');
        });

        DB::statement("
            UPDATE documentation_partners dp
            JOIN documentation_documents dd
                ON dd.id = dp.documentation_document_id
            SET dp.documentation_id = dd.documentation_id
        ");

        Schema::table('documentation_partners', function (Blueprint $table) {
            $table->foreign('documentation_id')
                ->references('id')
                ->on('documentations')
                ->cascadeOnDelete();

            $table->dropForeign(['documentation_document_id']);
            $table->dropColumn('documentation_document_id');
        });
    }
};
