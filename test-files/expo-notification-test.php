<?php

/**
 * Expo Push Notification Testing Script
 * 
 * This script tests the Expo push notification system endpoints
 * Run this from the project root: php test-files/expo-notification-test.php
 */

require_once 'vendor/autoload.php';

// Configuration
$BASE_URL = 'http://localhost:8000/api';
$TEST_TOKEN = 'ExponentPushToken[test-token-12345]'; // Replace with real token from mobile app

class ExpoNotificationTester
{
    private string $baseUrl;
    private string $authToken;
    
    public function __construct(string $baseUrl, string $authToken = null)
    {
        $this->baseUrl = $baseUrl;
        $this->authToken = $authToken;
    }
    
    /**
     * Make HTTP request
     */
    private function makeRequest(string $endpoint, array $data = null, string $method = 'GET'): array
    {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        if ($this->authToken) {
            $headers[] = 'Authorization: Bearer ' . $this->authToken;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($data && in_array($method, ['POST', 'PUT', 'DELETE'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status_code' => $httpCode,
            'body' => json_decode($response, true) ?? $response
        ];
    }
    
    /**
     * Test 1: Register Expo push token
     */
    public function testRegisterToken(): array
    {
        echo "🔔 Testing token registration...\n";
        
        $data = [
            'token' => $GLOBALS['TEST_TOKEN'],
            'device_id' => 'test-device-123',
            'platform' => 'ios'
        ];
        
        $response = $this->makeRequest('/expo-push/register', $data, 'POST');
        
        echo "Status: {$response['status_code']}\n";
        echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";
        
        return $response;
    }
    
    /**
     * Test 2: Get user's tokens
     */
    public function testGetTokens(): array
    {
        echo "🔔 Testing get tokens...\n";
        
        $response = $this->makeRequest('/expo-push/tokens');
        
        echo "Status: {$response['status_code']}\n";
        echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";
        
        return $response;
    }
    
    /**
     * Test 3: Send test notification
     */
    public function testSendNotification(): array
    {
        echo "🔔 Testing send notification...\n";
        
        $response = $this->makeRequest('/expo-push/test', [], 'POST');
        
        echo "Status: {$response['status_code']}\n";
        echo "Response: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";
        
        return $response;
    }
    
    /**
     * Test 4: Test Expo service directly
     */
    public function testExpoServiceDirectly(): array
    {
        echo "🔔 Testing Expo service directly...\n";
        
        // Test with Expo's push service directly
        $expoData = [
            [
                'to' => $GLOBALS['TEST_TOKEN'],
                'title' => 'Direct Expo Test',
                'body' => 'This is a direct test from Expo push service',
                'data' => ['type' => 'direct_test']
            ]
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://exp.host/--/api/v2/push/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($expoData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $result = [
            'status_code' => $httpCode,
            'body' => json_decode($response, true) ?? $response
        ];
        
        echo "Status: {$result['status_code']}\n";
        echo "Response: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";
        
        return $result;
    }
    
    /**
     * Run all tests
     */
    public function runAllTests(): void
    {
        echo "🚀 Starting Expo Push Notification Tests\n";
        echo "=====================================\n\n";
        
        $tests = [
            'Token Registration' => [$this, 'testRegisterToken'],
            'Get Tokens' => [$this, 'testGetTokens'],
            'Send Test Notification' => [$this, 'testSendNotification'],
            'Direct Expo Service Test' => [$this, 'testExpoServiceDirectly']
        ];
        
        $results = [];
        
        foreach ($tests as $name => $test) {
            echo "📋 {$name}\n";
            echo str_repeat('-', 50) . "\n";
            
            try {
                $result = call_user_func($test);
                $results[$name] = [
                    'success' => $result['status_code'] >= 200 && $result['status_code'] < 300,
                    'status_code' => $result['status_code'],
                    'response' => $result['body']
                ];
            } catch (Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "\n\n";
                $results[$name] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        // Summary
        echo "\n📊 Test Summary\n";
        echo "===============\n";
        
        foreach ($results as $name => $result) {
            $status = $result['success'] ? '✅' : '❌';
            echo "{$status} {$name}\n";
            if (!$result['success'] && isset($result['error'])) {
                echo "   Error: {$result['error']}\n";
            }
        }
        
        $successCount = count(array_filter($results, fn($r) => $r['success']));
        $totalCount = count($results);
        
        echo "\n🎯 Results: {$successCount}/{$totalCount} tests passed\n";
    }
}

// Check if running from command line
if (php_sapi_name() === 'cli') {
    // You can pass auth token as command line argument
    $authToken = $argv[1] ?? null;
    
    if (!$authToken) {
        echo "⚠️  Warning: No auth token provided. Some tests may fail.\n";
        echo "Usage: php test-files/expo-notification-test.php YOUR_AUTH_TOKEN\n\n";
    }
    
    $tester = new ExpoNotificationTester($BASE_URL, $authToken);
    $tester->runAllTests();
} else {
    echo "This script should be run from the command line.\n";
}
