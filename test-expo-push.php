<?php

// Test script to send push notification directly to Expo servers
// This bypasses our backend and tests if Expo push notifications work

$expoPushToken = 'ExponentPushToken[YOUR_ACTUAL_TOKEN_HERE]'; // Replace with your real token

$notification = [
    'to' => $expoPushToken,
    'title' => 'Test Notification from Curl',
    'body' => 'This is a direct test from curl to Expo servers',
    'data' => [
        'type' => 'test',
        'timestamp' => date('c')
    ],
    'sound' => 'default',
    'badge' => 1
];

$url = 'https://exp.host/--/api/v2/push/send';
$data = json_encode($notification);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Accept-Encoding: gzip, deflate'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

echo "Sending notification to Expo servers...\n";
echo "Token: " . $expoPushToken . "\n";
echo "URL: " . $url . "\n";
echo "Data: " . $data . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if ($error) {
    echo "CURL Error: " . $error . "\n";
}

echo "\nDone!\n";


