<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Perfume Box',
                'name_ar' => 'صندوق عطور',
                'description' => 'Premium perfume boxes and fragrances',
                'description_ar' => 'صناديق العطور والعطور الفاخرة',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Health & Nutrition',
                'name_ar' => 'الصحة والتغذية',
                'description' => 'Health supplements and nutrition products',
                'description_ar' => 'مكملات صحية ومنتجات التغذية',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Traditional Clothes',
                'name_ar' => 'الملابس التقليدية',
                'description' => 'Traditional and cultural clothing',
                'description_ar' => 'الملابس التقليدية والثقافية',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Travel Store',
                'name_ar' => 'متجر السفر',
                'description' => 'Travel accessories and luggage',
                'description_ar' => 'إكسسوارات السفر والأمتعة',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Travel Bags',
                'name_ar' => 'شنط السفر',
                'description' => 'Travel bags and suitcases',
                'description_ar' => 'حقائب السفر والأمتعة',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Beauty',
                'name_ar' => 'الجمال',
                'description' => 'Beauty and cosmetics products',
                'description_ar' => 'منتجات الجمال ومستحضرات التجميل',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Groceries',
                'name_ar' => 'المقاضي',
                'description' => 'Food and grocery items',
                'description_ar' => 'المواد الغذائية والبقالة',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Good Weather',
                'name_ar' => 'الجو الحلو',
                'description' => 'Outdoor and leisure products',
                'description_ar' => 'منتجات الهواء الطلق والترفيه',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Shoes',
                'name_ar' => 'الأحذية',
                'description' => 'Footwear and shoes',
                'description_ar' => 'الأحذية والأحذية',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Global Store',
                'name_ar' => 'متجر عالمي',
                'description' => 'International and global products',
                'description_ar' => 'منتجات دولية وعالمية',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'National Day 95',
                'name_ar' => 'اليوم الوطني 95',
                'description' => 'National Day special products',
                'description_ar' => 'منتجات خاصة باليوم الوطني',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Offers',
                'name_ar' => 'عروض',
                'description' => 'Special offers and discounts',
                'description_ar' => 'عروض خاصة وخصومات',
                'is_active' => true,
                'sort_order' => 12,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
