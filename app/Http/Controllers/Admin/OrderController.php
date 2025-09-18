<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.orders.index');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.product.options.values', 'designs']);
        return view('admin.dashboard.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.dashboard.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $data = $request->only(['status', 'notes', 'shipping_address', 'billing_address', 'phone']);

        // Handle status transitions
        switch ($request->status) {
            case 'confirmed':
                $order->confirm();
                break;
            case 'processing':
                $order->process();
                break;
            case 'shipped':
                $order->ship();
                break;
            case 'delivered':
                $order->deliver();
                break;
            case 'cancelled':
                $order->cancel();
                break;
            default:
                $order->update(['status' => $request->status]);
        }

        // Update other fields
        $order->update($data);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', trans('orders.order_updated_successfully'));
    }

    public function destroy(Order $order)
    {
        // Check if order can be deleted (only pending orders)
        if (!$order->isPending()) {
            return back()->with('error', trans('orders.cannot_delete_non_pending_order'));
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', trans('orders.order_deleted_successfully'));
    }
}
