<?php

/**
 * Tap Payment System Test Script
 * 
 * This script tests the complete payment flow to ensure everything works correctly
 * in both test and production modes.
 * 
 * Usage: php test-payment-system.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Config;
use App\Services\TapPaymentService;

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Tap Payment System Test\n";
echo "==========================\n\n";

// Test 1: Configuration Check
echo "1. Testing Configuration...\n";
$testMode = config('services.tap.test_mode', true);
$testKey = config('services.tap.test_secret_key');
$liveKey = config('services.tap.live_secret_key');
$publicKey = config('services.tap.public_key');

echo "   Test Mode: " . ($testMode ? '✅ Enabled' : '❌ Disabled') . "\n";
echo "   Test Secret Key: " . ($testKey ? '✅ Set' : '❌ Missing') . "\n";
echo "   Live Secret Key: " . ($liveKey ? '✅ Set' : '❌ Missing') . "\n";
echo "   Public Key: " . ($publicKey ? '✅ Set' : '❌ Missing') . "\n\n";

if (!$testKey && !$liveKey) {
    echo "❌ ERROR: No API keys configured!\n";
    echo "   Please set TAP_TEST_SECRET_KEY or TAP_LIVE_SECRET_KEY in your .env file\n\n";
    exit(1);
}

// Test 2: Service Initialization
echo "2. Testing Service Initialization...\n";
try {
    $tapService = new TapPaymentService();
    echo "   ✅ TapPaymentService initialized successfully\n";
} catch (Exception $e) {
    echo "   ❌ Failed to initialize TapPaymentService: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Test Card Data
echo "\n3. Testing Test Card Data...\n";
$testCards = $tapService->getTestCards();
foreach ($testCards as $type => $card) {
    echo "   $type: " . $card['number'] . " (CVV: " . $card['cvv'] . ")\n";
}

// Test 4: Create Test Charge (if in test mode)
if ($testMode) {
    echo "\n4. Testing Charge Creation (Test Mode)...\n";
    
    $testChargeData = [
        'amount' => 10.00,
        'currency' => 'SAR',
        'customer' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+966501234567'
        ],
        'source' => [
            'id' => 'src_all'
        ],
        'redirect' => [
            'url' => config('app.url') . '/payment/success?source=test&test_mode=true'
        ],
        'post' => [
            'url' => config('app.url') . '/api/webhooks/tap'
        ],
        'description' => 'Test Payment',
        'metadata' => [
            'test' => true
        ]
    ];
    
    try {
        $result = $tapService->createCharge($testChargeData);
        
        if ($result['success']) {
            echo "   ✅ Test charge created successfully\n";
            echo "   Charge ID: " . $result['charge_id'] . "\n";
            echo "   Payment URL: " . $result['payment_url'] . "\n";
            
            // Test 5: Retrieve Charge
            echo "\n5. Testing Charge Retrieval...\n";
            $chargeResult = $tapService->getCharge($result['charge_id']);
            
            if ($chargeResult['success']) {
                echo "   ✅ Charge retrieved successfully\n";
                echo "   Status: " . $chargeResult['data']['status'] . "\n";
            } else {
                echo "   ❌ Failed to retrieve charge: " . $chargeResult['error'] . "\n";
            }
            
        } else {
            echo "   ❌ Failed to create test charge: " . $result['error'] . "\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Exception during charge creation: " . $e->getMessage() . "\n";
    }
} else {
    echo "\n4. Skipping charge creation (Production Mode)\n";
    echo "   ⚠️  Production mode detected - skipping actual charge creation\n";
}

// Test 6: URL Generation
echo "\n6. Testing URL Generation...\n";
$baseUrl = config('app.url');
$successUrl = $baseUrl . '/payment/success?source=mobile&test_mode=' . ($testMode ? 'true' : 'false');
$webhookUrl = $baseUrl . '/api/webhooks/tap';

echo "   Success URL: $successUrl\n";
echo "   Webhook URL: $webhookUrl\n";

// Test 7: Webhook Authenticity Verification
echo "\n7. Testing Webhook Authenticity Verification...\n";
$testChargeId = 'chg_test_123456789';
$isValid = $tapService->verifyWebhookAuthenticity($testChargeId);
echo "   Webhook authenticity verification: " . ($isValid ? '✅ Working' : '⚠️  No test charge found (expected)') . "\n";
echo "   Note: Tap doesn't use signature verification - we verify by checking if charge exists in our system\n";

// Test 8: Environment Summary
echo "\n8. Environment Summary...\n";
echo "   App URL: " . config('app.url') . "\n";
echo "   Environment: " . config('app.env') . "\n";
echo "   Debug Mode: " . (config('app.debug') ? 'Enabled' : 'Disabled') . "\n";
echo "   Test Mode: " . ($testMode ? 'Enabled' : 'Disabled') . "\n";

echo "\n🎉 Payment System Test Complete!\n";
echo "===============================\n";

if ($testMode) {
    echo "\n📝 Next Steps:\n";
    echo "1. Test the payment flow using the test page: " . config('app.url') . "/payment-test\n";
    echo "2. Use the test card numbers provided above\n";
    echo "3. Check the success page works correctly\n";
    echo "4. Verify webhook notifications are received\n";
} else {
    echo "\n⚠️  Production Mode Active!\n";
    echo "Make sure you have:\n";
    echo "1. Set TAP_LIVE_SECRET_KEY with your live API key\n";
    echo "2. Configured webhook URL in Tap Dashboard\n";
    echo "3. Tested thoroughly in test mode first\n";
}

echo "\n📚 For more information, see: TAP_PAYMENT_SETUP.md\n";
