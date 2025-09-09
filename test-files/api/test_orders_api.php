<?php

/**
 * Orders API Test File
 *
 * This file demonstrates how to use the Orders API endpoints.
 * Make sure you have a valid authentication token before running these tests.
 */

// Configuration
$baseUrl = 'http://localhost:8000/api'; // Adjust this to your local server URL
$authToken = 'YOUR_AUTH_TOKEN_HERE'; // Replace with your actual auth token

// Helper function to make API requests
function makeApiRequest($method, $endpoint, $data = null, $token = null)
{
    $url = $GLOBALS['baseUrl'] . $endpoint;

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'status_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

// Test 1: Get all orders
echo "=== Test 1: Get all orders ===\n";
$result = makeApiRequest('GET', '/orders', null, $authToken);
echo "Status Code: " . $result['status_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Create a new order
echo "=== Test 2: Create a new order ===\n";
$orderData = [
    'phone' => '+1234567890',
    'shipping_address' => '123 Main St, City, Country',
    'billing_address' => '123 Main St, City, Country',
    'notes' => 'Please deliver in the morning',
    'items' => [
        [
            'product_id' => 1, // Replace with actual product ID
            'quantity' => 2,
            'options' => [1, 2], // Replace with actual option value IDs
            'notes' => 'Extra large size'
        ],
        [
            'product_id' => 2, // Replace with actual product ID
            'quantity' => 1,
            'options' => [],
            'notes' => 'Standard size'
        ]
    ]
];

$result = makeApiRequest('POST', '/orders', $orderData, $authToken);
echo "Status Code: " . $result['status_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Store the created order ID for subsequent tests
$orderId = $result['response']['data']['id'] ?? null;

if ($orderId) {
    // Test 3: Get specific order details
    echo "=== Test 3: Get specific order details ===\n";
    $result = makeApiRequest('GET', "/orders/{$orderId}", null, $authToken);
    echo "Status Code: " . $result['status_code'] . "\n";
    echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

    // Test 4: Add item to existing order
    echo "=== Test 4: Add item to existing order ===\n";
    $addItemData = [
        'product_id' => 3, // Replace with actual product ID
        'quantity' => 1,
        'options' => [3], // Replace with actual option value IDs
        'notes' => 'Added later'
    ];

    $result = makeApiRequest('POST', "/orders/{$orderId}/items", $addItemData, $authToken);
    echo "Status Code: " . $result['status_code'] . "\n";
    echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

    // Test 5: Remove item from order
    echo "=== Test 5: Remove item from order ===\n";
    $removeItemData = [
        'item_id' => 1 // Replace with actual order item ID
    ];

    $result = makeApiRequest('DELETE', "/orders/{$orderId}/items", $removeItemData, $authToken);
    echo "Status Code: " . $result['status_code'] . "\n";
    echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

    // Test 6: Delete order (only works for pending orders)
    echo "=== Test 6: Delete order ===\n";
    $result = makeApiRequest('DELETE', "/orders/{$orderId}", null, $authToken);
    echo "Status Code: " . $result['status_code'] . "\n";
    echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
}

// Test 7: Get orders with filters
echo "=== Test 7: Get orders with filters ===\n";
$result = makeApiRequest('GET', '/orders?status=pending&sort_by=created_at&sort_order=desc&per_page=10', null, $authToken);
echo "Status Code: " . $result['status_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== Orders API Tests Completed ===\n";
echo "Note: Make sure to replace placeholder values (product IDs, option IDs, etc.) with actual values from your database.\n";
echo "Also ensure you have a valid authentication token before running these tests.\n";
