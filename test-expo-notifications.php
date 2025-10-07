<?php

/**
 * Test script for Expo Push Notifications & WhatsApp Integration
 * 
 * Run from command line:
 * php test-expo-notifications.php
 */

require __DIR__ . '/vendor/autoload.php';

use App\Models\Order;
use App\Models\Appointment;
use App\Models\User;
use App\Services\UnifiedNotificationService;
use Illuminate\Support\Facades\Log;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Expo Push Notifications & WhatsApp Integration Test        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Test 1: Check ExpoPushToken Model
echo "📋 Test 1: ExpoPushToken Model\n";
echo "────────────────────────────────────────────────────────────────\n";
try {
    $totalTokens = \App\Models\ExpoPushToken::count();
    $activeTokens = \App\Models\ExpoPushToken::where('is_active', true)->count();
    $guestTokens = \App\Models\ExpoPushToken::whereNull('tokenable_id')->count();
    $userTokens = \App\Models\ExpoPushToken::whereNotNull('tokenable_id')->count();
    
    echo "✅ Total tokens in database: $totalTokens\n";
    echo "   - Active tokens: $activeTokens\n";
    echo "   - Guest tokens: $guestTokens\n";
    echo "   - User tokens: $userTokens\n";
    
    if ($totalTokens === 0) {
        echo "⚠️  No tokens found. Register a token from the mobile app first.\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 2: Check UnifiedNotificationService
echo "📋 Test 2: UnifiedNotificationService\n";
echo "────────────────────────────────────────────────────────────────\n";
try {
    $service = app(UnifiedNotificationService::class);
    echo "✅ UnifiedNotificationService instantiated successfully\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Check if users have tokens
echo "📋 Test 3: Users with Push Tokens\n";
echo "────────────────────────────────────────────────────────────────\n";
try {
    $usersWithTokens = User::whereHas('expoPushTokens')->count();
    echo "✅ Users with push tokens: $usersWithTokens\n";
    
    if ($usersWithTokens > 0) {
        $sampleUser = User::whereHas('expoPushTokens')->first();
        $tokenCount = $sampleUser->activeExpoPushTokens()->count();
        echo "   Sample: User #{$sampleUser->id} has $tokenCount active token(s)\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 4: Check Orders
echo "📋 Test 4: Recent Orders\n";
echo "────────────────────────────────────────────────────────────────\n";
try {
    $recentOrder = Order::with('user')->latest()->first();
    
    if ($recentOrder) {
        echo "✅ Most recent order:\n";
        echo "   - Order ID: #{$recentOrder->id}\n";
        echo "   - Order Number: {$recentOrder->order_number}\n";
        echo "   - Status: {$recentOrder->status}\n";
        echo "   - User: {$recentOrder->user->full_name ?? $recentOrder->user->company_name}\n";
        echo "   - User has push tokens: " . ($recentOrder->user->activeExpoPushTokens()->count() > 0 ? 'Yes' : 'No') . "\n";
    } else {
        echo "⚠️  No orders found in database\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 5: Check Appointments
echo "📋 Test 5: Recent Appointments\n";
echo "────────────────────────────────────────────────────────────────\n";
try {
    $recentAppointment = Appointment::with('user', 'designer')->latest()->first();
    
    if ($recentAppointment) {
        echo "✅ Most recent appointment:\n";
        echo "   - Appointment ID: #{$recentAppointment->id}\n";
        echo "   - Status: {$recentAppointment->status}\n";
        echo "   - Date: {$recentAppointment->appointment_date->format('Y-m-d')}\n";
        echo "   - Time: {$recentAppointment->appointment_time->format('H:i')}\n";
        echo "   - User: {$recentAppointment->user->full_name ?? $recentAppointment->user->company_name}\n";
        echo "   - User has push tokens: " . ($recentAppointment->user->activeExpoPushTokens()->count() > 0 ? 'Yes' : 'No') . "\n";
    } else {
        echo "⚠️  No appointments found in database\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 6: Send Test Notification
echo "📋 Test 6: Send Test Notification\n";
echo "────────────────────────────────────────────────────────────────\n";
$userWithToken = User::whereHas('activeExpoPushTokens')->first();

if ($userWithToken) {
    echo "Found user #{$userWithToken->id} with active token(s)\n";
    echo "Would you like to send a test notification? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    
    if (trim($line) === 'y') {
        try {
            $expoService = app(\App\Services\ExpoPushService::class);
            $tokens = $userWithToken->activeExpoPushTokens()->pluck('token')->toArray();
            
            $notification = [
                'title' => 'اختبار الإشعارات',
                'body' => 'هذا إشعار تجريبي من نظام الإشعارات',
                'data' => [
                    'type' => 'test',
                    'timestamp' => now()->toISOString()
                ]
            ];
            
            echo "Sending notification to " . count($tokens) . " token(s)...\n";
            $result = $expoService->sendToDevices($tokens, $notification);
            
            if ($result['success']) {
                echo "✅ Test notification sent successfully!\n";
                echo "   - Sent: " . ($result['data']['total_sent'] ?? 0) . "\n";
                echo "   - Failed: " . ($result['data']['total_failed'] ?? 0) . "\n";
            } else {
                echo "❌ Failed to send: " . ($result['message'] ?? 'Unknown error') . "\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Skipped test notification.\n";
    }
} else {
    echo "⚠️  No users with active push tokens found\n";
    echo "   Register a token from the mobile app first\n";
}
echo "\n";

// Test 7: Check WhatsApp Configuration
echo "📋 Test 7: WhatsApp Configuration\n";
echo "────────────────────────────────────────────────────────────────\n";
try {
    $whatsappService = app(\App\Services\WhatsAppService::class);
    
    if ($whatsappService->isConfigured()) {
        echo "✅ WhatsApp service is configured\n";
    } else {
        echo "⚠️  WhatsApp service is not configured\n";
        echo "   Set WHATSAPP_ACCESS_TOKEN and WHATSAPP_INSTANCE_ID in .env\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Summary
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Test Summary                                                ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "✅ All tests completed!\n\n";

echo "Next steps to test notifications:\n";
echo "1. Register an Expo push token from the mobile app\n";
echo "2. Login to admin panel\n";
echo "3. Change an order status or appointment status\n";
echo "4. Check that notification is received in the mobile app\n";
echo "5. Tap the notification and verify deep linking works\n\n";

echo "For debugging:\n";
echo "- Check logs: storage/logs/laravel.log\n";
echo "- View tokens: GET /api/debug/notifications\n";
echo "- Test notification: POST /api/test-notification-guest\n\n";

