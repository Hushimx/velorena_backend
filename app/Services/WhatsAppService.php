<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    protected $accessToken;
    protected $instanceId;
    protected $baseUrl;
    protected $timeout;

    public function __construct()
    {
        $this->accessToken = config('whatsapp.access_token');
        $this->instanceId = config('whatsapp.instance_id');
        $this->baseUrl = config('whatsapp.base_url', 'https://app.smartwats.com/api');
        $this->timeout = config('whatsapp.timeout', 30);
    }

    /**
     * Send a text message to a phone number
     *
     * @param string $phoneNumber Phone number (with country code, e.g., 9665XXXXXXX)
     * @param string $message Text message to send
     * @return array Response from WhatsApp API
     * @throws Exception
     */
    public function sendTextMessage(string $phoneNumber, string $message): array
    {
        try {
            // Validate inputs
            if (empty($phoneNumber) || empty($message)) {
                throw new Exception('Phone number and message are required');
            }

            // Ensure phone number is properly formatted
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            if (!$this->validatePhoneNumber($formattedPhone)) {
                throw new Exception('Invalid phone number format: ' . $phoneNumber);
            }

            $response = Http::timeout($this->timeout ?? 30)->post($this->baseUrl . '/send', [
                'number' => $formattedPhone,
                'type' => 'text',
                'message' => $message,
                'instance_id' => $this->instanceId,
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Check if the response indicates success
                if (isset($data['status']) && $data['status'] === 'success') {
                    Log::info('WhatsApp message sent successfully', [
                        'phone' => $formattedPhone,
                        'message_length' => strlen($message),
                        'response' => $data
                    ]);
                    return $data;
                } else {
                    Log::warning('WhatsApp message may not have been delivered', [
                        'phone' => $formattedPhone,
                        'response' => $data
                    ]);
                    return $data;
                }
            } else {
                Log::error('WhatsApp API error', [
                    'phone' => $formattedPhone,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new Exception('Failed to send WhatsApp message: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error('WhatsApp service error', [
                'phone' => $phoneNumber,
                'message_length' => strlen($message),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send a text message to multiple phone numbers
     *
     * @param array $phoneNumbers Array of phone numbers
     * @param string $message Text message to send
     * @return array Array of responses
     */
    public function sendBulkTextMessage(array $phoneNumbers, string $message): array
    {
        $results = [];
        
        foreach ($phoneNumbers as $phoneNumber) {
            try {
                $result = $this->sendTextMessage($phoneNumber, $message);
                $results[$phoneNumber] = [
                    'success' => true,
                    'response' => $result
                ];
            } catch (Exception $e) {
                $results[$phoneNumber] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }

    /**
     * Get QR code for WhatsApp connection
     *
     * @return array Response with QR code
     * @throws Exception
     */
    public function getQRCode(): array
    {
        try {
            $response = Http::get($this->baseUrl . '/get_qrcode', [
                'instance_id' => $this->instanceId,
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                throw new Exception('Failed to get QR code: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error('WhatsApp QR code error', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Set webhook for receiving messages
     *
     * @param string $webhookUrl Webhook URL
     * @param bool $enable Enable webhook
     * @param array $allowedEvents Allowed events
     * @return array Response from API
     * @throws Exception
     */
    public function setWebhook(string $webhookUrl, bool $enable = true, array $allowedEvents = []): array
    {
        try {
            $params = [
                'webhook_url' => $webhookUrl,
                'enable' => $enable,
                'instance_id' => $this->instanceId,
                'access_token' => $this->accessToken
            ];

            if (!empty($allowedEvents)) {
                $params['allowed_events'] = implode(',', $allowedEvents);
            }

            $response = Http::post($this->baseUrl . '/set_webhook', $params);

            if ($response->successful()) {
                return $response->json();
            } else {
                throw new Exception('Failed to set webhook: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error('WhatsApp webhook error', [
                'webhook_url' => $webhookUrl,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Reboot WhatsApp instance
     *
     * @return array Response from API
     * @throws Exception
     */
    public function rebootInstance(): array
    {
        try {
            $response = Http::post($this->baseUrl . '/reboot', [
                'instance_id' => $this->instanceId,
                'access_token' => $this->accessToken
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                throw new Exception('Failed to reboot instance: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error('WhatsApp reboot error', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Check if the service is properly configured
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && !empty($this->instanceId);
    }

    /**
     * Validate phone number format
     *
     * @param string $phoneNumber
     * @return bool
     */
    public function validatePhoneNumber(string $phoneNumber): bool
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Check if it's a valid international format (7-15 digits)
        return strlen($cleaned) >= 7 && strlen($cleaned) <= 15;
    }

    /**
     * Format phone number for WhatsApp API
     *
     * @param string $phoneNumber
     * @return string
     */
    public function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Ensure it starts with country code
        if (!str_starts_with($cleaned, '966') && !str_starts_with($cleaned, '9665')) {
            // Add Saudi Arabia country code if not present
            if (str_starts_with($cleaned, '5')) {
                $cleaned = '966' . $cleaned;
            } else {
                $cleaned = '966' . $cleaned;
            }
        }
        
        return $cleaned;
    }
}

