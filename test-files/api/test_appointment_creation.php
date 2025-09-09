<?php

/**
 * Test Appointment Creation with Designs
 * Simulate the appointment creation process to debug design saving
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Design;
use App\Models\User;
use App\Models\Order;

echo "🧪 Testing Appointment Creation with Designs\n";
echo "===========================================\n\n";

try {
    // Get a user and order for testing
    $user = User::first();
    $order = Order::first();
    $designs = Design::take(2)->get();

    if (!$user || !$order || $designs->count() == 0) {
        echo "❌ Missing required data for testing\n";
        echo "   User: " . ($user ? 'Found' : 'Missing') . "\n";
        echo "   Order: " . ($order ? 'Found' : 'Missing') . "\n";
        echo "   Designs: " . $designs->count() . "\n";
        exit(1);
    }

    echo "✅ Test data ready:\n";
    echo "   User: " . $user->full_name . " (ID: " . $user->id . ")\n";
    echo "   Order: #" . $order->order_number . " (ID: " . $order->id . ")\n";
    echo "   Designs: " . $designs->count() . " available\n\n";

    // Simulate the appointment creation process
    echo "🔧 Creating test appointment...\n";

    DB::beginTransaction();

    try {
        // Create appointment (same as in BookAppointmentWithOrders)
        $appointment = Appointment::create([
            'user_id' => $user->id,
            'designer_id' => null,
            'appointment_date' => now()->addDays(7)->format('Y-m-d'),
            'appointment_time' => '10:00',
            'duration_minutes' => 30,
            'notes' => 'Test appointment with designs',
            'order_id' => $order->id,
            'order_notes' => 'Test order notes',
            'status' => 'pending'
        ]);

        echo "   ✅ Appointment created: ID " . $appointment->id . "\n";

        // Attach designs (same logic as in the component)
        if ($designs->count() > 0) {
            $designData = [];
            foreach ($designs as $index => $design) {
                $designData[$design->id] = [
                    'notes' => 'Test notes for ' . $design->title,
                    'priority' => $index + 1
                ];
            }

            echo "   🎨 Attaching " . count($designData) . " designs...\n";
            $appointment->designs()->attach($designData);
            echo "   ✅ Designs attached successfully\n";
        }

        DB::commit();
        echo "   ✅ Transaction committed\n";

        // Verify the result
        $appointment->refresh();
        echo "\n📊 Verification:\n";
        echo "   Appointment ID: " . $appointment->id . "\n";
        echo "   Designs attached: " . $appointment->designs->count() . "\n";

        if ($appointment->designs->count() > 0) {
            foreach ($appointment->designs as $design) {
                echo "     - " . $design->title . " (Notes: " . $design->pivot->notes . ")\n";
            }
        }

        // Check pivot table
        $pivotCount = DB::table('appointment_designs')->where('appointment_id', $appointment->id)->count();
        echo "   Pivot records: " . $pivotCount . "\n";

        echo "\n✅ Test appointment created successfully!\n";
        echo "   You can now view this appointment at: /appointments/" . $appointment->id . "\n";
    } catch (Exception $e) {
        DB::rollBack();
        echo "   ❌ Error during creation: " . $e->getMessage() . "\n";
        throw $e;
    }
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
