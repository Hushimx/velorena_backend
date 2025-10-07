<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TapPaymentService
{
    private $apiKey;
    private $baseUrl;
    private $isTestMode;

    public function __construct()
    {
        $this->apiKey = config('services.tap.secret_key');
        $this->isTestMode = config('services.tap.test_mode', true);
        
        // Use different API keys for test vs production
        if ($this->isTestMode) {
            $this->apiKey = config('services.tap.test_secret_key', $this->apiKey);
        } else {
            $this->apiKey = config('services.tap.live_secret_key', $this->apiKey);
        }
        
        // Both test and production use the same API endpoint
        $this->baseUrl = 'https://api.tap.company/v2';
    }

    /**
     * Create a charge for payment
     */
    public function createCharge(array $chargeData): array
    {
        try {
            // Validate API key
            if (empty($this->apiKey)) {
                Log::error('Tap Payment API Key Missing', [
                    'api_key_exists' => !empty($this->apiKey),
                    'test_mode' => $this->isTestMode
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Tap payment API key is not configured'
                ];
            }

            // Log the request being sent
            Log::info('Tap Payment Request', [
                'url' => $this->baseUrl . '/charges',
                'test_mode' => $this->isTestMode,
                'amount' => $chargeData['amount'] ?? null,
                'currency' => $chargeData['currency'] ?? null,
                'customer_email' => $chargeData['customer']['email'] ?? null,
                'customer_phone' => $chargeData['customer']['phone']['number'] ?? $chargeData['customer']['phone'] ?? null
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/charges', $chargeData);

            $responseData = $response->json();

            if ($response->successful()) {
                Log::info('Tap Payment Charge Created Successfully', [
                    'charge_id' => $responseData['id'] ?? null,
                    'amount' => $chargeData['amount'] ?? null,
                    'currency' => $chargeData['currency'] ?? null,
                    'payment_url' => $responseData['transaction']['url'] ?? null
                ]);

                return [
                    'success' => true,
                    'data' => $responseData,
                    'charge_id' => $responseData['id'] ?? null,
                    'payment_url' => $responseData['transaction']['url'] ?? null
                ];
            } else {
                Log::error('Tap Payment Charge Failed', [
                    'status' => $response->status(),
                    'response_headers' => $response->headers(),
                    'response_body' => $response->body(),
                    'response_json' => $responseData,
                    'request_data' => $chargeData,
                    'api_key_prefix' => substr($this->apiKey, 0, 10) . '...'
                ]);

                $errorMessage = $responseData['message'] ?? 'Payment charge creation failed';
                if (isset($responseData['errors'])) {
                    $errorMessage .= ' - Errors: ' . json_encode($responseData['errors']);
                }

                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'details' => $responseData,
                    'status_code' => $response->status()
                ];
            }
        } catch (Exception $e) {
            Log::error('Tap Payment Service Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $chargeData
            ]);

            return [
                'success' => false,
                'error' => 'Payment service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve a charge by ID
     */
    public function getCharge(string $chargeId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/charges/' . $chargeId);

            $responseData = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Failed to retrieve charge',
                    'details' => $responseData
                ];
            }
        } catch (Exception $e) {
            Log::error('Tap Payment Get Charge Exception', [
                'charge_id' => $chargeId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to retrieve charge: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a refund for a charge
     */
    public function createRefund(string $chargeId, array $refundData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/refunds', array_merge($refundData, [
                'charge_id' => $chargeId
            ]));

            $responseData = $response->json();

            if ($response->successful()) {
                Log::info('Tap Payment Refund Created', [
                    'refund_id' => $responseData['id'] ?? null,
                    'charge_id' => $chargeId,
                    'amount' => $refundData['amount'] ?? null
                ]);

                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Refund creation failed',
                    'details' => $responseData
                ];
            }
        } catch (Exception $e) {
            Log::error('Tap Payment Refund Exception', [
                'charge_id' => $chargeId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Refund failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a token for card payments
     */
    public function createToken(array $cardData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/tokens', $cardData);

            $responseData = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $responseData,
                    'token' => $responseData['id'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Token creation failed',
                    'details' => $responseData
                ];
            }
        } catch (Exception $e) {
            Log::error('Tap Payment Token Exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Token creation failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook authenticity (Tap doesn't use signature verification)
     * Instead, we verify the webhook by checking if the charge exists in our system
     */
    public function verifyWebhookAuthenticity(string $chargeId): bool
    {
        // Verify the charge exists in our database
        $payment = \App\Models\Payment::where('charge_id', $chargeId)->first();
        return $payment !== null;
    }

    /**
     * Get test card numbers for testing
     */
    public function getTestCards(): array
    {
        return [
            'visa' => [
                'number' => '4242424242424242',
                'cvv' => '100',
                'exp_month' => '05',
                'exp_year' => '2025'
            ],
            'mastercard' => [
                'number' => '5555555555554444',
                'cvv' => '100',
                'exp_month' => '05',
                'exp_year' => '2025'
            ],
            'amex' => [
                'number' => '378282246310005',
                'cvv' => '1000',
                'exp_month' => '05',
                'exp_year' => '2025'
            ]
        ];
    }
}
