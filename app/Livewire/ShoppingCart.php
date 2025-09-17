<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\CartDesign;
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
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;
    public $itemCount = 0;
    public $showCheckoutForm = false;
    public $checkoutData = [
        'phone' => '',
        'shipping_address' => '',
        'billing_address' => '',
        'notes' => ''
    ];

    // Cart Design properties
    public $showCartDesignModal = false;
    public $cartDesigns = [];
    public $showDesignSelector = false;

    // Login modal properties
    public $showLoginModal = false;

    protected $listeners = [
        'cartUpdated' => 'loadCart',
        'save-cart-design' => 'saveCartDesign',
        'close-cart-design-modal' => 'closeCartDesignModal',
        'close-modal' => 'closeLoginModal'
    ];

    public function mount()
    {
        $this->loadCart();
        $this->loadCartDesigns();
    }

    public function loadCart()
    {
        if (!Auth::check()) {
            $this->cartItems = [];
            $this->subtotal = 0;
            $this->tax = 0;
            $this->total = 0;
            $this->itemCount = 0;
            return;
        }

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['product.options.values', 'designs.design'])
            ->get();

        $this->cartItems = [];
        $this->subtotal = 0;
        $this->tax = 0;
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

            $this->subtotal += $item->total_price;
        }

        // Calculate tax (assuming 15% VAT rate)
        $this->tax = $this->subtotal * 0.15;

        // Calculate total
        $this->total = $this->subtotal + $this->tax;

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
        if (!Auth::check()) {
            $this->showLoginModal = true;
            return;
        }

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        // Create order directly without form
        $this->createOrderDirectly();
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
            $this->showLoginModal = true;
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
            
            // Clear cart designs after successful order creation
            CartDesign::where('user_id', $user->id)->delete();

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

    public function createOrderDirectly()
    {
        if (!Auth::check()) {
            $this->showLoginModal = true;
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

            // Use user's default data for order creation
            $orderData = [
                'phone' => $user->phone ?? '',
                'shipping_address' => $user->address ?? '',
                'billing_address' => $user->address ?? '',
                'notes' => 'Order created directly from cart checkout',
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
            
            // Clear cart designs after successful order creation
            CartDesign::where('user_id', $user->id)->delete();

            DB::commit();

            session()->flash('success', 'Order created successfully! Order #' . $order->order_number);
            $this->loadCart();
            $this->dispatch('cartUpdated');

            // Redirect to orders page
            return redirect()->route('user.orders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to create order. Please try again.');
        }
    }

    public function removeDesignFromProduct($productId, $designId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'يجب تسجيل الدخول لحذف التصاميم');
            return;
        }

        try {
            $user = Auth::user();
            $productDesign = ProductDesign::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->where('design_id', $designId)
                ->first();

            if ($productDesign) {
                $design = $productDesign->design;
                $designTitle = $design ? $design->title : 'تصميم غير معروف';
                $productDesign->delete();
                
                $this->loadCart();
                $this->dispatch('cartUpdated');
                
                session()->flash('success', "تم حذف التصميم '{$designTitle}' من المنتج بنجاح!");
                
                Log::info('Design removed from product successfully', [
                    'product_id' => $productId,
                    'design_id' => $designId,
                    'design_title' => $designTitle,
                    'user_id' => $user->id
                ]);
            } else {
                session()->flash('error', 'التصميم غير موجود في هذا المنتج');
                Log::warning('Attempted to remove non-existent design from product', [
                    'product_id' => $productId,
                    'design_id' => $designId,
                    'user_id' => $user->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to remove design from product', [
                'error' => $e->getMessage(),
                'product_id' => $productId,
                'design_id' => $designId,
                'user_id' => Auth::id()
            ]);
            session()->flash('error', 'فشل في حذف التصميم من المنتج: ' . $e->getMessage());
        }
    }

    public function checkout()
    {
        $this->showCheckout();
    }

    public function bookAppointment()
    {
        if (!Auth::check()) {
            $this->showLoginModal = true;
            return;
        }

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty');
            return;
        }

        // Redirect directly to appointment booking page without creating an order
        return redirect()->route('appointments.create');
    }

    public function createOrderFromCart()
    {
        if (!Auth::check()) {
            $this->showLoginModal = true;
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
            
            // Clear cart designs after successful order creation
            CartDesign::where('user_id', $user->id)->delete();

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

    // Old product design methods removed - now using cart-wide designs

    // Cart Design Methods
    public function loadCartDesigns()
    {
        if (Auth::check()) {
            // Use a more efficient query structure to avoid memory issues
            $this->cartDesigns = CartDesign::where('user_id', Auth::id())
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(50) // Limit to prevent memory issues
                ->get()
                ->toArray();
        } else {
            // For testing, get all guest designs for now
            // In production, this should use session ID
            $sessionId = session()->getId();
            $this->cartDesigns = CartDesign::whereNull('user_id')
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(50) // Limit to prevent memory issues
                ->get()
                ->toArray();
        }
        
        Log::info('Loaded cart designs', [
            'count' => count($this->cartDesigns),
            'user_id' => Auth::check() ? Auth::id() : null,
            'session_id' => session()->getId()
        ]);
    }

    public function openCartDesignModal()
    {
        $this->showCartDesignModal = true;
        $this->showDesignSelector = true;
        
        // Emit event for JavaScript to initialize the design studio
        $this->dispatch('design-modal-opened');
    }

    public function closeCartDesignModal()
    {
        $this->showCartDesignModal = false;
        $this->showDesignSelector = false;
    }

    public function saveCartDesign($data)
    {
        try {
            Log::info('saveCartDesign called', ['data' => $data]);
            
            $title = $data['title'] ?? 'My Design';
            $designData = $data['designData'] ?? [];
            $imageUrl = $data['imageUrl'] ?? null;
            
            Log::info('Processing cart design save', [
                'title' => $title,
                'has_design_data' => !empty($designData),
                'has_image_url' => !empty($imageUrl),
                'user_id' => Auth::check() ? Auth::id() : null,
                'session_id' => Auth::check() ? null : session()->getId()
            ]);
            
            // Check for duplicate designs (same title and image URL within last 5 minutes)
            $userId = Auth::check() ? Auth::id() : null;
            $sessionId = Auth::check() ? null : session()->getId();
            
            $recentDuplicate = CartDesign::where('title', $title)
                ->where('image_url', $imageUrl)
                ->where('is_active', true)
                ->where(function($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })
                ->where('created_at', '>=', now()->subMinutes(5))
                ->first();
            
            if ($recentDuplicate) {
                Log::info('Duplicate design detected, skipping save', [
                    'existing_id' => $recentDuplicate->id,
                    'title' => $title
                ]);
                session()->flash('info', 'تم حفظ التصميم مسبقاً!');
                $this->closeCartDesignModal();
                return;
            }
            
            // Create thumbnail (smaller version)
            $thumbnailUrl = $imageUrl; // For now, use same image
            
            $cartDesign = CartDesign::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'title' => $title,
                'design_data' => $designData,
                'image_url' => $imageUrl,
                'thumbnail_url' => $thumbnailUrl,
                'is_active' => true
            ]);

            Log::info('Cart design saved successfully', ['design_id' => $cartDesign->id]);

            $this->loadCartDesigns();
            $this->closeCartDesignModal();
            
            session()->flash('success', 'تم حفظ التصميم بنجاح!');
            
        } catch (\Exception $e) {
            Log::error('Failed to save cart design', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            session()->flash('error', 'فشل في حفظ التصميم: ' . $e->getMessage());
        }
    }

    public function deleteCartDesign($designId)
    {
        try {
            $query = CartDesign::where('id', $designId);
            
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', session()->getId());
            }
            
            $design = $query->first();
            
            if ($design) {
                $designTitle = $design->title;
                $design->delete();
                $this->loadCartDesigns();
                $this->dispatch('cartUpdated');
                session()->flash('success', "تم حذف التصميم '{$designTitle}' بنجاح!");
                
                Log::info('Cart design deleted successfully', [
                    'design_id' => $designId,
                    'design_title' => $designTitle,
                    'user_id' => Auth::check() ? Auth::id() : null
                ]);
            } else {
                session()->flash('error', 'التصميم غير موجود أو لا يمكن حذفه');
                Log::warning('Attempted to delete non-existent cart design', [
                    'design_id' => $designId,
                    'user_id' => Auth::check() ? Auth::id() : null
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to delete cart design', [
                'error' => $e->getMessage(),
                'design_id' => $designId,
                'user_id' => Auth::check() ? Auth::id() : null
            ]);
            session()->flash('error', 'فشل في حذف التصميم: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple cart designs at once
     */
    public function deleteMultipleCartDesigns($designIds)
    {
        if (!is_array($designIds)) {
            $designIds = [$designIds];
        }

        try {
            $deletedCount = 0;
            $deletedTitles = [];

            foreach ($designIds as $designId) {
                $query = CartDesign::where('id', $designId);
                
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', session()->getId());
                }
                
                $design = $query->first();
                
                if ($design) {
                    $deletedTitles[] = $design->title;
                    $design->delete();
                    $deletedCount++;
                }
            }

            if ($deletedCount > 0) {
                $this->loadCartDesigns();
                $this->dispatch('cartUpdated');
                session()->flash('success', "تم حذف {$deletedCount} تصميم بنجاح!");
                
                Log::info('Multiple cart designs deleted successfully', [
                    'deleted_count' => $deletedCount,
                    'design_ids' => $designIds,
                    'deleted_titles' => $deletedTitles,
                    'user_id' => Auth::check() ? Auth::id() : null
                ]);
            } else {
                session()->flash('error', 'لم يتم العثور على تصاميم للحذف');
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to delete multiple cart designs', [
                'error' => $e->getMessage(),
                'design_ids' => $designIds,
                'user_id' => Auth::check() ? Auth::id() : null
            ]);
            session()->flash('error', 'فشل في حذف التصاميم: ' . $e->getMessage());
        }
    }

    /**
     * Clear all cart designs for the current user
     */
    public function clearAllCartDesigns()
    {
        try {
            $query = CartDesign::where('is_active', true);
            
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', session()->getId());
            }
            
            $designs = $query->get();
            $count = $designs->count();
            
            if ($count > 0) {
                $query->delete();
                $this->loadCartDesigns();
                $this->dispatch('cartUpdated');
                session()->flash('success', "تم حذف جميع التصاميم ({$count} تصميم) بنجاح!");
                
                Log::info('All cart designs cleared successfully', [
                    'deleted_count' => $count,
                    'user_id' => Auth::check() ? Auth::id() : null
                ]);
            } else {
                session()->flash('info', 'لا توجد تصاميم للحذف');
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to clear all cart designs', [
                'error' => $e->getMessage(),
                'user_id' => Auth::check() ? Auth::id() : null
            ]);
            session()->flash('error', 'فشل في حذف جميع التصاميم: ' . $e->getMessage());
        }
    }

    public function closeLoginModal()
    {
        $this->showLoginModal = false;
    }

    public function updatedShowLoginModal()
    {
        // When the modal is closed, reload the cart in case user logged in
        if (!$this->showLoginModal && Auth::check()) {
            $this->loadCart();
        }
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
