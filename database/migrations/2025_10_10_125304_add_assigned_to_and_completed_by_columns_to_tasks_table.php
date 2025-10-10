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
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->after('priority')->constrained('users')->onDelete('cascade');
            $table->foreignId('completed_by')->nullable()->after('assigned_to')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_assigned_to_foreign');
            $table->dropForeign('tasks_completed_by_foreign');
            $table->dropColumn(['assigned_to', 'completed_by']);
        });
    }
};
