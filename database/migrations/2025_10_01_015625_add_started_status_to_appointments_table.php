<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the enum to include 'started' status
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'completed', 'cancelled', 'started') DEFAULT 'pending'");
        
        // Add started_at timestamp column
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove started_at column
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('started_at');
        });
        
        // Revert the enum to original values
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};