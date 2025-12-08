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
        Schema::create('documentation_pages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('documentation_id');
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('title');
            $table->string('slug');
            $table->text('git_link')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('icon')->nullable();
            $table->boolean('is_published')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id', 'fk_doc_pages_user')
                ->references('id')->on('users')->cascadeOnDelete();

            $table->foreign('documentation_id', 'fk_doc_pages_documentation')
                ->references('id')->on('documentations')->cascadeOnDelete();

            $table->foreign('parent_id', 'fk_doc_pages_parent')
                ->references('id')->on('documentation_pages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_pages');
    }
};
