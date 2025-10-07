<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeBannerController extends Controller
{
    /**
     * Display a listing of home banners
     * 
     * @OA\Get(
     *     path="/api/home-banners",
     *     operationId="getHomeBanners",
     *     tags={"Home Banners"},
     *     summary="Get all active home banners",
     *     description="Retrieve a list of active home banners ordered by sort_order",
     *     @OA\Response(
     *         response=200,
     *         description="Home banners retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Welcome to Qaads"),
     *                     @OA\Property(property="title_ar", type="string", example="مرحباً بك في قادس"),
     *                     @OA\Property(property="description", type="string", example="Discover our amazing products"),
     *                     @OA\Property(property="description_ar", type="string", example="اكتشف منتجاتنا المذهلة"),
     *                     @OA\Property(property="image", type="string", example="storage/banners/banner1.jpg"),
     *                     @OA\Property(property="link", type="string", nullable=true, example="/products"),
     *                     @OA\Property(property="is_active", type="boolean", example=true),
     *                     @OA\Property(property="sort_order", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        // Fixed JSON response for the three banners
        $banners = [
            [
                'id' => 1,
                'title' => 'From Idea to Print',
                'title_ar' => 'من الفكرة إلى الطباعة',
                'description' => 'Transform your creative ideas into stunning printed products',
                'description_ar' => 'حول أفكارك الإبداعية إلى منتجات مطبوعة مذهلة',
                'image' => '/banners/بنر - من الفكرة إلى الطباعة[1].jpg',
                'link' => '/products',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ],
            [
                'id' => 2,
                'title' => 'Your Scent Precedes Your Impact',
                'title_ar' => 'عطرك يسبق أثره',
                'description' => 'Discover our premium fragrance collection',
                'description_ar' => 'اكتشف مجموعتنا المميزة من العطور',
                'image' => '/banners/بنر للمنصة - عطرك يسبق أثره[1].jpg',
                'link' => '/categories/fragrances',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ],
            [
                'id' => 3,
                'title' => 'Premium Quality Products',
                'title_ar' => 'منتجات عالية الجودة',
                'description' => 'Experience the finest quality in every product',
                'description_ar' => 'اختبر أجود أنواع الجودة في كل منتج',
                'image' => '/banners/بنر11[1].jpg',
                'link' => '/products/premium',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }

    /**
     * Display the specified home banner
     * 
     * @OA\Get(
     *     path="/api/home-banners/{banner}",
     *     operationId="getHomeBanner",
     *     tags={"Home Banners"},
     *     summary="Get a specific home banner",
     *     description="Retrieve detailed information about a specific home banner",
     *     @OA\Parameter(
     *         name="banner",
     *         in="path",
     *         description="Banner ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Home banner retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Welcome to Qaads"),
     *                 @OA\Property(property="title_ar", type="string", example="مرحباً بك في قادس"),
     *                 @OA\Property(property="description", type="string", example="Discover our amazing products"),
     *                 @OA\Property(property="description_ar", type="string", example="اكتشف منتجاتنا المذهلة"),
     *                 @OA\Property(property="image", type="string", example="storage/banners/banner1.jpg"),
     *                 @OA\Property(property="link", type="string", nullable=true, example="/products"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="sort_order", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Home banner not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Home banner not found")
     *         )
     *     )
     * )
     */
    public function show(HomeBanner $banner): JsonResponse
    {
        if (!$banner->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Home banner not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }
}