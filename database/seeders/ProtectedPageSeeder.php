<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProtectedPage;

class ProtectedPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Welcome to Qaads Store',
                'title_ar' => 'مرحباً بك في متجر فيلورينا',
                'slug' => 'welcome-store',
                'content' => 'Welcome to our premium printing and design store. Here you can find the best quality products and services.',
                'content_ar' => 'مرحباً بك في متجرنا المتميز للطباعة والتصميم. هنا يمكنك العثور على أفضل المنتجات والخدمات.',
                'type' => 'page',
                'access_level' => 'authenticated',
                'is_active' => true,
                'sort_order' => 1,
                'metadata' => [
                    'icon' => 'fas fa-store',
                    'color' => '#ffde9f'
                ]
            ],
            [
                'title' => 'Premium Services',
                'title_ar' => 'خدمات متميزة',
                'slug' => 'premium-services',
                'content' => 'Discover our premium printing services including custom designs, high-quality materials, and fast delivery.',
                'content_ar' => 'اكتشف خدماتنا المتميزة للطباعة بما في ذلك التصاميم المخصصة والمواد عالية الجودة والتوصيل السريع.',
                'type' => 'section',
                'access_level' => 'authenticated',
                'is_active' => true,
                'sort_order' => 2,
                'metadata' => [
                    'icon' => 'fas fa-star',
                    'color' => '#2a1e1e'
                ]
            ],
            [
                'title' => 'Design Studio Access',
                'title_ar' => 'الوصول إلى استوديو التصميم',
                'slug' => 'design-studio-access',
                'content' => 'Access our advanced design studio with AI-powered tools and professional templates.',
                'content_ar' => 'الوصول إلى استوديو التصميم المتقدم مع أدوات مدعومة بالذكاء الاصطناعي والقوالب المهنية.',
                'type' => 'modal',
                'access_level' => 'authenticated',
                'is_active' => true,
                'sort_order' => 3,
                'metadata' => [
                    'icon' => 'fas fa-palette',
                    'color' => '#f5d182',
                    'modal_size' => 'large'
                ]
            ],
            [
                'title' => 'Exclusive Offers',
                'title_ar' => 'عروض حصرية',
                'slug' => 'exclusive-offers',
                'content' => 'Get access to exclusive offers and discounts available only to registered members.',
                'content_ar' => 'احصل على عروض حصرية وخصومات متاحة فقط للأعضاء المسجلين.',
                'type' => 'page',
                'access_level' => 'authenticated',
                'is_active' => true,
                'sort_order' => 4,
                'metadata' => [
                    'icon' => 'fas fa-gift',
                    'color' => '#ffde9f'
                ]
            ],
            [
                'title' => 'Admin Dashboard',
                'title_ar' => 'لوحة تحكم الإدارة',
                'slug' => 'admin-dashboard',
                'content' => 'Administrative dashboard for managing the store and user accounts.',
                'content_ar' => 'لوحة تحكم إدارية لإدارة المتجر وحسابات المستخدمين.',
                'type' => 'page',
                'access_level' => 'admin',
                'is_active' => true,
                'sort_order' => 5,
                'metadata' => [
                    'icon' => 'fas fa-cogs',
                    'color' => '#2a1e1e'
                ]
            ]
        ];

        foreach ($pages as $page) {
            ProtectedPage::create($page);
        }
    }
}
