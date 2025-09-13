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
        $this->baseUrl = $this->isTestMode 
            ? 'https://api.tap.company/v2' 
            : 'https://api.tap.company/v2';
    }

    /**
     * Create a charge for payment
     */
    public function createCharge(array $chargeData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/charges', $chargeData);

            $responseData = $response->json();

            if ($response->successful()) {
                Log::info('Tap Payment Charge Created', [
                    'charge_id' => $responseData['id'] ?? null,
                    'amount' => $chargeData['amount'] ?? null,
                    'currency' => $chargeData['currency'] ?? null
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
                    'response' => $responseData,
                    'request_data' => $chargeData
                ]);

                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Payment charge creation failed',
                    'details' => $responseData
                ];
            }
        } catch (Exception $e) {
            Log::error('Tap Payment Service Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, config('services.tap.webhook_secret', ''));
        return hash_equals($expectedSignature, $signature);
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
