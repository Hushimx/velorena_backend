<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Services\DesignApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
}
