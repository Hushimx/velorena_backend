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
        Schema::create('option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_option_id')->constrained()->onDelete('cascade');
            $table->string('value'); // e.g., "A4", "A3", "Glossy", "Matte"
            $table->string('value_ar')->nullable(); // Arabic value
            $table->decimal('price_adjustment', 10, 2)->default(0); // Price change when this option is selected
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('additional_data')->nullable(); // For any additional configuration
            $table->timestamps();
            
            // Performance indexes
            $table->index(['product_option_id', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_values');
    }
};
