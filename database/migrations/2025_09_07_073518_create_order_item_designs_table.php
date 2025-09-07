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
        Schema::create('order_item_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('design_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable(); // User notes about this design for this order item
            $table->integer('priority')->default(1); // Design priority/order for this order item
            $table->timestamps();

            // Ensure unique combinations
            $table->unique(['order_item_id', 'design_id']);

            // Indexes for better performance
            $table->index(['order_item_id']);
            $table->index('design_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_designs');
    }
};
