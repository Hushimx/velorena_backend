<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        // OTP service removed for now
    }

    /**
     * Register a new user
     * 
     * @group Authentication
     * 
     * @bodyParam client_type string required The type of client (individual or company). Example: individual
     * @bodyParam full_name string required The full name of the user (required for individual clients). Example: John Doe
     * @bodyParam company_name string required The company name (required for company clients). Example: Acme Corp
     * @bodyParam contact_person string required The contact person name (required for company clients). Example: Jane Smith
     * @bodyParam email string required The email address. Example: john@example.com
     * @bodyParam phone string The phone number. Example: +1234567890
     * @bodyParam address string The address. Example: 123 Main St
     * @bodyParam city string The city. Example: New York
     * @bodyParam country string The country. Example: USA
     * @bodyParam vat_number string The VAT number. Example: VAT123456
     * @bodyParam cr_number string The CR number. Example: CR123456
     * @bodyParam notes string Additional notes. Example: Important client
     * @bodyParam password string required The password (minimum 8 characters). Example: password123
     * @bodyParam password_confirmation string required Password confirmation. Example: password123
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "User registered successfully",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "client_type": "individual",
     *       "full_name": "John Doe",
     *       "company_name": null,
     *       "email": "john@example.com",
     *       "phone": "+1234567890"
     *     },
     *     "token": "1|abc123..."
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "email": ["The email field is required."]
     *   }
     * }
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
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user
     * 
     * @group Authentication
     * 
     * @bodyParam email string required The email address. Example: john@example.com
     * @bodyParam password string required The password. Example: password123
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Login successful",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "client_type": "individual",
     *       "full_name": "John Doe",
     *       "company_name": null,
     *       "email": "john@example.com",
     *       "phone": "+1234567890"
     *     },
     *     "token": "1|abc123..."
     *   }
     * }
     * 
     * @response 401 {
     *   "success": false,
     *   "message": "Invalid credentials"
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Validation failed",
     *   "errors": {
     *     "email": ["The email field is required."]
     *   }
     * }
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
     * 
     * @group Authentication
     * @authenticated
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Logged out successfully"
     * }
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
     * @group User Profile
     * @authenticated
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "client_type": "individual",
     *       "full_name": "John Doe",
     *       "company_name": null,
     *       "contact_person": null,
     *       "email": "john@example.com",
     *       "phone": "+1234567890",
     *       "address": "123 Main St",
     *       "city": "New York",
     *       "country": "USA",
     *       "vat_number": null,
     *       "cr_number": null,
     *       "notes": null,
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "cr_document_url": null,
     *       "vat_document_url": null
     *     }
     *   }
     * }
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
