<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="Qaads API",
 *     version="1.0.0",
 *     description="API for Qaads Backend - Orders and Appointments Management",
 *     @OA\Contact(
 *         email="admin@Qaads.com"
 *     )
 * )
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\AddOrderItemRequest;
use App\Http\Requests\Api\RemoveOrderItemRequest;
use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\Api\OrderCollection;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API Endpoints for order management"
 * )
 */
class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Get user's orders
     * 
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get user's orders",
     *     description="Retrieve all orders for the authenticated user with filtering, sorting, and pagination",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by order status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "confirmed", "shipped", "delivered", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in order number or phone",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string", default="created_at", enum={"created_at", "order_number", "total", "status"})
     *     ),
     *     @OA\Parameter(
     *         name="sort_order",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", default="desc", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15, minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request): OrderCollection
    {
        $user = Auth::user();

        $query = Order::where('user_id', $user->id)
            ->with(['items.product', 'items.product.options.values']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        return new OrderCollection($orders);
    }

    /**
     * Get specific order details
     * 
     * @OA\Get(
     *     path="/api/orders/{order}",
     *     summary="Get order details",
     *     description="Retrieve detailed information about a specific order",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order retrieved successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to order"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function show(Order $order): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns this order
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        $order->load(['items.product', 'items.product.options.values']);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => new OrderResource($order)
        ]);
    }

    /**
     * Initiate payment for an order
     * 
     * @OA\Post(
     *     path="/api/orders/{order}/payment",
     *     summary="Initiate payment for order",
     *     description="Create a payment charge for the specified order",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment initiated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment initiated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Order cannot be paid"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to order"
     *     )
     * )
     */
    public function initiatePayment(Order $order): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns this order
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order'
            ], 403);
        }

        // Auto-confirm order if it's pending (for immediate payment)
        if ($order->status === 'pending') {
            $order->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        }

        // Check if order can make payment
        if (!$order->canMakePayment()) {
            Log::warning('Order cannot make payment', [
                'order_id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->getPaymentStatus()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be paid at this time. Order status: ' . $order->status . ', Payment status: ' . $order->getPaymentStatus()
            ], 400);
        }

        try {
            // Validate order total
            if (!$order->total || $order->total <= 0) {
                Log::error('Invalid order total for payment', [
                    'order_id' => $order->id,
                    'total' => $order->total
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order total for payment'
                ], 400);
            }

            // Use the existing TapPaymentService
            $tapPaymentService = app(\App\Services\TapPaymentService::class);
            
            // Prepare customer phone number
            $customerPhone = $order->phone ?? $user->phone ?? '';
            $cleanPhone = preg_replace('/\D/', '', $customerPhone);
            
            // Ensure we have a valid phone number
            if (empty($cleanPhone) || strlen($cleanPhone) < 9) {
                $cleanPhone = '123456789'; // Fallback phone number
            }
            
            // Prepare customer name
            $customerName = $user->full_name ?? $user->company_name ?? 'Customer';
            if (empty($customerName)) {
                $customerName = 'Customer';
            }
            
            // Split name into first and last name
            $nameParts = explode(' ', trim($customerName), 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
            
            $chargeData = [
                'amount' => $order->total,
                'currency' => 'SAR',
                'customer' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $user->email,
                    'phone' => [
                        'country_code' => '966',
                        'number' => $cleanPhone
                    ]
                ],
                'source' => [
                    'id' => 'src_all'
                ],
                'redirect' => [
                    'url' => config('app.url') . '/payment/success?source=mobile&test_mode=' . (config('services.tap.test_mode', true) ? 'true' : 'false')
                ],
                'post' => [
                    'url' => config('app.url') . '/api/webhooks/tap'
                ],
                'description' => "Payment for Order #{$order->order_number}",
                'reference' => [
                    'order' => $order->order_number
                ],
                'receipt' => [
                    'email' => true,
                    'sms' => true
                ],
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $order->user_id
                ]
            ];

            Log::info('Initiating Tap payment', [
                'order_id' => $order->id,
                'amount' => $order->total,
                'charge_data' => $chargeData
            ]);

            $result = $tapPaymentService->createCharge($chargeData);

            if ($result['success']) {
                // Create payment record
                $payment = \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'charge_id' => $result['charge_id'],
                    'amount' => $order->total,
                    'currency' => 'SAR',
                    'status' => 'pending',
                    'payment_method' => 'tap',
                    'gateway_response' => $result['data']
                ]);

                Log::info('Payment initiated successfully', [
                    'payment_id' => $payment->id,
                    'charge_id' => $result['charge_id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated successfully',
                    'data' => [
                        'payment_id' => $payment->id,
                        'charge_id' => $result['charge_id'],
                        'payment_url' => $result['payment_url'],
                        'order' => new OrderResource($order)
                    ]
                ]);
            } else {
                Log::error('Tap payment charge failed', [
                    'order_id' => $order->id,
                    'error' => $result['error'] ?? 'Unknown error',
                    'result' => $result
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment charge with payment gateway',
                    'error' => $result['error'] ?? 'Payment gateway error',
                    'details' => config('app.debug') ? $result : null
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment initiation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $order->id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment service error: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Create a new order
     * 
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create new order",
     *     description="Create a new order with products and options",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"products"},
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2, minimum=1),
     *                     @OA\Property(property="options", type="array", @OA\Items(type="integer"), example={1, 3})
     *                 )
     *             ),
     *             @OA\Property(property="shipping_address", type="string", example="123 Main St, City"),
     *             @OA\Property(property="billing_address", type="string", example="123 Main St, City"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="notes", type="string", example="Please deliver in the morning")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => new OrderResource($order)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an order
     * 
     * @OA\Delete(
     *     path="/api/orders/{order}",
     *     summary="Delete order",
     *     description="Delete a specific order (only if status is pending)",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to order"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function destroy(Order $order): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user owns this order
            if ($order->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to order'
                ], 403);
            }

            // Check if order can be deleted
            if (!$this->orderService->canDeleteOrder($order)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete order that is not pending'
                ], 400);
            }

            $this->orderService->deleteOrder($order);

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to existing order
     */

}
