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
        Schema::table('documentation_partners', function (Blueprint $table) {

            $table->dropForeign(['documentation_id']);
            $table->dropColumn('documentation_id');
            $table->unsignedBigInteger('documentation_document_id')->after('uuid');
            $table->foreign('documentation_document_id')
                ->references('id')
                ->on('documentation_documents')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_partners', function (Blueprint $table) {
            $table->dropForeign(['documentation_document_id']);
            $table->dropColumn('documentation_document_id');
            $table->unsignedBigInteger('documentation_id')->after('uuid');
            $table->foreign('documentation_id')
                ->references('id')
                ->on('documentations')
                ->cascadeOnDelete();
        });
    }
};
