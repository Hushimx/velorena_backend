<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class MoreProductsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get all active categories
    $categories = Category::where('is_active', true)->get();

    if ($categories->isEmpty()) {
      $this->command->error('No active categories found. Please run CategorySeeder first.');
      return;
    }

    $this->command->info('Adding more products for pagination testing...');

    // Product data for different categories - 20 products per category
    $productData = [
      // Electronics (20 products)
      [
        'name' => 'Smartphone Pro Max',
        'name_ar' => 'هاتف ذكي برو ماكس',
        'description' => 'Latest smartphone with advanced features',
        'description_ar' => 'أحدث هاتف ذكي بميزات متقدمة',
        'base_price' => 899.99
      ],
      [
        'name' => 'Wireless Headphones',
        'name_ar' => 'سماعات لاسلكية',
        'description' => 'Premium wireless headphones with noise cancellation',
        'description_ar' => 'سماعات لاسلكية مميزة مع إلغاء الضوضاء',
        'base_price' => 199.99
      ],
      [
        'name' => 'Gaming Laptop',
        'name_ar' => 'لابتوب ألعاب',
        'description' => 'High-performance gaming laptop',
        'description_ar' => 'لابتوب ألعاب عالي الأداء',
        'base_price' => 1299.99
      ],
      [
        'name' => 'Smart Watch',
        'name_ar' => 'ساعة ذكية',
        'description' => 'Advanced smartwatch with health monitoring',
        'description_ar' => 'ساعة ذكية متقدمة مع مراقبة الصحة',
        'base_price' => 299.99
      ],
      [
        'name' => 'Tablet Pro',
        'name_ar' => 'تابلت برو',
        'description' => 'Professional tablet for work and entertainment',
        'description_ar' => 'تابلت مهني للعمل والترفيه',
        'base_price' => 599.99
      ],
      [
        'name' => 'Bluetooth Speaker',
        'name_ar' => 'مكبر صوت بلوتوث',
        'description' => 'Portable Bluetooth speaker with great sound',
        'description_ar' => 'مكبر صوت بلوتوث محمول بصوت رائع',
        'base_price' => 79.99
      ],
      [
        'name' => 'Gaming Mouse',
        'name_ar' => 'ماوس ألعاب',
        'description' => 'High-precision gaming mouse',
        'description_ar' => 'ماوس ألعاب عالي الدقة',
        'base_price' => 49.99
      ],
      [
        'name' => 'Mechanical Keyboard',
        'name_ar' => 'لوحة مفاتيح ميكانيكية',
        'description' => 'Premium mechanical keyboard',
        'description_ar' => 'لوحة مفاتيح ميكانيكية مميزة',
        'base_price' => 129.99
      ],
      [
        'name' => 'Webcam HD',
        'name_ar' => 'كاميرا ويب عالية الدقة',
        'description' => 'HD webcam for video calls',
        'description_ar' => 'كاميرا ويب عالية الدقة للمكالمات المرئية',
        'base_price' => 89.99
      ],
      [
        'name' => 'External Hard Drive',
        'name_ar' => 'قرص صلب خارجي',
        'description' => '1TB external hard drive',
        'description_ar' => 'قرص صلب خارجي 1 تيرابايت',
        'base_price' => 59.99
      ],
      [
        'name' => 'USB-C Hub',
        'name_ar' => 'محور USB-C',
        'description' => 'Multi-port USB-C hub',
        'description_ar' => 'محور USB-C متعدد المنافذ',
        'base_price' => 39.99
      ],
      [
        'name' => 'Wireless Charger',
        'name_ar' => 'شاحن لاسلكي',
        'description' => 'Fast wireless charging pad',
        'description_ar' => 'وسادة شحن لاسلكي سريع',
        'base_price' => 29.99
      ],
      [
        'name' => 'Gaming Monitor',
        'name_ar' => 'شاشة ألعاب',
        'description' => 'High refresh rate gaming monitor',
        'description_ar' => 'شاشة ألعاب بمعدل تحديث عالي',
        'base_price' => 399.99
      ],
      [
        'name' => 'RGB Strip Lights',
        'name_ar' => 'أضواء LED RGB',
        'description' => 'Smart RGB LED strip lights',
        'description_ar' => 'أضواء LED RGB ذكية',
        'base_price' => 24.99
      ],
      [
        'name' => 'Power Bank',
        'name_ar' => 'بنك طاقة',
        'description' => 'High capacity portable power bank',
        'description_ar' => 'بنك طاقة محمول عالي السعة',
        'base_price' => 49.99
      ],
      [
        'name' => 'Cable Management Kit',
        'name_ar' => 'طقم إدارة الكابلات',
        'description' => 'Cable management and organization kit',
        'description_ar' => 'طقم إدارة وتنظيم الكابلات',
        'base_price' => 19.99
      ],
      [
        'name' => 'Desk Lamp',
        'name_ar' => 'مصباح مكتب',
        'description' => 'LED desk lamp with adjustable brightness',
        'description_ar' => 'مصباح مكتب LED بسطوع قابل للتعديل',
        'base_price' => 34.99
      ],
      [
        'name' => 'Microphone',
        'name_ar' => 'ميكروفون',
        'description' => 'Professional USB microphone',
        'description_ar' => 'ميكروفون USB مهني',
        'base_price' => 79.99
      ],
      [
        'name' => 'Router WiFi 6',
        'name_ar' => 'موجه واي فاي 6',
        'description' => 'High-speed WiFi 6 router',
        'description_ar' => 'موجه واي فاي 6 عالي السرعة',
        'base_price' => 149.99
      ],

      // Clothing (20 products)
      [
        'name' => 'Cotton T-Shirt',
        'name_ar' => 'قميص قطني',
        'description' => 'Comfortable cotton t-shirt',
        'description_ar' => 'قميص قطني مريح',
        'base_price' => 19.99
      ],
      [
        'name' => 'Denim Jeans',
        'name_ar' => 'جينز',
        'description' => 'Classic denim jeans',
        'description_ar' => 'جينز كلاسيكي',
        'base_price' => 49.99
      ],
      [
        'name' => 'Hoodie',
        'name_ar' => 'هودي',
        'description' => 'Warm and comfortable hoodie',
        'description_ar' => 'هودي دافئ ومريح',
        'base_price' => 39.99
      ],
      [
        'name' => 'Running Shoes',
        'name_ar' => 'حذاء جري',
        'description' => 'Professional running shoes',
        'description_ar' => 'حذاء جري مهني',
        'base_price' => 89.99
      ],
      [
        'name' => 'Winter Jacket',
        'name_ar' => 'معطف شتوي',
        'description' => 'Warm winter jacket',
        'description_ar' => 'معطف شتوي دافئ',
        'base_price' => 79.99
      ],
      [
        'name' => 'Summer Dress',
        'name_ar' => 'فستان صيفي',
        'description' => 'Light and elegant summer dress',
        'description_ar' => 'فستان صيفي خفيف وأنيق',
        'base_price' => 34.99
      ],
      [
        'name' => 'Baseball Cap',
        'name_ar' => 'قبعة بيسبول',
        'description' => 'Stylish baseball cap',
        'description_ar' => 'قبعة بيسبول أنيقة',
        'base_price' => 14.99
      ],
      [
        'name' => 'Leather Belt',
        'name_ar' => 'حزام جلد',
        'description' => 'Genuine leather belt',
        'description_ar' => 'حزام جلد أصلي',
        'base_price' => 24.99
      ],
      [
        'name' => 'Sunglasses',
        'name_ar' => 'نظارات شمسية',
        'description' => 'UV protection sunglasses',
        'description_ar' => 'نظارات شمسية واقية من الأشعة فوق البنفسجية',
        'base_price' => 29.99
      ],
      [
        'name' => 'Backpack',
        'name_ar' => 'حقيبة ظهر',
        'description' => 'Durable backpack for daily use',
        'description_ar' => 'حقيبة ظهر متينة للاستخدام اليومي',
        'base_price' => 44.99
      ],
      [
        'name' => 'Polo Shirt',
        'name_ar' => 'قميص بولو',
        'description' => 'Classic polo shirt',
        'description_ar' => 'قميص بولو كلاسيكي',
        'base_price' => 29.99
      ],
      [
        'name' => 'Sweater',
        'name_ar' => 'سترة',
        'description' => 'Warm wool sweater',
        'description_ar' => 'سترة صوف دافئة',
        'base_price' => 54.99
      ],
      [
        'name' => 'Shorts',
        'name_ar' => 'شورت',
        'description' => 'Comfortable summer shorts',
        'description_ar' => 'شورت صيفي مريح',
        'base_price' => 24.99
      ],
      [
        'name' => 'Blazer',
        'name_ar' => 'بليزر',
        'description' => 'Professional blazer',
        'description_ar' => 'بليزر مهني',
        'base_price' => 89.99
      ],
      [
        'name' => 'Tank Top',
        'name_ar' => 'قميص بدون أكمام',
        'description' => 'Comfortable tank top',
        'description_ar' => 'قميص بدون أكمام مريح',
        'base_price' => 16.99
      ],
      [
        'name' => 'Cardigan',
        'name_ar' => 'كارديجان',
        'description' => 'Warm cardigan sweater',
        'description_ar' => 'كارديجان دافئ',
        'base_price' => 39.99
      ],
      [
        'name' => 'Tracksuit',
        'name_ar' => 'بدلة رياضية',
        'description' => 'Comfortable tracksuit',
        'description_ar' => 'بدلة رياضية مريحة',
        'base_price' => 64.99
      ],
      [
        'name' => 'Scarf',
        'name_ar' => 'وشاح',
        'description' => 'Warm winter scarf',
        'description_ar' => 'وشاح شتوي دافئ',
        'base_price' => 19.99
      ],
      [
        'name' => 'Gloves',
        'name_ar' => 'قفازات',
        'description' => 'Warm winter gloves',
        'description_ar' => 'قفازات شتوية دافئة',
        'base_price' => 14.99
      ],
      [
        'name' => 'Socks Pack',
        'name_ar' => 'طقم جوارب',
        'description' => 'Pack of comfortable socks',
        'description_ar' => 'طقم جوارب مريحة',
        'base_price' => 12.99
      ],

      // Home & Garden (20 products)
      [
        'name' => 'Coffee Maker',
        'name_ar' => 'صانع قهوة',
        'description' => 'Automatic coffee maker',
        'description_ar' => 'صانع قهوة أوتوماتيكي',
        'base_price' => 99.99
      ],
      [
        'name' => 'Air Purifier',
        'name_ar' => 'منقي هواء',
        'description' => 'HEPA air purifier for clean air',
        'description_ar' => 'منقي هواء HEPA للهواء النظيف',
        'base_price' => 149.99
      ],
      [
        'name' => 'Smart Thermostat',
        'name_ar' => 'منظم حرارة ذكي',
        'description' => 'WiFi enabled smart thermostat',
        'description_ar' => 'منظم حرارة ذكي متصل بالواي فاي',
        'base_price' => 199.99
      ],
      [
        'name' => 'Garden Tools Set',
        'name_ar' => 'مجموعة أدوات حديقة',
        'description' => 'Complete garden tools set',
        'description_ar' => 'مجموعة أدوات حديقة كاملة',
        'base_price' => 69.99
      ],
      [
        'name' => 'LED Light Bulbs',
        'name_ar' => 'مصابيح LED',
        'description' => 'Energy efficient LED bulbs',
        'description_ar' => 'مصابيح LED موفرة للطاقة',
        'base_price' => 12.99
      ],
      [
        'name' => 'Kitchen Knife Set',
        'name_ar' => 'مجموعة سكاكين مطبخ',
        'description' => 'Professional kitchen knife set',
        'description_ar' => 'مجموعة سكاكين مطبخ مهنية',
        'base_price' => 79.99
      ],
      [
        'name' => 'Vacuum Cleaner',
        'name_ar' => 'مكنسة كهربائية',
        'description' => 'Powerful cordless vacuum cleaner',
        'description_ar' => 'مكنسة كهربائية لاسلكية قوية',
        'base_price' => 199.99
      ],
      [
        'name' => 'Plant Pots Set',
        'name_ar' => 'مجموعة أواني نباتات',
        'description' => 'Decorative plant pots set',
        'description_ar' => 'مجموعة أواني نباتات زخرفية',
        'base_price' => 34.99
      ],
      [
        'name' => 'Smart Doorbell',
        'name_ar' => 'جرس باب ذكي',
        'description' => 'Video doorbell with mobile app',
        'description_ar' => 'جرس باب فيديو مع تطبيق الهاتف',
        'base_price' => 179.99
      ],
      [
        'name' => 'Blender',
        'name_ar' => 'خلاط',
        'description' => 'High-speed blender for smoothies',
        'description_ar' => 'خلاط عالي السرعة للعصائر',
        'base_price' => 89.99
      ],
      [
        'name' => 'Toaster',
        'name_ar' => 'محمصة خبز',
        'description' => '4-slice toaster with timer',
        'description_ar' => 'محمصة خبز 4 شرائح مع مؤقت',
        'base_price' => 39.99
      ],
      [
        'name' => 'Bed Sheets Set',
        'name_ar' => 'طقم ملاءات سرير',
        'description' => 'Cotton bed sheets set',
        'description_ar' => 'طقم ملاءات سرير قطنية',
        'base_price' => 29.99
      ],
      [
        'name' => 'Pillows',
        'name_ar' => 'وسائد',
        'description' => 'Memory foam pillows',
        'description_ar' => 'وسائد رغوة الذاكرة',
        'base_price' => 24.99
      ],
      [
        'name' => 'Curtains',
        'name_ar' => 'ستائر',
        'description' => 'Blackout curtains',
        'description_ar' => 'ستائر حاجبة للضوء',
        'base_price' => 44.99
      ],
      [
        'name' => 'Rug',
        'name_ar' => 'سجادة',
        'description' => 'Soft area rug',
        'description_ar' => 'سجادة ناعمة',
        'base_price' => 59.99
      ],
      [
        'name' => 'Lamp',
        'name_ar' => 'مصباح',
        'description' => 'Table lamp with LED bulb',
        'description_ar' => 'مصباح طاولة مع مصباح LED',
        'base_price' => 34.99
      ],
      [
        'name' => 'Mirror',
        'name_ar' => 'مرآة',
        'description' => 'Wall mirror with frame',
        'description_ar' => 'مرآة حائط مع إطار',
        'base_price' => 49.99
      ],
      [
        'name' => 'Candles Set',
        'name_ar' => 'طقم شموع',
        'description' => 'Scented candles set',
        'description_ar' => 'طقم شموع معطرة',
        'base_price' => 19.99
      ],
      [
        'name' => 'Vase',
        'name_ar' => 'مزهرية',
        'description' => 'Decorative ceramic vase',
        'description_ar' => 'مزهرية خزفية زخرفية',
        'base_price' => 24.99
      ],
      [
        'name' => 'Clock',
        'name_ar' => 'ساعة حائط',
        'description' => 'Digital wall clock',
        'description_ar' => 'ساعة حائط رقمية',
        'base_price' => 29.99
      ],

      // Sports & Outdoors (20 products)
      [
        'name' => 'Yoga Mat',
        'name_ar' => 'حصيرة يوجا',
        'description' => 'Non-slip yoga mat',
        'description_ar' => 'حصيرة يوجا غير قابلة للانزلاق',
        'base_price' => 29.99
      ],
      [
        'name' => 'Dumbbells Set',
        'name_ar' => 'مجموعة دمبل',
        'description' => 'Adjustable dumbbells set',
        'description_ar' => 'مجموعة دمبل قابلة للتعديل',
        'base_price' => 149.99
      ],
      [
        'name' => 'Running Watch',
        'name_ar' => 'ساعة جري',
        'description' => 'GPS running watch',
        'description_ar' => 'ساعة جري بنظام GPS',
        'base_price' => 199.99
      ],
      [
        'name' => 'Tennis Racket',
        'name_ar' => 'مضرب تنس',
        'description' => 'Professional tennis racket',
        'description_ar' => 'مضرب تنس مهني',
        'base_price' => 89.99
      ],
      [
        'name' => 'Camping Tent',
        'name_ar' => 'خيمة تخييم',
        'description' => '4-person camping tent',
        'description_ar' => 'خيمة تخييم لـ 4 أشخاص',
        'base_price' => 129.99
      ],
      [
        'name' => 'Bicycle',
        'name_ar' => 'دراجة هوائية',
        'description' => 'Mountain bicycle',
        'description_ar' => 'دراجة هوائية جبلية',
        'base_price' => 299.99
      ],
      [
        'name' => 'Swimming Goggles',
        'name_ar' => 'نظارات سباحة',
        'description' => 'Anti-fog swimming goggles',
        'description_ar' => 'نظارات سباحة مضادة للضباب',
        'base_price' => 19.99
      ],
      [
        'name' => 'Hiking Boots',
        'name_ar' => 'حذاء مشي لمسافات طويلة',
        'description' => 'Waterproof hiking boots',
        'description_ar' => 'حذاء مشي لمسافات طويلة مقاوم للماء',
        'base_price' => 119.99
      ],
      [
        'name' => 'Fitness Tracker',
        'name_ar' => 'متتبع اللياقة',
        'description' => 'Activity and sleep tracker',
        'description_ar' => 'متتبع النشاط والنوم',
        'base_price' => 79.99
      ],
      [
        'name' => 'Basketball',
        'name_ar' => 'كرة سلة',
        'description' => 'Official size basketball',
        'description_ar' => 'كرة سلة بالحجم الرسمي',
        'base_price' => 24.99
      ],
      [
        'name' => 'Resistance Bands',
        'name_ar' => 'أشرطة المقاومة',
        'description' => 'Set of resistance bands',
        'description_ar' => 'طقم أشرطة المقاومة',
        'base_price' => 19.99
      ],
      [
        'name' => 'Jump Rope',
        'name_ar' => 'حبل القفز',
        'description' => 'Adjustable jump rope',
        'description_ar' => 'حبل قفز قابل للتعديل',
        'base_price' => 9.99
      ],
      [
        'name' => 'Water Bottle',
        'name_ar' => 'زجاجة ماء',
        'description' => 'Insulated water bottle',
        'description_ar' => 'زجاجة ماء معزولة',
        'base_price' => 14.99
      ],
      [
        'name' => 'Gym Towel',
        'name_ar' => 'منشفة صالة رياضية',
        'description' => 'Quick-dry gym towel',
        'description_ar' => 'منشفة صالة رياضية سريعة الجفاف',
        'base_price' => 12.99
      ],
      [
        'name' => 'Protein Shaker',
        'name_ar' => 'خلاط البروتين',
        'description' => 'Protein powder shaker bottle',
        'description_ar' => 'زجاجة خلط مسحوق البروتين',
        'base_price' => 16.99
      ],
      [
        'name' => 'Exercise Ball',
        'name_ar' => 'كرة تمرين',
        'description' => 'Anti-burst exercise ball',
        'description_ar' => 'كرة تمرين مقاومة للانفجار',
        'base_price' => 24.99
      ],
      [
        'name' => 'Yoga Block',
        'name_ar' => 'كتلة يوجا',
        'description' => 'Foam yoga block',
        'description_ar' => 'كتلة يوجا رغوية',
        'base_price' => 14.99
      ],
      [
        'name' => 'Pilates Ring',
        'name_ar' => 'حلقة بيلاتس',
        'description' => 'Pilates resistance ring',
        'description_ar' => 'حلقة مقاومة بيلاتس',
        'base_price' => 19.99
      ],
      [
        'name' => 'Kettlebell',
        'name_ar' => 'كيتل بيل',
        'description' => 'Adjustable kettlebell',
        'description_ar' => 'كيتل بيل قابل للتعديل',
        'base_price' => 39.99
      ],
      [
        'name' => 'Foam Roller',
        'name_ar' => 'أسطوانة رغوية',
        'description' => 'Massage foam roller',
        'description_ar' => 'أسطوانة رغوية للتدليك',
        'base_price' => 29.99
      ],

      // Books & Media (20 products)
      [
        'name' => 'Programming Book',
        'name_ar' => 'كتاب برمجة',
        'description' => 'Learn programming fundamentals',
        'description_ar' => 'تعلم أساسيات البرمجة',
        'base_price' => 39.99
      ],
      [
        'name' => 'Cookbook',
        'name_ar' => 'كتاب طبخ',
        'description' => 'Traditional recipes cookbook',
        'description_ar' => 'كتاب وصفات تقليدية',
        'base_price' => 24.99
      ],
      [
        'name' => 'Novel',
        'name_ar' => 'رواية',
        'description' => 'Bestselling fiction novel',
        'description_ar' => 'رواية خيالية من الأكثر مبيعاً',
        'base_price' => 14.99
      ],
      [
        'name' => 'Language Learning Book',
        'name_ar' => 'كتاب تعلم اللغة',
        'description' => 'Learn a new language',
        'description_ar' => 'تعلم لغة جديدة',
        'base_price' => 29.99
      ],
      [
        'name' => 'Children\'s Book',
        'name_ar' => 'كتاب أطفال',
        'description' => 'Educational children\'s book',
        'description_ar' => 'كتاب أطفال تعليمي',
        'base_price' => 12.99
      ],
      [
        'name' => 'Music CD',
        'name_ar' => 'قرص موسيقى',
        'description' => 'Latest music album',
        'description_ar' => 'أحدث ألبوم موسيقي',
        'base_price' => 16.99
      ],
      [
        'name' => 'Movie DVD',
        'name_ar' => 'قرص فيلم',
        'description' => 'Latest blockbuster movie',
        'description_ar' => 'أحدث فيلم ناجح',
        'base_price' => 19.99
      ],
      [
        'name' => 'Magazine',
        'name_ar' => 'مجلة',
        'description' => 'Monthly lifestyle magazine',
        'description_ar' => 'مجلة نمط حياة شهرية',
        'base_price' => 4.99
      ],
      [
        'name' => 'Comic Book',
        'name_ar' => 'كتاب مصور',
        'description' => 'Superhero comic book',
        'description_ar' => 'كتاب مصور للأبطال الخارقين',
        'base_price' => 8.99
      ],
      [
        'name' => 'Educational DVD',
        'name_ar' => 'قرص تعليمي',
        'description' => 'Educational documentary',
        'description_ar' => 'فيلم وثائقي تعليمي',
        'base_price' => 22.99
      ],
      [
        'name' => 'Biography',
        'name_ar' => 'سيرة ذاتية',
        'description' => 'Famous person biography',
        'description_ar' => 'سيرة ذاتية لشخص مشهور',
        'base_price' => 18.99
      ],
      [
        'name' => 'History Book',
        'name_ar' => 'كتاب تاريخ',
        'description' => 'World history book',
        'description_ar' => 'كتاب تاريخ العالم',
        'base_price' => 32.99
      ],
      [
        'name' => 'Science Book',
        'name_ar' => 'كتاب علوم',
        'description' => 'Popular science book',
        'description_ar' => 'كتاب علوم شائع',
        'base_price' => 26.99
      ],
      [
        'name' => 'Art Book',
        'name_ar' => 'كتاب فن',
        'description' => 'Art and design book',
        'description_ar' => 'كتاب الفن والتصميم',
        'base_price' => 44.99
      ],
      [
        'name' => 'Poetry Book',
        'name_ar' => 'كتاب شعر',
        'description' => 'Classic poetry collection',
        'description_ar' => 'مجموعة شعر كلاسيكية',
        'base_price' => 16.99
      ],
      [
        'name' => 'Travel Guide',
        'name_ar' => 'دليل سفر',
        'description' => 'Travel guide book',
        'description_ar' => 'كتاب دليل السفر',
        'base_price' => 21.99
      ],
      [
        'name' => 'Self-Help Book',
        'name_ar' => 'كتاب مساعدة ذاتية',
        'description' => 'Personal development book',
        'description_ar' => 'كتاب تطوير شخصي',
        'base_price' => 19.99
      ],
      [
        'name' => 'Mystery Novel',
        'name_ar' => 'رواية غموض',
        'description' => 'Thriller mystery novel',
        'description_ar' => 'رواية غموض مثيرة',
        'base_price' => 17.99
      ],
      [
        'name' => 'Romance Novel',
        'name_ar' => 'رواية رومانسية',
        'description' => 'Romantic fiction novel',
        'description_ar' => 'رواية خيالية رومانسية',
        'base_price' => 15.99
      ],
      [
        'name' => 'Fantasy Book',
        'name_ar' => 'كتاب خيال',
        'description' => 'Fantasy adventure book',
        'description_ar' => 'كتاب مغامرة خيالية',
        'base_price' => 23.99
      ],
      [
        'name' => 'Audiobook',
        'name_ar' => 'كتاب صوتي',
        'description' => 'Digital audiobook',
        'description_ar' => 'كتاب صوتي رقمي',
        'base_price' => 24.99
      ]
    ];

    // Create 100 products (20 per category)
    $categoryIndex = 0;
    $productsPerCategory = 20;

    for ($i = 0; $i < 100; $i++) {
      $productInfo = $productData[$i];
      $category = $categories[$categoryIndex];

      Product::create([
        'category_id' => $category->id,
        'name' => $productInfo['name'],
        'name_ar' => $productInfo['name_ar'],
        'description' => $productInfo['description'],
        'description_ar' => $productInfo['description_ar'],
        'base_price' => $productInfo['base_price'],
        'is_active' => true,
        'sort_order' => ($i % $productsPerCategory) + 1,
        'specifications' => [
          'brand' => 'TestBrand',
          'model' => 'Model-' . ($i + 1),
          'warranty' => '1 year',
          'weight' => '1kg',
          'dimensions' => '10x10x10cm'
        ]
      ]);

      // Move to next category after 20 products
      if (($i + 1) % $productsPerCategory === 0) {
        $categoryIndex = ($categoryIndex + 1) % $categories->count();
      }
    }

    $this->command->info('✅ Successfully added 100 more products for pagination testing!');
    $this->command->info('Products distributed across categories:');

    foreach ($categories as $category) {
      $productCount = Product::where('category_id', $category->id)->count();
      $this->command->info("- {$category->name}: {$productCount} products");
    }
  }
}
