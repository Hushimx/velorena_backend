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
        Schema::table('cart_designs', function (Blueprint $table) {
            // Add composite index for efficient querying
            // This will optimize queries like: WHERE user_id = ? AND is_active = ? ORDER BY created_at DESC
            $table->index(['user_id', 'is_active', 'created_at'], 'cart_designs_user_active_created_idx');
            
            // Also add index for session-based queries
            $table->index(['session_id', 'is_active', 'created_at'], 'cart_designs_session_active_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_designs', function (Blueprint $table) {
            $table->dropIndex('cart_designs_user_active_created_idx');
            $table->dropIndex('cart_designs_session_active_created_idx');
        });
    }
};