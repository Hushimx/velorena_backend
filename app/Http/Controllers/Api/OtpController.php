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
     * Send OTP to user
     * 
     * @OA\Post(
     *     path="/api/auth/send-otp",
     *     operationId="sendOtp",
     *     tags={"Authentication"},
     *     summary="Send OTP to user",
     *     description="Send a one-time password (OTP) to the user's email, phone, or WhatsApp. The OTP can be used for verification purposes.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"identifier","type"},
     *             @OA\Property(
     *                 property="identifier", 
     *                 type="string", 
     *                 description="Email address or phone number where the OTP will be sent. For email OTP, use a valid email format. For SMS/WhatsApp, use international phone number format with country code.",
     *                 example="john@example.com"
     *             ),
     *             @OA\Property(
     *                 property="type", 
     *                 type="string", 
     *                 enum={"email","sms","whatsapp","fake"}, 
     *                 description="Delivery method for the OTP. 'email' sends via email, 'sms' sends via text message, 'whatsapp' sends via WhatsApp, 'fake' generates a test OTP for development",
     *                 example="email"
     *             ),
     *             @OA\Property(
     *                 property="expiry_minutes", 
     *                 type="integer", 
     *                 minimum=1,
     *                 maximum=60,
     *                 description="Number of minutes before the OTP expires. Must be between 1-60 minutes. Default is 10 minutes if not specified.",
     *                 example=10
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP sent successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="otp_id", type="string", example="123"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time", example="2024-01-01T00:10:00.000000Z"),
     *                 @OA\Property(property="type", type="string", example="email")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="identifier", type="array", @OA\Items(type="string", example="The identifier field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to send OTP"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
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
     * Verify OTP code
     * 
     * @OA\Post(
     *     path="/api/auth/verify-otp",
     *     operationId="verifyOtp",
     *     tags={"Authentication"},
     *     summary="Verify OTP code",
     *     description="Verify the OTP code sent to the user. The code must be exactly 6 digits and not expired.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"identifier","code","type"},
     *             @OA\Property(
     *                 property="identifier", 
     *                 type="string", 
     *                 description="Email address or phone number that was used when the OTP was sent. Must match exactly with the identifier used in the send-otp request.",
     *                 example="john@example.com"
     *             ),
     *             @OA\Property(
     *                 property="code", 
     *                 type="string", 
     *                 minLength=6,
     *                 maxLength=6,
     *                 description="6-digit OTP code received via the specified delivery method. Must be exactly 6 characters long.",
     *                 example="123456"
     *             ),
     *             @OA\Property(
     *                 property="type", 
     *                 type="string", 
     *                 enum={"email","sms","whatsapp","fake"}, 
     *                 description="Delivery method that was used to send the OTP. Must match the type used in the send-otp request.",
     *                 example="email"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP verified successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="verified_at", type="string", format="date-time", example="2024-01-01T00:05:00.000000Z"),
     *                 @OA\Property(property="otp_id", type="string", example="123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid OTP code",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid OTP code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="code", type="array", @OA\Items(type="string", example="The code field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to verify OTP"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
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
     * Resend OTP to user
     * 
     * @OA\Post(
     *     path="/api/auth/resend-otp",
     *     operationId="resendOtp",
     *     tags={"Authentication"},
     *     summary="Resend OTP to user",
     *     description="Resend a new OTP to the user. This will invalidate any previous OTP and generate a new one.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"identifier","type"},
     *             @OA\Property(
     *                 property="identifier", 
     *                 type="string", 
     *                 description="Email address or phone number where the new OTP will be sent. Must be the same identifier used in the original send-otp request.",
     *                 example="john@example.com"
     *             ),
     *             @OA\Property(
     *                 property="type", 
     *                 type="string", 
     *                 enum={"email","sms","whatsapp","fake"}, 
     *                 description="Delivery method for the new OTP. Must be the same type used in the original send-otp request.",
     *                 example="email"
     *             ),
     *             @OA\Property(
     *                 property="expiry_minutes", 
     *                 type="integer", 
     *                 minimum=1,
     *                 maximum=60,
     *                 description="Number of minutes before the new OTP expires. Must be between 1-60 minutes. Default is 10 minutes if not specified.",
     *                 example=10
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP resent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP resent successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="otp_id", type="string", example="124"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time", example="2024-01-01T00:10:00.000000Z"),
     *                 @OA\Property(property="type", type="string", example="email")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="identifier", type="array", @OA\Items(type="string", example="The identifier field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to resend OTP"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
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
