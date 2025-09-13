<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Highlight;

class HighlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $highlights = [
            [
                'name' => 'Spring Offers',
                'name_ar' => 'عروض الربيع',
                'slug' => 'spring-offers',
                'description' => 'Special spring season offers on selected products',
                'description_ar' => 'عروض خاصة لموسم الربيع على منتجات مختارة',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Big Discounts',
                'name_ar' => 'تخفيضات الخميص',
                'slug' => 'big-discounts',
                'description' => 'Massive discounts on premium products',
                'description_ar' => 'تخفيضات كبيرة على المنتجات المميزة',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'New Arrivals',
                'name_ar' => 'وصل حديثاً',
                'slug' => 'new-arrivals',
                'description' => 'Check out our latest product additions',
                'description_ar' => 'اطلع على أحدث إضافاتنا للمنتجات',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Best Sellers',
                'name_ar' => 'الأكثر مبيعاً',
                'slug' => 'best-sellers',
                'description' => 'Our most popular and best-selling products',
                'description_ar' => 'منتجاتنا الأكثر شعبية ومبيعاً',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Limited Edition',
                'name_ar' => 'إصدار محدود',
                'slug' => 'limited-edition',
                'description' => 'Exclusive limited edition products',
                'description_ar' => 'منتجات حصرية بإصدار محدود',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($highlights as $highlight) {
            Highlight::create($highlight);
        }
    }
}