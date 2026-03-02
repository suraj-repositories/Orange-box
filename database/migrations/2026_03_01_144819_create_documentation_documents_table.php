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
        Schema::create('documentation_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documentation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug');

            $table->string('type')->comment("privacy, terms, code_of_conduct, guide, custom")->index();

            $table->json('content');

            $table->enum('status', ['active', 'inactive'])->default('inactive');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['documentation_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_documents');
    }
};
