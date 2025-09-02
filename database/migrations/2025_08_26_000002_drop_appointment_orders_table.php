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
        Schema::dropIfExists('appointment_orders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('appointment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['appointment_id', 'order_id']);
            $table->index(['appointment_id']);
            $table->index(['order_id']);
        });
    }
};
