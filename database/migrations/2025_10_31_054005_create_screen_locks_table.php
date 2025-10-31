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
        Schema::create('screen_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('uuid')->unique();
            $table->text('redirect_url');
            $table->string('user_agent')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('locked_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('unlocked')->default(false);
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screen_locks');
    }
};
