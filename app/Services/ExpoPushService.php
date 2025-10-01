<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ExpoPushService
{
    private string $apiUrl = 'https://exp.host/--/api/v2/push/send';
    private int $timeout = 30;
    private int $retryAttempts = 3;

    /**
     * Send push notification to single device
     */
    public function sendToDevice(string $expoPushToken, array $notification): array
    {
        return $this->sendToDevices([$expoPushToken], $notification);
    }

    /**
     * Send push notification to multiple devices
     */
    public function sendToDevices(array $expoPushTokens, array $notification): array
    {
        try {
            // Validate tokens
            $validTokens = $this->validateTokens($expoPushTokens);
            
            if (empty($validTokens)) {
                return [
                    'success' => false,
                    'message' => 'No valid Expo push tokens provided',
                    'errors' => []
                ];
            }

            // Prepare messages
            $messages = [];
            foreach ($validTokens as $token) {
                $messages[] = array_merge([
                    'to' => $token,
                ], $notification);
            }

            // Send request to Expo push service
            $response = Http::timeout($this->timeout)
                ->retry($this->retryAttempts, 1000)
                ->post($this->apiUrl, $messages);

            if (!$response->successful()) {
                throw new Exception('Expo push service returned error: ' . $response->body());
            }

            $responseData = $response->json();
            
            // Process response
            $results = $this->processResponse($responseData, $validTokens);

            Log::info('Expo push notifications sent', [
                'total_tokens' => count($expoPushTokens),
                'valid_tokens' => count($validTokens),
                'successful' => $results['successful'],
                'failed' => $results['failed']
            ]);

            return [
                'success' => true,
                'message' => 'Push notifications sent successfully',
                'data' => $results
            ];

        } catch (Exception $e) {
            Log::error('Expo push notification failed', [
                'tokens_count' => count($expoPushTokens),
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send push notifications',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send notification to user's devices
     */
    public function sendToUser($user, array $notification): array
    {
        if (!$user || !method_exists($user, 'expoPushTokens')) {
            return [
                'success' => false,
                'message' => 'User does not have Expo push tokens'
            ];
        }

        $tokens = $user->expoPushTokens()
            ->where('is_active', true)
            ->pluck('token')
            ->toArray();

        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'User has no active Expo push tokens'
            ];
        }

        return $this->sendToDevices($tokens, $notification);
    }

    /**
     * Validate Expo push tokens
     */
    private function validateTokens(array $tokens): array
    {
        $validTokens = [];
        
        foreach ($tokens as $token) {
            if ($this->isValidExpoToken($token)) {
                $validTokens[] = $token;
            } else {
                Log::warning('Invalid Expo push token', ['token' => $token]);
            }
        }

        return $validTokens;
    }

    /**
     * Check if token is valid Expo push token format
     */
    private function isValidExpoToken(string $token): bool
    {
        // Expo push tokens start with ExponentPushToken[ or ExpoPushToken[
        return preg_match('/^(ExponentPushToken\[|ExpoPushToken\[)/', $token);
    }

    /**
     * Process Expo push service response
     */
    private function processResponse(array $responseData, array $sentTokens): array
    {
        $successful = [];
        $failed = [];
        $receiptIds = [];

        foreach ($responseData as $index => $result) {
            if (isset($result['status']) && $result['status'] === 'ok') {
                $successful[] = $sentTokens[$index];
                if (isset($result['id'])) {
                    $receiptIds[] = $result['id'];
                }
            } else {
                $failed[] = [
                    'token' => $sentTokens[$index],
                    'error' => $result['details']['error'] ?? 'Unknown error'
                ];
            }
        }

        return [
            'successful' => $successful,
            'failed' => $failed,
            'receipt_ids' => $receiptIds,
            'total_sent' => count($successful),
            'total_failed' => count($failed)
        ];
    }

    /**
     * Create a basic notification payload
     */
    public function createNotification(
        string $title,
        string $body,
        ?array $data = null,
        ?string $sound = 'default',
        ?int $badge = null
    ): array {
        $notification = [
            'title' => $title,
            'body' => $body,
        ];

        if ($data) {
            $notification['data'] = $data;
        }

        if ($sound) {
            $notification['sound'] = $sound;
        }

        if ($badge !== null) {
            $notification['badge'] = $badge;
        }

        return $notification;
    }

    /**
     * Check if Expo push service is available
     */
    public function isServiceAvailable(): bool
    {
        try {
            $response = Http::timeout(10)->get('https://exp.host/--/api/v2/status');
            return $response->successful();
        } catch (Exception $e) {
            Log::warning('Expo push service check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
