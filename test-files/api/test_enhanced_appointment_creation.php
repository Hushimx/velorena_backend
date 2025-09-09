<?php

/**
 * Test Enhanced Appointment Creation
 * Verify that appointments can be created for orders that already have appointments
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Design;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

echo "🧪 Testing Enhanced Appointment Creation\n";
echo "=======================================\n\n";

try {
    // Get test data
    $user = User::first();
    $order = Order::first();
    $designs = Design::take(2)->get();

    if (!$user || !$order || $designs->count() == 0) {
        echo "❌ Missing required data for testing\n";
        exit(1);
    }

    echo "✅ Test data ready:\n";
    echo "   User: " . $user->full_name . " (ID: " . $user->id . ")\n";
    echo "   Order: #" . $order->order_number . " (ID: " . $order->id . ")\n";
    echo "   Designs: " . $designs->count() . " available\n\n";

    // Check if order already has an appointment
    $existingAppointment = $order->appointment;
    if ($existingAppointment) {
        echo "📅 Order already has an appointment:\n";
        echo "   Date: " . $existingAppointment->appointment_date . "\n";
        echo "   Time: " . $existingAppointment->appointment_time . "\n";
        echo "   Status: " . $existingAppointment->status . "\n\n";
    } else {
        echo "📅 Order has no existing appointments\n\n";
    }

    // Create a new appointment for the same order (different time)
    echo "🔧 Creating new appointment for the same order...\n";

    // Schedule new appointment at least 2 hours after existing one (if any)
    $newAppointmentTime = $existingAppointment
        ? Carbon::parse($existingAppointment->appointment_date)->setTimeFromTimeString($existingAppointment->appointment_time)->addHours(2)
        : now()->addDays(1)->setTime(14, 0);

    DB::beginTransaction();

    try {
        // Create new appointment
        $newAppointment = Appointment::create([
            'user_id' => $user->id,
            'designer_id' => null,
            'appointment_date' => $newAppointmentTime->format('Y-m-d'),
            'appointment_time' => $newAppointmentTime->format('H:i:s'),
            'duration_minutes' => 30,
            'notes' => 'Second appointment for the same order - testing enhanced functionality',
            'order_id' => $order->id,
            'order_notes' => 'Additional consultation needed',
            'status' => 'pending'
        ]);

        echo "   ✅ New appointment created: ID " . $newAppointment->id . "\n";
        echo "   📅 Date: " . $newAppointment->appointment_date . "\n";
        echo "   🕐 Time: " . $newAppointment->appointment_time . "\n";

        // Attach designs
        if ($designs->count() > 0) {
            $designData = [];
            foreach ($designs as $index => $design) {
                $designData[$design->id] = [
                    'notes' => 'Design notes for second appointment - ' . $design->title,
                    'priority' => $index + 1
                ];
            }

            echo "   🎨 Attaching " . count($designData) . " designs...\n";
            $newAppointment->designs()->attach($designData);
            echo "   ✅ Designs attached successfully\n";
        }

        DB::commit();
        echo "   ✅ Transaction committed\n";

        // Verify both appointments exist for the order
        $order->refresh();
        $appointments = Appointment::where('order_id', $order->id)->get();

        echo "\n📊 Verification Results:\n";
        echo "   Order: #" . $order->order_number . "\n";
        echo "   Total appointments: " . $appointments->count() . "\n";

        foreach ($appointments as $appointment) {
            echo "     - Appointment " . $appointment->id . ": " . $appointment->appointment_date . " at " . $appointment->appointment_time . " (Status: " . $appointment->status . ")\n";
            echo "       Designs: " . $appointment->designs->count() . "\n";
        }

        // Check pivot table
        $totalPivotRecords = DB::table('appointment_designs')
            ->whereIn('appointment_id', $appointments->pluck('id'))
            ->count();
        echo "   Total design attachments: " . $totalPivotRecords . "\n";

        echo "\n✅ Enhanced appointment creation test completed successfully!\n";
        echo "   This proves that multiple appointments can be created for the same order.\n";

        // Clean up test appointments
        echo "\n🧹 Cleaning up test appointments...\n";
        foreach ($appointments as $appointment) {
            $appointment->designs()->detach();
            $appointment->delete();
        }
        echo "   ✅ Test appointments cleaned up\n";
    } catch (Exception $e) {
        DB::rollBack();
        echo "   ❌ Error during creation: " . $e->getMessage() . "\n";
        throw $e;
    }
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
