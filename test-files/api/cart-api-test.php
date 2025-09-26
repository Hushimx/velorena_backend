<?php

/**
 * Cart API Test Script
 * 
 * This script demonstrates how to test the Cart API endpoints
 * Run this after setting up authentication and having products in the database
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class CartApiTest
{
    private $baseUrl;
    private $token;
    private $lastCartItemId;
    
    public function __construct($baseUrl = 'http://localhost:8000/api', $token = null)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }
    
    /**
     * Test all cart API endpoints
     */
    public function runTests()
    {
        echo "🧪 Testing Cart API Endpoints\n";
        echo "============================\n\n";
        
        if (!$this->token) {
            echo "❌ No authentication token provided. Please login first.\n";
            echo "You can get a token by calling: POST /api/auth/login\n\n";
            return;
        }
        
        // Test 1: Get cart items (should be empty initially)
        $this->testGetCartItems();
        
        // Test 2: Add item to cart (requires existing product)
        $this->testAddToCart();
        
        // Test 3: Get cart items again (should have items now)
        $this->testGetCartItems();
        
        // Test 4: Update cart item quantity
        $this->testUpdateCartItem();
        
        // Test 5: Remove item from cart
        $this->testRemoveCartItem();
        
        // Test 6: Clear cart
        $this->testClearCart();
        
        echo "\n✅ All Cart API tests completed!\n";
    }
    
    private function testGetCartItems()
    {
        echo "📋 Testing: GET /api/cart/items\n";
        
        $response = $this->makeRequest('GET', '/cart/items');
        
        if ($response['success']) {
            echo "✅ Cart items retrieved successfully\n";
            echo "   Items count: " . count($response['data']['items']) . "\n";
            echo "   Total: $" . $response['data']['summary']['total'] . "\n";
        } else {
            echo "❌ Failed to get cart items: " . $response['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testAddToCart()
    {
        echo "➕ Testing: POST /api/cart/add\n";
        
        // Note: You need to replace with actual product ID from your database
        $data = [
            'product_id' => 1, // Replace with actual product ID
            'quantity' => 2,
            'selected_options' => [],
            'notes' => 'Test item added via API'
        ];
        
        $response = $this->makeRequest('POST', '/cart/add', $data);
        
        if ($response['success']) {
            echo "✅ Item added to cart successfully\n";
            echo "   Cart Item ID: " . $response['data']['cart_item_id'] . "\n";
            echo "   Quantity: " . $response['data']['quantity'] . "\n";
            echo "   Total Price: $" . $response['data']['total_price'] . "\n";
            $this->lastCartItemId = $response['data']['cart_item_id'];
        } else {
            echo "❌ Failed to add item to cart: " . $response['message'] . "\n";
            if (isset($response['errors'])) {
                echo "   Errors: " . json_encode($response['errors']) . "\n";
            }
        }
        echo "\n";
    }
    
    private function testUpdateCartItem()
    {
        echo "✏️ Testing: PUT /api/cart/items/{id}\n";
        
        if (!isset($this->lastCartItemId)) {
            echo "❌ No cart item ID available for update test\n\n";
            return;
        }
        
        $data = ['quantity' => 3];
        $response = $this->makeRequest('PUT', "/cart/items/{$this->lastCartItemId}", $data);
        
        if ($response['success']) {
            echo "✅ Cart item updated successfully\n";
            echo "   New Quantity: " . $response['data']['quantity'] . "\n";
            echo "   New Total Price: $" . $response['data']['total_price'] . "\n";
        } else {
            echo "❌ Failed to update cart item: " . $response['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testRemoveCartItem()
    {
        echo "🗑️ Testing: DELETE /api/cart/items/{id}\n";
        
        if (!isset($this->lastCartItemId)) {
            echo "❌ No cart item ID available for removal test\n\n";
            return;
        }
        
        $response = $this->makeRequest('DELETE', "/cart/items/{$this->lastCartItemId}");
        
        if ($response['success']) {
            echo "✅ Cart item removed successfully\n";
        } else {
            echo "❌ Failed to remove cart item: " . $response['message'] . "\n";
        }
        echo "\n";
    }
    
    private function testClearCart()
    {
        echo "🧹 Testing: DELETE /api/cart/clear\n";
        
        $response = $this->makeRequest('DELETE', '/cart/clear');
        
        if ($response['success']) {
            echo "✅ Cart cleared successfully\n";
        } else {
            echo "❌ Failed to clear cart: " . $response['message'] . "\n";
        }
        echo "\n";
    }
    
    
    private function makeRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $options = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->token,
                    'Accept: application/json'
                ],
                'ignore_errors' => true
            ]
        ];
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $options['http']['content'] = json_encode($data);
        }
        
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        // Get response code
        $httpCode = 0;
        if (isset($http_response_header[0])) {
            preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches);
            $httpCode = isset($matches[1]) ? (int)$matches[1] : 0;
        }
        
        $decodedResponse = json_decode($response, true);
        
        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'http_code' => $httpCode,
            'data' => $decodedResponse['data'] ?? null,
            'message' => $decodedResponse['message'] ?? 'Unknown error',
            'errors' => $decodedResponse['errors'] ?? null
        ];
    }
}

// Example usage:
// $tester = new CartApiTest('http://localhost:8000/api', 'your_bearer_token_here');
// $tester->runTests();

echo "Cart API Test Script\n";
echo "===================\n\n";
echo "To use this test script:\n";
echo "1. Make sure your Laravel application is running\n";
echo "2. Login via POST /api/auth/login to get a bearer token\n";
echo "3. Replace the product_id in the test with an actual product from your database\n";
echo "4. Uncomment and run the test code below:\n\n";

echo "// Example usage:\n";
echo "// \$tester = new CartApiTest('http://localhost:8000/api', 'your_bearer_token_here');\n";
echo "// \$tester->runTests();\n\n";

echo "Available Cart API Endpoints:\n";
echo "- GET    /api/cart/items - Get cart items\n";
echo "- POST   /api/cart/add - Add item to cart\n";
echo "- PUT    /api/cart/items/{id} - Update cart item\n";
echo "- DELETE /api/cart/items/{id} - Remove cart item\n";
echo "- DELETE /api/cart/clear - Clear entire cart\n\n";

echo "For detailed documentation, see: docs/CART_API_DOCUMENTATION.md\n";
