<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Business Cards',
                'name_ar' => 'بطاقات عمل',
                'description' => 'Professional business cards for companies and individuals',
                'description_ar' => 'بطاقات عمل احترافية للشركات والأفراد',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Brochures & Catalogs',
                'name_ar' => 'كتيبات وكتالوجات',
                'description' => 'Marketing materials including brochures, catalogs, and flyers',
                'description_ar' => 'مواد تسويقية تشمل الكتيبات والكتالوجات والنشرات',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Banners & Signs',
                'name_ar' => 'لافتات وعلامات',
                'description' => 'Large format printing for banners, signs, and displays',
                'description_ar' => 'طباعة كبيرة الحجم للافتات والعلامات والعروض',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Stickers & Labels',
                'name_ar' => 'ملصقات وتسميات',
                'description' => 'Custom stickers, labels, and adhesive materials',
                'description_ar' => 'ملصقات وتسميات مخصصة ومواد لاصقة',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Packaging',
                'name_ar' => 'تغليف',
                'description' => 'Custom packaging solutions and boxes',
                'description_ar' => 'حلول تغليف مخصصة وصناديق',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
