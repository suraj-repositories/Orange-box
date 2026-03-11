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
        Schema::create('documentation_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documentation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('release_id')
                ->constrained('documentation_releases')
                ->cascadeOnDelete();

            $table->string('question');
            $table->longText('answer');

            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_faqs');
    }
};
