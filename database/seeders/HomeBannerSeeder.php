<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HomeBanner;

class HomeBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Welcome to Velorena',
                'title_ar' => 'مرحباً بك في فيلورينا',
                'description' => 'Discover our amazing collection of products and services',
                'description_ar' => 'اكتشف مجموعتنا المذهلة من المنتجات والخدمات',
                'image' => 'storage/home-banners/welcome-banner.jpg',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Special Offers',
                'title_ar' => 'عروض خاصة',
                'description' => 'Don\'t miss out on our exclusive deals and discounts',
                'description_ar' => 'لا تفوت عروضنا الحصرية والخصومات',
                'image' => 'storage/home-banners/special-offers.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'New Arrivals',
                'title_ar' => 'وصل حديثاً',
                'description' => 'Check out our latest products and collections',
                'description_ar' => 'اطلع على أحدث منتجاتنا ومجموعاتنا',
                'image' => 'storage/home-banners/new-arrivals.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            HomeBanner::create($banner);
        }
    }
}