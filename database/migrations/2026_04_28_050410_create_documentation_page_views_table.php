<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentation_page_views', function (Blueprint $table) {
            $table->id();

            $table->foreignId('documentation_page_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('session_id')->index();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamp('left_at')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamps();


            $table->index(
                ['visited_at', 'documentation_page_id'],
                'dpv_visited_page_idx'
            );

            $table->index(
                ['documentation_page_id', 'session_id'],
                'dpv_page_session_idx'
            );
        });

        Schema::table('documentation_page_views', function (Blueprint $table) {
            $table->date('visited_date')
                ->storedAs('DATE(visited_at)');

            $table->index(
                ['visited_date', 'documentation_page_id'],
                'dpv_visited_date_page_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentation_page_views');
    }
};
