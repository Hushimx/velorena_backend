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
        Schema::create('expo_push_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->morphs('tokenable'); // user_id, user_type (polymorphic)
            $table->string('device_id')->nullable(); // Device identifier
            $table->string('platform')->nullable(); // ios, android, web
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            
            $table->index(['tokenable_type', 'tokenable_id'], 'expo_tokens_tokenable_index');
            $table->index('is_active', 'expo_tokens_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expo_push_tokens');
    }
};
