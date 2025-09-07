<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemDesign;
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
    public $showCheckoutForm = false;
    public $checkoutData = [
        'phone' => '',
        'shipping_address' => '',
        'billing_address' => '',
        'notes' => ''
    ];

    protected $listeners = ['cartUpdated' => 'loadCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        if (!Auth::check()) {
            $this->cartItems = [];
            $this->total = 0;
            $this->itemCount = 0;
            return;
        }

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['product.options.values', 'designs.design'])
            ->get();

        $this->cartItems = [];
        $this->total = 0;
        $this->itemCount = 0;

        foreach ($cartItems as $item) {
            // Update prices if not set
            if (!$item->unit_price || !$item->total_price) {
                $item->updatePrices();
            }

            // Get designs attached to this product for this user
            $designs = ProductDesign::where('user_id', $user->id)
                ->where('product_id', $item->product_id)
                ->with('design')
                ->get()
                ->filter(function ($productDesign) {
                    return $productDesign->design !== null;
                })
                ->map(function ($productDesign) {
                    return [
                        'id' => $productDesign->design->id,
                        'title' => $productDesign->design->title,
                        'image_url' => $productDesign->design->image_url,
                        'thumbnail_url' => $productDesign->design->thumbnail_url,
                        'notes' => $productDesign->notes,
                        'priority' => $productDesign->priority,
                        'attached_at' => $productDesign->created_at
                    ];
                });

            // Ensure selected_options is an array
            $selectedOptions = $item->selected_options;
            if (is_string($selectedOptions)) {
                $selectedOptions = json_decode($selectedOptions, true) ?? [];
            }
            if (!is_array($selectedOptions)) {
                $selectedOptions = [];
            }

            // Build selected options with full option value data for display
            $selectedOptionsDisplay = [];
            if (!empty($selectedOptions)) {
                foreach ($selectedOptions as $optionId => $valueId) {
                    if ($valueId) {
                        $option = $item->product->options->find($optionId);
                        $optionValue = \App\Models\OptionValue::find($valueId);
                        if ($option && $optionValue) {
                            $selectedOptionsDisplay[$option->name] = [
                                'value' => $optionValue->value,
                                'price_adjustment' => $optionValue->price_adjustment ?? 0
                            ];
                        }
                    }
                }
            }

            $this->cartItems[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'base_price' => $item->product->base_price,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'name_ar' => $item->product->name_ar,
                    'base_price' => $item->product->base_price,
                    'image' => $item->product->image,
                    'options' => $item->product->options
                ],
                'quantity' => $item->quantity,
                'selected_options' => $selectedOptionsDisplay,
                'notes' => $item->notes ?? '',
                'designs' => $designs,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price
            ];

            $this->total += $item->total_price;
        }

        $this->itemCount = count($this->cartItems);
    }

    /**
     * Legacy method for compatibility with old localStorage cart
     * This method is called by the view but we just reload the cart from database
     */
    public function updateCart($cartData = null)
    {
        // For database cart, we just reload from database
        $this->loadCart();
    }

    public function removeItem($cartItemId)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if (!Auth::check() || $quantity < 1) {
            return;
        }

        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->updatePrices();
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function clearCart()
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        CartItem::where('user_id', $user->id)->delete();
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function showCheckout()
    {
        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        $this->showCheckoutForm = true;
    }

    public function hideCheckout()
    {
        $this->showCheckoutForm = false;
        $this->checkoutData = [
            'phone' => '',
            'shipping_address' => '',
            'billing_address' => '',
            'notes' => ''
        ];
    }

    public function createOrder()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to create an order');
            return;
        }

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        $this->validate([
            'checkoutData.phone' => 'required|string|max:20',
            'checkoutData.shipping_address' => 'nullable|string|max:500',
            'checkoutData.billing_address' => 'nullable|string|max:500',
            'checkoutData.notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

            $orderService = new OrderService();

            // Prepare items data for OrderService
            $items = [];
            foreach ($cartItems as $cartItem) {
                $items[] = [
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'selected_options' => $cartItem->selected_options,
                    'notes' => $cartItem->notes
                ];
            }

            $orderData = [
                'phone' => $this->checkoutData['phone'],
                'shipping_address' => $this->checkoutData['shipping_address'],
                'billing_address' => $this->checkoutData['billing_address'],
                'notes' => $this->checkoutData['notes'],
                'items' => $items
            ];

            $order = $orderService->createOrder($orderData);

            // Copy design attachments to order items
            foreach ($order->items as $orderItem) {
                $designs = ProductDesign::where('user_id', $user->id)
                    ->where('product_id', $orderItem->product_id)
                    ->get();

                foreach ($designs as $design) {
                    // Create order item design attachment
                    OrderItemDesign::create([
                        'order_item_id' => $orderItem->id,
                        'design_id' => $design->design_id,
                        'notes' => $design->notes,
                        'priority' => $design->priority
                    ]);
                }
            }

            // Clear cart after successful order creation
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            session()->flash('success', 'Order created successfully! Order #' . $order->order_number);
            $this->hideCheckout();
            $this->loadCart();
            $this->dispatch('cartUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to create order. Please try again.');
        }
    }

    public function removeDesignFromProduct($productId, $designId)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $productDesign = ProductDesign::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('design_id', $designId)
            ->first();

        if ($productDesign) {
            $productDesign->delete();
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function checkout()
    {
        $this->showCheckout();
    }

    public function bookAppointment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to book an appointment');
            return;
        }

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        // First create an order from the cart
        $order = $this->createOrderFromCart();

        if ($order) {
            // Redirect to appointment booking page with the created order ID
            return redirect()->route('appointments.create', ['order_id' => $order->id]);
        } else {
            session()->flash('error', 'Failed to create order. Please try again.');
            return;
        }
    }

    public function createOrderFromCart()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to create an order');
            return;
        }

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

            $orderService = new OrderService();

            // Prepare items data for OrderService
            $items = [];
            foreach ($cartItems as $cartItem) {
                $items[] = [
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'selected_options' => $cartItem->selected_options,
                    'notes' => $cartItem->notes
                ];
            }

            $orderData = [
                'phone' => $user->phone ?? '',
                'shipping_address' => $user->address ?? '',
                'billing_address' => $user->address ?? '',
                'notes' => 'Order created from cart for appointment booking',
                'items' => $items
            ];

            $order = $orderService->createOrder($orderData);

            // Copy design attachments to order items
            foreach ($order->items as $orderItem) {
                $designs = ProductDesign::where('user_id', $user->id)
                    ->where('product_id', $orderItem->product_id)
                    ->get();

                foreach ($designs as $design) {
                    // Create order item design attachment
                    OrderItemDesign::create([
                        'order_item_id' => $orderItem->id,
                        'design_id' => $design->design_id,
                        'notes' => $design->notes,
                        'priority' => $design->priority
                    ]);
                }
            }

            // Clear cart after successful order creation
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            session()->flash('success', 'Order created successfully! Order #' . $order->order_number);
            $this->loadCart();
            $this->dispatch('cartUpdated');

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to create order. Please try again.');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
