<?php
/**
 * Helper script to check recent OTP codes from Laravel logs
 * Usage: php check-otp-logs.php
 */

$logFile = __DIR__ . '/storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "❌ Log file not found: $logFile\n";
    exit(1);
}

// Read last 500 lines of the log file
$lines = [];
$handle = fopen($logFile, 'r');
if ($handle) {
    fseek($handle, -50000, SEEK_END); // Read last 50KB
    while (($line = fgets($handle)) !== false) {
        $lines[] = $line;
    }
    fclose($handle);
}

// Filter for OTP-related logs
$otpLogs = [];
foreach ($lines as $line) {
    if (strpos($line, 'OTP') !== false || strpos($line, 'otp') !== false) {
        // Parse the line to extract relevant info
        if (preg_match('/sent to ([^\:]+):\s*(\d{6})/', $line, $matches)) {
            $phone = $matches[1];
            $code = $matches[2];
            
            // Extract timestamp
            if (preg_match('/\[(\d{4}-\d{2}-\d{2}[^\]]+)\]/', $line, $timeMatches)) {
                $timestamp = $timeMatches[1];
            } else {
                $timestamp = 'Unknown time';
            }
            
            $otpLogs[] = [
                'timestamp' => $timestamp,
                'phone' => $phone,
                'code' => $code,
                'full_line' => trim($line)
            ];
        }
    }
}

// Display recent OTPs
echo "\n🔐 Recent OTP Codes (Last 20)\n";
echo str_repeat("=", 80) . "\n\n";

if (empty($otpLogs)) {
    echo "❌ No OTP logs found.\n";
    echo "💡 Make sure you've requested an OTP first.\n";
} else {
    // Show last 20 OTPs
    $recentOtps = array_slice($otpLogs, -20);
    
    foreach (array_reverse($recentOtps) as $index => $otp) {
        echo sprintf(
            "%d. 📱 %s\n   🔑 Code: %s\n   ⏰ Time: %s\n\n",
            $index + 1,
            $otp['phone'],
            $otp['code'],
            $otp['timestamp']
        );
    }
    
    echo str_repeat("=", 80) . "\n";
    echo "Total OTP logs found: " . count($otpLogs) . "\n\n";
}

// Also show last error if any
echo "🔍 Recent Errors:\n";
echo str_repeat("-", 80) . "\n";
$errorLines = array_filter($lines, function($line) {
    return strpos($line, 'ERROR') !== false || strpos($line, 'Failed') !== false;
});
$recentErrors = array_slice($errorLines, -5);
if (empty($recentErrors)) {
    echo "✅ No recent errors found.\n";
} else {
    foreach ($recentErrors as $error) {
        echo trim($error) . "\n";
    }
}
echo "\n";

