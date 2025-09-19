<?php

/**
 * Support Tickets API Test File
 * 
 * This file demonstrates how to use the Support Tickets API endpoints
 * for creating, managing, and following up on support tickets.
 */

// Base URL for your API
$baseUrl = 'http://localhost:8000/api';

// Example API token (replace with actual token from login)
$apiToken = 'your_api_token_here';

// Headers for authenticated requests
$headers = [
    'Authorization: Bearer ' . $apiToken,
    'Content-Type: application/json',
    'Accept: application/json'
];

/**
 * Function to make HTTP requests
 */
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status_code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

echo "=== Support Tickets API Test ===\n\n";

// 1. Create a new support ticket
echo "1. Creating a new support ticket...\n";
$ticketData = [
    'subject' => 'Login Issue',
    'description' => 'I am unable to login to my account. I keep getting an error message.',
    'priority' => 'high',
    'category' => 'technical'
];

$response = makeRequest(
    $baseUrl . '/support-tickets',
    'POST',
    $ticketData,
    $headers
);

if ($response['status_code'] === 201) {
    $ticket = $response['body']['data'];
    $ticketId = $ticket['id'];
    echo "✓ Ticket created successfully!\n";
    echo "  Ticket Number: " . $ticket['ticket_number'] . "\n";
    echo "  Ticket ID: " . $ticketId . "\n\n";
} else {
    echo "✗ Failed to create ticket: " . json_encode($response['body']) . "\n\n";
    exit;
}

// 2. Get all support tickets
echo "2. Getting all support tickets...\n";
$response = makeRequest(
    $baseUrl . '/support-tickets',
    'GET',
    null,
    $headers
);

if ($response['status_code'] === 200) {
    $tickets = $response['body']['data']['tickets'];
    echo "✓ Retrieved " . count($tickets) . " tickets\n";
    
    foreach ($tickets as $ticket) {
        echo "  - " . $ticket['ticket_number'] . ": " . $ticket['subject'] . " (" . $ticket['status'] . ")\n";
    }
    echo "\n";
} else {
    echo "✗ Failed to get tickets: " . json_encode($response['body']) . "\n\n";
}

// 3. Get specific support ticket
echo "3. Getting specific support ticket...\n";
$response = makeRequest(
    $baseUrl . '/support-tickets/' . $ticketId,
    'GET',
    null,
    $headers
);

if ($response['status_code'] === 200) {
    $ticket = $response['body']['data'];
    echo "✓ Retrieved ticket details\n";
    echo "  Subject: " . $ticket['subject'] . "\n";
    echo "  Status: " . $ticket['status'] . "\n";
    echo "  Priority: " . $ticket['priority'] . "\n";
    echo "  Category: " . $ticket['category'] . "\n";
    echo "  Created: " . $ticket['created_at'] . "\n\n";
} else {
    echo "✗ Failed to get ticket: " . json_encode($response['body']) . "\n\n";
}

// 4. Add a reply to the ticket
echo "4. Adding a reply to the ticket...\n";
$replyData = [
    'message' => 'Thank you for creating this ticket. I have provided more details about the login issue in the description.'
];

$response = makeRequest(
    $baseUrl . '/support-tickets/' . $ticketId . '/replies',
    'POST',
    $replyData,
    $headers
);

if ($response['status_code'] === 201) {
    $reply = $response['body']['data'];
    echo "✓ Reply added successfully!\n";
    echo "  Reply ID: " . $reply['id'] . "\n";
    echo "  Message: " . substr($reply['message'], 0, 50) . "...\n\n";
} else {
    echo "✗ Failed to add reply: " . json_encode($response['body']) . "\n\n";
}

// 5. Get all replies for the ticket
echo "5. Getting all replies for the ticket...\n";
$response = makeRequest(
    $baseUrl . '/support-tickets/' . $ticketId . '/replies',
    'GET',
    null,
    $headers
);

if ($response['status_code'] === 200) {
    $replies = $response['body']['data'];
    echo "✓ Retrieved " . count($replies) . " replies\n";
    
    foreach ($replies as $reply) {
        echo "  - " . $reply['author_name'] . " (" . $reply['created_at'] . "): " . substr($reply['message'], 0, 50) . "...\n";
    }
    echo "\n";
} else {
    echo "✗ Failed to get replies: " . json_encode($response['body']) . "\n\n";
}

// 6. Get support ticket statistics
echo "6. Getting support ticket statistics...\n";
$response = makeRequest(
    $baseUrl . '/support-tickets/statistics',
    'GET',
    null,
    $headers
);

if ($response['status_code'] === 200) {
    $stats = $response['body']['data'];
    echo "✓ Retrieved statistics\n";
    echo "  Total tickets: " . $stats['total'] . "\n";
    echo "  Open tickets: " . $stats['open'] . "\n";
    echo "  Closed tickets: " . $stats['closed'] . "\n";
    echo "  By priority: " . json_encode($stats['by_priority']) . "\n";
    echo "  By category: " . json_encode($stats['by_category']) . "\n\n";
} else {
    echo "✗ Failed to get statistics: " . json_encode($response['body']) . "\n\n";
}

// 7. Filter tickets by status
echo "7. Filtering tickets by status (open)...\n";
$response = makeRequest(
    $baseUrl . '/support-tickets?status=open',
    'GET',
    null,
    $headers
);

if ($response['status_code'] === 200) {
    $tickets = $response['body']['data']['tickets'];
    echo "✓ Retrieved " . count($tickets) . " open tickets\n\n";
} else {
    echo "✗ Failed to filter tickets: " . json_encode($response['body']) . "\n\n";
}

echo "=== Test Complete ===\n";

/**
 * Example API Endpoints Summary:
 * 
 * GET    /api/support-tickets                    - Get user's support tickets (with filtering)
 * POST   /api/support-tickets                    - Create a new support ticket
 * GET    /api/support-tickets/statistics         - Get support ticket statistics
 * GET    /api/support-tickets/{id}               - Get specific support ticket
 * POST   /api/support-tickets/{id}/replies       - Add reply to support ticket
 * GET    /api/support-tickets/{id}/replies       - Get replies for support ticket
 * 
 * Query Parameters for filtering:
 * - status: open, in_progress, pending, resolved, closed
 * - priority: low, medium, high, urgent
 * - category: technical, billing, general, feature_request, bug_report
 * - page: for pagination
 * 
 * Admin Routes (for admin panel):
 * GET    /admin/support-tickets                  - Admin ticket management
 * GET    /admin/support-tickets/{id}             - View ticket details
 * PUT    /admin/support-tickets/{id}             - Update ticket
 * POST   /admin/support-tickets/{id}/assign      - Assign ticket to admin
 * POST   /admin/support-tickets/{id}/replies     - Add admin reply
 * POST   /admin/support-tickets/bulk-action      - Bulk actions
 * GET    /admin/support-tickets-statistics       - Admin statistics
 */




