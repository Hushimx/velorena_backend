<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StoreContent;
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
        return $this->welcome();
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

        // Get active categories
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name_ar ?? $category->name,
                    'name_en' => $category->name,
                    'name_ar' => $category->name_ar,
                    'image_url' => $category->image ? asset($category->image) : asset('assets/imgs/تنظيم المـواتمرات (2).png'),
                    'url' => route('user.products.index', ['category' => $category->id]),
                    'badge' => null // You can add badge logic here if needed
                ];
            });

        // Get dynamic homepage content
        $homepageContent = [
            'title' => StoreContent::getSetting('homepage_title.value', trans('Print Your Design Now with the Highest Quality')),
            'subtitle' => StoreContent::getSetting('homepage_subtitle.value', trans('Premium Design & Print Solutions')),
            'description' => StoreContent::getSetting('homepage_description.value', trans('Transform your ideas into distinctive prints with high quality and professional design that meets your needs and reflects your identity')),
        ];

        return view('users.welcome', [
            'latestProducts' => $latestProducts,
            'bestSellingProducts' => $bestSellingProducts,
            'categories' => $categories,
            'homepageContent' => $homepageContent
        ]);
    }
}
