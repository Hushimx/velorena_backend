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
        Schema::create('support_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // User who replied
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade'); // Admin who replied
            $table->text('message');
            $table->json('attachments')->nullable(); // Store file paths
            $table->boolean('is_internal')->default(false); // Internal admin notes
            $table->timestamps();
            
            // Indexes
            $table->index(['ticket_id', 'created_at']);
            $table->index('user_id');
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_replies');
    }
};
