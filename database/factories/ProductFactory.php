<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
  protected $model = Product::class;

  public function definition()
  {
    // Sample product data arrays
    $productNames = [
      'Laptop Pro X1',
      'Smartphone Galaxy S',
      'Wireless Headphones',
      'Gaming Mouse',
      'Mechanical Keyboard',
      '4K Monitor',
      'Bluetooth Speaker',
      'USB-C Cable',
      'Power Bank',
      'Webcam HD',
      'Tablet Pro',
      'Smart Watch',
      'Wireless Charger',
      'Laptop Stand',
      'Monitor Arm',
      'Desk Lamp',
      'Office Chair',
      'Standing Desk',
      'File Cabinet',
      'Whiteboard',
      'Projector',
      'Microphone',
      'Camera Tripod',
      'Green Screen',
      'LED Strip',
      'Router WiFi',
      'Network Switch',
      'Security Camera',
      'Smart Bulb',
      'Thermostat',
      'Coffee Maker',
      'Blender',
      'Toaster',
      'Microwave',
      'Refrigerator',
      'Washing Machine',
      'Dishwasher',
      'Vacuum Cleaner',
      'Air Purifier',
      'Humidifier'
    ];

    $productNamesAr = [
      'لابتوب برو إكس 1',
      'هاتف ذكي جالاكسي إس',
      'سماعات لاسلكية',
      'ماوس ألعاب',
      'لوحة مفاتيح ميكانيكية',
      'شاشة 4K',
      'مكبر صوت بلوتوث',
      'كابل USB-C',
      'بنك طاقة',
      'كاميرا ويب HD',
      'تابلت برو',
      'ساعة ذكية',
      'شاحن لاسلكي',
      'حامل لابتوب',
      'ذراع شاشة',
      'مصباح مكتب',
      'كرسي مكتب',
      'طاولة وقوف',
      'خزانة ملفات',
      'سبورة بيضاء',
      'عرض ضوئي',
      'ميكروفون',
      'حامل كاميرا',
      'خلفية خضراء',
      'شريط LED',
      'راوتر واي فاي',
      'مبدل شبكة',
      'كاميرا أمان',
      'لمبة ذكية',
      'منظم حرارة',
      'صانع قهوة',
      'خلاط',
      'محمصة',
      'مايكروويف',
      'ثلاجة',
      'غسالة ملابس',
      'غسالة صحون',
      'مكنسة كهربائية',
      'منقي هواء',
      'مرطب'
    ];

    $descriptions = [
      'High-performance device with cutting-edge technology and premium build quality.',
      'Advanced features with intuitive user interface and seamless connectivity.',
      'Professional-grade equipment designed for optimal performance and reliability.',
      'Innovative design with ergonomic features for enhanced user experience.',
      'Premium materials and craftsmanship for long-lasting durability.',
      'Smart technology integration for modern lifestyle convenience.',
      'Energy-efficient design with eco-friendly manufacturing processes.',
      'Versatile functionality suitable for both personal and professional use.',
      'Compact and portable design for maximum convenience and mobility.',
      'Advanced security features with robust protection mechanisms.'
    ];

    $descriptionsAr = [
      'جهاز عالي الأداء مع تقنية متطورة وجودة بناء متميزة.',
      'ميزات متقدمة مع واجهة مستخدم بديهية واتصال سلس.',
      'معدات احترافية مصممة للأداء الأمثل والموثوقية.',
      'تصميم مبتكر مع ميزات ارجونوميكية لتحسين تجربة المستخدم.',
      'مواد متميزة وحرفية للديمومة طويلة الأمد.',
      'تكامل تقني ذكي لراحة نمط الحياة الحديث.',
      'تصميم موفر للطاقة مع عمليات تصنيع صديقة للبيئة.',
      'وظائف متعددة مناسبة للاستخدام الشخصي والمهني.',
      'تصميم مضغوط ومحمول للراحة والحركة القصوى.',
      'ميزات أمان متقدمة مع آليات حماية قوية.'
    ];

    $specifications = [
      [
        'brand' => 'TechCorp',
        'model' => 'TC-2024',
        'warranty' => '2 years',
        'weight' => '1.5 kg',
        'dimensions' => '30 x 20 x 5 cm',
        'color' => 'Black',
        'material' => 'Aluminum',
        'connectivity' => 'WiFi 6, Bluetooth 5.0',
        'battery' => '5000mAh',
        'screen_size' => '15.6 inch',
        'resolution' => '1920x1080'
      ],
      [
        'brand' => 'SmartTech',
        'model' => 'ST-Pro',
        'warranty' => '1 year',
        'weight' => '200g',
        'dimensions' => '15 x 8 x 1 cm',
        'color' => 'Silver',
        'material' => 'Plastic',
        'connectivity' => 'USB-C, Wireless',
        'battery' => '3000mAh',
        'screen_size' => '6.1 inch',
        'resolution' => '2560x1440'
      ],
      [
        'brand' => 'AudioMax',
        'model' => 'AM-500',
        'warranty' => '3 years',
        'weight' => '300g',
        'dimensions' => '18 x 15 x 8 cm',
        'color' => 'Blue',
        'material' => 'Leather',
        'connectivity' => 'Bluetooth 5.2',
        'battery' => '40 hours',
        'noise_cancellation' => 'Active',
        'water_resistance' => 'IPX4'
      ],
      [
        'brand' => 'GameZone',
        'model' => 'GZ-X1',
        'warranty' => '2 years',
        'weight' => '120g',
        'dimensions' => '12 x 7 x 4 cm',
        'color' => 'RGB',
        'material' => 'Plastic',
        'connectivity' => 'USB, Wireless',
        'dpi' => '16000',
        'buttons' => '7 programmable',
        'rgb_lighting' => '16.8M colors'
      ],
      [
        'brand' => 'KeyMaster',
        'model' => 'KM-Pro',
        'warranty' => '1 year',
        'weight' => '900g',
        'dimensions' => '44 x 13 x 3 cm',
        'color' => 'Black',
        'material' => 'Aluminum',
        'connectivity' => 'USB-C',
        'switches' => 'Cherry MX Blue',
        'backlight' => 'RGB',
        'keys' => '104 keys'
      ]
    ];

    $name = $this->faker->randomElement($productNames);
    $nameAr = $productNamesAr[array_search($name, $productNames)];
    $description = $this->faker->randomElement($descriptions);
    $descriptionAr = $descriptionsAr[array_search($description, $descriptions)];
    $specs = $this->faker->randomElement($specifications);

    return [
      'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
      'name' => $name,
      'name_ar' => $nameAr,
      'description' => $description,
      'description_ar' => $descriptionAr,
      'base_price' => $this->faker->randomFloat(2, 50, 2000),
      'is_active' => $this->faker->boolean(80), // 80% chance of being active
      'sort_order' => $this->faker->numberBetween(1, 100),
      'specifications' => $specs,
    ];
  }

  /**
   * Indicate that the product is active.
   */
  public function active()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_active' => true,
      ];
    });
  }

  /**
   * Indicate that the product is inactive.
   */
  public function inactive()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_active' => false,
      ];
    });
  }

  /**
   * Indicate that the product is expensive (high price).
   */
  public function expensive()
  {
    return $this->state(function (array $attributes) {
      return [
        'base_price' => $this->faker->randomFloat(2, 1000, 5000),
      ];
    });
  }

  /**
   * Indicate that the product is affordable (low price).
   */
  public function affordable()
  {
    return $this->state(function (array $attributes) {
      return [
        'base_price' => $this->faker->randomFloat(2, 10, 100),
      ];
    });
  }
}
