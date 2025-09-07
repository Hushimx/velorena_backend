<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductDesign;
use App\Models\Order;
use App\Models\OrderItem;
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
            ->with(['product.options.values', 'designs.design'])
            ->get();

        $enhancedItems = [];
        $total = 0;

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

            $enhancedItems[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'name_ar' => $item->product->name_ar,
                    'base_price' => $item->product->base_price,
                    'image' => $item->product->image,
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

    /**
     * Add design to cart item
     */
    public function addDesignToCartItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'design_id' => 'required|exists:designs,id',
            'notes' => 'nullable|string|max:1000',
            'priority' => 'nullable|integer|min:1|max:10'
        ]);

        $user = Auth::user();
        $design = \App\Models\Design::findOrFail($request->design_id);

        if (!$design->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found or inactive'
            ], 404);
        }

        // Verify product is in user's cart
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        }

        // Add design to product
        $productDesign = ProductDesign::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'design_id' => $request->design_id
            ],
            [
                'notes' => $request->notes,
                'priority' => $request->priority ?? 1
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Design added to cart item successfully',
            'data' => [
                'product_design_id' => $productDesign->id,
                'design' => [
                    'id' => $design->id,
                    'title' => $design->title,
                    'image_url' => $design->image_url,
                    'thumbnail_url' => $design->thumbnail_url
                ],
                'notes' => $productDesign->notes,
                'priority' => $productDesign->priority
            ]
        ]);
    }

    /**
     * Remove design from cart item
     */
    public function removeDesignFromCartItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'design_id' => 'required|exists:designs,id'
        ]);

        $user = Auth::user();

        $productDesign = ProductDesign::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('design_id', $request->design_id)
            ->first();

        if (!$productDesign) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found in cart item'
            ], 404);
        }

        $productDesign->delete();

        return response()->json([
            'success' => true,
            'message' => 'Design removed from cart item successfully'
        ]);
    }

    /**
     * Update design notes in cart item
     */
    public function updateDesignNotes(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'design_id' => 'required|exists:designs,id',
            'notes' => 'nullable|string|max:1000',
            'priority' => 'nullable|integer|min:1|max:10'
        ]);

        $user = Auth::user();

        $productDesign = ProductDesign::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('design_id', $request->design_id)
            ->first();

        if (!$productDesign) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found in cart item'
            ], 404);
        }

        $productDesign->update([
            'notes' => $request->notes,
            'priority' => $request->priority ?? $productDesign->priority
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Design notes updated successfully',
            'data' => [
                'notes' => $productDesign->notes,
                'priority' => $productDesign->priority
            ]
        ]);
    }

    /**
     * Get designs for specific cart item
     */
    public function getCartItemDesigns(Request $request, $productId): JsonResponse
    {
        $user = Auth::user();

        $designs = ProductDesign::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->with('design')
            ->orderBy('priority')
            ->get()
            ->filter(function ($productDesign) {
                return $productDesign->design !== null;
            })
            ->map(function ($productDesign) {
                return [
                    'id' => $productDesign->design->id,
                    'title' => $productDesign->design->title,
                    'description' => $productDesign->design->description,
                    'image_url' => $productDesign->design->image_url,
                    'thumbnail_url' => $productDesign->design->thumbnail_url,
                    'category' => $productDesign->design->category,
                    'notes' => $productDesign->notes,
                    'priority' => $productDesign->priority,
                    'attached_at' => $productDesign->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $designs
        ]);
    }

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
                    'selected_options' => $cartItem->selected_options,
                    'notes' => $cartItem->notes
                ];

                $orderItem = OrderItem::create($orderItemData);

                // Copy design attachments to order item
                $designs = ProductDesign::where('user_id', $user->id)
                    ->where('product_id', $cartItem->product_id)
                    ->get();

                foreach ($designs as $design) {
                    // Create order item design attachment
                    DB::table('order_item_designs')->insert([
                        'order_item_id' => $orderItem->id,
                        'design_id' => $design->design_id,
                        'notes' => $design->notes,
                        'priority' => $design->priority,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Clear cart after successful order creation
            CartItem::where('user_id', $user->id)->delete();

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
        return view('cart.index');
    }
}
