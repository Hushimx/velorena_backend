<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     * 
     * @OA\Get(
     *     path="/api/products",
     *     operationId="getProducts",
     *     tags={"Products"},
     *     summary="Get all active products",
     *     description="Retrieve a paginated list of active products with optional filtering by category and search functionality. Products include their options and values for customization.",
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter products by specific category ID. Only products from this category will be returned.",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search products by name in English or Arabic. Performs partial matching on product names.",
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
     *         description="Number of products to return per page. Minimum: 1, Maximum: 100, Default: 15.",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products retrieved successfully",
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
     *                         @OA\Property(property="category_id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Standard Business Cards"),
     *                         @OA\Property(property="name_ar", type="string", example="بطاقات عمل قياسية"),
     *                         @OA\Property(property="description", type="string", example="Professional business cards with various customization options"),
     *                         @OA\Property(property="description_ar", type="string", example="بطاقات عمل احترافية مع خيارات تخصيص متنوعة"),
     *                         @OA\Property(property="image", type="string", nullable=true),
     *                         @OA\Property(property="base_price", type="string", example="50.00"),
     *                         @OA\Property(property="is_active", type="boolean", example=true),
     *                         @OA\Property(property="sort_order", type="integer", example=1),
     *                         @OA\Property(property="specifications", type="string", nullable=true),
     *                         @OA\Property(
     *                             property="category",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Business Cards"),
     *                             @OA\Property(property="name_ar", type="string", example="بطاقات عمل")
     *                         ),
     *                         @OA\Property(
     *                             property="options",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=1),
     *                                 @OA\Property(property="name", type="string", example="Paper Size"),
     *                                 @OA\Property(property="name_ar", type="string", example="حجم الورق"),
     *                                 @OA\Property(property="type", type="string", example="select"),
     *                                 @OA\Property(property="is_required", type="boolean", example=true),
     *                                 @OA\Property(
     *                                     property="values",
     *                                     type="array",
     *                                     @OA\Items(
     *                                         type="object",
     *                                         @OA\Property(property="id", type="integer", example=1),
     *                                         @OA\Property(property="value", type="string", example="Standard (85x55mm)"),
     *                                         @OA\Property(property="value_ar", type="string", example="قياسي (85x55مم)"),
     *                                         @OA\Property(property="price_adjustment", type="string", example="0.00")
     *                                     )
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=30),
     *                 @OA\Property(property="last_page", type="integer", example=2),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="to", type="integer", example=15)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve products"),
     *             @OA\Property(property="error", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->where('is_active', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

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

        $products = $query->with(['category', 'options.values'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Display the specified product
     * 
     * @OA\Get(
     *     path="/api/products/{product}",
     *     operationId="getProduct",
     *     tags={"Products"},
     *     summary="Get a specific product",
     *     description="Retrieve detailed information about a specific product including its options and values",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Standard Business Cards"),
     *                 @OA\Property(property="name_ar", type="string", example="بطاقات عمل قياسية"),
     *                 @OA\Property(property="description", type="string", example="Professional business cards with various customization options"),
     *                 @OA\Property(property="description_ar", type="string", example="بطاقات عمل احترافية مع خيارات تخصيص متنوعة"),
     *                 @OA\Property(property="image", type="string", nullable=true),
     *                 @OA\Property(property="base_price", type="string", example="50.00"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="sort_order", type="integer", example=1),
     *                 @OA\Property(property="specifications", type="string", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-24T01:43:15.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-24T01:43:15.000000Z"),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Business Cards"),
     *                     @OA\Property(property="name_ar", type="string", example="بطاقات عمل")
     *                 ),
     *                 @OA\Property(
     *                     property="options",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Paper Size"),
     *                         @OA\Property(property="name_ar", type="string", example="حجم الورق"),
     *                         @OA\Property(property="type", type="string", example="select"),
     *                         @OA\Property(property="is_required", type="boolean", example=true),
     *                         @OA\Property(
     *                             property="values",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=1),
     *                                 @OA\Property(property="value", type="string", example="Standard (85x55mm)"),
     *                                 @OA\Property(property="value_ar", type="string", example="قياسي (85x55مم)"),
     *                                 @OA\Property(property="price_adjustment", type="string", example="0.00")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'options.values']);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}
