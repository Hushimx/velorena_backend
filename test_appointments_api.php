<?php

/**
 * Appointments API Test Script
 *
 * This script demonstrates how to use the Appointments API endpoints.
 * Make sure to update the BASE_URL and TOKEN variables before running.
 */

// Configuration
$BASE_URL = 'http://localhost:8000/api';
$TOKEN = 'your-token-here'; // Replace with your actual token

// Headers for all requests
$headers = [
  'Authorization: Bearer ' . $TOKEN,
  'Accept: application/json',
  'Content-Type: application/json'
];

/**
 * Make an API request
 */
function makeRequest($method, $endpoint, $data = null)
{
  global $BASE_URL, $headers;

  $url = $BASE_URL . $endpoint;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

  if ($data && in_array($method, ['POST', 'PUT'])) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  }

  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  return [
    'status' => $httpCode,
    'data' => json_decode($response, true)
  ];
}

/**
 * Test 1: Get User Appointments
 */
function testGetAppointments()
{
  echo "=== Test 1: Get User Appointments ===\n";

  $response = makeRequest('GET', '/appointments');

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 2: Get Available Time Slots
 */
function testGetAvailableTimeSlots()
{
  echo "=== Test 2: Get Available Time Slots ===\n";

  $designerId = 1; // Replace with actual designer ID
  $date = date('Y-m-d', strtotime('+1 day')); // Tomorrow

  $response = makeRequest('GET', "/appointments/available-slots?designer_id={$designerId}&date={$date}");

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 3: Create New Appointment
 */
function testCreateAppointment()
{
  echo "=== Test 3: Create New Appointment ===\n";

  $appointmentData = [
    'designer_id' => 1, // Replace with actual designer ID
    'appointment_date' => date('Y-m-d', strtotime('+2 days')), // Day after tomorrow
    'appointment_time' => '14:00',
    'service_type' => 'Logo Design',
    'description' => 'Create a modern logo for my business',
    'duration' => 60,
    'location' => 'Office',
    'notes' => 'Please bring design samples'
  ];

  $response = makeRequest('POST', '/appointments', $appointmentData);

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";

  // Return the created appointment ID for further tests
  if ($response['status'] === 201 && isset($response['data']['data']['id'])) {
    return $response['data']['data']['id'];
  }

  return null;
}

/**
 * Test 4: Get Specific Appointment
 */
function testGetAppointment($appointmentId)
{
  echo "=== Test 4: Get Specific Appointment ===\n";

  $response = makeRequest('GET', "/appointments/{$appointmentId}");

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 5: Update Appointment
 */
function testUpdateAppointment($appointmentId)
{
  echo "=== Test 5: Update Appointment ===\n";

  $updateData = [
    'appointment_time' => '15:00',
    'duration' => 90,
    'notes' => 'Updated notes - extended duration'
  ];

  $response = makeRequest('PUT', "/appointments/{$appointmentId}", $updateData);

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 6: Confirm Appointment
 */
function testConfirmAppointment($appointmentId)
{
  echo "=== Test 6: Confirm Appointment ===\n";

  $response = makeRequest('POST', "/appointments/{$appointmentId}/confirm");

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 7: Get Upcoming Appointments
 */
function testGetUpcomingAppointments()
{
  echo "=== Test 7: Get Upcoming Appointments ===\n";

  $response = makeRequest('GET', '/appointments/upcoming?limit=5');

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 8: Cancel Appointment
 */
function testCancelAppointment($appointmentId)
{
  echo "=== Test 8: Cancel Appointment ===\n";

  $cancelData = [
    'reason' => 'Client requested cancellation due to schedule conflict'
  ];

  $response = makeRequest('POST', "/appointments/{$appointmentId}/cancel", $cancelData);

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 9: Get Designer Appointments (if user is a designer)
 */
function testGetDesignerAppointments()
{
  echo "=== Test 9: Get Designer Appointments ===\n";

  $response = makeRequest('GET', '/designer/appointments');

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Test 10: Delete Appointment (only works for pending appointments)
 */
function testDeleteAppointment($appointmentId)
{
  echo "=== Test 10: Delete Appointment ===\n";

  $response = makeRequest('DELETE', "/appointments/{$appointmentId}");

  echo "Status: " . $response['status'] . "\n";
  echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";
}

/**
 * Main test execution
 */
function runAllTests()
{
  echo "🚀 Starting Appointments API Tests\n";
  echo "Base URL: " . $GLOBALS['BASE_URL'] . "\n";
  echo "Token: " . substr($GLOBALS['TOKEN'], 0, 20) . "...\n\n";

  // Test 1: Get appointments
  testGetAppointments();

  // Test 2: Get available time slots
  testGetAvailableTimeSlots();

  // Test 3: Create appointment
  $appointmentId = testCreateAppointment();

  if ($appointmentId) {
    // Test 4: Get specific appointment
    testGetAppointment($appointmentId);

    // Test 5: Update appointment
    testUpdateAppointment($appointmentId);

    // Test 6: Confirm appointment
    testConfirmAppointment($appointmentId);

    // Test 7: Get upcoming appointments
    testGetUpcomingAppointments();

    // Test 8: Cancel appointment
    testCancelAppointment($appointmentId);

    // Test 9: Get designer appointments
    testGetDesignerAppointments();

    // Note: Delete test is commented out as it would delete the appointment
    // Uncomment the line below if you want to test deletion
    // testDeleteAppointment($appointmentId);
  }

  echo "✅ All tests completed!\n";
}

// Check if token is set
if ($TOKEN === 'your-token-here') {
  echo "❌ Please update the TOKEN variable with your actual authentication token.\n";
  echo "You can get a token by logging in through the API.\n";
  exit(1);
}

// Run the tests
runAllTests();

