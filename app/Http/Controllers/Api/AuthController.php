<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), User::getValidationRules());

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create user
            $user = User::create([
                'client_type' => $request->client_type,
                'full_name' => $request->full_name,
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'vat_number' => $request->vat_number,
                'cr_number' => $request->cr_number,
                'notes' => $request->notes,
                'password' => Hash::make($request->password),
            ]);

            // Send OTP for email verification
            $otpResult = $this->otpService->sendOtp($user->email, 'email');

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user->only(['id', 'client_type', 'full_name', 'company_name', 'email', 'phone']),
                    'token' => $token,
                    'otp_sent' => $otpResult['success'],
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user->only(['id', 'client_type', 'full_name', 'company_name', 'email', 'phone']),
                    'token' => $token,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user
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
