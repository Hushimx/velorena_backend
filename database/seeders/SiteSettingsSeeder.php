<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StoreContent;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'type' => 'setting',
                'value_en' => ['value' => 'QAADS'],
                'value_ar' => ['value' => 'قعدس'],
                'description' => 'Website name',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'site_description',
                'type' => 'setting',
                'value_en' => ['value' => 'Your premium design and print solutions'],
                'value_ar' => ['value' => 'حلول التصميم والطباعة المتميزة'],
                'description' => 'Website description',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'key' => 'contact_email',
                'type' => 'setting',
                'value_en' => ['value' => 'info@qaads.com'],
                'value_ar' => ['value' => 'info@qaads.com'],
                'description' => 'Contact email address',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'contact_phone',
                'type' => 'setting',
                'value_en' => ['value' => '+966 50 123 4567'],
                'value_ar' => ['value' => '+966 50 123 4567'],
                'description' => 'Contact phone number',
                'is_active' => true,
                'sort_order' => 4,
            ],

            // Social Media
            [
                'key' => 'facebook_url',
                'type' => 'setting',
                'value_en' => ['value' => 'https://facebook.com/qaads'],
                'value_ar' => ['value' => 'https://facebook.com/qaads'],
                'description' => 'Facebook page URL',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'key' => 'instagram_url',
                'type' => 'setting',
                'value_en' => ['value' => 'https://instagram.com/qaads'],
                'value_ar' => ['value' => 'https://instagram.com/qaads'],
                'description' => 'Instagram profile URL',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'key' => 'twitter_url',
                'type' => 'setting',
                'value_en' => ['value' => 'https://twitter.com/qaads'],
                'value_ar' => ['value' => 'https://twitter.com/qaads'],
                'description' => 'Twitter profile URL',
                'is_active' => true,
                'sort_order' => 12,
            ],
            [
                'key' => 'linkedin_url',
                'type' => 'setting',
                'value_en' => ['value' => 'https://linkedin.com/company/qaads'],
                'value_ar' => ['value' => 'https://linkedin.com/company/qaads'],
                'description' => 'LinkedIn company URL',
                'is_active' => true,
                'sort_order' => 13,
            ],

            // Footer Content
            [
                'key' => 'footer_about',
                'type' => 'content',
                'value_en' => ['value' => 'QAADS is your trusted partner for premium design and print solutions. We deliver exceptional quality and creativity for all your business needs.'],
                'value_ar' => ['value' => 'قعدس هو شريكك الموثوق لحلول التصميم والطباعة المتميزة. نحن نقدم جودة وإبداع استثنائيين لجميع احتياجات عملك.'],
                'description' => 'Footer about section',
                'is_active' => true,
                'sort_order' => 20,
            ],
            [
                'key' => 'footer_address',
                'type' => 'content',
                'value_en' => ['value' => '123 Business District, Riyadh, Saudi Arabia'],
                'value_ar' => ['value' => '123 حي الأعمال، الرياض، المملكة العربية السعودية'],
                'description' => 'Company address',
                'is_active' => true,
                'sort_order' => 21,
            ],

            // Homepage Content
            [
                'key' => 'homepage_title',
                'type' => 'content',
                'value_en' => ['value' => 'Welcome to QAADS'],
                'value_ar' => ['value' => 'مرحباً بك في قعدس'],
                'description' => 'Homepage main title',
                'is_active' => true,
                'sort_order' => 30,
            ],
            [
                'key' => 'homepage_subtitle',
                'type' => 'content',
                'value_en' => ['value' => 'Premium Design & Print Solutions'],
                'value_ar' => ['value' => 'حلول التصميم والطباعة المتميزة'],
                'description' => 'Homepage subtitle',
                'is_active' => true,
                'sort_order' => 31,
            ],
            [
                'key' => 'homepage_description',
                'type' => 'content',
                'value_en' => ['value' => 'Transform your ideas into stunning visual experiences with our professional design and printing services.'],
                'value_ar' => ['value' => 'حول أفكارك إلى تجارب بصرية مذهلة مع خدمات التصميم والطباعة المهنية لدينا.'],
                'description' => 'Homepage description',
                'is_active' => true,
                'sort_order' => 32,
            ],
        ];

        foreach ($settings as $setting) {
            StoreContent::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
