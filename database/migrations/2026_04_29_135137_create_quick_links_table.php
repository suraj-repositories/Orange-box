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
        Schema::create('quick_links', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('color')->default('primary');

            $table->string('route_name')->nullable();
            $table->json('route_params')->nullable();
            $table->string('external_url')->nullable();

            $table->enum('target', ['_self', '_blank'])->default('_self');

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_links');
    }
};
