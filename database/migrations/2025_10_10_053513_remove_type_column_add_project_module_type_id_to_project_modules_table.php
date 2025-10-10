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
        Schema::table('project_modules', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->foreignId('project_module_type_id')
                ->after('description')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_modules', function (Blueprint $table) {
            // Reverse the above changes
            $table->dropForeign(['project_module_type_id']);
            $table->dropColumn('project_module_type_id');
            $table->string('type')->nullable();
        });
    }
};
