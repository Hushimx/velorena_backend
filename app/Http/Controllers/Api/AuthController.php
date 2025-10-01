<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Qaads API Documentation",
 *     description="API documentation for Qaads Backend - Authentication and User Management",
 *     @OA\Contact(
 *         email="admin@Qaads.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    public function __construct()
    {
        // OTP service removed for now
    }

    /**
     * Register a new user
     * 
     * @OA\Post(
     *     path="/api/auth/register",
     *     operationId="register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Register a new user with the provided information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_type","email","password","password_confirmation"},
     *             @OA\Property(
     *                 property="client_type", 
     *                 type="string", 
     *                 enum={"individual","company"}, 
     *                 description="Type of client account - individual person or company",
     *                 example="individual"
     *             ),
     *             @OA\Property(
     *                 property="full_name", 
     *                 type="string", 
     *                 description="Full name of the individual or primary contact person",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="company_name", 
     *                 type="string", 
     *                 description="Company name (required if client_type is 'company')",
     *                 example="Acme Corp"
     *             ),
     *             @OA\Property(
     *                 property="contact_person", 
     *                 type="string", 
     *                 description="Name of the contact person for company accounts",
     *                 example="Jane Smith"
     *             ),
     *             @OA\Property(
     *                 property="email", 
     *                 type="string", 
     *                 format="email", 
     *                 description="Valid email address for account login and communications",
     *                 example="john@example.com"
     *             ),
     *             @OA\Property(
     *                 property="phone", 
     *                 type="string", 
     *                 description="Phone number with country code for contact purposes",
     *                 example="+1234567890"
     *             ),
     *             @OA\Property(
     *                 property="address", 
     *                 type="string", 
     *                 description="Street address for billing and delivery purposes",
     *                 example="123 Main St"
     *             ),
     *             @OA\Property(
     *                 property="city", 
     *                 type="string", 
     *                 description="City name for billing and delivery purposes",
     *                 example="New York"
     *             ),
     *             @OA\Property(
     *                 property="country", 
     *                 type="string", 
     *                 description="Country name for billing and delivery purposes",
     *                 example="USA"
     *             ),
     *             @OA\Property(
     *                 property="vat_number", 
     *                 type="string", 
     *                 description="VAT registration number for tax purposes (optional)",
     *                 example="VAT123456"
     *             ),
     *             @OA\Property(
     *                 property="cr_number", 
     *                 type="string", 
     *                 description="Commercial registration number for business accounts (optional)",
     *                 example="CR123456"
     *             ),
     *             @OA\Property(
     *                 property="notes", 
     *                 type="string", 
     *                 description="Additional notes or special requirements for the account",
     *                 example="Important client"
     *             ),
     *             @OA\Property(
     *                 property="password", 
     *                 type="string", 
     *                 format="password", 
     *                 description="Account password (minimum 8 characters)",
     *                 example="password123"
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation", 
     *                 type="string", 
     *                 format="password", 
     *                 description="Password confirmation (must match password field)",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="client_type", type="string", example="individual"),
     *                     @OA\Property(property="full_name", type="string", example="John Doe"),
     *                     @OA\Property(property="company_name", type="string", nullable=true),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="1|abc123...")
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
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required."))
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            // Log request data for debugging
            \Log::info('Registration attempt', [
                'data' => $request->except('password', 'password_confirmation')
            ]);

            // Get validation rules
            try {
                $rules = User::getValidationRules();
            } catch (\Exception $e) {
                \Log::error('Failed to get validation rules', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation configuration error',
                    'error' => $e->getMessage(),
                ], 500);
            }

            // Validate request
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                \Log::warning('Registration validation failed', [
                    'errors' => $validator->errors()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create user
            $user = User::create([
                'client_type' => $request->client_type,
                'full_name' => $request->full_name ?? null,
                'company_name' => $request->company_name ?? null,
                'contact_person' => $request->contact_person ?? null,
                'email' => $request->email,
                'phone' => $request->phone ?? null,
                'address' => $request->address ?? null,
                'city' => $request->city ?? null,
                'country' => $request->country ?? 'Saudi Arabia',
                'vat_number' => $request->vat_number ?? null,
                'cr_number' => $request->cr_number ?? null,
                'notes' => $request->notes ?? null,
                'password' => Hash::make($request->password),
            ]);

            \Log::info('User registered successfully', ['user_id' => $user->id]);

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user->only(['id', 'client_type', 'full_name', 'company_name', 'email', 'phone']),
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check phone availability
     * 
     * @OA\Post(
     *     path="/api/auth/check-phone",
     *     operationId="checkPhone",
     *     tags={"Authentication"},
     *     summary="Check phone availability",
     *     description="Check if a phone number is available for registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone"},
     *             @OA\Property(
     *                 property="phone", 
     *                 type="string", 
     *                 description="Phone number to check",
     *                 example="966501234567"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Phone availability checked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Phone is available"),
     *             @OA\Property(property="available", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Phone already taken",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Phone is already taken"),
     *             @OA\Property(property="available", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function checkPhone(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid phone format',
                    'available' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $phone = $request->phone;
            $exists = User::where('phone', $phone)->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone is already taken',
                    'available' => false,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Phone is available',
                'available' => true,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check phone availability',
                'available' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check email availability
     * 
     * @OA\Post(
     *     path="/api/auth/check-email",
     *     operationId="checkEmail",
     *     tags={"Authentication"},
     *     summary="Check email availability",
     *     description="Check if an email address is available for registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(
     *                 property="email", 
     *                 type="string", 
     *                 format="email", 
     *                 description="Email address to check",
     *                 example="john@example.com"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email availability checked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Email is available"),
     *             @OA\Property(property="available", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Email already taken",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email is already taken"),
     *             @OA\Property(property="available", type="boolean", example=false)
     *         )
     *     )
     * )
     */
    public function checkEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email format',
                    'available' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $email = $request->email;
            $exists = User::where('email', $email)->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email is already taken',
                    'available' => false,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Email is available',
                'available' => true,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check email availability',
                'available' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Forgot Password - Send OTP
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identifier' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $identifier = $request->identifier;
            
            // Check if user exists by email or phone
            $user = User::where('email', $identifier)
                       ->orWhere('phone', $identifier)
                       ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            // Send OTP via WhatsApp to user's phone
            $otpService = app(\App\Services\OtpService::class);
            $result = $otpService->sendOtp($user->phone, 'whatsapp', 10);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'otp_id' => $result['otp_id'],
                    'phone' => $user->phone,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Forgot password failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reset Password - Verify OTP and Set New Password
     */
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string',
                'code' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Verify OTP
            $otpService = app(\App\Services\OtpService::class);
            $otpResult = $otpService->verifyOtp($request->phone, $request->code, 'whatsapp');

            if (!$otpResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $otpResult['message'],
                ], 422);
            }

            // Find user by phone
            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            \Log::info('Password reset successfully', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully',
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Reset password failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user
     * 
     * @OA\Post(
     *     path="/api/auth/login",
     *     operationId="login",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="Authenticate user with email and password. Returns a Bearer token for API access.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(
     *                 property="email", 
     *                 type="string", 
     *                 format="email", 
     *                 description="Registered email address for the account",
     *                 example="john@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password", 
     *                 type="string", 
     *                 format="password", 
     *                 description="Account password for authentication",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="client_type", type="string", example="individual"),
     *                     @OA\Property(property="full_name", type="string", example="John Doe"),
     *                     @OA\Property(property="company_name", type="string", nullable=true),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="1|abc123...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
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
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required."))
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string', // Can be email or phone
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $loginField = $request->email;
            $password = $request->password;

            // Determine if login field is email or phone
            $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            // Attempt authentication
            if (!Auth::attempt([$fieldType => $loginField, 'password' => $password])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            \Log::info('User logged in successfully', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user->only(['id', 'client_type', 'full_name', 'company_name', 'email', 'phone']),
                    'token' => $token,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user
     * 
     * @OA\Post(
     *     path="/api/auth/logout",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Logout the authenticated user and invalidate their token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Logout failed"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user profile
     * 
     * @OA\Get(
     *     path="/api/profile",
     *     operationId="getProfile",
     *     tags={"User Profile"},
     *     summary="Get user profile",
     *     description="Get the authenticated user's profile information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="client_type", type="string", example="individual"),
     *                     @OA\Property(property="full_name", type="string", example="John Doe"),
     *                     @OA\Property(property="company_name", type="string", nullable=true),
     *                     @OA\Property(property="contact_person", type="string", nullable=true),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="city", type="string", example="New York"),
     *                     @OA\Property(property="country", type="string", example="USA"),
     *                     @OA\Property(property="vat_number", type="string", nullable=true),
     *                     @OA\Property(property="cr_number", type="string", nullable=true),
     *                     @OA\Property(property="notes", type="string", nullable=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
     *                     @OA\Property(property="cr_document_url", type="string", nullable=true),
     *                     @OA\Property(property="vat_document_url", type="string", nullable=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to get profile"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => array_merge($user->only([
                        'id', 'client_type', 'full_name', 'company_name', 'contact_person',
                        'email', 'phone', 'address', 'city', 'country', 'vat_number',
                        'cr_number', 'notes', 'created_at'
                    ]), [
                        'cr_document_url' => $user->cr_document_url,
                        'vat_document_url' => $user->vat_document_url,
                    ]),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user profile
     * 
     * @OA\Put(
     *     path="/api/profile",
     *     operationId="updateProfile",
     *     tags={"User Profile"},
     *     summary="Update user profile",
     *     description="Update the authenticated user's profile information. Only non-sensitive fields can be updated.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="client_type", 
     *                 type="string", 
     *                 enum={"individual","company"}, 
     *                 description="Type of client account - individual person or company",
     *                 example="individual"
     *             ),
     *             @OA\Property(
     *                 property="full_name", 
     *                 type="string", 
     *                 description="Full name of the individual or primary contact person",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="company_name", 
     *                 type="string", 
     *                 description="Company name (required if client_type is 'company')",
     *                 example="Acme Corp"
     *             ),
     *             @OA\Property(
     *                 property="contact_person", 
     *                 type="string", 
     *                 description="Name of the contact person for company accounts",
     *                 example="Jane Smith"
     *             ),
     *             @OA\Property(
     *                 property="phone", 
     *                 type="string", 
     *                 description="Phone number with country code for contact purposes",
     *                 example="+1234567890"
     *             ),
     *             @OA\Property(
     *                 property="address", 
     *                 type="string", 
     *                 description="Street address for billing and delivery purposes",
     *                 example="123 Main St"
     *             ),
     *             @OA\Property(
     *                 property="city", 
     *                 type="string", 
     *                 description="City name for billing and delivery purposes",
     *                 example="New York"
     *             ),
     *             @OA\Property(
     *                 property="country", 
     *                 type="string", 
     *                 description="Country name for billing and delivery purposes",
     *                 example="USA"
     *             ),
     *             @OA\Property(
     *                 property="vat_number", 
     *                 type="string", 
     *                 description="VAT registration number for tax purposes (optional)",
     *                 example="VAT123456"
     *             ),
     *             @OA\Property(
     *                 property="cr_number", 
     *                 type="string", 
     *                 description="Commercial registration number for business accounts (optional)",
     *                 example="CR123456"
     *             ),
     *             @OA\Property(
     *                 property="notes", 
     *                 type="string", 
     *                 description="Additional notes or special requirements for the account",
     *                 example="Important client"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="client_type", type="string", example="individual"),
     *                     @OA\Property(property="full_name", type="string", example="John Doe"),
     *                     @OA\Property(property="company_name", type="string", nullable=true),
     *                     @OA\Property(property="contact_person", type="string", nullable=true),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="city", type="string", example="New York"),
     *                     @OA\Property(property="country", type="string", example="USA"),
     *                     @OA\Property(property="vat_number", type="string", nullable=true),
     *                     @OA\Property(property="cr_number", type="string", nullable=true),
     *                     @OA\Property(property="notes", type="string", nullable=true),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00.000000Z"),
     *                     @OA\Property(property="cr_document_url", type="string", nullable=true),
     *                     @OA\Property(property="vat_document_url", type="string", nullable=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
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
     *                 @OA\Property(property="full_name", type="array", @OA\Items(type="string", example="The full name field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update profile"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();
            
            $validator = Validator::make($request->all(), User::getValidationRules(true));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user->update($request->only([
                'client_type', 'full_name', 'company_name', 'contact_person',
                'phone', 'address', 'city', 'country', 'vat_number',
                'cr_number', 'notes'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => array_merge($user->only([
                        'id', 'client_type', 'full_name', 'company_name', 'contact_person',
                        'email', 'phone', 'address', 'city', 'country', 'vat_number',
                        'cr_number', 'notes', 'updated_at'
                    ]), [
                        'cr_document_url' => $user->cr_document_url,
                        'vat_document_url' => $user->vat_document_url,
                    ]),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
