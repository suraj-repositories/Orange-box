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
        Schema::create('documentation_releases', function (Blueprint $table) {
             $table->id();

            $table->foreignId('documentation_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('version');
            $table->boolean('is_current')->default(false);
            $table->boolean('is_published')->default(false);

            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->unique(['documentation_id', 'version']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_releases');
    }
};
