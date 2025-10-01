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
                'title' => 'From Idea to Print',
                'title_ar' => 'من الفكرة إلى الطباعة',
                'description' => 'Transform your ideas into reality with our professional printing services',
                'description_ar' => 'حول أفكارك إلى واقع مع خدمات الطباعة الاحترافية',
                'image' => 'banners/بنر - من الفكرة إلى الطباعة[1].jpg',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Your Scent Precedes Your Impact',
                'title_ar' => 'عطرك يسبق أثره',
                'description' => 'Make a lasting impression with premium quality prints',
                'description_ar' => 'اترك انطباعاً دائماً مع طباعة عالية الجودة',
                'image' => 'banners/بنر للمنصة - عطرك يسبق أثره[1].jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Premium Printing Services',
                'title_ar' => 'خدمات طباعة متميزة',
                'description' => 'Discover our wide range of printing and design solutions',
                'description_ar' => 'اكتشف مجموعتنا الواسعة من حلول الطباعة والتصميم',
                'image' => 'banners/بنر11[1].jpg',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        // Clear existing banners first
        HomeBanner::truncate();

        foreach ($banners as $banner) {
            HomeBanner::create($banner);
        }
    }
}