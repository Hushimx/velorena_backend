<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserOrderController extends Controller
{
    public function index()
    {
        // Clean up orders without items for the current user
        $this->cleanupEmptyOrders();

        return view('users.orders.index');
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['items.product', 'payments', 'user']);

        // If the order has no items, delete it and redirect to orders index
        if ($order->items->count() === 0) {
            $order->delete();
            return redirect()->route('user.orders.index')
                ->with('info', 'Order was empty and has been removed.');
        }

        return view('users.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending orders can be cancelled.');
        }

        try {
            // Delete order items first (due to foreign key constraints)
            $order->items()->delete();
            
            // Delete the order
            $order->delete();

            return redirect()->route('user.orders.index')
                ->with('success', 'Order has been cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel order. Please try again.');
        }
    }

    /**
     * Clean up orders that don't have any items for the current user
     */
    private function cleanupEmptyOrders()
    {
        $userId = Auth::id();

        // Find orders without items for the current user
        $emptyOrders = Order::where('user_id', $userId)
            ->whereDoesntHave('items')
            ->get();

        if ($emptyOrders->count() > 0) {
            // Delete the empty orders
            $deletedCount = $emptyOrders->count();
            Order::where('user_id', $userId)
                ->whereDoesntHave('items')
                ->delete();

            // Log the cleanup for debugging purposes
            Log::info("Cleaned up {$deletedCount} empty orders for user {$userId}");
        }
    }

    /**
     * Initiate Tap payment for an order
     */
    public function initiatePayment(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        // Check if order can make payment
        if (!$order->canMakePayment()) {
            return redirect()->back()
                ->with('error', 'This order cannot be paid at this time.');
        }

        try {
            // Use the existing TapPaymentService
            $tapPaymentService = app(\App\Services\TapPaymentService::class);
            
            $chargeData = [
                'amount' => $order->total,
                'currency' => 'SAR',
                'customer' => [
                    'first_name' => $order->user->full_name ?? $order->user->company_name ?? 'Customer',
                    'last_name' => '',
                    'email' => $order->user->email,
                    'phone' => $order->phone ?? $order->user->phone ?? '+966500000000'
                ],
                'source' => [
                    'id' => 'src_all'
                ],
                'redirect' => [
                    'url' => route('user.orders.show', $order)
                ],
                'post' => [
                    'url' => route('api.webhooks.tap')
                ],
                'description' => "Payment for Order #{$order->order_number}",
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id
                ]
            ];

            $result = $tapPaymentService->createCharge($chargeData);

            if ($result['success']) {
                // Create payment record with pending status
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'charge_id' => $result['charge_id'],
                    'amount' => $order->total,
                    'currency' => 'SAR',
                    'status' => 'pending',
                    'payment_method' => 'tap',
                    'gateway_response' => $result['data']
                ]);

                // Redirect to Tap payment page
                return redirect($result['payment_url']);

            } else {
                return redirect()->back()
                    ->with('error', 'Failed to create payment. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Tap payment initiation failed', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    /**
     * Check payment status manually
     */
    public function checkPaymentStatus(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        try {
            // Get the latest payment for this order
            $payment = $order->payments()->latest()->first();
            
            if (!$payment) {
                return redirect()->back()
                    ->with('error', 'No payment found for this order.');
            }

            // Use TapPaymentService to check status
            $tapPaymentService = app(\App\Services\TapPaymentService::class);
            $result = $tapPaymentService->getCharge($payment->charge_id);

            if ($result['success']) {
                $chargeData = $result['data'];
                $status = $this->mapTapStatusToLocal($chargeData['status']);
                
                // Update payment status
                $payment->update([
                    'status' => $status,
                    'gateway_response' => $chargeData
                ]);

                // Update order status if payment is successful
                if ($status === 'completed') {
                    $order->update(['status' => 'processing']);
                    return redirect()->back()
                        ->with('success', 'Payment completed successfully! Order status updated to processing.');
                } elseif ($status === 'failed') {
                    return redirect()->back()
                        ->with('error', 'Payment failed. Please try again.');
                } else {
                    return redirect()->back()
                        ->with('info', 'Payment status: ' . $status);
                }
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to check payment status. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Payment status check failed', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to check payment status. Please try again.');
        }
    }

    /**
     * Map Tap payment status to local status
     */
    private function mapTapStatusToLocal($tapStatus)
    {
        switch (strtoupper($tapStatus)) {
            case 'CAPTURED':
            case 'SUCCESS':
                return 'completed';
            case 'FAILED':
            case 'CANCELLED':
                return 'failed';
            case 'PENDING':
            case 'INITIATED':
                return 'pending';
            default:
                return 'pending';
        }
    }
}
