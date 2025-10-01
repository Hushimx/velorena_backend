<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ExpoPushNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationExampleController extends Controller
{
    /**
     * Send welcome notification to authenticated user
     */
    public function sendWelcome(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            $user->notify(new ExpoPushNotification(
                title: 'Welcome to QAADS!',
                body: 'Thank you for joining our platform. Explore our amazing designs!',
                data: ['type' => 'welcome', 'timestamp' => now()->toISOString()]
            ));

            return response()->json([
                'success' => true,
                'message' => 'Welcome notification sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send welcome notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send order update notification
     */
    public function sendOrderUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled'
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            $statusMessages = [
                'pending' => 'Your order is being processed',
                'processing' => 'Your order is being prepared',
                'shipped' => 'Your order has been shipped',
                'delivered' => 'Your order has been delivered',
                'cancelled' => 'Your order has been cancelled'
            ];

            $user->notify(new ExpoPushNotification(
                title: 'Order Update',
                body: $statusMessages[$request->status] ?? 'Your order status has been updated',
                data: [
                    'type' => 'order',
                    'order_id' => $request->order_id,
                    'status' => $request->status,
                    'timestamp' => now()->toISOString()
                ]
            ));

            return response()->json([
                'success' => true,
                'message' => 'Order update notification sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send order update notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send appointment reminder
     */
    public function sendAppointmentReminder(Request $request): JsonResponse
    {
        $request->validate([
            'appointment_id' => 'required|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string'
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            $user->notify(new ExpoPushNotification(
                title: 'Appointment Reminder',
                body: "You have an appointment scheduled for {$request->appointment_date} at {$request->appointment_time}",
                data: [
                    'type' => 'appointment',
                    'appointment_id' => $request->appointment_id,
                    'date' => $request->appointment_date,
                    'time' => $request->appointment_time,
                    'timestamp' => now()->toISOString()
                ]
            ));

            return response()->json([
                'success' => true,
                'message' => 'Appointment reminder sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send appointment reminder',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send promotional notification
     */
    public function sendPromotional(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:255',
            'promotion_id' => 'nullable|integer'
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            $user->notify(new ExpoPushNotification(
                title: $request->title,
                body: $request->message,
                data: [
                    'type' => 'promotion',
                    'promotion_id' => $request->promotion_id,
                    'timestamp' => now()->toISOString()
                ]
            ));

            return response()->json([
                'success' => true,
                'message' => 'Promotional notification sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send promotional notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
