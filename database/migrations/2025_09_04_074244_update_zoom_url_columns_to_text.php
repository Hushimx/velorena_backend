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
        Schema::table('appointments', function (Blueprint $table) {
            // Change zoom URL columns from string to text to accommodate long URLs
            $table->text('zoom_meeting_url')->nullable()->change();
            $table->text('zoom_start_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Revert back to string (this might cause data loss if URLs are too long)
            $table->string('zoom_meeting_url')->nullable()->change();
            $table->string('zoom_start_url')->nullable()->change();
        });
    }
};