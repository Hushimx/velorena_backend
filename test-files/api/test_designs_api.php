<?php

/**
 * Test file for Design API Integration
 * Run this file to test the design API endpoints
 */

// Test configuration
$baseUrl = 'http://localhost:8000/api'; // Adjust to your local URL
$testDesignId = 1; // Adjust based on your seeded data

echo "🧪 Testing Design API Integration\n";
echo "================================\n\n";

// Test 1: Get all designs
echo "1. Testing GET /api/designs\n";
$response = file_get_contents($baseUrl . '/designs');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Success: Found " . count($data['data']) . " designs\n";
        if (isset($data['pagination'])) {
            echo "   Pagination: Page {$data['pagination']['current_page']} of {$data['pagination']['last_page']}\n";
        }
    } else {
        echo "❌ Failed: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ Failed: Could not connect to API\n";
}
echo "\n";

// Test 2: Search designs
echo "2. Testing GET /api/designs/search?q=business\n";
$response = file_get_contents($baseUrl . '/designs/search?q=business');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Success: Found " . count($data['data']) . " business-related designs\n";
        echo "   Query: {$data['query']}\n";
    } else {
        echo "❌ Failed: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ Failed: Could not connect to API\n";
}
echo "\n";

// Test 3: Get categories
echo "3. Testing GET /api/designs/categories\n";
$response = file_get_contents($baseUrl . '/designs/categories');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Success: Found " . count($data['data']) . " categories\n";
        echo "   Categories: " . implode(', ', $data['data']) . "\n";
    } else {
        echo "❌ Failed: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ Failed: Could not connect to API\n";
}
echo "\n";

// Test 4: Get specific design
echo "4. Testing GET /api/designs/{$testDesignId}\n";
$response = file_get_contents($baseUrl . "/designs/{$testDesignId}");
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Success: Found design '{$data['data']['title']}'\n";
        echo "   Category: {$data['data']['category']}\n";
        echo "   Tags: {$data['data']['tags']}\n";
    } else {
        echo "❌ Failed: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ Failed: Could not connect to API\n";
}
echo "\n";

// Test 5: Test design filtering
echo "5. Testing GET /api/designs?category=business\n";
$response = file_get_contents($baseUrl . '/designs?category=business');
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && $data['success']) {
        echo "✅ Success: Found " . count($data['data']) . " business category designs\n";
    } else {
        echo "❌ Failed: " . ($data['message'] ?? 'Unknown error') . "\n";
    }
} else {
    echo "❌ Failed: Could not connect to API\n";
}
echo "\n";

echo "🎯 Test Summary\n";
echo "===============\n";
echo "All tests completed. Check the results above.\n";
echo "\n";
echo "💡 Next Steps:\n";
echo "1. Run migrations: php artisan migrate\n";
echo "2. Seed designs: php artisan db:seed --class=DesignSeeder\n";
echo "3. Test Livewire components in your views\n";
echo "4. Use the design selector in appointment booking\n";
echo "\n";
echo "🔧 Troubleshooting:\n";
echo "- Ensure your Laravel app is running\n";
echo "- Check database connection\n";
echo "- Verify migrations have been run\n";
echo "- Check API routes are accessible\n";
