<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\OptionValue;
use Illuminate\Database\Seeder;

class ProductOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get latest 10 products
        $products = Product::latest()->take(10)->get();

        foreach ($products as $product) {
            // Add Size option (Select type)
            $sizeOption = ProductOption::create([
                'product_id' => $product->id,
                'name' => 'Size',
                'name_ar' => 'الحجم',
                'type' => 'select',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            // Add size values
            OptionValue::create([
                'product_option_id' => $sizeOption->id,
                'value' => 'Small',
                'value_ar' => 'صغير',
                'price_adjustment' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            OptionValue::create([
                'product_option_id' => $sizeOption->id,
                'value' => 'Medium',
                'value_ar' => 'وسط',
                'price_adjustment' => 10,
                'is_active' => true,
                'sort_order' => 2,
            ]);

            OptionValue::create([
                'product_option_id' => $sizeOption->id,
                'value' => 'Large',
                'value_ar' => 'كبير',
                'price_adjustment' => 20,
                'is_active' => true,
                'sort_order' => 3,
            ]);

            // Add Material Type option (Radio type)
            $materialOption = ProductOption::create([
                'product_id' => $product->id,
                'name' => 'Material Type',
                'name_ar' => 'نوع المادة',
                'type' => 'radio',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 2,
            ]);

            // Add material values
            OptionValue::create([
                'product_option_id' => $materialOption->id,
                'value' => 'Matte',
                'value_ar' => 'مطفي',
                'price_adjustment' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            OptionValue::create([
                'product_option_id' => $materialOption->id,
                'value' => 'Glossy',
                'value_ar' => 'لامع',
                'price_adjustment' => 5,
                'is_active' => true,
                'sort_order' => 2,
            ]);

            OptionValue::create([
                'product_option_id' => $materialOption->id,
                'value' => 'Premium',
                'value_ar' => 'فاخر',
                'price_adjustment' => 15,
                'is_active' => true,
                'sort_order' => 3,
            ]);

            // Add Color option (Radio type)
            $colorOption = ProductOption::create([
                'product_id' => $product->id,
                'name' => 'Color',
                'name_ar' => 'اللون',
                'type' => 'radio',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 3,
            ]);

            // Add color values
            $colors = [
                ['value' => 'White', 'value_ar' => 'أبيض', 'price' => 0],
                ['value' => 'Black', 'value_ar' => 'أسود', 'price' => 0],
                ['value' => 'Red', 'value_ar' => 'أحمر', 'price' => 5],
                ['value' => 'Blue', 'value_ar' => 'أزرق', 'price' => 5],
                ['value' => 'Gold', 'value_ar' => 'ذهبي', 'price' => 10],
            ];

            foreach ($colors as $index => $color) {
                OptionValue::create([
                    'product_option_id' => $colorOption->id,
                    'value' => $color['value'],
                    'value_ar' => $color['value_ar'],
                    'price_adjustment' => $color['price'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
            }

            // Add Quantity option (Select type) for some variety
            $quantityOption = ProductOption::create([
                'product_id' => $product->id,
                'name' => 'Package Size',
                'name_ar' => 'حجم الحزمة',
                'type' => 'select',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 4,
            ]);

            // Add quantity values
            OptionValue::create([
                'product_option_id' => $quantityOption->id,
                'value' => 'Single Pack',
                'value_ar' => 'حزمة واحدة',
                'price_adjustment' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            OptionValue::create([
                'product_option_id' => $quantityOption->id,
                'value' => 'Pack of 3',
                'value_ar' => 'حزمة من 3',
                'price_adjustment' => 25,
                'is_active' => true,
                'sort_order' => 2,
            ]);

            OptionValue::create([
                'product_option_id' => $quantityOption->id,
                'value' => 'Pack of 5',
                'value_ar' => 'حزمة من 5',
                'price_adjustment' => 40,
                'is_active' => true,
                'sort_order' => 3,
            ]);
        }

        $this->command->info('✅ Product options seeded successfully for ' . $products->count() . ' products!');
    }
}

