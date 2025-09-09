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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->json('selected_options')->nullable(); // Store selected product options
            $table->text('notes')->nullable(); // User notes for this cart item
            $table->decimal('unit_price', 10, 2)->nullable(); // Cached unit price
            $table->decimal('total_price', 10, 2)->nullable(); // Cached total price
            $table->timestamps();

            // Add index for better performance
            $table->index(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
