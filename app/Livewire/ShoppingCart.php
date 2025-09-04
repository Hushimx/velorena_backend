<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Appointment;
use App\Services\OrderService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShoppingCart extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $itemCount = 0;

    protected $listeners = ['cartUpdated' => 'loadCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        // Load cart from localStorage via JavaScript
        $this->dispatch('loadCartFromStorage');
    }

    public function updateCart($cartData)
    {
        \Log::info('ShoppingCart updateCart called', $cartData);
        $this->cartItems = $cartData['items'] ?? [];
        $this->total = $cartData['total'] ?? 0;
        $this->itemCount = $cartData['itemCount'] ?? 0;
    }

    public function removeItem($productId)
    {
        $this->dispatch('removeFromCart', $productId);
    }

    public function updateQuantity($productId, $quantity)
    {
        // Validate productId
        if (!$productId || $productId <= 0) {
            session()->flash('error', trans('cart.invalid_product', ['default' => 'Invalid product.']));
            return;
        }

        // Ensure quantity is at least 1
        $quantity = max(1, $quantity);

        // If quantity is 1 and user is trying to decrease, don't do anything
        if ($quantity <= 0) {
            session()->flash('error', trans('cart.quantity_minimum', ['default' => 'Minimum quantity is 1. Use remove button to delete item.']));
            return;
        }

        // Dispatch event to update localStorage
        $this->dispatch('updateCartQuantity', $productId, $quantity);

        // Refresh the component to sync with updated localStorage
        $this->dispatch('$refresh');
    }

    public function clearCart()
    {
        // Clear the Livewire component data
        $this->cartItems = [];
        $this->total = 0;
        $this->itemCount = 0;

        // Dispatch event to clear localStorage
        $this->dispatch('clearCart');
    }

    public function checkout()
    {
        \Log::info('Checkout method called', ['cartItems' => $this->cartItems]);

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        try {
            DB::beginTransaction();

            // Create order using existing OrderService
            $orderService = new OrderService();

            $orderData = [
                'phone' => Auth::user()->phone ?? '',
                'shipping_address' => Auth::user()->address ?? '',
                'billing_address' => Auth::user()->address ?? '',
                'notes' => 'Order created from shopping cart',
                'items' => $this->prepareOrderItems()
            ];

            $order = $orderService->createOrder($orderData);

            // Clear cart after successful order creation
            $this->clearCart();

            DB::commit();

            session()->flash('success', trans('cart.checkout_success') . ' Order #' . $order->order_number);
            return redirect()->route('user.orders.show', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            session()->flash('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function bookAppointment()
    {
        \Log::info('BookAppointment method called', ['cartItems' => $this->cartItems]);

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        try {
            DB::beginTransaction();

            // Create order first
            $orderService = new OrderService();

            $orderData = [
                'phone' => Auth::user()->phone ?? '',
                'shipping_address' => Auth::user()->address ?? '',
                'billing_address' => Auth::user()->address ?? '',
                'notes' => 'Order created from cart for appointment booking',
                'items' => $this->prepareOrderItems()
            ];

            $order = $orderService->createOrder($orderData);

            // Clear cart after successful order creation
            $this->clearCart();

            DB::commit();

            // Redirect to appointment booking with the order
            session()->flash('success', trans('cart.appointment_redirect'));
            return redirect()->route('appointments.create', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Appointment booking error: ' . $e->getMessage());
            session()->flash('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    private function prepareOrderItems()
    {
        $items = [];
        \Log::info('Preparing order items', ['cartItems' => $this->cartItems]);

        foreach ($this->cartItems as $item) {
            // Handle mixed structure - some items might be wrapped in arrays
            if (is_array($item) && isset($item[0]) && is_array($item[0])) {
                // Item is wrapped in an array, get the first element
                $item = $item[0];
            }

            // Ensure we have a valid item structure
            if (!is_array($item) || !isset($item['product_id'])) {
                \Log::warning('Invalid item structure', ['item' => $item]);
                continue;
            }

            $product = Product::find($item['product_id']);
            if (!$product) {
                \Log::warning('Product not found', ['product_id' => $item['product_id']]);
                continue;
            }

            $items[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'selected_options' => $item['selected_options'] ?? [],
                'notes' => $item['notes'] ?? ''
            ];
        }

        \Log::info('Prepared order items', ['items' => $items]);
        return $items;
    }

    public function removeDesignFromProduct($productId, $designId)
    {
        try {
            // Get the design title before deletion for success message
            $design = \App\Models\Design::find($designId);
            $designTitle = $design ? $design->title : 'Design';

            // Use direct deletion with count to verify success
            $deleted = \App\Models\ProductDesign::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('design_id', $designId)
                ->delete();

            if ($deleted === 0) {
                session()->flash('error', trans('cart.design_not_found', ['default' => 'Design not found or already removed.']));
                return;
            }

            // Show success message
            session()->flash('success', trans('cart.design_removed_success', ['default' => "Design ':design' removed successfully!", 'design' => $designTitle]));

            // Just refresh the component without resetting cart data
            $this->dispatch('$refresh');

            // Dispatch custom event for frontend refresh
            $this->dispatch('design-removed', [
                'product_id' => $productId,
                'design_id' => $designId
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing design from product: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'design_id' => $designId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', trans('cart.design_remove_failed', ['default' => 'Failed to remove design. Please try again.']));
        }
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
