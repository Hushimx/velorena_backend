<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Get reviews for a specific product
     */
    public function index(Request $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);
            
            $query = $product->approvedReviews()
                ->with(['user:id,name'])
                ->orderBy('created_at', 'desc');

            // Filter by rating if provided
            if ($request->has('rating') && $request->rating > 0) {
                $query->where('rating', $request->rating);
            }

            // Filter by verified purchase if provided
            if ($request->has('verified') && $request->verified) {
                $query->where('is_verified_purchase', true);
            }

            $perPage = $request->get('per_page', 10);
            $reviews = $query->paginate($perPage);

            // Get rating statistics
            $ratingStats = [
                'average' => round($product->average_rating, 1),
                'total' => $product->review_count,
                'distribution' => $product->rating_distribution
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'reviews' => $reviews->items(),
                    'pagination' => [
                        'current_page' => $reviews->currentPage(),
                        'last_page' => $reviews->lastPage(),
                        'per_page' => $reviews->perPage(),
                        'total' => $reviews->total(),
                        'has_more' => $reviews->hasMorePages()
                    ],
                    'rating_stats' => $ratingStats
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'comment_ar' => 'nullable|string|max:1000',
                'order_id' => 'nullable|exists:orders,id',
                'order_item_id' => 'nullable|exists:order_items,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $productId = $request->product_id;

            // Check if user already reviewed this product
            $existingReview = Review::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this product'
                ], 400);
            }

            // Check if user has purchased this product with a completed order
            $completedOrder = Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereHas('orderItems', function($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->with(['orderItems' => function($query) use ($productId) {
                    $query->where('product_id', $productId);
                }])
                ->first();

            if (!$completedOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only review products you have purchased and received (order status must be completed)'
                ], 403);
            }

            $orderItem = $completedOrder->orderItems->first();

            $review = Review::create([
                'product_id' => $productId,
                'user_id' => $user->id,
                'order_id' => $completedOrder->id,
                'order_item_id' => $orderItem->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'comment_ar' => $request->comment_ar,
                'is_approved' => false, // Reviews need admin approval
                'is_verified_purchase' => true, // Always true since we verified the purchase
                'metadata' => $request->metadata ?? []
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully and is pending approval',
                'data' => [
                    'review' => $review->load('user:id,name'),
                    'is_pending_approval' => true
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's reviews
     */
    public function userReviews(Request $request)
    {
        try {
            $user = Auth::user();
            
            $query = $user->reviews()
                ->with(['product:id,name,name_ar,image_url'])
                ->orderBy('created_at', 'desc');

            $perPage = $request->get('per_page', 10);
            $reviews = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'reviews' => $reviews->items(),
                    'pagination' => [
                        'current_page' => $reviews->currentPage(),
                        'last_page' => $reviews->lastPage(),
                        'per_page' => $reviews->perPage(),
                        'total' => $reviews->total(),
                        'has_more' => $reviews->hasMorePages()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user's own review
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $review = Review::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you do not have permission to edit it'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'sometimes|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
                'comment_ar' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review->update($request->only(['rating', 'comment', 'comment_ar']));

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review->load('user:id,name')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user's own review
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $review = Review::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you do not have permission to delete it'
                ], 404);
            }

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user can review a product
     */
    public function canReview(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            
            // Check if user already reviewed this product
            $existingReview = Review::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'can_review' => false,
                        'reason' => 'already_reviewed',
                        'message' => 'You have already reviewed this product'
                    ]
                ]);
            }

            // Check if user has purchased this product with a completed order
            $hasCompletedPurchase = Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereHas('orderItems', function($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->exists();

            if (!$hasCompletedPurchase) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'can_review' => false,
                        'reason' => 'not_purchased',
                        'message' => 'You can only review products you have purchased and received (order status must be completed)'
                    ]
                ]);
            }

            // Check if user has completed orders for this product
            $completedOrders = Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereHas('orderItems', function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'can_review' => true,
                    'has_verified_purchase' => $completedOrders > 0,
                    'completed_orders_count' => $completedOrders
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check review eligibility',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
