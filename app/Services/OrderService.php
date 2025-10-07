<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OptionValue;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Create a new order with items
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();

            // Debug: Log the incoming data
            Log::info('Order creation data:', $data);

            // Prepare order data
            $orderData = [
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'phone' => $data['phone'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
                'billing_address' => $data['billing_address'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0
            ];

            // Handle address relationship and detailed shipping info
            if (isset($data['address_id'])) {
                $address = Address::where('user_id', $user->id)
                    ->where('id', $data['address_id'])
                    ->first();
                
                if ($address) {
                    $orderData['address_id'] = $address->id;
                    $orderData['shipping_contact_name'] = $address->contact_name;
                    $orderData['shipping_contact_phone'] = $address->contact_phone;
                    $orderData['shipping_city'] = $address->city;
                    $orderData['shipping_district'] = $address->district;
                    $orderData['shipping_street'] = $address->street;
                    $orderData['shipping_house_description'] = $address->house_description;
                    $orderData['shipping_postal_code'] = $address->postal_code;
                    // Build full address for backward compatibility
                    $orderData['shipping_address'] = implode(', ', array_filter([
                        $address->street,
                        $address->district,
                        $address->city
                    ]));
                }
            } else {
                // Handle direct shipping data if provided (for backward compatibility)
                $orderData['shipping_contact_name'] = $data['shipping_contact_name'] ?? $user->full_name ?? $user->contact_person;
                $orderData['shipping_contact_phone'] = $data['shipping_contact_phone'] ?? $data['phone'];
                $orderData['shipping_city'] = $data['shipping_city'] ?? null;
                $orderData['shipping_district'] = $data['shipping_district'] ?? null;
                $orderData['shipping_street'] = $data['shipping_street'] ?? null;
                $orderData['shipping_house_description'] = $data['shipping_house_description'] ?? null;
                $orderData['shipping_postal_code'] = $data['shipping_postal_code'] ?? null;
            }

            // Create order
            $order = Order::create($orderData);

            $subtotal = $this->addOrderItems($order, $data['items']);
            $this->updateOrderTotals($order, $subtotal);

            return $order->load(['items.product', 'items.product.options.values', 'address']);
        });
    }

    /**
     * Add items to an existing order
     */
    public function addItemToOrder(Order $order, array $itemData): Order
    {
        return DB::transaction(function () use ($order, $itemData) {
            $product = Product::findOrFail($itemData['product_id']);

            // Calculate unit price (base price + options)
            $unitPrice = $product->base_price;
            $optionsPrice = 0;
            $selectedOptions = [];

            // Handle options safely
            $options = $itemData['options'] ?? [];
            if (is_array($options) && !empty($options)) {
                foreach ($options as $optionValueId) {
                    $optionValue = OptionValue::find($optionValueId);
                    if ($optionValue) {
                        $optionsPrice += $optionValue->price_adjustment;
                        $selectedOptions[] = $optionValueId;
                    }
                }
            }

            $unitPrice += $optionsPrice;
            $totalPrice = $unitPrice * $itemData['quantity'];

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'options' => json_encode($selectedOptions),
                'notes' => $itemData['notes'] ?? null
            ]);

            // Recalculate order totals
            $subtotal = $order->items->sum('total_price');
            $this->updateOrderTotals($order, $subtotal);

            return $order->load(['items.product', 'items.product.options.values']);
        });
    }

    /**
     * Remove item from order
     */
    public function removeItemFromOrder(Order $order, int $itemId): Order
    {
        return DB::transaction(function () use ($order, $itemId) {
            // Find and delete the order item
            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('id', $itemId)
                ->firstOrFail();

            $orderItem->delete();

            // Recalculate order totals
            $subtotal = $order->items->sum('total_price');
            $this->updateOrderTotals($order, $subtotal);

            return $order->load(['items.product', 'items.product.options.values']);
        });
    }

    /**
     * Delete an order
     */
    public function deleteOrder(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            // Delete order items first (due to foreign key constraints)
            $order->items()->delete();

            // Delete the order
            return $order->delete();
        });
    }

    /**
     * Add multiple items to order
     */
    private function addOrderItems(Order $order, array $items): float
    {
        $subtotal = 0;

        foreach ($items as $itemData) {
            $product = Product::findOrFail($itemData['product_id']);

            // Use provided prices if available (from cart), otherwise calculate
            if (isset($itemData['unit_price']) && isset($itemData['total_price'])) {
                // Use cart-calculated prices
                $unitPrice = $itemData['unit_price'];
                $totalPrice = $itemData['total_price'];
                $selectedOptions = $itemData['selected_options'] ?? $itemData['options'] ?? [];
            } else {
                // Calculate unit price (base price + options)
                $unitPrice = $product->base_price;
                $optionsPrice = 0;
                $selectedOptions = [];

                // Handle options safely
                $options = $itemData['options'] ?? [];
                if (is_array($options) && !empty($options)) {
                    foreach ($options as $optionValueId) {
                        $optionValue = OptionValue::find($optionValueId);
                        if ($optionValue) {
                            $optionsPrice += $optionValue->price_adjustment;
                            $selectedOptions[] = $optionValueId;
                        }
                    }
                }

                $unitPrice += $optionsPrice;
                $totalPrice = $unitPrice * $itemData['quantity'];
            }

            $subtotal += $totalPrice;

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'options' => is_array($selectedOptions) ? json_encode($selectedOptions) : $selectedOptions,
                'notes' => $itemData['notes'] ?? null
            ]);
        }

        return $subtotal;
    }

    /**
     * Update order totals
     */
    private function updateOrderTotals(Order $order, float $subtotal): void
    {
        $tax = $subtotal * 0.15; // 15% tax
        $total = $subtotal + $tax;

        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);
    }

    /**
     * Check if user can modify order
     */
    public function canModifyOrder(Order $order): bool
    {
        $user = Auth::user();

        return $order->user_id === $user->id && $order->status === 'pending';
    }

    /**
     * Check if user can delete order
     */
    public function canDeleteOrder(Order $order): bool
    {
        $user = Auth::user();

        return $order->user_id === $user->id && $order->status === 'pending';
    }
}
