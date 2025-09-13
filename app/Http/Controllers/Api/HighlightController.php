<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HighlightController extends Controller
{
    /**
     * Display a listing of highlights
     * 
     * @OA\Get(
     *     path="/api/highlights",
     *     operationId="getHighlights",
     *     tags={"Highlights"},
     *     summary="Get all active highlights",
     *     description="Retrieve a list of active highlights with their associated products",
     *     @OA\Parameter(
     *         name="with_products",
     *         in="query",
     *         description="Include products for each highlight",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Highlights retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Spring Offers"),
     *                     @OA\Property(property="name_ar", type="string", example="عروض الربيع"),
     *                     @OA\Property(property="description", type="string", example="Special spring season offers"),
     *                     @OA\Property(property="description_ar", type="string", example="عروض خاصة لموسم الربيع"),
     *                     @OA\Property(property="color", type="string", example="#3B82F6"),
     *                     @OA\Property(property="is_active", type="boolean", example=true),
     *                     @OA\Property(property="sort_order", type="integer", example=1),
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Standard Business Cards"),
     *                             @OA\Property(property="name_ar", type="string", example="بطاقات عمل قياسية"),
     *                             @OA\Property(property="base_price", type="string", example="50.00"),
     *                             @OA\Property(property="image", type="string", nullable=true)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Highlight::active()->ordered();

        // Include products if requested
        if ($request->boolean('with_products')) {
            $query->with(['products' => function ($q) {
                $q->where('is_active', true)
                  ->select('products.id', 'products.name', 'products.name_ar', 'products.base_price', 'products.image')
                  ->orderByPivot('sort_order');
            }]);
        }

        $highlights = $query->get();

        return response()->json([
            'success' => true,
            'data' => $highlights
        ]);
    }

    /**
     * Get products for a specific highlight
     * 
     * @OA\Get(
     *     path="/api/highlights/{highlight}/products",
     *     operationId="getHighlightProducts",
     *     tags={"Highlights"},
     *     summary="Get products for a specific highlight",
     *     description="Retrieve all products associated with a specific highlight",
     *     @OA\Parameter(
     *         name="highlight",
     *         in="path",
     *         description="Highlight ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
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
     *         description="Products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="highlight", type="object"),
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
     *                 @OA\Property(property="total", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Highlight not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Highlight not found")
     *         )
     *     )
     * )
     */
    public function products(Request $request, $highlight): JsonResponse
    {
        $highlight = Highlight::where('slug', $highlight)->first();
        
        if (!$highlight || !$highlight->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Highlight not found'
            ], 404);
        }

        // Get limit parameter with validation
        $limit = $request->get('limit', 15);
        $limit = min(max((int) $limit, 1), 100);

        $products = $highlight->products()
            ->where('is_active', true)
            ->with(['category:id,name,name_ar'])
            ->orderByPivot('sort_order')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'highlight' => [
                'id' => $highlight->id,
                'name' => $highlight->name,
                'name_ar' => $highlight->name_ar,
                'slug' => $highlight->slug
            ],
            'data' => $products
        ]);
    }

    /**
     * Display the specified highlight
     * 
     * @OA\Get(
     *     path="/api/highlights/{highlight}",
     *     operationId="getHighlight",
     *     tags={"Highlights"},
     *     summary="Get a specific highlight",
     *     description="Retrieve detailed information about a specific highlight",
     *     @OA\Parameter(
     *         name="highlight",
     *         in="path",
     *         description="Highlight ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Highlight retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Spring Offers"),
     *                 @OA\Property(property="name_ar", type="string", example="عروض الربيع"),
     *                 @OA\Property(property="description", type="string", example="Special spring season offers"),
     *                 @OA\Property(property="description_ar", type="string", example="عروض خاصة لموسم الربيع"),
     *                 @OA\Property(property="color", type="string", example="#3B82F6"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="sort_order", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Highlight not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Highlight not found")
     *         )
     *     )
     * )
     */
    public function show($highlight): JsonResponse
    {
        $highlight = Highlight::where('slug', $highlight)->first();
        
        if (!$highlight || !$highlight->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Highlight not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $highlight
        ]);
    }
}