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
        Schema::create('documentation_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documentation_page_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('heading');

            $table->string('slug')->index();

            $table->tinyInteger('level')->index();

            $table->longText('content');

            $table->integer('position')->default(0)->index();

            $table->timestamps();

            $table->index(['documentation_page_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_sections');
    }
};
