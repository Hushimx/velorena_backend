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
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration_minutes')->default(15); // Duration of each slot
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // Optional notes for this availability
            $table->timestamps();

            // Indexes for better performance
            $table->index(['day_of_week']);
            $table->index(['is_active']);
            $table->unique(['day_of_week', 'start_time', 'end_time'], 'availability_slots_day_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};