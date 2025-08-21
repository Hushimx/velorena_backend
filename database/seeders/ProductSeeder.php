<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\OptionValue;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Business Cards Product
        $businessCards = Product::create([
            'category_id' => 1, // Business Cards category
            'name' => 'Standard Business Cards',
            'name_ar' => 'بطاقات عمل قياسية',
            'description' => 'Professional business cards with various customization options',
            'description_ar' => 'بطاقات عمل احترافية مع خيارات تخصيص متنوعة',
            'base_price' => 50.00,
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Add options for business cards
        $this->createBusinessCardOptions($businessCards);

        // Brochures Product
        $brochures = Product::create([
            'category_id' => 2, // Brochures & Catalogs category
            'name' => 'Tri-Fold Brochures',
            'name_ar' => 'كتيبات ثلاثية الطي',
            'description' => 'Professional tri-fold brochures for marketing campaigns',
            'description_ar' => 'كتيبات ثلاثية الطي احترافية لحملات التسويق',
            'base_price' => 200.00,
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Add options for brochures
        $this->createBrochureOptions($brochures);

        // Banners Product
        $banners = Product::create([
            'category_id' => 3, // Banners & Signs category
            'name' => 'Roll-Up Banners',
            'name_ar' => 'لافتات قابلة للطي',
            'description' => 'Professional roll-up banners for events and exhibitions',
            'description_ar' => 'لافتات قابلة للطي احترافية للمعارض والفعاليات',
            'base_price' => 300.00,
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Add options for banners
        $this->createBannerOptions($banners);
    }

    private function createBusinessCardOptions($product)
    {
        // Paper Size Option
        $sizeOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Paper Size',
            'name_ar' => 'حجم الورق',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $sizeValues = [
            ['value' => 'Standard (85x55mm)', 'value_ar' => 'قياسي (85x55مم)', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Large (90x60mm)', 'value_ar' => 'كبير (90x60مم)', 'price_adjustment' => 10, 'sort_order' => 2],
            ['value' => 'Square (55x55mm)', 'value_ar' => 'مربع (55x55مم)', 'price_adjustment' => 5, 'sort_order' => 3]
        ];

        foreach ($sizeValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $sizeOption->id]));
        }

        // Paper Type Option
        $paperOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Paper Type',
            'name_ar' => 'نوع الورق',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $paperValues = [
            ['value' => 'Standard (300gsm)', 'value_ar' => 'قياسي (300جم)', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Premium (400gsm)', 'value_ar' => 'مميز (400جم)', 'price_adjustment' => 15, 'sort_order' => 2],
            ['value' => 'Luxury (500gsm)', 'value_ar' => 'فاخر (500جم)', 'price_adjustment' => 25, 'sort_order' => 3]
        ];

        foreach ($paperValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $paperOption->id]));
        }

        // Finish Option
        $finishOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Finish',
            'name_ar' => 'الإنهاء',
            'type' => 'select',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 3
        ]);

        $finishValues = [
            ['value' => 'Standard', 'value_ar' => 'قياسي', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'UV Coating', 'value_ar' => 'طلاء بالأشعة فوق البنفسجية', 'price_adjustment' => 20, 'sort_order' => 2],
            ['value' => 'Spot UV', 'value_ar' => 'طلاء موضعي بالأشعة فوق البنفسجية', 'price_adjustment' => 30, 'sort_order' => 3]
        ];

        foreach ($finishValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $finishOption->id]));
        }
    }

    private function createBrochureOptions($product)
    {
        // Size Option
        $sizeOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Size',
            'name_ar' => 'الحجم',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $sizeValues = [
            ['value' => 'A4 (210x297mm)', 'value_ar' => 'A4 (210x297مم)', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'A5 (148x210mm)', 'value_ar' => 'A5 (148x210مم)', 'price_adjustment' => -50, 'sort_order' => 2],
            ['value' => 'A3 (297x420mm)', 'value_ar' => 'A3 (297x420مم)', 'price_adjustment' => 100, 'sort_order' => 3]
        ];

        foreach ($sizeValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $sizeOption->id]));
        }

        // Paper Type Option
        $paperOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Paper Type',
            'name_ar' => 'نوع الورق',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $paperValues = [
            ['value' => 'Glossy (150gsm)', 'value_ar' => 'لامع (150جم)', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Matte (150gsm)', 'value_ar' => 'مطفي (150جم)', 'price_adjustment' => 10, 'sort_order' => 2],
            ['value' => 'Premium (200gsm)', 'value_ar' => 'مميز (200جم)', 'price_adjustment' => 30, 'sort_order' => 3]
        ];

        foreach ($paperValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $paperOption->id]));
        }

        // Quantity Option
        $quantityOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Quantity',
            'name_ar' => 'الكمية',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 3
        ]);

        $quantityValues = [
            ['value' => '100 pieces', 'value_ar' => '100 قطعة', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => '250 pieces', 'value_ar' => '250 قطعة', 'price_adjustment' => 150, 'sort_order' => 2],
            ['value' => '500 pieces', 'value_ar' => '500 قطعة', 'price_adjustment' => 250, 'sort_order' => 3],
            ['value' => '1000 pieces', 'value_ar' => '1000 قطعة', 'price_adjustment' => 400, 'sort_order' => 4]
        ];

        foreach ($quantityValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $quantityOption->id]));
        }
    }

    private function createBannerOptions($product)
    {
        // Size Option
        $sizeOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Size',
            'name_ar' => 'الحجم',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $sizeValues = [
            ['value' => '85x200cm', 'value_ar' => '85x200سم', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => '100x200cm', 'value_ar' => '100x200سم', 'price_adjustment' => 50, 'sort_order' => 2],
            ['value' => '120x200cm', 'value_ar' => '120x200سم', 'price_adjustment' => 100, 'sort_order' => 3]
        ];

        foreach ($sizeValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $sizeOption->id]));
        }

        // Material Option
        $materialOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Material',
            'name_ar' => 'المادة',
            'type' => 'select',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $materialValues = [
            ['value' => 'Vinyl', 'value_ar' => 'فينيل', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Fabric', 'value_ar' => 'قماش', 'price_adjustment' => 30, 'sort_order' => 2],
            ['value' => 'Premium Vinyl', 'value_ar' => 'فينيل مميز', 'price_adjustment' => 20, 'sort_order' => 3]
        ];

        foreach ($materialValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $materialOption->id]));
        }

        // Stand Option
        $standOption = ProductOption::create([
            'product_id' => $product->id,
            'name' => 'Stand',
            'name_ar' => 'الحامل',
            'type' => 'select',
            'is_required' => false,
            'is_active' => true,
            'sort_order' => 3
        ]);

        $standValues = [
            ['value' => 'No Stand', 'value_ar' => 'بدون حامل', 'price_adjustment' => 0, 'sort_order' => 1],
            ['value' => 'Basic Stand', 'value_ar' => 'حامل أساسي', 'price_adjustment' => 80, 'sort_order' => 2],
            ['value' => 'Premium Stand', 'value_ar' => 'حامل مميز', 'price_adjustment' => 150, 'sort_order' => 3]
        ];

        foreach ($standValues as $value) {
            OptionValue::create(array_merge($value, ['product_option_id' => $standOption->id]));
        }
    }
}
