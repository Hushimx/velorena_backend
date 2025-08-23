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
                'name' => 'Electronics',
                'name_ar' => 'الإلكترونيات',
                'description' => 'Electronic devices and gadgets',
                'description_ar' => 'الأجهزة الإلكترونية والأدوات',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Clothing',
                'name_ar' => 'الملابس',
                'description' => 'Fashion and clothing items',
                'description_ar' => 'الأزياء والملابس',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Home & Garden',
                'name_ar' => 'المنزل والحديقة',
                'description' => 'Home improvement and garden supplies',
                'description_ar' => 'تحسين المنزل ومستلزمات الحدائق',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Sports & Outdoors',
                'name_ar' => 'الرياضة والهواء الطلق',
                'description' => 'Sports equipment and outdoor gear',
                'description_ar' => 'معدات رياضية ومعدات خارجية',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Books & Media',
                'name_ar' => 'الكتب والوسائط',
                'description' => 'Books, movies, and media content',
                'description_ar' => 'الكتب والأفلام والمحتوى الإعلامي',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
