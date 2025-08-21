<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\OptionValue;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 25 products using the factory
        $products = Product::factory(25)->create();

        // Add options and values to each product
        foreach ($products as $product) {
            $this->createProductOptions($product);
        }

        // Create some specific products with detailed options
        $this->createSpecificProducts();
    }

    private function createProductOptions($product)
    {
        // Get random number of options (1-4) for each product
        $numOptions = rand(1, 4);

        for ($i = 1; $i <= $numOptions; $i++) {
            $optionData = $this->getRandomOptionData($i);

            $option = ProductOption::create([
                'product_id' => $product->id,
                'name' => $optionData['name'],
                'name_ar' => $optionData['name_ar'],
                'type' => 'select',
                'is_required' => $optionData['is_required'],
                'is_active' => true,
                'sort_order' => $i
            ]);

            // Create values for this option
            foreach ($optionData['values'] as $valueData) {
                OptionValue::create([
                    'product_option_id' => $option->id,
                    'value' => $valueData['value'],
                    'value_ar' => $valueData['value_ar'],
                    'price_adjustment' => $valueData['price_adjustment'],
                    'sort_order' => $valueData['sort_order']
                ]);
            }
        }
    }

    private function getRandomOptionData($optionNumber)
    {
        $options = [
            [
                'name' => 'Size',
                'name_ar' => 'الحجم',
                'is_required' => true,
                'values' => [
                    ['value' => 'Small', 'value_ar' => 'صغير', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => 'Medium', 'value_ar' => 'متوسط', 'price_adjustment' => 50, 'sort_order' => 2],
                    ['value' => 'Large', 'value_ar' => 'كبير', 'price_adjustment' => 100, 'sort_order' => 3],
                    ['value' => 'Extra Large', 'value_ar' => 'كبير جداً', 'price_adjustment' => 150, 'sort_order' => 4]
                ]
            ],
            [
                'name' => 'Color',
                'name_ar' => 'اللون',
                'is_required' => true,
                'values' => [
                    ['value' => 'Black', 'value_ar' => 'أسود', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => 'White', 'value_ar' => 'أبيض', 'price_adjustment' => 10, 'sort_order' => 2],
                    ['value' => 'Blue', 'value_ar' => 'أزرق', 'price_adjustment' => 15, 'sort_order' => 3],
                    ['value' => 'Red', 'value_ar' => 'أحمر', 'price_adjustment' => 20, 'sort_order' => 4],
                    ['value' => 'Green', 'value_ar' => 'أخضر', 'price_adjustment' => 25, 'sort_order' => 5]
                ]
            ],
            [
                'name' => 'Material',
                'name_ar' => 'المادة',
                'is_required' => false,
                'values' => [
                    ['value' => 'Standard', 'value_ar' => 'قياسي', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => 'Premium', 'value_ar' => 'مميز', 'price_adjustment' => 75, 'sort_order' => 2],
                    ['value' => 'Luxury', 'value_ar' => 'فاخر', 'price_adjustment' => 150, 'sort_order' => 3]
                ]
            ],
            [
                'name' => 'Warranty',
                'name_ar' => 'الضمان',
                'is_required' => false,
                'values' => [
                    ['value' => '1 Year', 'value_ar' => 'سنة واحدة', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => '2 Years', 'value_ar' => 'سنتان', 'price_adjustment' => 50, 'sort_order' => 2],
                    ['value' => '3 Years', 'value_ar' => '3 سنوات', 'price_adjustment' => 100, 'sort_order' => 3],
                    ['value' => '5 Years', 'value_ar' => '5 سنوات', 'price_adjustment' => 200, 'sort_order' => 4]
                ]
            ],
            [
                'name' => 'Storage',
                'name_ar' => 'التخزين',
                'is_required' => false,
                'values' => [
                    ['value' => '64GB', 'value_ar' => '64 جيجابايت', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => '128GB', 'value_ar' => '128 جيجابايت', 'price_adjustment' => 100, 'sort_order' => 2],
                    ['value' => '256GB', 'value_ar' => '256 جيجابايت', 'price_adjustment' => 200, 'sort_order' => 3],
                    ['value' => '512GB', 'value_ar' => '512 جيجابايت', 'price_adjustment' => 400, 'sort_order' => 4],
                    ['value' => '1TB', 'value_ar' => '1 تيرابايت', 'price_adjustment' => 600, 'sort_order' => 5]
                ]
            ],
            [
                'name' => 'Connectivity',
                'name_ar' => 'الاتصال',
                'is_required' => false,
                'values' => [
                    ['value' => 'WiFi Only', 'value_ar' => 'واي فاي فقط', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => 'WiFi + Bluetooth', 'value_ar' => 'واي فاي + بلوتوث', 'price_adjustment' => 30, 'sort_order' => 2],
                    ['value' => 'WiFi + 4G', 'value_ar' => 'واي فاي + 4G', 'price_adjustment' => 150, 'sort_order' => 3],
                    ['value' => 'WiFi + 5G', 'value_ar' => 'واي فاي + 5G', 'price_adjustment' => 300, 'sort_order' => 4]
                ]
            ],
            [
                'name' => 'Quantity',
                'name_ar' => 'الكمية',
                'is_required' => true,
                'values' => [
                    ['value' => '1 Piece', 'value_ar' => 'قطعة واحدة', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => '5 Pieces', 'value_ar' => '5 قطع', 'price_adjustment' => 200, 'sort_order' => 2],
                    ['value' => '10 Pieces', 'value_ar' => '10 قطع', 'price_adjustment' => 350, 'sort_order' => 3],
                    ['value' => '20 Pieces', 'value_ar' => '20 قطعة', 'price_adjustment' => 600, 'sort_order' => 4]
                ]
            ],
            [
                'name' => 'Finish',
                'name_ar' => 'الإنهاء',
                'is_required' => false,
                'values' => [
                    ['value' => 'Standard', 'value_ar' => 'قياسي', 'price_adjustment' => 0, 'sort_order' => 1],
                    ['value' => 'Matte', 'value_ar' => 'مطفي', 'price_adjustment' => 25, 'sort_order' => 2],
                    ['value' => 'Glossy', 'value_ar' => 'لامع', 'price_adjustment' => 35, 'sort_order' => 3],
                    ['value' => 'Premium', 'value_ar' => 'مميز', 'price_adjustment' => 75, 'sort_order' => 4]
                ]
            ]
        ];

        return $options[array_rand($options)];
    }

    private function createSpecificProducts()
    {
        // Create some specific products with detailed options
        $this->createLaptopProduct();
        $this->createSmartphoneProduct();
        $this->createHeadphonesProduct();
        $this->createGamingMouseProduct();
        $this->createKeyboardProduct();
    }

    private function createLaptopProduct()
    {
        $laptop = Product::create([
            'category_id' => Category::where('name', 'like', '%computer%')->first()->id ?? 1,
            'name' => 'UltraBook Pro X1',
            'name_ar' => 'ألترابوك برو إكس 1',
            'description' => 'Premium ultrabook with the latest Intel processor and stunning display',
            'description_ar' => 'ألترابوك مميز بأحدث معالج إنتل وشاشة مذهلة',
            'base_price' => 1299.99,
            'is_active' => true,
            'sort_order' => 1,
            'specifications' => [
                'brand' => 'TechCorp',
                'model' => 'UltraBook Pro X1',
                'processor' => 'Intel Core i7-12700H',
                'ram' => '16GB DDR4',
                'storage' => '512GB NVMe SSD',
                'display' => '15.6" 4K OLED',
                'graphics' => 'Intel Iris Xe',
                'battery' => '8 hours',
                'weight' => '1.8kg',
                'warranty' => '3 years'
            ]
        ]);

        // Processor Option
        $processorOption = ProductOption::create([
            'product_id' => $laptop->id,
            'name' => 'Processor',
            'name_ar' => 'المعالج',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $processorValues = [
            ['value' => 'Intel Core i5-12500H', 'value_ar' => 'إنتل كور آي 5-12500H', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Intel Core i7-12700H', 'value_ar' => 'إنتل كور آي 7-12700H', 'price_adjustment' => 300, 'sort_order' => 2],
            ['value' => 'Intel Core i9-12900H', 'value_ar' => 'إنتل كور آي 9-12900H', 'price_adjustment' => 600, 'sort_order' => 3]
        ];

        foreach ($processorValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $processorOption->id]));
        }

        // RAM Option
        $ramOption = ProductOption::create([
            'product_id' => $laptop->id,
            'name' => 'RAM',
            'name_ar' => 'الذاكرة العشوائية',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $ramValues = [
            ['value' => '8GB DDR4', 'value_ar' => '8 جيجابايت DDR4', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => '16GB DDR4', 'value_ar' => '16 جيجابايت DDR4', 'price_adjustment' => 150, 'sort_order' => 2],
            ['value' => '32GB DDR4', 'value_ar' => '32 جيجابايت DDR4', 'price_adjustment' => 400, 'sort_order' => 3]
        ];

        foreach ($ramValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $ramOption->id]));
        }

        // Storage Option
        $storageOption = ProductOption::create([
            'product_id' => $laptop->id,
            'name' => 'Storage',
            'name_ar' => 'التخزين',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 3
        ]);

        $storageValues = [
            ['value' => '256GB NVMe SSD', 'value_ar' => '256 جيجابايت NVMe SSD', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => '512GB NVMe SSD', 'value_ar' => '512 جيجابايت NVMe SSD', 'price_adjustment' => 200, 'sort_order' => 2],
            ['value' => '1TB NVMe SSD', 'value_ar' => '1 تيرابايت NVMe SSD', 'price_adjustment' => 400, 'sort_order' => 3],
            ['value' => '2TB NVMe SSD', 'value_ar' => '2 تيرابايت NVMe SSD', 'price_adjustment' => 800, 'sort_order' => 4]
        ];

        foreach ($storageValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $storageOption->id]));
        }
    }

    private function createSmartphoneProduct()
    {
        $smartphone = Product::create([
            'category_id' => Category::where('name', 'like', '%phone%')->first()->id ?? 1,
            'name' => 'Galaxy Ultra S24',
            'name_ar' => 'جالاكسي ألترا إس 24',
            'description' => 'Flagship smartphone with advanced camera system and powerful performance',
            'description_ar' => 'هاتف ذكي راقي مع نظام كاميرا متقدم وأداء قوي',
            'base_price' => 999.99,
            'is_active' => true,
            'sort_order' => 2,
            'specifications' => [
                'brand' => 'Samsung',
                'model' => 'Galaxy Ultra S24',
                'processor' => 'Snapdragon 8 Gen 3',
                'ram' => '12GB LPDDR5X',
                'storage' => '256GB UFS 4.0',
                'display' => '6.8" Dynamic AMOLED 2X',
                'camera' => '200MP + 12MP + 50MP',
                'battery' => '5000mAh',
                'charging' => '45W Fast Charging',
                'warranty' => '2 years'
            ]
        ]);

        // Storage Option
        $storageOption = ProductOption::create([
            'product_id' => $smartphone->id,
            'name' => 'Storage',
            'name_ar' => 'التخزين',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $storageValues = [
            ['value' => '128GB', 'value_ar' => '128 جيجابايت', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => '256GB', 'value_ar' => '256 جيجابايت', 'price_adjustment' => 100, 'sort_order' => 2],
            ['value' => '512GB', 'value_ar' => '512 جيجابايت', 'price_adjustment' => 300, 'sort_order' => 3],
            ['value' => '1TB', 'value_ar' => '1 تيرابايت', 'price_adjustment' => 600, 'sort_order' => 4]
        ];

        foreach ($storageValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $storageOption->id]));
        }

        // Color Option
        $colorOption = ProductOption::create([
            'product_id' => $smartphone->id,
            'name' => 'Color',
            'name_ar' => 'اللون',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $colorValues = [
            ['value' => 'Phantom Black', 'value_ar' => 'أسود شبح', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Phantom White', 'value_ar' => 'أبيض شبح', 'price_adjustment' => 0, 'sort_order' => 2],
            ['value' => 'Titanium Gray', 'value_ar' => 'رمادي تيتانيوم', 'price_adjustment' => 50, 'sort_order' => 3],
            ['value' => 'Titanium Violet', 'value_ar' => 'بنفسجي تيتانيوم', 'price_adjustment' => 50, 'sort_order' => 4]
        ];

        foreach ($colorValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $colorOption->id]));
        }
    }

    private function createHeadphonesProduct()
    {
        $headphones = Product::create([
            'category_id' => Category::where('name', 'like', '%audio%')->first()->id ?? 1,
            'name' => 'Noise Cancelling Pro',
            'name_ar' => 'إلغاء الضوضاء برو',
            'description' => 'Premium wireless headphones with active noise cancellation and exceptional sound quality',
            'description_ar' => 'سماعات لاسلكية مميزة مع إلغاء ضوضاء نشط وجودة صوت استثنائية',
            'base_price' => 299.99,
            'is_active' => true,
            'sort_order' => 3,
            'specifications' => [
                'brand' => 'AudioMax',
                'model' => 'NC-Pro 500',
                'driver_size' => '40mm',
                'frequency_response' => '20Hz-20kHz',
                'impedance' => '32Ω',
                'battery_life' => '30 hours',
                'noise_cancellation' => 'Active',
                'connectivity' => 'Bluetooth 5.2',
                'water_resistance' => 'IPX4',
                'warranty' => '2 years'
            ]
        ]);

        // Color Option
        $colorOption = ProductOption::create([
            'product_id' => $headphones->id,
            'name' => 'Color',
            'name_ar' => 'اللون',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $colorValues = [
            ['value' => 'Black', 'value_ar' => 'أسود', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'White', 'value_ar' => 'أبيض', 'price_adjustment' => 0, 'sort_order' => 2],
            ['value' => 'Blue', 'value_ar' => 'أزرق', 'price_adjustment' => 25, 'sort_order' => 3],
            ['value' => 'Rose Gold', 'value_ar' => 'ذهبي وردي', 'price_adjustment' => 50, 'sort_order' => 4]
        ];

        foreach ($colorValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $colorOption->id]));
        }

        // Warranty Option
        $warrantyOption = ProductOption::create([
            'product_id' => $headphones->id,
            'name' => 'Extended Warranty',
            'name_ar' => 'ضمان ممتد',
            'type' => 'select',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $warrantyValues = [
            ['value' => '2 Years (Standard)', 'value_ar' => 'سنتان (قياسي)', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => '3 Years', 'value_ar' => '3 سنوات', 'price_adjustment' => 50, 'sort_order' => 2],
            ['value' => '5 Years', 'value_ar' => '5 سنوات', 'price_adjustment' => 100, 'sort_order' => 3]
        ];

        foreach ($warrantyValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $warrantyOption->id]));
        }
    }

    private function createGamingMouseProduct()
    {
        $mouse = Product::create([
            'category_id' => Category::where('name', 'like', '%gaming%')->first()->id ?? 1,
            'name' => 'Pro Gaming Mouse X1',
            'name_ar' => 'ماوس ألعاب برو إكس 1',
            'description' => 'High-performance gaming mouse with customizable RGB lighting and precision sensors',
            'description_ar' => 'ماوس ألعاب عالي الأداء مع إضاءة RGB قابلة للتخصيص ومستشعرات دقيقة',
            'base_price' => 89.99,
            'is_active' => true,
            'sort_order' => 4,
            'specifications' => [
                'brand' => 'GameZone',
                'model' => 'Pro-X1',
                'sensor' => 'PixArt PAW3395',
                'dpi' => '26000',
                'ips' => '650',
                'acceleration' => '50G',
                'buttons' => '7 programmable',
                'rgb_lighting' => '16.8M colors',
                'weight' => '75g',
                'warranty' => '2 years'
            ]
        ]);

        // DPI Option
        $dpiOption = ProductOption::create([
            'product_id' => $mouse->id,
            'name' => 'DPI Range',
            'name_ar' => 'نطاق DPI',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $dpiValues = [
            ['value' => 'Up to 16000 DPI', 'value_ar' => 'حتى 16000 DPI', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Up to 26000 DPI', 'value_ar' => 'حتى 26000 DPI', 'price_adjustment' => 30, 'sort_order' => 2],
            ['value' => 'Up to 32000 DPI', 'value_ar' => 'حتى 32000 DPI', 'price_adjustment' => 60, 'sort_order' => 3]
        ];

        foreach ($dpiValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $dpiOption->id]));
        }

        // Color Option
        $colorOption = ProductOption::create([
            'product_id' => $mouse->id,
            'name' => 'Color',
            'name_ar' => 'اللون',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $colorValues = [
            ['value' => 'Black', 'value_ar' => 'أسود', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'White', 'value_ar' => 'أبيض', 'price_adjustment' => 10, 'sort_order' => 2],
            ['value' => 'Pink', 'value_ar' => 'وردي', 'price_adjustment' => 15, 'sort_order' => 3]
        ];

        foreach ($colorValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $colorOption->id]));
        }
    }

    private function createKeyboardProduct()
    {
        $keyboard = Product::create([
            'category_id' => Category::where('name', 'like', '%keyboard%')->first()->id ?? 1,
            'name' => 'Mechanical Gaming Keyboard',
            'name_ar' => 'لوحة مفاتيح ألعاب ميكانيكية',
            'description' => 'Premium mechanical keyboard with customizable switches and RGB backlighting',
            'description_ar' => 'لوحة مفاتيح ميكانيكية مميزة مع مفاتيح قابلة للتخصيص وإضاءة خلفية RGB',
            'base_price' => 149.99,
            'is_active' => true,
            'sort_order' => 5,
            'specifications' => [
                'brand' => 'KeyMaster',
                'model' => 'Mech-Gaming Pro',
                'switches' => 'Cherry MX',
                'layout' => 'Full-size',
                'keys' => '104 keys',
                'backlight' => 'RGB',
                'connectivity' => 'USB-C',
                'material' => 'Aluminum',
                'warranty' => '2 years'
            ]
        ]);

        // Switch Type Option
        $switchOption = ProductOption::create([
            'product_id' => $keyboard->id,
            'name' => 'Switch Type',
            'name_ar' => 'نوع المفتاح',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $switchValues = [
            ['value' => 'Cherry MX Red', 'value_ar' => 'شيري إم إكس أحمر', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Cherry MX Blue', 'value_ar' => 'شيري إم إكس أزرق', 'price_adjustment' => 0, 'sort_order' => 2],
            ['value' => 'Cherry MX Brown', 'value_ar' => 'شيري إم إكس بني', 'price_adjustment' => 0, 'sort_order' => 3],
            ['value' => 'Cherry MX Silent', 'value_ar' => 'شيري إم إكس صامت', 'price_adjustment' => 25, 'sort_order' => 4]
        ];

        foreach ($switchValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $switchOption->id]));
        }

        // Layout Option
        $layoutOption = ProductOption::create([
            'product_id' => $keyboard->id,
            'name' => 'Layout',
            'name_ar' => 'التخطيط',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $layoutValues = [
            ['value' => 'Full-size (104 keys)', 'value_ar' => 'حجم كامل (104 مفتاح)', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Tenkeyless (87 keys)', 'value_ar' => 'بدون أرقام (87 مفتاح)', 'price_adjustment' => -20, 'sort_order' => 2],
            ['value' => 'Compact (60 keys)', 'value_ar' => 'مدمج (60 مفتاح)', 'price_adjustment' => -40, 'sort_order' => 3]
        ];

        foreach ($layoutValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $layoutOption->id]));
        }
    }
}
