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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // Auto-generated ticket number
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'pending', 'resolved', 'closed'])->default('open');
            $table->enum('category', ['technical', 'billing', 'general', 'feature_request', 'bug_report'])->default('general');
            $table->foreignId('assigned_to')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->json('attachments')->nullable(); // Store file paths
            $table->text('admin_notes')->nullable(); // Internal admin notes
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index('ticket_number');
            $table->index('priority');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
