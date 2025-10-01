<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpoPushToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ExpoPushTokenController extends Controller
{
    /**
     * Register or update Expo push token for the authenticated user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:255',
            'device_id' => 'nullable|string|max:255',
            'platform' => 'nullable|string|in:ios,android,web',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            /** @var User $user */
            $user = Auth::user();
            $token = $request->input('token');
            $deviceId = $request->input('device_id');
            $platform = $request->input('platform', 'unknown');

            // Check if token already exists
            $existingToken = ExpoPushToken::where('token', $token)->first();

            if ($existingToken) {
                // Update existing token
                $existingToken->update([
                    'tokenable_id' => $user->id,
                    'tokenable_type' => get_class($user),
                    'device_id' => $deviceId,
                    'platform' => $platform,
                    'is_active' => true,
                    'last_used_at' => now(),
                ]);

                $expoToken = $existingToken;
                $action = 'updated';
            } else {
                // Create new token
                $expoToken = ExpoPushToken::create([
                    'token' => $token,
                    'tokenable_id' => $user->id,
                    'tokenable_type' => get_class($user),
                    'device_id' => $deviceId,
                    'platform' => $platform,
                    'is_active' => true,
                    'last_used_at' => now(),
                ]);

                $action = 'created';
            }

            Log::info('Expo push token registered', [
                'user_id' => $user->id,
                'token_id' => $expoToken->id,
                'action' => $action,
                'platform' => $platform
            ]);

            return response()->json([
                'success' => true,
                'message' => "Expo push token {$action} successfully",
                'data' => [
                    'id' => $expoToken->id,
                    'token' => $expoToken->token,
                    'platform' => $expoToken->platform,
                    'device_id' => $expoToken->device_id,
                    'is_active' => $expoToken->is_active,
                    'created_at' => $expoToken->created_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to register Expo push token', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to register Expo push token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all Expo push tokens for the authenticated user
     */
    public function index(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            
            if (!method_exists($user, 'expoPushTokens')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User model does not support Expo push tokens'
                ], 400);
            }
            
            $tokens = $user->expoPushTokens()
                ->select(['id', 'token', 'device_id', 'platform', 'is_active', 'last_used_at', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tokens
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch Expo push tokens', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Expo push tokens',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate an Expo push token
     */
    public function deactivate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token_id' => 'required|integer|exists:expo_push_tokens,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            /** @var User $user */
            $user = Auth::user();
            $tokenId = $request->input('token_id');

            if (!method_exists($user, 'expoPushTokens')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User model does not support Expo push tokens'
                ], 400);
            }

            $token = $user->expoPushTokens()->find($tokenId);

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found or does not belong to user'
                ], 404);
            }

            $token->deactivate();

            Log::info('Expo push token deactivated', [
                'user_id' => $user->id,
                'token_id' => $tokenId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expo push token deactivated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to deactivate Expo push token', [
                'user_id' => Auth::id(),
                'token_id' => $request->input('token_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate Expo push token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an Expo push token
     */
    public function delete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token_id' => 'required|integer|exists:expo_push_tokens,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            /** @var User $user */
            $user = Auth::user();
            $tokenId = $request->input('token_id');

            if (!method_exists($user, 'expoPushTokens')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User model does not support Expo push tokens'
                ], 400);
            }

            $token = $user->expoPushTokens()->find($tokenId);

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found or does not belong to user'
                ], 404);
            }

            $token->delete();

            Log::info('Expo push token deleted', [
                'user_id' => $user->id,
                'token_id' => $tokenId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expo push token deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete Expo push token', [
                'user_id' => Auth::id(),
                'token_id' => $request->input('token_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Expo push token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send test notification to user's devices
     */
    public function sendTest(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            
            if (!method_exists($user, 'notify')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User model does not support notifications'
                ], 400);
            }
            
            // Send test notification
            $user->notify(new \App\Notifications\ExpoPushNotification(
                title: 'Test Notification',
                body: 'This is a test push notification from QAADS',
                data: ['type' => 'test', 'timestamp' => now()->toISOString()]
            ));

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send test notification', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
