<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews with filtering options
     */
    public function index(Request $request): View
    {
        $query = Review::with(['product:id,name,name_ar,image_url', 'user:id,name,email', 'order:id,order_number'])
            ->where('is_verified_purchase', true)
            ->orderBy('created_at', 'desc');

        // Filter by product if provided
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by rating if provided
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->whereNull('is_approved');
            } elseif ($request->status === 'rejected') {
                $query->where('is_approved', false);
            }
        }


        // Search by user name or product name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('name_ar', 'like', "%{$search}%");
                });
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        // Get products for filter dropdown
        $products = Product::select('id', 'name', 'name_ar')->orderBy('name')->get();

        // Get statistics
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending' => Review::where('is_approved', false)->count(),
            'verified' => Review::where('is_verified_purchase', true)->count(),
            'average_rating' => Review::where('is_approved', true)->avg('rating') ?? 0,
        ];

        return view('admin.dashboard.reviews.index', compact('reviews', 'products', 'stats'));
    }

    /**
     * Display the specified review
     */
    public function show(Review $review): View
    {
        $review->load(['product', 'user', 'order', 'orderItem']);
        
        return view('admin.dashboard.reviews.show', compact('review'));
    }

    /**
     * Approve a review
     */
    public function approve(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);
        
        return redirect()->back()->with('success', 'تم قبول التقييم بنجاح');
    }

    /**
     * Reject a review
     */
    public function reject(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => false]);
        
        return redirect()->back()->with('success', 'تم رفض التقييم بنجاح');
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'comment_ar' => 'nullable|string|max:1000',
            'is_approved' => 'boolean',
            'is_verified_purchase' => 'boolean',
        ]);

        $review->update($request->only([
            'rating', 'comment', 'comment_ar', 'is_approved', 'is_verified_purchase'
        ]));

        return redirect()->route('admin.reviews.index')->with('success', 'تم تحديث التقييم بنجاح');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')->with('success', 'تم حذف التقييم بنجاح');
    }

    /**
     * Bulk approve reviews
     */
    public function bulkApprove(Request $request): RedirectResponse
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $request->review_ids)->update(['is_approved' => true]);
        
        return redirect()->back()->with('success', 'تم قبول التقييمات المحددة بنجاح');
    }

    /**
     * Bulk reject reviews
     */
    public function bulkReject(Request $request): RedirectResponse
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $request->review_ids)->update(['is_approved' => false]);
        
        return redirect()->back()->with('success', 'تم رفض التقييمات المحددة بنجاح');
    }

    /**
     * Bulk delete reviews
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $request->review_ids)->delete();
        
        return redirect()->back()->with('success', 'تم حذف التقييمات المحددة بنجاح');
    }

    /**
     * Show reviews for a specific product
     */
    public function productReviews(Product $product): View
    {
        $reviews = $product->reviews()
            ->with(['user:id,name', 'order:id,order_number'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.dashboard.reviews.product-reviews', compact('product', 'reviews'));
    }
}
