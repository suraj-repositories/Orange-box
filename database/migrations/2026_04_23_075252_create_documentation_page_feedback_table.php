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
        Schema::create('documentation_page_feedback', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->unsignedBigInteger('documentation_page_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->tinyInteger('rating');

            $table->text('feedback')->nullable();

            $table->string('ip_address')->nullable();

            $table->timestamps();

            $table->foreign('documentation_page_id')
                ->references('id')
                ->on('documentation_pages')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->unique(['documentation_page_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_page_feedback');
    }
};
