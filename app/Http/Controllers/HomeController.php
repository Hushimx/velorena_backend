<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the welcome page with latest and best selling products
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        // Get latest products (5 most recent)
        $latestProducts = Product::where('is_active', true)
            ->with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
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

        // Get best selling products (5 most ordered)
        $bestSellingProducts = Product::where('is_active', true)
            ->with(['category', 'images'])
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
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

        return view('users.welcome', [
            'latestProducts' => $latestProducts,
            'bestSellingProducts' => $bestSellingProducts
        ]);
    }
}