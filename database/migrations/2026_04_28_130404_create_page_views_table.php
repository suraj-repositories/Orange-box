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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();

            $table->morphs('viewable');

            $table->string('session_id')->nullable()->index();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->unsignedInteger('duration')->nullable();
            $table->timestamp('visited_at')->useCurrent();

            $table->timestamps();

            $table->index(['viewable_type', 'viewable_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
