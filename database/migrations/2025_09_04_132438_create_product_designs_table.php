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
        Schema::create('product_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('design_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable(); // User notes about this design for this product
            $table->integer('priority')->default(1); // Design priority/order for this product
            $table->timestamps();

            // Ensure unique combinations
            $table->unique(['user_id', 'product_id', 'design_id']);

            // Indexes for better performance
            $table->index(['user_id', 'product_id']);
            $table->index('design_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_designs');
    }
};
