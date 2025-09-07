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
        Schema::create('appointment_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('design_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable(); // User notes about this specific design
            $table->integer('priority')->default(1); // Design priority/order
            $table->timestamps();

            // Ensure unique combinations
            $table->unique(['appointment_id', 'design_id']);
            
            // Indexes for better performance
            $table->index(['appointment_id', 'priority']);
            $table->index('design_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_designs');
    }
};

