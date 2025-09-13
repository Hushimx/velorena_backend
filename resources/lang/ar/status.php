<?php

return [
    // Order Statuses
    'order' => [
        'pending' => 'معلق',
        'processing' => 'قيد المعالجة',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'shipped' => 'تم الشحن',
        'delivered' => 'تم التسليم',
        'refunded' => 'مسترد',
        'failed' => 'فشل',
        'on_hold' => 'معلق',
        'in_progress' => 'قيد التنفيذ',
    ],

    // Appointment Statuses
    'appointment' => [
        'pending' => 'معلق',
        'scheduled' => 'مجدول',
        'confirmed' => 'مؤكد',
        'in_progress' => 'قيد التنفيذ',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'no_show' => 'لم يحضر',
        'rescheduled' => 'أعيد جدولته',
        'rejected' => 'مرفوض',
        'expired' => 'منتهي الصلاحية',
    ],

    // Lead Statuses
    'lead' => [
        'new' => 'جديد',
        'contacted' => 'تم التواصل',
        'qualified' => 'مؤهل',
        'proposal' => 'عرض سعر',
        'negotiation' => 'مفاوضات',
        'closed_won' => 'مغلق - فوز',
        'closed_lost' => 'مغلق - خسارة',
        'on_hold' => 'معلق',
        'follow_up' => 'متابعة',
        'converted' => 'محول',
    ],

    // User Statuses
    'user' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'suspended' => 'معلق',
        'pending' => 'في الانتظار',
        'verified' => 'متحقق',
        'unverified' => 'غير متحقق',
        'banned' => 'محظور',
    ],

    // Designer Statuses
    'designer' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'في الانتظار',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'suspended' => 'معلق',
        'available' => 'متاح',
        'busy' => 'مشغول',
        'offline' => 'غير متصل',
    ],

    // Marketer Statuses
    'marketer' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'في الانتظار',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'suspended' => 'معلق',
    ],

    // Admin Statuses
    'admin' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'suspended' => 'معلق',
    ],

    // Product Statuses
    'product' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'draft' => 'مسودة',
        'pending' => 'في الانتظار',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'out_of_stock' => 'نفد المخزون',
        'discontinued' => 'متوقف',
    ],

    // Category Statuses
    'category' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'في الانتظار',
    ],

    // Payment Statuses
    'payment' => [
        'pending' => 'في الانتظار',
        'processing' => 'قيد المعالجة',
        'completed' => 'مكتمل',
        'failed' => 'فشل',
        'cancelled' => 'ملغي',
        'refunded' => 'مسترد',
        'partially_refunded' => 'مسترد جزئياً',
    ],

    // General Statuses
    'general' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'في الانتظار',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'suspended' => 'معلق',
        'draft' => 'مسودة',
        'published' => 'منشور',
        'archived' => 'مؤرشف',
    ],
];

