<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Send OTP
     * 
     * @group Authentication
     * 
     * @bodyParam identifier string required The email or phone number to send OTP to. Example: john@example.com
     * @bodyParam type string required The type of OTP (email, sms, whatsapp, fake). Example: email
     * @bodyParam expiry_minutes integer The expiry time in minutes (1-60). Example: 10
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "OTP sent successfully",
     *   "data": {
     *     "otp_id": "123",
     *     "expires_at": "2024-01-01T00:10:00.000000Z",
     *     "type": "email"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "identifier": ["The identifier field is required."]
     *   }
     * }
     */
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identifier' => 'required|string',
                'type' => 'required|in:email,sms,whatsapp,fake',
                'expiry_minutes' => 'nullable|integer|min:1|max:60',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $expiryMinutes = $request->input('expiry_minutes', 10);
            $result = $this->otpService->sendOtp(
                $request->identifier,
                $request->type,
                $expiryMinutes
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'otp_id' => $result['otp_id'],
                        'expires_at' => $result['expires_at'],
                        'type' => $request->type,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'error' => $result['error'] ?? null,
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify OTP
     * 
     * @group Authentication
     * 
     * @bodyParam identifier string required The email or phone number. Example: john@example.com
     * @bodyParam code string required The 6-digit OTP code. Example: 123456
     * @bodyParam type string required The type of OTP (email, sms, whatsapp, fake). Example: email
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "OTP verified successfully",
     *   "data": {
     *     "verified_at": "2024-01-01T00:05:00.000000Z",
     *     "otp_id": "123"
     *   }
     * }
     * 
     * @response 400 {
     *   "success": false,
     *   "message": "Invalid OTP code"
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "code": ["The code field is required."]
     *   }
     * }
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identifier' => 'required|string',
                'code' => 'required|string|size:6',
                'type' => 'required|in:email,sms,whatsapp,fake',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $result = $this->otpService->verifyOtp(
                $request->identifier,
                $request->code,
                $request->type
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'verified_at' => now(),
                        'otp_id' => $result['otp']->id,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify OTP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resend OTP
     * 
     * @group Authentication
     * 
     * @bodyParam identifier string required The email or phone number. Example: john@example.com
     * @bodyParam type string required The type of OTP (email, sms, whatsapp, fake). Example: email
     * @bodyParam expiry_minutes integer The expiry time in minutes (1-60). Example: 10
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "OTP resent successfully",
     *   "data": {
     *     "otp_id": "124",
     *     "expires_at": "2024-01-01T00:10:00.000000Z",
     *     "type": "email"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "identifier": ["The identifier field is required."]
     *   }
     * }
     */
    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identifier' => 'required|string',
                'type' => 'required|in:email,sms,whatsapp,fake',
                'expiry_minutes' => 'nullable|integer|min:1|max:60',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $expiryMinutes = $request->input('expiry_minutes', 10);
            $result = $this->otpService->resendOtp(
                $request->identifier,
                $request->type
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP resent successfully',
                    'data' => [
                        'otp_id' => $result['otp_id'],
                        'expires_at' => $result['expires_at'],
                        'type' => $request->type,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'error' => $result['error'] ?? null,
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
