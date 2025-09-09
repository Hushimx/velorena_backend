<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Marketer;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketers = Marketer::all();
        
        if ($marketers->isEmpty()) {
            $this->command->info('No marketers found. Please run MarketerSeeder first.');
            return;
        }

        $leads = [
            [
                'company_name' => 'شركة التقنية المتقدمة',
                'contact_person' => 'سعد العتيبي',
                'email' => 'saad@tech-advanced.com',
                'phone' => '+966501111111',
                'address' => 'الرياض، المملكة العربية السعودية',
                'notes' => 'شركة متخصصة في الحلول التقنية',
                'status' => 'new',
                'priority' => 'high',
                'marketer_id' => $marketers->random()->id,
            ],
            [
                'company_name' => 'مؤسسة البناء الحديث',
                'contact_person' => 'نورا السعيد',
                'email' => 'nora@modern-building.com',
                'phone' => '+966502222222',
                'address' => 'جدة، المملكة العربية السعودية',
                'notes' => 'مؤسسة بناء وتطوير عقاري',
                'status' => 'contacted',
                'priority' => 'medium',
                'marketer_id' => $marketers->random()->id,
            ],
            [
                'company_name' => 'مجموعة التجارة الدولية',
                'contact_person' => 'خالد المطيري',
                'email' => 'khalid@international-trade.com',
                'phone' => '+966503333333',
                'address' => 'الدمام، المملكة العربية السعودية',
                'notes' => 'مجموعة تجارية متعددة الأنشطة',
                'status' => 'qualified',
                'priority' => 'high',
                'marketer_id' => $marketers->random()->id,
            ],
            [
                'company_name' => 'شركة الخدمات الطبية',
                'contact_person' => 'د. فهد القحطاني',
                'email' => 'fahad@medical-services.com',
                'phone' => '+966504444444',
                'address' => 'الرياض، المملكة العربية السعودية',
                'notes' => 'شركة خدمات طبية متخصصة',
                'status' => 'proposal_sent',
                'priority' => 'medium',
                'marketer_id' => $marketers->random()->id,
            ],
            [
                'company_name' => 'مؤسسة التعليم الذكي',
                'contact_person' => 'أمل الزهراني',
                'email' => 'amal@smart-education.com',
                'phone' => '+966505555555',
                'address' => 'الرياض، المملكة العربية السعودية',
                'notes' => 'مؤسسة تعليمية متخصصة في التقنيات الحديثة',
                'status' => 'negotiation',
                'priority' => 'high',
                'marketer_id' => $marketers->random()->id,
            ],
        ];

        foreach ($leads as $lead) {
            Lead::create($lead);
        }
    }
}
