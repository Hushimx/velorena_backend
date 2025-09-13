<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $order->load(['items.product']);

        // If the order has no items, delete it and redirect to orders index
        if ($order->items->count() === 0) {
            $order->delete();
            return redirect()->route('user.orders.index')
                ->with('info', 'Order was empty and has been removed.');
        }

        return view('users.orders.show', compact('order'));
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
            \Log::info("Cleaned up {$deletedCount} empty orders for user {$userId}");
        }
    }
}
