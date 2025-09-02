<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     * 
     * @OA\Get(
     *     path="/api/categories",
     *     operationId="getCategories",
     *     tags={"Categories"},
     *     summary="Get all active categories",
     *     description="Retrieve a paginated list of active categories with optional search functionality. Categories are sorted by sort_order and then by name.",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search categories by name in English or Arabic. Performs partial matching on category names.",
     *         required=false,
     *         @OA\Schema(type="string", example="business")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination. Starts from 1.",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of categories to return per page. Minimum: 1, Maximum: 100, Default: 15.",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Business Cards"),
     *                         @OA\Property(property="name_ar", type="string", example="بطاقات عمل"),
     *                         @OA\Property(property="description", type="string", example="Professional business cards for companies and individuals"),
     *                         @OA\Property(property="description_ar", type="string", example="بطاقات عمل احترافية للشركات والأفراد"),
     *                         @OA\Property(property="image", type="string", nullable=true),
     *                         @OA\Property(property="is_active", type="boolean", example=true),
     *                         @OA\Property(property="sort_order", type="integer", example=1),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-24T01:43:15.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-24T01:43:15.000000Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=5),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="to", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve categories"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::query()->where('is_active', true);

        // Search by name
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        // Get limit parameter with validation
        $limit = $request->get('limit', 15);
        $limit = min(max((int) $limit, 1), 100); // Ensure limit is between 1 and 100

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified category
     * 
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     operationId="getCategory",
     *     tags={"Categories"},
     *     summary="Get a specific category",
     *     description="Retrieve detailed information about a specific category",
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Business Cards"),
     *                 @OA\Property(property="name_ar", type="string", example="بطاقات عمل"),
     *                 @OA\Property(property="description", type="string", example="Professional business cards for companies and individuals"),
     *                 @OA\Property(property="description_ar", type="string", example="بطاقات عمل احترافية للشركات والأفراد"),
     *                 @OA\Property(property="image", type="string", nullable=true),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="sort_order", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-24T01:43:15.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-24T01:43:15.000000Z"),
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Standard Business Cards"),
     *                         @OA\Property(property="name_ar", type="string", example="بطاقات عمل قياسية"),
     *                         @OA\Property(property="base_price", type="string", example="50.00")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function show(Category $category): JsonResponse
    {
        $category->load(['products' => function ($query) {
            $query->where('is_active', true);
        }]);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}
