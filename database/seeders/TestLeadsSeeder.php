<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Category;
use App\Models\Marketer;

class TestLeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a category
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Test Category',
                'name_ar' => 'فئة تجريبية',
                'description' => 'Test category for leads',
                'description_ar' => 'فئة تجريبية للعملاء المحتملين',
                'is_active' => true,
            ]);
        }

        // Create test leads
        $testLeads = [
            [
                'company_name' => 'شركة التقنية المتقدمة',
                'contact_person' => 'أحمد محمد',
                'email' => 'ahmed@tech-advanced.com',
                'phone' => '+966501234567',
                'address' => 'الرياض، المملكة العربية السعودية',
                'notes' => 'عميل مهتم بتطوير تطبيق موبايل',
                'status' => 'new',
                'priority' => 'high',
                'category_id' => $category->id,
            ],
            [
                'company_name' => 'مؤسسة التصميم الإبداعي',
                'contact_person' => 'فاطمة أحمد',
                'email' => 'fatima@creative-design.com',
                'phone' => '+966502345678',
                'address' => 'جدة، المملكة العربية السعودية',
                'notes' => 'تطلب تصميم هوية بصرية كاملة',
                'status' => 'new',
                'priority' => 'medium',
                'category_id' => $category->id,
            ],
            [
                'company_name' => 'شركة التجارة الإلكترونية',
                'contact_person' => 'محمد علي',
                'email' => 'mohammed@ecommerce.com',
                'phone' => '+966503456789',
                'address' => 'الدمام، المملكة العربية السعودية',
                'notes' => 'يريد تطوير متجر إلكتروني',
                'status' => 'new',
                'priority' => 'high',
                'category_id' => $category->id,
            ],
            [
                'company_name' => 'مطعم الأصالة',
                'contact_person' => 'سارة خالد',
                'email' => 'sara@asalah-restaurant.com',
                'phone' => '+966504567890',
                'address' => 'الرياض، المملكة العربية السعودية',
                'notes' => 'يحتاج تصميم قائمة طعام وموقع ويب',
                'status' => 'new',
                'priority' => 'medium',
                'category_id' => $category->id,
            ],
            [
                'company_name' => 'عيادة الأسنان المتخصصة',
                'contact_person' => 'د. خالد عبدالله',
                'email' => 'dr.khalid@dental-clinic.com',
                'phone' => '+966505678901',
                'address' => 'الرياض، المملكة العربية السعودية',
                'notes' => 'يريد تطوير موقع ويب للعيادة',
                'status' => 'new',
                'priority' => 'low',
                'category_id' => $category->id,
            ],
            [
                'company_name' => 'شركة العقارات الذكية',
                'contact_person' => 'نورا سعد',
                'email' => 'nora@smart-realestate.com',
                'phone' => '+966506789012',
                'address' => 'جدة، المملكة العربية السعودية',
                'notes' => 'تطلب تطوير منصة عقارية',
                'status' => 'new',
                'priority' => 'high',
                'category_id' => $category->id,
            ],
            [
                'company_name' => 'صالون الجمال الأنيق',
                'contact_person' => 'ريم أحمد',
                'email' => 'reem@elegant-beauty.com',
                'phone' => '+966507890123',
                'address' => 'الدمام، المملكة العربية السعودية',
                'notes' => 'يريد تصميم هوية بصرية وموقع ويب',
                'status' => 'new',
                'priority' => 'medium',
                'category_id' => $category->id,
            ],
            [
                'company_name' => 'مكتب المحاماة المتقدم',
                'contact_person' => 'م. عبدالرحمن محمد',
                'email' => 'lawyer@advanced-law.com',
                'phone' => '+966508901234',
                'address' => 'الرياض، المملكة العربية السعودية',
                'notes' => 'يحتاج تطوير موقع ويب للمكتب',
                'status' => 'new',
                'priority' => 'low',
                'category_id' => $category->id,
            ],
        ];

        foreach ($testLeads as $leadData) {
            Lead::create($leadData);
        }

        $this->command->info('Created ' . count($testLeads) . ' test leads successfully!');
    }
}