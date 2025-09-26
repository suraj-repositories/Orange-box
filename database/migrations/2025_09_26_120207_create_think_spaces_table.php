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
        Schema::create('think_pads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('title')->index();
            $table->string('sub_title')->nullable();
            $table->longText('description')->nullable();

            $table->string('slug')->unique();

            $table->foreignId('emoji_id')->nullable()->constrained('emojis')->onDelete('set null');
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null');

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('think_spaces');
    }
};
