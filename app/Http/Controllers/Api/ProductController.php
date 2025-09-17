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

        $products = $query->with(['category', 'options.values', 'highlights'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Search products with advanced filtering
     * 
     * @OA\Get(
     *     path="/api/products/search",
     *     operationId="searchProducts",
     *     tags={"Products"},
     *     summary="Search products with advanced filtering",
     *     description="Search products by name, description, category, price range, and other criteria with pagination support.",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Search query to match against product names and descriptions in English and Arabic",
     *         required=true,
     *         @OA\Schema(type="string", example="business card")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter by specific category ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Minimum price filter",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=10.00)
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="Maximum price filter",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=100.00)
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Sort field (name, price, created_at, sort_order)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"name", "price", "created_at", "sort_order"}, example="name")
     *     ),
     *     @OA\Parameter(
     *         name="sort_order",
     *         in="query",
     *         description="Sort direction (asc, desc)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, example="asc")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of products per page (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search results retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="query", type="string", example="business card"),
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
     *                         @OA\Property(property="name", type="string", example="Standard Business Cards"),
     *                         @OA\Property(property="name_ar", type="string", example="بطاقات عمل قياسية"),
     *                         @OA\Property(property="description", type="string", example="Professional business cards"),
     *                         @OA\Property(property="base_price", type="string", example="50.00"),
     *                         @OA\Property(property="image", type="string", nullable=true),
     *                         @OA\Property(
     *                             property="category",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Business Cards"),
     *                             @OA\Property(property="name_ar", type="string", example="بطاقات عمل")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=5),
     *                 @OA\Property(property="last_page", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="q", type="array", @OA\Items(type="string", example="The q field is required."))
     *             )
     *         )
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'sort_by' => 'nullable|string|in:name,price,created_at,sort_order',
            'sort_order' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $query = Product::query()->where('is_active', true);
        $searchTerm = $request->get('q');

        // Search in name and description (English and Arabic)
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('name_ar', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('description_ar', 'like', "%{$searchTerm}%");
        });

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('base_price', '>=', $request->get('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('base_price', '<=', $request->get('max_price'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');

        // Handle different sort fields
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'price':
                $query->orderBy('base_price', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortOrder);
                break;
            case 'sort_order':
            default:
                $query->orderBy('sort_order', $sortOrder)
                      ->orderBy('name', 'asc');
                break;
        }

        // Pagination
        $limit = $request->get('limit', 15);
        $limit = min(max((int) $limit, 1), 100);

        $products = $query->with(['category', 'options.values', 'highlights'])
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'query' => $searchTerm,
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
        $product->load(['category', 'options.values', 'highlights']);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Get latest products
     * 
     * @OA\Get(
     *     path="/api/products/latest",
     *     operationId="getLatestProducts",
     *     tags={"Products"},
     *     summary="Get latest products",
     *     description="Retrieve the most recently added active products with their images and basic information",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of products to return (1-20)",
     *         required=false,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Latest products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Standard Business Cards"),
     *                     @OA\Property(property="name_ar", type="string", example="بطاقات عمل قياسية"),
     *                     @OA\Property(property="base_price", type="string", example="50.00"),
     *                     @OA\Property(property="image_url", type="string", example="https://example.com/images/product1.jpg"),
     *                     @OA\Property(property="url", type="string", example="/products/1"),
     *                     @OA\Property(
     *                         property="category",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Business Cards"),
     *                         @OA\Property(property="name_ar", type="string", example="بطاقات عمل")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->get('limit', 5), 1), 20);
        
        $products = Product::where('is_active', true)
            ->with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'name_ar' => $product->name_ar,
                    'base_price' => number_format($product->base_price, 2),
                    'image_url' => $product->best_image_url ?? asset('assets/images/placeholder-product.jpg'),
                    'url' => route('user.products.show', $product->id),
                    'category' => $product->category
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get best selling products
     * 
     * @OA\Get(
     *     path="/api/products/best-selling",
     *     operationId="getBestSellingProducts",
     *     tags={"Products"},
     *     summary="Get best selling products",
     *     description="Retrieve the best selling products based on order count with their images and basic information",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of products to return (1-20)",
     *         required=false,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Best selling products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Standard Business Cards"),
     *                     @OA\Property(property="name_ar", type="string", example="بطاقات عمل قياسية"),
     *                     @OA\Property(property="base_price", type="string", example="50.00"),
     *                     @OA\Property(property="image_url", type="string", example="https://example.com/images/product1.jpg"),
     *                     @OA\Property(property="url", type="string", example="/products/1"),
     *                     @OA\Property(property="order_count", type="integer", example=25),
     *                     @OA\Property(
     *                         property="category",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Business Cards"),
     *                         @OA\Property(property="name_ar", type="string", example="بطاقات عمل")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function bestSelling(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->get('limit', 5), 1), 20);
        
        $products = Product::where('is_active', true)
            ->with(['category', 'images'])
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'name_ar' => $product->name_ar,
                    'base_price' => number_format($product->base_price, 2),
                    'image_url' => $product->best_image_url ?? asset('assets/images/placeholder-product.jpg'),
                    'url' => route('user.products.show', $product->id),
                    'order_count' => $product->order_items_count,
                    'category' => $product->category
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
