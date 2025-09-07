<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\DesignFavorite;
use App\Models\DesignCollection;
use App\Models\DesignCollectionItem;
use App\Models\Appointment;
use App\Models\Order;
use App\Services\DesignApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignController extends Controller
{
    /**
     * Display a listing of designs
     */
    public function index(Request $request): JsonResponse
    {
        $query = Design::active();

        // Apply filters
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $designs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $designs->items(),
            'pagination' => [
                'current_page' => $designs->currentPage(),
                'last_page' => $designs->lastPage(),
                'per_page' => $designs->perPage(),
                'total' => $designs->total(),
            ]
        ]);
    }

    /**
     * Search designs
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
            'category' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = $request->get('q');
        $category = $request->get('category');
        $perPage = $request->get('per_page', 20);

        // Try to sync from API first
        $apiService = new DesignApiService();
        if ($category) {
            $apiService->getDesignsByCategory($category, ['q' => $query, 'limit' => $perPage]);
        } else {
            $apiService->searchDesigns($query, ['limit' => $perPage]);
        }

        // Now search in local database
        $dbQuery = Design::active()->search($query);
        if ($category) {
            $dbQuery->byCategory($category);
        }

        $designs = $dbQuery->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'query' => $query,
            'data' => $designs->items(),
            'pagination' => [
                'current_page' => $designs->currentPage(),
                'last_page' => $designs->lastPage(),
                'per_page' => $designs->perPage(),
                'total' => $designs->total(),
            ]
        ]);
    }

    /**
     * Get design categories
     */
    public function categories(): JsonResponse
    {
        $categories = Design::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified design
     */
    public function show(Design $design): JsonResponse
    {
        if (!$design->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $design
        ]);
    }

    /**
     * Sync designs from external API
     */
    public function sync(Request $request): JsonResponse
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:1000',
            'category' => 'nullable|string',
            'search' => 'nullable|string'
        ]);

        try {
            $apiService = new DesignApiService();
            $limit = $request->get('limit', 100);
            $category = $request->get('category');
            $search = $request->get('search');

            if ($search) {
                $result = $apiService->searchDesigns($search, ['limit' => $limit]);
            } elseif ($category) {
                $result = $apiService->getDesignsByCategory($category, ['limit' => $limit]);
            } else {
                $result = $apiService->syncDesigns($limit);
            }

            if ($result === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to sync designs from API'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Designs synced successfully',
                'synced_count' => is_numeric($result) ? $result : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error syncing designs: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // USER DESIGN FAVORITES
    // ========================================

    /**
     * Add design to user favorites
     */
    public function addToFavorites(Request $request, Design $design): JsonResponse
    {
        $user = Auth::user();

        if (!$design->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found'
            ], 404);
        }

        $favorite = DesignFavorite::updateOrCreate(
            [
                'user_id' => $user->id,
                'design_id' => $design->id
            ],
            [
                'notes' => $request->get('notes')
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Design added to favorites',
            'data' => $favorite
        ]);
    }

    /**
     * Remove design from user favorites
     */
    public function removeFromFavorites(Design $design): JsonResponse
    {
        $user = Auth::user();

        $deleted = DesignFavorite::where('user_id', $user->id)
            ->where('design_id', $design->id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Design removed from favorites'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Design was not in favorites'
        ], 404);
    }

    /**
     * Get user's favorite designs
     */
    public function getFavorites(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 20);

        $favorites = $user->designFavorites()
            ->active()
            ->orderBy('design_favorites.created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $favorites->items(),
            'pagination' => [
                'current_page' => $favorites->currentPage(),
                'last_page' => $favorites->lastPage(),
                'per_page' => $favorites->perPage(),
                'total' => $favorites->total(),
            ]
        ]);
    }

    // ========================================
    // USER DESIGN COLLECTIONS
    // ========================================

    /**
     * Create a new design collection
     */
    public function createCollection(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $user = Auth::user();

        $collection = DesignCollection::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->get('is_public', false),
            'color' => $request->color
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Collection created successfully',
            'data' => $collection
        ], 201);
    }

    /**
     * Get user's design collections
     */
    public function getCollections(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 20);

        $collections = $user->designCollections()
            ->withCount('designs')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $collections->items(),
            'pagination' => [
                'current_page' => $collections->currentPage(),
                'last_page' => $collections->lastPage(),
                'per_page' => $collections->perPage(),
                'total' => $collections->total(),
            ]
        ]);
    }

    /**
     * Get specific collection
     */
    public function getCollection(DesignCollection $collection): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns the collection or it's public
        if ($collection->user_id !== $user->id && !$collection->is_public) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        }

        $collection->load(['designs' => function ($query) {
            $query->active()->orderBy('design_collection_items.added_at', 'desc');
        }]);

        return response()->json([
            'success' => true,
            'data' => $collection
        ]);
    }

    /**
     * Update collection
     */
    public function updateCollection(Request $request, DesignCollection $collection): JsonResponse
    {
        $user = Auth::user();

        if ($collection->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $collection->update($request->only(['name', 'description', 'is_public', 'color']));

        return response()->json([
            'success' => true,
            'message' => 'Collection updated successfully',
            'data' => $collection
        ]);
    }

    /**
     * Delete collection
     */
    public function deleteCollection(DesignCollection $collection): JsonResponse
    {
        $user = Auth::user();

        if ($collection->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $collection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collection deleted successfully'
        ]);
    }

    /**
     * Add design to collection
     */
    public function addDesignToCollection(Request $request, DesignCollection $collection, Design $design): JsonResponse
    {
        $user = Auth::user();

        if ($collection->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!$design->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found'
            ], 404);
        }

        $item = DesignCollectionItem::updateOrCreate(
            [
                'collection_id' => $collection->id,
                'design_id' => $design->id
            ],
            [
                'notes' => $request->get('notes'),
                'added_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Design added to collection',
            'data' => $item
        ]);
    }

    /**
     * Remove design from collection
     */
    public function removeDesignFromCollection(DesignCollection $collection, Design $design): JsonResponse
    {
        $user = Auth::user();

        if ($collection->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $deleted = DesignCollectionItem::where('collection_id', $collection->id)
            ->where('design_id', $design->id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Design removed from collection'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Design was not in collection'
        ], 404);
    }

    // ========================================
    // DESIGN INTEGRATION WITH APPOINTMENTS
    // ========================================

    /**
     * Link design to appointment
     */
    public function linkToAppointment(Request $request, Design $design, Appointment $appointment): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns the appointment
        if ($appointment->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!$design->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found'
            ], 404);
        }

        $appointment->designs()->syncWithoutDetaching([
            $design->id => [
                'notes' => $request->get('notes', ''),
                'priority' => $request->get('priority', 1)
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Design linked to appointment'
        ]);
    }

    /**
     * Unlink design from appointment
     */
    public function unlinkFromAppointment(Design $design, Appointment $appointment): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns the appointment
        if ($appointment->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $appointment->designs()->detach($design->id);

        return response()->json([
            'success' => true,
            'message' => 'Design unlinked from appointment'
        ]);
    }

    // ========================================
    // DESIGN INTEGRATION WITH ORDERS
    // ========================================

    /**
     * Link design to order
     */
    public function linkToOrder(Request $request, Design $design, Order $order): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns the order
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!$design->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found'
            ], 404);
        }

        // Link design to all products in the order
        $productIds = $order->orderItems()->pluck('product_id')->unique();

        foreach ($productIds as $productId) {
            DB::table('product_designs')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'design_id' => $design->id
                ],
                [
                    'notes' => $request->get('notes', ''),
                    'priority' => $request->get('priority', 1),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Design linked to order'
        ]);
    }

    /**
     * Unlink design from order
     */
    public function unlinkFromOrder(Design $design, Order $order): JsonResponse
    {
        $user = Auth::user();

        // Check if user owns the order
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get all product IDs from the order
        $productIds = $order->orderItems()->pluck('product_id')->unique();

        // Remove design from all products in the order
        DB::table('product_designs')
            ->where('user_id', $user->id)
            ->where('design_id', $design->id)
            ->whereIn('product_id', $productIds)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Design unlinked from order'
        ]);
    }

    // ========================================
    // DESIGN HISTORY
    // ========================================

    /**
     * Get user's design interaction history
     */
    public function getDesignHistory(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 20);

        // Get designs from favorites, collections, appointments, and orders
        $favoriteDesigns = $user->designFavorites()->pluck('design_id');
        $collectionDesigns = $user->designCollections()->with('designs')->get()->pluck('designs')->flatten()->pluck('id');
        $appointmentDesigns = $user->appointments()->with('designs')->get()->pluck('designs')->flatten()->pluck('id');

        // Get designs from user's orders
        $orderDesigns = DB::table('product_designs')
            ->where('user_id', $user->id)
            ->pluck('design_id');

        $allDesignIds = $favoriteDesigns
            ->merge($collectionDesigns)
            ->merge($appointmentDesigns)
            ->merge($orderDesigns)
            ->unique();

        $designs = Design::whereIn('id', $allDesignIds)
            ->active()
            ->with(['favorites' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $designs->items(),
            'pagination' => [
                'current_page' => $designs->currentPage(),
                'last_page' => $designs->lastPage(),
                'per_page' => $designs->perPage(),
                'total' => $designs->total(),
            ]
        ]);
    }
}
