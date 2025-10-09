<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    /**
     * Get all favorite products for the authenticated user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get favorites with product details
            $favorites = $user->favorites()
                ->with(['product' => function($query) {
                    $query->select([
                        'id',
                        'name',
                        'name_ar',
                        'slug',
                        'description',
                        'description_ar',
                        'base_price',
                        'image_url',
                        'category_id',
                        'is_active',
                        'created_at'
                    ]);
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            // Format the response
            $formattedFavorites = $favorites->map(function($favorite) {
                $product = $favorite->product;
                
                if (!$product) {
                    return null;
                }

                return [
                    'id' => $favorite->id,
                    'product_id' => $product->id,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'name_ar' => $product->name_ar,
                        'slug' => $product->slug,
                        'description' => $product->description,
                        'description_ar' => $product->description_ar,
                        'price' => $product->base_price,
                        'image_url' => $product->image_url,
                        'category_id' => $product->category_id,
                        'is_active' => $product->is_active,
                    ],
                    'favorited_at' => $favorite->created_at,
                ];
            })->filter()->values();

            return response()->json([
                'success' => true,
                'data' => $formattedFavorites,
                'total' => $formattedFavorites->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching favorites', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a product to favorites
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $user = Auth::user();
            $productId = $validated['product_id'];

            // Check if product is already in favorites
            $existingFavorite = Favorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($existingFavorite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is already in favorites',
                    'data' => [
                        'id' => $existingFavorite->id,
                        'product_id' => $productId,
                        'is_favorited' => true
                    ]
                ], 409);
            }

            // Add to favorites
            $favorite = Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            // Load product details
            $favorite->load('product');

            return response()->json([
                'success' => true,
                'message' => 'Product added to favorites',
                'data' => [
                    'id' => $favorite->id,
                    'product_id' => $productId,
                    'product' => $favorite->product,
                    'favorited_at' => $favorite->created_at,
                    'is_favorited' => true
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error adding product to favorites', [
                'user_id' => Auth::id(),
                'product_id' => $request->input('product_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a product from favorites
     * 
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($productId)
    {
        try {
            $user = Auth::user();

            // Find the favorite
            $favorite = Favorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if (!$favorite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in favorites',
                    'data' => [
                        'product_id' => $productId,
                        'is_favorited' => false
                    ]
                ], 404);
            }

            // Delete the favorite
            $favorite->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from favorites',
                'data' => [
                    'product_id' => $productId,
                    'is_favorited' => false
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing product from favorites', [
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle favorite status for a product
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);

            $user = Auth::user();
            $productId = $validated['product_id'];

            // Check if product is already in favorites
            $favorite = Favorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($favorite) {
                // Remove from favorites
                $favorite->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from favorites',
                    'data' => [
                        'product_id' => $productId,
                        'is_favorited' => false
                    ]
                ]);
            } else {
                // Add to favorites
                $favorite = Favorite::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);

                $favorite->load('product');

                return response()->json([
                    'success' => true,
                    'message' => 'Product added to favorites',
                    'data' => [
                        'id' => $favorite->id,
                        'product_id' => $productId,
                        'product' => $favorite->product,
                        'favorited_at' => $favorite->created_at,
                        'is_favorited' => true
                    ]
                ], 201);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error toggling favorite', [
                'user_id' => Auth::id(),
                'product_id' => $request->input('product_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle favorite',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a product is favorited by the authenticated user
     * 
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function check($productId)
    {
        try {
            $user = Auth::user();

            $isFavorited = Favorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'product_id' => $productId,
                    'is_favorited' => $isFavorited
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking favorite status', [
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check favorite status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get favorite product IDs for the authenticated user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavoriteIds()
    {
        try {
            $user = Auth::user();

            $favoriteIds = Favorite::where('user_id', $user->id)
                ->pluck('product_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $favoriteIds,
                'total' => count($favoriteIds)
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching favorite IDs', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch favorite IDs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}