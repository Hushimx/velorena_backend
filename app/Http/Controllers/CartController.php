<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\CartDesign;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderDesign;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // ========================================
    // API ENDPOINTS FOR CART MANAGEMENT
    // ========================================

    /**
     * Get user's cart items with designs
     */
    public function getCartItems(Request $request): JsonResponse
    {
        $user = Auth::user();

        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['product.options.values'])
            ->get();

        $enhancedItems = [];
        $total = 0;

        foreach ($cartItems as $item) {
            // Update prices if not set
            if (!$item->unit_price || !$item->total_price) {
                $item->updatePrices();
            }

            // No more product-specific designs - designs are order-level now
            $designs = [];

            $enhancedItems[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'name_ar' => $item->product->name_ar,
                    'base_price' => $item->product->base_price,
                    'image' => $item->product->image_url ?? $item->product->image,
                    'options' => $item->product->options
                ],
                'quantity' => $item->quantity,
                'selected_options' => $item->selected_options ?? [],
                'notes' => $item->notes ?? '',
                'designs' => $designs,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price
            ];

            $total += $item->total_price;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $enhancedItems,
                'total' => $total,
                'item_count' => count($enhancedItems)
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'selected_options' => 'nullable|array',
            'notes' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Check if item already exists with same options
        $existingItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('selected_options', json_encode($request->selected_options ?? []))
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $request->quantity;
            $existingItem->updatePrices();

            return response()->json([
                'success' => true,
                'message' => 'Item quantity updated in cart',
                'data' => [
                    'cart_item_id' => $existingItem->id,
                    'quantity' => $existingItem->quantity,
                    'total_price' => $existingItem->total_price
                ]
            ]);
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'selected_options' => $request->selected_options ?? [],
                'notes' => $request->notes
            ]);

            $cartItem->updatePrices();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => [
                    'cart_item_id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price
                ]
            ]);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(Request $request, $cartItemId): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->updatePrices();

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully',
            'data' => [
                'cart_item_id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
                'total_price' => $cartItem->total_price
            ]
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cartItemId): JsonResponse
    {
        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clearCart(): JsonResponse
    {
        $user = Auth::user();
        CartItem::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    // Product design functionality removed - designs are now order-level only

    /**
     * Create order from cart with designs
     */
    public function createOrderFromCart(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'shipping_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $orderService = new OrderService();
            $orderData = [
                'user_id' => $user->id,
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'notes' => $request->notes,
                'status' => 'pending'
            ];

            $order = $orderService->createOrder($orderData);

            // Add cart items to order
            foreach ($cartItems as $cartItem) {
                $orderItemData = [
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'options' => $cartItem->selected_options, // Fixed: use 'options' instead of 'selected_options'
                    'notes' => $cartItem->notes
                ];

                $orderItem = OrderItem::create($orderItemData);

                // No more product-specific designs
            }

            // Copy cart designs to order
            $cartDesigns = CartDesign::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            foreach ($cartDesigns as $design) {
                OrderDesign::create([
                    'order_id' => $order->id,
                    'title' => $design->title,
                    'image_url' => $design->image_url,
                    'thumbnail_url' => $design->thumbnail_url,
                    'design_data' => $design->design_data,
                    'notes' => $design->notes ?? 'Design from cart',
                    'priority' => 1
                ]);
            }

            // Calculate order totals after adding all items
            $order->calculateTotals();

            // Clear cart after successful order creation
            CartItem::where('user_id', $user->id)->delete();
            
            // Clear cart designs after successful order creation
            CartDesign::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // WEB ROUTES (for Livewire components)
    // ========================================

    /**
     * Show cart page
     */
    public function index()
    {
        // Allow both authenticated and guest users to view the cart page
        return view('users.cart.index');
    }
}
