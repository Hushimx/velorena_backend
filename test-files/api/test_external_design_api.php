<?php

/**
 * Test script for External Design API endpoints
 *
 * This script demonstrates how to use the new external design API endpoints
 * that provide protected access to the FreeAPI service.
 */

// Base URL for your API
$baseUrl = 'http://localhost:8000/api/external/designs';

/**
 * Make HTTP request to API endpoint
 */
function makeRequest($url, $method = 'GET', $data = null)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);

    if ($method === 'POST' && $data) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'status_code' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

/**
 * Test search functionality
 */
function testSearchDesigns($baseUrl)
{
    echo "=== Testing Search Designs ===\n";

    $searchQueries = [
        'business',
        'technology',
        'nature',
        'abstract'
    ];

    foreach ($searchQueries as $query) {
        $url = $baseUrl . '/search?q=' . urlencode($query) . '&limit=5';
        $result = makeRequest($url);

        echo "Search for '{$query}':\n";
        echo "Status: {$result['status_code']}\n";

        if ($result['data']['success']) {
            echo "Results: " . count($result['data']['data']) . " designs found\n";
            if (!empty($result['data']['data'])) {
                $firstDesign = $result['data']['data'][0];
                echo "First result: {$firstDesign['title']}\n";
            }
        } else {
            echo "Error: {$result['data']['message']}\n";
        }
        echo "\n";
    }
}

/**
 * Test category functionality
 */
function testGetDesignsByCategory($baseUrl)
{
    echo "=== Testing Get Designs by Category ===\n";

    $categories = [
        'business',
        'technology',
        'nature',
        'abstract'
    ];

    foreach ($categories as $category) {
        $url = $baseUrl . '/category?category=' . urlencode($category) . '&limit=5';
        $result = makeRequest($url);

        echo "Category '{$category}':\n";
        echo "Status: {$result['status_code']}\n";

        if ($result['data']['success']) {
            echo "Results: " . count($result['data']['data']) . " designs found\n";
            if (!empty($result['data']['data'])) {
                $firstDesign = $result['data']['data'][0];
                echo "First result: {$firstDesign['title']}\n";
            }
        } else {
            echo "Error: {$result['data']['message']}\n";
        }
        echo "\n";
    }
}

/**
 * Test get categories
 */
function testGetCategories($baseUrl)
{
    echo "=== Testing Get Categories ===\n";

    $url = $baseUrl . '/categories';
    $result = makeRequest($url);

    echo "Status: {$result['status_code']}\n";

    if ($result['data']['success']) {
        echo "Categories found: " . count($result['data']['data']) . "\n";
        foreach (array_slice($result['data']['data'], 0, 5) as $category) {
            echo "- {$category['name']}: {$category['count']} designs\n";
        }
    } else {
        echo "Error: {$result['data']['message']}\n";
    }
    echo "\n";
}

/**
 * Test featured designs
 */
function testGetFeaturedDesigns($baseUrl)
{
    echo "=== Testing Get Featured Designs ===\n";

    $url = $baseUrl . '/featured?limit=5';
    $result = makeRequest($url);

    echo "Status: {$result['status_code']}\n";

    if ($result['data']['success']) {
        echo "Featured designs: " . count($result['data']['data']) . "\n";
        foreach ($result['data']['data'] as $design) {
            echo "- {$design['title']} ({$design['type']})\n";
        }
    } else {
        echo "Error: {$result['data']['message']}\n";
    }
    echo "\n";
}

/**
 * Test advanced search with filters
 */
function testAdvancedSearch($baseUrl)
{
    echo "=== Testing Advanced Search with Filters ===\n";

    $filters = [
        'q=business&type=vector&orientation=horizontal&limit=3',
        'q=technology&type=photo&min_width=1920&limit=3',
        'q=nature&color=%23FF0000&limit=3'
    ];

    foreach ($filters as $filter) {
        $url = $baseUrl . '/search?' . $filter;
        $result = makeRequest($url);

        echo "Advanced search with filters: {$filter}\n";
        echo "Status: {$result['status_code']}\n";

        if ($result['data']['success']) {
            echo "Results: " . count($result['data']['data']) . " designs found\n";
            if (!empty($result['data']['filters_applied'])) {
                echo "Filters applied: " . json_encode($result['data']['filters_applied']) . "\n";
            }
        } else {
            echo "Error: {$result['data']['message']}\n";
        }
        echo "\n";
    }
}

/**
 * Test error handling
 */
function testErrorHandling($baseUrl)
{
    echo "=== Testing Error Handling ===\n";

    // Test with invalid search query (too short)
    $url = $baseUrl . '/search?q=a';
    $result = makeRequest($url);

    echo "Short search query (should fail):\n";
    echo "Status: {$result['status_code']}\n";
    echo "Success: " . ($result['data']['success'] ? 'true' : 'false') . "\n";
    if (!$result['data']['success']) {
        echo "Error: {$result['data']['message']}\n";
    }
    echo "\n";

    // Test with missing category parameter
    $url = $baseUrl . '/category';
    $result = makeRequest($url);

    echo "Missing category parameter (should fail):\n";
    echo "Status: {$result['status_code']}\n";
    echo "Success: " . ($result['data']['success'] ? 'true' : 'false') . "\n";
    if (!$result['data']['success']) {
        echo "Error: {$result['data']['message']}\n";
    }
    echo "\n";
}

// Run all tests
echo "External Design API Test Suite\n";
echo "==============================\n\n";

testSearchDesigns($baseUrl);
testGetDesignsByCategory($baseUrl);
testGetCategories($baseUrl);
testGetFeaturedDesigns($baseUrl);
testAdvancedSearch($baseUrl);
testErrorHandling($baseUrl);

echo "Test suite completed!\n";
echo "\nNote: Make sure your Laravel application is running and the FREEPIK_API_KEY is configured in your .env file.\n";
