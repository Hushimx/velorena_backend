<?php

namespace App\Http\Controllers\Api;

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

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Get user's orders
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
     * Create a new order
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
    public function addItem(AddOrderItemRequest $request, Order $order): JsonResponse
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

            // Check if order can be modified
            if (!$this->orderService->canModifyOrder($order)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot modify order that is not pending'
                ], 400);
            }

            $updatedOrder = $this->orderService->addItemToOrder($order, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Item added to order successfully',
                'data' => new OrderResource($updatedOrder)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from order
     */
    public function removeItem(RemoveOrderItemRequest $request, Order $order): JsonResponse
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

            // Check if order can be modified
            if (!$this->orderService->canModifyOrder($order)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot modify order that is not pending'
                ], 400);
            }

            $updatedOrder = $this->orderService->removeItemFromOrder($order, $request->validated()['item_id']);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from order successfully',
                'data' => new OrderResource($updatedOrder)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
