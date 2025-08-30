<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if appointment_orders table exists
        if (Schema::hasTable('appointment_orders')) {
            // Get all appointment-order relationships from the pivot table
            $appointmentOrders = DB::table('appointment_orders')->get();

            foreach ($appointmentOrders as $appointmentOrder) {
                // Update the appointment with the order_id and notes
                DB::table('appointments')
                    ->where('id', $appointmentOrder->appointment_id)
                    ->update([
                        'order_id' => $appointmentOrder->order_id,
                        'order_notes' => $appointmentOrder->notes
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we're moving data
        // The data would need to be manually restored if needed
    }
};
