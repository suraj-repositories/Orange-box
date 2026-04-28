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
        Schema::create('documentation_page_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documentation_page_id')->constrained()->cascadeOnDelete();

            $table->date('date')->index();

            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->unsignedBigInteger('bounces')->default(0);

            $table->float('avg_time_on_page')->default(0);

            $table->timestamps();

            $table->unique(['documentation_page_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_page_stats');
    }
};
