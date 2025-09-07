<?php

/**
 * Debug Design Saving
 * Test if designs can be saved to appointments
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Debugging Design Saving\n";
echo "===========================\n\n";

try {
    // Check if we have designs
    $designs = \App\Models\Design::take(3)->get();
    echo "✅ Found " . $designs->count() . " designs\n";

    if ($designs->count() == 0) {
        echo "❌ No designs found. Run seeder first.\n";
        exit(1);
    }

    // Check if we have appointments
    $appointments = \App\Models\Appointment::take(3)->get();
    echo "✅ Found " . $appointments->count() . " appointments\n";

    if ($appointments->count() == 0) {
        echo "❌ No appointments found.\n";
        exit(1);
    }

    // Get the latest appointment
    $latestAppointment = \App\Models\Appointment::latest()->first();
    echo "📅 Latest appointment ID: " . $latestAppointment->id . "\n";
    echo "   Status: " . $latestAppointment->status . "\n";
    echo "   Created: " . $latestAppointment->created_at . "\n";

    // Check current designs
    echo "🎨 Current designs attached: " . $latestAppointment->designs->count() . "\n";

    // Try to attach a design manually
    echo "\n🔧 Testing manual design attachment...\n";

    $designToAttach = $designs->first();
    echo "   Attaching design: " . $designToAttach->title . " (ID: " . $designToAttach->id . ")\n";

    // Check if design is already attached
    $isAlreadyAttached = $latestAppointment->designs()->where('design_id', $designToAttach->id)->exists();
    if ($isAlreadyAttached) {
        echo "   ⚠️ Design already attached\n";
    } else {
        // Try to attach
        try {
            $latestAppointment->designs()->attach($designToAttach->id, [
                'notes' => 'Test attachment from debug script',
                'priority' => 1
            ]);
            echo "   ✅ Design attached successfully\n";
        } catch (Exception $e) {
            echo "   ❌ Failed to attach design: " . $e->getMessage() . "\n";
        }
    }

    // Check pivot table
    echo "\n📊 Checking pivot table...\n";
    $pivotCount = DB::table('appointment_designs')->count();
    echo "   Total pivot records: " . $pivotCount . "\n";

    if ($pivotCount > 0) {
        $pivotRecords = DB::table('appointment_designs')->get();
        foreach ($pivotRecords as $record) {
            echo "   - Appointment: " . $record->appointment_id . ", Design: " . $record->design_id . ", Notes: " . ($record->notes ?: 'none') . "\n";
        }
    }

    // Refresh appointment and check designs
    $latestAppointment->refresh();
    echo "\n🔄 After refresh - designs count: " . $latestAppointment->designs->count() . "\n";

    if ($latestAppointment->designs->count() > 0) {
        foreach ($latestAppointment->designs as $design) {
            echo "   - " . $design->title . " (Notes: " . ($design->pivot->notes ?: 'none') . ")\n";
        }
    }

    echo "\n✅ Debug complete!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
