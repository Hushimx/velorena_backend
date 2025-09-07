<?php

/**
 * Test Design Selection in Appointment Creation
 * Verify that designs are properly selected and saved
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Design;
use App\Models\User;
use App\Models\Order;

echo "🧪 Testing Design Selection in Appointment Creation\n";
echo "==================================================\n\n";

try {
  // Get test data
  $user = User::first();
  $order = Order::first();
  $designs = Design::take(3)->get();

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

  // Simulate design selection (like the Livewire component would do)
  echo "🎨 Simulating design selection...\n";

  $selectedDesigns = [];
  $designNotes = [];

  foreach ($designs as $index => $design) {
    $selectedDesigns[] = $design->id;
    $designNotes[$design->id] = 'Test notes for ' . $design->title;
    echo "   ✅ Selected: " . $design->title . " (ID: " . $design->id . ")\n";
  }

  echo "\n📊 Design selection summary:\n";
  echo "   Total selected: " . count($selectedDesigns) . "\n";
  echo "   Selected IDs: " . implode(', ', $selectedDesigns) . "\n";
  echo "   Notes count: " . count($designNotes) . "\n\n";

  // Create appointment with designs
  echo "🔧 Creating appointment with selected designs...\n";

  DB::beginTransaction();

  try {
    // Create appointment
    $appointment = Appointment::create([
      'user_id' => $user->id,
      'designer_id' => null,
      'appointment_date' => now()->addDays(7)->format('Y-m-d'),
      'appointment_time' => '14:00',
      'duration_minutes' => 30,
      'notes' => 'Test appointment with design selection verification',
      'order_id' => $order->id,
      'order_notes' => 'Test order notes for design verification',
      'status' => 'pending'
    ]);

    echo "   ✅ Appointment created: ID " . $appointment->id . "\n";

    // Attach selected designs
    if (!empty($selectedDesigns)) {
      $designData = [];
      foreach ($selectedDesigns as $index => $designId) {
        $designData[$designId] = [
          'notes' => $designNotes[$designId] ?? '',
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
    echo "\n📊 Verification Results:\n";
    echo "   Appointment ID: " . $appointment->id . "\n";
    echo "   Designs attached: " . $appointment->designs->count() . "\n";

    if ($appointment->designs->count() > 0) {
      foreach ($appointment->designs as $design) {
        echo "     - " . $design->title . " (Notes: " . $design->pivot->notes . ", Priority: " . $design->pivot->priority . ")\n";
      }
    }

    // Check pivot table directly
    $pivotCount = DB::table('appointment_designs')->where('appointment_id', $appointment->id)->count();
    echo "   Pivot records: " . $pivotCount . "\n";

    // Check if the designs are properly linked
    $linkedDesigns = DB::table('appointment_designs')
      ->where('appointment_id', $appointment->id)
      ->join('designs', 'appointment_designs.design_id', '=', 'designs.id')
      ->select('designs.title', 'appointment_designs.notes', 'appointment_designs.priority')
      ->get();

    echo "   Linked designs from DB:\n";
    foreach ($linkedDesigns as $linked) {
      echo "     - " . $linked->title . " (Notes: " . $linked->notes . ", Priority: " . $linked->priority . ")\n";
    }

    echo "\n✅ Test completed successfully!\n";
    echo "   Appointment with designs created at: /appointments/" . $appointment->id . "\n";

    // Clean up test appointment
    echo "\n🧹 Cleaning up test appointment...\n";
    $appointment->designs()->detach();
    $appointment->delete();
    echo "   ✅ Test appointment cleaned up\n";
  } catch (Exception $e) {
    DB::rollBack();
    echo "   ❌ Error during creation: " . $e->getMessage() . "\n";
    throw $e;
  }
} catch (Exception $e) {
  echo "❌ Test failed: " . $e->getMessage() . "\n";
  echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
