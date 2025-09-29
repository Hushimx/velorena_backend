<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send a text message to a single phone number
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|min:7|max:15',
            'message' => 'required|string|max:4096'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!$this->whatsappService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp service is not properly configured'
                ], 500);
            }

            $phoneNumber = $this->whatsappService->formatPhoneNumber($request->phone_number);
            
            if (!$this->whatsappService->validatePhoneNumber($phoneNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid phone number format'
                ], 422);
            }

            $result = $this->whatsappService->sendTextMessage($phoneNumber, $request->message);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send bulk text messages to multiple phone numbers
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendBulkMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone_numbers' => 'required|array|min:1|max:100',
            'phone_numbers.*' => 'required|string|min:7|max:15',
            'message' => 'required|string|max:4096'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!$this->whatsappService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp service is not properly configured'
                ], 500);
            }

            $results = $this->whatsappService->sendBulkTextMessage(
                $request->phone_numbers,
                $request->message
            );

            $successCount = collect($results)->where('success', true)->count();
            $failureCount = collect($results)->where('success', false)->count();

            return response()->json([
                'success' => true,
                'message' => "Bulk message processing completed. Success: {$successCount}, Failed: {$failureCount}",
                'data' => $results,
                'summary' => [
                    'total' => count($results),
                    'successful' => $successCount,
                    'failed' => $failureCount
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send bulk messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR code for WhatsApp connection
     *
     * @return JsonResponse
     */
    public function getQRCode(): JsonResponse
    {
        try {
            if (!$this->whatsappService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp service is not properly configured'
                ], 500);
            }

            $result = $this->whatsappService->getQRCode();

            return response()->json([
                'success' => true,
                'message' => 'QR code retrieved successfully',
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get QR code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set webhook for receiving messages
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setWebhook(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'webhook_url' => 'required|url',
            'enable' => 'boolean',
            'allowed_events' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!$this->whatsappService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp service is not properly configured'
                ], 500);
            }

            $result = $this->whatsappService->setWebhook(
                $request->webhook_url,
                $request->enable ?? true,
                $request->allowed_events ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Webhook set successfully',
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set webhook',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reboot WhatsApp instance
     *
     * @return JsonResponse
     */
    public function rebootInstance(): JsonResponse
    {
        try {
            if (!$this->whatsappService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp service is not properly configured'
                ], 500);
            }

            $result = $this->whatsappService->rebootInstance();

            return response()->json([
                'success' => true,
                'message' => 'Instance rebooted successfully',
                'data' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reboot instance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check WhatsApp service status
     *
     * @return JsonResponse
     */
    public function getStatus(): JsonResponse
    {
        $isConfigured = $this->whatsappService->isConfigured();
        
        return response()->json([
            'success' => true,
            'data' => [
                'configured' => $isConfigured,
                'access_token_set' => !empty(config('whatsapp.access_token')),
                'instance_id_set' => !empty(config('whatsapp.instance_id')),
                'base_url' => config('whatsapp.base_url')
            ]
        ]);
    }

    /**
     * Validate phone number format
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function validatePhoneNumber(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number is required'
            ], 422);
        }

        $phoneNumber = $request->phone_number;
        $formatted = $this->whatsappService->formatPhoneNumber($phoneNumber);
        $isValid = $this->whatsappService->validatePhoneNumber($formatted);

        return response()->json([
            'success' => true,
            'data' => [
                'original' => $phoneNumber,
                'formatted' => $formatted,
                'valid' => $isValid
            ]
        ]);
    }
}
