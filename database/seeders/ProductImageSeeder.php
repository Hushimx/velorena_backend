<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Product;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping of product IDs to their corresponding images
        $productImages = [
            // Product ID 40 - بوث معارض
            40 => [
                [
                    'image_path' => 'storage/products/1758452511_0_بوث.jpg',
                    'alt_text' => 'بوث معارض',
                    'sort_order' => 1
                ]
            ],

            // Product ID 41 - طاولة دعائية
            41 => [
                [
                    'image_path' => 'storage/products/1758453822_0_طاولة--.jpg',
                    'alt_text' => 'طاولة دعائية',
                    'sort_order' => 1
                ]
            ],

            // Product ID 45 - لوحة بنر
            45 => [
                [
                    'image_path' => 'storage/products/1758518421_0_بنر.png',
                    'alt_text' => 'لوحة بنر',
                    'sort_order' => 1
                ]
            ],

            // Product ID 46 - بوب اب
            46 => [
                [
                    'image_path' => 'storage/products/1758518679_0_بوب اب.png',
                    'alt_text' => 'بوب اب',
                    'sort_order' => 1
                ]
            ],

            // Product ID 47 - رول اب
            47 => [
                [
                    'image_path' => 'storage/products/1758519504_0_رول اب.png',
                    'alt_text' => 'رول اب',
                    'sort_order' => 1
                ]
            ],

            // Product ID 49 - بروش اسم
            49 => [
                [
                    'image_path' => 'storage/products/1758519638_0_بروش اســم.png',
                    'alt_text' => 'بروش اسم',
                    'sort_order' => 1
                ],
                [
                    'image_path' => 'storage/products/1758519642_0_بروش اســم.png',
                    'alt_text' => 'بروش اسم - تصميم 2',
                    'sort_order' => 2
                ]
            ],

            // Product ID 50 - تشيرتات
            50 => [
                [
                    'image_path' => 'storage/products/1758519793_0_تيشترتات.jpg',
                    'alt_text' => 'تيشرتات',
                    'sort_order' => 1
                ]
            ],

            // Product ID 51 - كابات
            51 => [
                [
                    'image_path' => 'storage/products/1758520853_0_كابات.png',
                    'alt_text' => 'كابات',
                    'sort_order' => 1
                ]
            ],

            // Product ID 52 - بطاقات تعريفية
            52 => [
                [
                    'image_path' => 'storage/products/1758521690_0_بطائق اي دي.png',
                    'alt_text' => 'بطاقات تعريفية ( عادي ـ ID )',
                    'sort_order' => 1
                ]
            ],

            // Product ID 53 - طاولة دعائية مع مضلة
            53 => [
                [
                    'image_path' => 'storage/products/1758522227_0_طاولة دعائية مع مظلة .png',
                    'alt_text' => 'طاولة دعائية مع مضلة',
                    'sort_order' => 1
                ]
            ],

            // Product ID 54 - طاولة قابلة للطي
            54 => [
                [
                    'image_path' => 'storage/products/1758522487_0_طاولة قـــابلـــة للطـــي .png',
                    'alt_text' => 'طاولة قابلة للطي',
                    'sort_order' => 1
                ]
            ],

            // Product ID 55 - طاولة دعائية مقوسة
            55 => [
                [
                    'image_path' => 'storage/products/1758522795_0_طاولة دعائية مقوسة .png',
                    'alt_text' => 'طاولة دعائية مقوسة',
                    'sort_order' => 1
                ]
            ],

            // Product ID 56 - استاند باك دروب
            56 => [
                [
                    'image_path' => 'storage/products/1758523555_0_باك دورب.png',
                    'alt_text' => 'استاند باك دروب',
                    'sort_order' => 1
                ]
            ],

            // Product ID 57 - طباعة على شنط
            57 => [
                [
                    'image_path' => 'storage/products/1758524944_0_شنطة.jpg',
                    'alt_text' => 'طباعة على شنط',
                    'sort_order' => 1
                ]
            ],

            // Product ID 58 - كاسات سيراميك مع الطباعة
            58 => [
                [
                    'image_path' => 'storage/products/1758525377_0_مج سيراميك.jpg',
                    'alt_text' => 'كاسات سيراميك مع الطباعة',
                    'sort_order' => 1
                ]
            ],

            // Product ID 59 - مج ستيل
            59 => [
                [
                    'image_path' => 'storage/products/1758525838_0_مح اسيتل.jpg',
                    'alt_text' => 'مج ستيل',
                    'sort_order' => 1
                ]
            ]
        ];

        // Create product images
        foreach ($productImages as $productId => $images) {
            // Check if product exists
            $product = Product::find($productId);
            if (!$product) {
                $this->command->warn("Product with ID {$productId} not found, skipping...");
                continue;
            }

            foreach ($images as $imageData) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'image_path' => $imageData['image_path']
                    ],
                    array_merge($imageData, ['product_id' => $productId])
                );
            }

            $this->command->info("Added " . count($images) . " image(s) for product: {$product->name}");
        }

        $this->command->info('Product images seeded successfully!');
    }
}
