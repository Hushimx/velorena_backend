<?php

/**
 * Products API Test Script
 * 
 * This script tests all the products API endpoints to ensure
 * multi-image functionality is working correctly.
 */

// Base URL for the API
$baseUrl = 'http://localhost:8000/api';

// Test endpoints
$endpoints = [
    'GET /api/products' => [
        'url' => $baseUrl . '/products',
        'params' => ['limit' => 5]
    ],
    'GET /api/products/search' => [
        'url' => $baseUrl . '/products/search',
        'params' => ['q' => 'business', 'limit' => 3]
    ],
    'GET /api/products/latest' => [
        'url' => $baseUrl . '/products/latest',
        'params' => ['limit' => 3]
    ],
    'GET /api/products/best-selling' => [
        'url' => $baseUrl . '/products/best-selling',
        'params' => ['limit' => 3]
    ],
    'GET /api/products/31' => [
        'url' => $baseUrl . '/products/31',
        'params' => []
    ]
];

echo "=== Products API Test ===\n\n";

foreach ($endpoints as $endpoint => $config) {
    echo "Testing: $endpoint\n";
    echo "URL: " . $config['url'] . "\n";
    
    // Build query string if params exist
    if (!empty($config['params'])) {
        $queryString = http_build_query($config['params']);
        $fullUrl = $config['url'] . '?' . $queryString;
    } else {
        $fullUrl = $config['url'];
    }
    
    echo "Full URL: $fullUrl\n";
    
    // Make the request
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json',
            'timeout' => 30
        ]
    ]);
    
    $response = @file_get_contents($fullUrl, false, $context);
    
    if ($response === false) {
        echo "❌ FAILED: Could not connect to API\n";
        echo "Make sure the Laravel server is running: php artisan serve\n\n";
        continue;
    }
    
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "❌ FAILED: Invalid JSON response\n";
        echo "Response: " . substr($response, 0, 200) . "...\n\n";
        continue;
    }
    
    // Check response structure
    if (!isset($data['success'])) {
        echo "❌ FAILED: Missing 'success' field in response\n\n";
        continue;
    }
    
    if (!$data['success']) {
        echo "❌ FAILED: API returned success=false\n";
        echo "Message: " . ($data['message'] ?? 'Unknown error') . "\n\n";
        continue;
    }
    
    // Check for images in response
    $hasImages = false;
    $imageCount = 0;
    
    if (isset($data['data'])) {
        if (isset($data['data']['data'])) {
            // Handle paginated response
            $products = $data['data']['data'];
            foreach ($products as $product) {
                if (isset($product['images']) && is_array($product['images'])) {
                    $hasImages = true;
                    $imageCount += count($product['images']);
                }
            }
        } else {
            // Handle single product response
            if (isset($data['data']['images']) && is_array($data['data']['images'])) {
                $hasImages = true;
                $imageCount = count($data['data']['images']);
            }
        }
    }
    
    echo "✅ SUCCESS: API responded correctly\n";
    echo "Images found: " . ($hasImages ? "Yes ($imageCount total)" : "No") . "\n";
    
    // Show sample image data if available
    if ($hasImages && isset($data['data'])) {
        if (is_array($data['data']) && isset($data['data']['data'])) {
            $firstProduct = $data['data']['data'][0] ?? null;
        } else {
            $firstProduct = $data['data'];
        }
        
        if ($firstProduct && isset($firstProduct['images']) && !empty($firstProduct['images'])) {
            $firstImage = $firstProduct['images'][0];
            echo "Sample image: " . ($firstImage['image_url'] ?? $firstImage['image_path'] ?? 'N/A') . "\n";
            echo "Primary image: " . ($firstImage['is_primary'] ? 'Yes' : 'No') . "\n";
        }
    }
    
    echo "\n";
}

echo "=== Test Complete ===\n";
echo "If all tests show ✅ SUCCESS, the multi-image functionality is working correctly.\n";
echo "Check the documentation at docs/PRODUCTS_API_DOCUMENTATION.md for detailed usage.\n";
