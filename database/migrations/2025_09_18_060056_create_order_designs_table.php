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
        Schema::create('order_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Design title
            $table->text('image_url'); // Design image URL
            $table->text('thumbnail_url')->nullable(); // Thumbnail URL
            $table->json('design_data')->nullable(); // Design data/properties
            $table->text('notes')->nullable(); // User notes about this design
            $table->integer('priority')->default(1); // Design priority/order
            $table->timestamps();

            // Indexes for better performance
            $table->index(['order_id']);
            $table->index(['order_id', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_designs');
    }
};
