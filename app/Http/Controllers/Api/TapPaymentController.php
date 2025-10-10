<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TapPaymentService;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TapPaymentController extends Controller
{
    private $tapPaymentService;

    public function __construct(TapPaymentService $tapPaymentService)
    {
        $this->tapPaymentService = $tapPaymentService;
    }

    /**
     * Create a payment charge
     */
    public function createCharge(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|in:KWD,SAR,AED,BHD,EGP,USD,EUR,GBP',
            'customer' => 'required|array',
            'customer.first_name' => 'required|string|max:255',
            'customer.last_name' => 'required|string|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.phone' => 'required|string|max:20',
            'redirect_url' => 'nullable|url',
            'post_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $order = Order::findOrFail($request->order_id);
            
            // Check if order can make payment
            if (!$order->canMakePayment()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order cannot be paid at this time. Order must be confirmed and unpaid.'
                ], 400);
            }

            $chargeData = [
                'amount' => $request->amount,
                'currency' => $request->currency,
                'customer' => $request->customer,
                'source' => [
                    'id' => 'src_all'
                ],
                'redirect' => [
                    'url' => $request->redirect_url ?? $this->getSuccessUrl()
                ],
                'post' => [
                    'url' => $request->post_url ?? $this->getWebhookUrl()
                ],
                'description' => "Payment for Order #{$order->order_number}",
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id
                ]
            ];

            $result = $this->tapPaymentService->createCharge($chargeData);

            if ($result['success']) {
                // Create payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'charge_id' => $result['charge_id'],
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'status' => 'pending',
                    'payment_method' => 'tap',
                    'gateway_response' => $result['data']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment charge created successfully',
                    'data' => [
                        'payment_id' => $payment->id,
                        'charge_id' => $result['charge_id'],
                        'payment_url' => $result['payment_url'],
                        'order' => $order
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment charge',
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment charge creation failed', [
                'error' => $e->getMessage(),
                'order_id' => $request->order_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'charge_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->tapPaymentService->getCharge($request->charge_id);

            if ($result['success']) {
                $chargeData = $result['data'];
                
                // Update payment status in database
                $payment = Payment::where('charge_id', $request->charge_id)->first();
                if ($payment) {
                    $status = $this->mapTapStatusToLocal($chargeData['status']);
                    $payment->update([
                        'status' => $status,
                        'gateway_response' => $chargeData
                    ]);

                    // Update order status if payment is successful
                    if ($status === 'completed') {
                        $payment->order->update(['status' => 'processing']);
                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'charge_id' => $chargeData['id'],
                        'status' => $chargeData['status'],
                        'amount' => $chargeData['amount'],
                        'currency' => $chargeData['currency'],
                        'created' => $chargeData['created'],
                        'payment' => $payment
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve payment status',
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment status retrieval failed', [
                'error' => $e->getMessage(),
                'charge_id' => $request->charge_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Tap webhook
     */
    public function webhook(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $chargeId = $data['id'] ?? null;

            if (!$chargeId) {
                Log::warning('Tap webhook missing charge ID', ['data' => $data]);
                return response()->json(['error' => 'Invalid webhook data'], 400);
            }

            // Verify webhook authenticity by checking if charge exists in our system
            if (!$this->tapPaymentService->verifyWebhookAuthenticity($chargeId)) {
                Log::warning('Tap webhook for unknown charge', ['charge_id' => $chargeId]);
                return response()->json(['error' => 'Charge not found'], 404);
            }

            // Find payment record
            $payment = Payment::where('charge_id', $chargeId)->first();
            if (!$payment) {
                Log::warning('Payment not found for webhook', ['charge_id' => $chargeId]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Update payment status
            $status = $this->mapTapStatusToLocal($data['status']);
            $payment->update([
                'status' => $status,
                'gateway_response' => $data
            ]);

            // Update order status based on payment status
            if ($status === 'completed') {
                // Payment successful - move order to processing
                $payment->order->update(['status' => 'processing']);
            } elseif ($status === 'failed') {
                // Payment failed - keep order confirmed for retry
                $payment->order->update([
                    'status' => 'confirmed', // Keep order confirmed for retry
                    'notes' => ($payment->order->notes ? $payment->order->notes . ' | ' : '') . 'Payment failed - can retry'
                ]);
            }
            // For pending status, don't change order status

            Log::info('Tap webhook processed', [
                'charge_id' => $chargeId,
                'status' => $status,
                'order_id' => $payment->order_id
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Tap webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->getContent()
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Create refund
     */
    public function createRefund(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'charge_id' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $refundData = [
                'amount' => $request->amount,
                'reason' => $request->reason ?? 'Customer request'
            ];

            $result = $this->tapPaymentService->createRefund($request->charge_id, $refundData);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Refund created successfully',
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create refund',
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Refund creation failed', [
                'error' => $e->getMessage(),
                'charge_id' => $request->charge_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get test cards for testing
     */
    public function getTestCards(): JsonResponse
    {
        $testCards = $this->tapPaymentService->getTestCards();
        
        return response()->json([
            'success' => true,
            'data' => $testCards
        ]);
    }

    /**
     * Create a test payment charge (no authentication required)
     */
    public function createTestCharge(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|in:KWD,SAR,AED,BHD,EGP,USD,EUR,GBP',
            'customer' => 'required|array',
            'customer.first_name' => 'required|string|max:255',
            'customer.last_name' => 'required|string|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.phone' => 'required|string|max:20',
            'redirect_url' => 'nullable|url',
            'post_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create a test order for testing purposes
            $testOrder = \App\Models\Order::create([
                'user_id' => 1, // Default test user
                'order_number' => 'TEST-' . time(),
                'status' => 'pending',
                'subtotal' => $request->amount,
                'tax' => 0,
                'total' => $request->amount,
                'notes' => 'Test payment order'
            ]);

            $chargeData = [
                'amount' => $request->amount,
                'currency' => $request->currency,
                'customer' => $request->customer,
                'source' => [
                    'id' => 'src_all'
                ],
                'redirect' => [
                    'url' => $request->redirect_url ?? $this->getWebSuccessUrl()
                ],
                'post' => [
                    'url' => $request->post_url ?? $this->getWebhookUrl()
                ],
                'description' => "Test Payment for Order #{$testOrder->order_number}",
                'metadata' => [
                    'order_id' => $testOrder->id,
                    'order_number' => $testOrder->order_number,
                    'test_mode' => true
                ]
            ];

            $result = $this->tapPaymentService->createCharge($chargeData);

            if ($result['success']) {
                // Create payment record
                $payment = Payment::create([
                    'order_id' => $testOrder->id,
                    'charge_id' => $result['charge_id'],
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'status' => 'pending',
                    'payment_method' => 'tap',
                    'gateway_response' => $result['data']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Test payment charge created successfully',
                    'data' => [
                        'payment_id' => $payment->id,
                        'charge_id' => $result['charge_id'],
                        'payment_url' => $result['payment_url'],
                        'order' => $testOrder
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create test payment charge',
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Test payment charge creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get test payment status (no authentication required)
     */
    public function getTestPaymentStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'charge_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->tapPaymentService->getCharge($request->charge_id);

            if ($result['success']) {
                $chargeData = $result['data'];
                
                // Update payment status in database
                $payment = Payment::where('charge_id', $request->charge_id)->first();
                if ($payment) {
                    $status = $this->mapTapStatusToLocal($chargeData['status']);
                    $payment->update([
                        'status' => $status,
                        'gateway_response' => $chargeData
                    ]);

                    // Update order status if payment is successful
                    if ($status === 'completed') {
                        $payment->order->update(['status' => 'processing']);
                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'charge_id' => $chargeData['id'],
                        'status' => $chargeData['status'],
                        'amount' => $chargeData['amount'],
                        'currency' => $chargeData['currency'],
                        'created' => $chargeData['created'],
                        'payment' => $payment
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve test payment status',
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Test payment status retrieval failed', [
                'error' => $e->getMessage(),
                'charge_id' => $request->charge_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map Tap payment status to local status
     */
    private function mapTapStatusToLocal(string $tapStatus): string
    {
        return match ($tapStatus) {
            'CAPTURED' => 'completed',
            'DECLINED' => 'failed',
            'FAILED' => 'failed',
            'CANCELLED' => 'cancelled',
            'PENDING' => 'pending',
            'INITIATED' => 'pending',
            default => 'pending'
        };
    }

    /**
     * Get success URL for payment redirects
     */
    private function getSuccessUrl(): string
    {
        $baseUrl = config('app.url');
        $isTestMode = config('services.tap.test_mode', true);
        
        // For API calls (React Native), use mobile source
        return $baseUrl . '/payment/success?source=mobile&test_mode=' . ($isTestMode ? 'true' : 'false');
    }

    /**
     * Get success URL for web redirects
     */
    private function getWebSuccessUrl(): string
    {
        $baseUrl = config('app.url');
        $isTestMode = config('services.tap.test_mode', true);
        
        // For web calls, use web source
        return $baseUrl . '/payment/success?source=web&test_mode=' . ($isTestMode ? 'true' : 'false');
    }

    /**
     * Get webhook URL for payment notifications
     */
    private function getWebhookUrl(): string
    {
        return config('app.url') . '/api/webhooks/tap';
    }
}
