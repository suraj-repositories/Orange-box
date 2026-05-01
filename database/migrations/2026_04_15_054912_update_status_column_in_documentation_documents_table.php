<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE documentation_documents
            MODIFY status ENUM('active', 'inactive', 'draft', 'live', 'off')
            NOT NULL DEFAULT 'draft'
        ");

        DB::table('documentation_documents')
            ->where('status', 'active')
            ->update(['status' => 'live']);

        DB::table('documentation_documents')
            ->where('status', 'inactive')
            ->update(['status' => 'draft']);

        DB::statement("
            ALTER TABLE documentation_documents
            MODIFY status ENUM('draft', 'live', 'off')
            NOT NULL DEFAULT 'draft'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE documentation_documents
            MODIFY status ENUM('active', 'inactive', 'draft', 'live', 'off')
            NOT NULL DEFAULT 'inactive'
        ");

        DB::table('documentation_documents')
            ->where('status', 'live')
            ->update(['status' => 'active']);

        DB::table('documentation_documents')
            ->where('status', 'draft')
            ->update(['status' => 'inactive']);

        DB::table('documentation_documents')
            ->where('status', 'off')
            ->update(['status' => 'inactive']);

        DB::statement("
            ALTER TABLE documentation_documents
            MODIFY status ENUM('active', 'inactive')
            NOT NULL DEFAULT 'inactive'
        ");
    }
};
