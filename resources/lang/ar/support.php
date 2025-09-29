<?php

return [
    // Support Ticket Statuses
    'open' => 'مفتوح',
    'in_progress' => 'قيد التنفيذ',
    'pending' => 'معلق',
    'resolved' => 'محلول',
    'closed' => 'مغلق',

    // Support Ticket Priorities
    'low' => 'منخفض',
    'medium' => 'متوسط',
    'high' => 'عالي',
    'urgent' => 'عاجل',

    // Support Ticket Categories
    'technical' => 'تقني',
    'billing' => 'فوترة',
    'general' => 'عام',
    'feature_request' => 'طلب ميزة',
    'bug_report' => 'تقرير خطأ',

    // Admin Support Tickets Management
    'tickets_list' => 'قائمة تذاكر الدعم',
    'manage_tickets' => 'إدارة جميع تذاكر دعم العملاء',
    'add_ticket' => 'إضافة تذكرة',
    'ticket_number' => 'رقم التذكرة',
    'customer' => 'العميل',
    'subject' => 'الموضوع',
    'priority' => 'الأولوية',
    'status' => 'الحالة',
    'assigned_to' => 'مخصص لـ',
    'created_at' => 'تاريخ الإنشاء',
    'unassigned' => 'غير مخصص',
    'search' => 'بحث',
    'tickets_search_placeholder' => 'البحث في التذاكر بالرقم، الموضوع، الوصف، أو العميل...',
    'status_filter' => 'تصفية الحالة',
    'priority_filter' => 'تصفية الأولوية',
    'all_statuses' => 'جميع الحالات',
    'all_priorities' => 'جميع الأولويات',
    'actions' => 'الإجراءات',
    'show' => 'عرض',
    'edit' => 'تعديل',
    'delete' => 'حذف',
    'no_tickets_exist' => 'لا توجد تذاكر دعم',
    'confirm_delete_title' => 'تأكيد الحذف',
    'confirm_delete_ticket' => 'هل أنت متأكد من حذف هذه التذكرة؟ لا يمكن التراجع عن هذا الإجراء.',
    'cancel' => 'إلغاء',
    'ticket_deleted_successfully' => 'تم حذف تذكرة الدعم بنجاح',
    'cannot_delete_non_open_ticket' => 'يمكن حذف التذاكر المفتوحة فقط',
    'delete_error' => 'حدث خطأ أثناء حذف التذكرة',

    // Ticket Details
    'ticket_details' => 'تفاصيل التذكرة',
    'description' => 'الوصف',
    'category' => 'الفئة',
    'assigned_admin' => 'المدير المخصص',
    'admin_notes' => 'ملاحظات المدير',
    'attachments' => 'المرفقات',
    'replies' => 'الردود',
    'add_reply' => 'إضافة رد',
    'reply_message' => 'رسالة الرد',
    'internal_note' => 'ملاحظة داخلية',
    'public_reply' => 'رد عام',

    // Ticket Actions
    'assign_ticket' => 'تخصيص التذكرة',
    'close_ticket' => 'إغلاق التذكرة',
    'reopen_ticket' => 'إعادة فتح التذكرة',
    'resolve_ticket' => 'حل التذكرة',
    'update_priority' => 'تحديث الأولوية',
    'update_status' => 'تحديث الحالة',

    // Statistics
    'ticket_statistics' => 'إحصائيات التذاكر',
    'total_tickets' => 'إجمالي التذاكر',
    'open_tickets' => 'التذاكر المفتوحة',
    'resolved_tickets' => 'التذاكر المحلولة',
    'closed_tickets' => 'التذاكر المغلقة',
    'average_resolution_time' => 'متوسط وقت الحل',
    'tickets_by_priority' => 'التذاكر حسب الأولوية',
    'tickets_by_category' => 'التذاكر حسب الفئة',
    'tickets_by_status' => 'التذاكر حسب الحالة',

    // Messages
    'ticket_created_successfully' => 'تم إنشاء تذكرة الدعم بنجاح',
    'ticket_updated_successfully' => 'تم تحديث تذكرة الدعم بنجاح',
    'ticket_assigned_successfully' => 'تم تخصيص التذكرة بنجاح',
    'ticket_closed_successfully' => 'تم إغلاق التذكرة بنجاح',
    'ticket_reopened_successfully' => 'تم إعادة فتح التذكرة بنجاح',
    'reply_added_successfully' => 'تم إضافة الرد بنجاح',
    'reply_updated_successfully' => 'تم تحديث الرد بنجاح',
    'reply_deleted_successfully' => 'تم حذف الرد بنجاح',

    // Form Fields
    'enter_subject' => 'أدخل موضوع التذكرة',
    'enter_description' => 'أدخل وصف التذكرة',
    'select_priority' => 'اختر الأولوية',
    'select_category' => 'اختر الفئة',
    'select_status' => 'اختر الحالة',
    'select_admin' => 'اختر المدير',
    'enter_admin_notes' => 'أدخل ملاحظات المدير...',
    'enter_reply_message' => 'أدخل رسالة الرد...',

    // Help Text
    'subject_help' => 'وصف مختصر للمشكلة',
    'description_help' => 'وصف مفصل للمشكلة أو الطلب',
    'priority_help' => 'ما مدى إلحاح هذه التذكرة؟',
    'category_help' => 'ما نوع هذه المشكلة؟',
    'admin_notes_help' => 'ملاحظات داخلية لاستخدام المدير فقط',
    'attachments_help' => 'رفع ملفات متعلقة بهذه التذكرة (حد أقصى 10 ميجابايت لكل ملف)',

    // Bulk Actions
    'bulk_actions' => 'الإجراءات المجمعة',
    'select_tickets' => 'اختر التذاكر',
    'bulk_assign' => 'تخصيص مجمع',
    'bulk_close' => 'إغلاق مجمع',
    'bulk_delete' => 'حذف مجمع',
    'bulk_update_priority' => 'تحديث الأولوية مجمعاً',
    'bulk_update_status' => 'تحديث الحالة مجمعاً',
    'selected_tickets' => 'التذاكر المختارة',
    'bulk_action_success' => 'تم إكمال الإجراء المجمع بنجاح',
    'bulk_action_error' => 'حدث خطأ أثناء الإجراء المجمع',

    // Filters and Search
    'filter_by_status' => 'تصفية حسب الحالة',
    'filter_by_priority' => 'تصفية حسب الأولوية',
    'filter_by_category' => 'تصفية حسب الفئة',
    'filter_by_admin' => 'تصفية حسب المدير',
    'filter_by_date' => 'تصفية حسب التاريخ',
    'date_from' => 'من تاريخ',
    'date_to' => 'إلى تاريخ',
    'clear_filters' => 'مسح المرشحات',
    'export_tickets' => 'تصدير التذاكر',
    'import_tickets' => 'استيراد التذاكر',

    // Common
    'loading' => 'جاري التحميل...',
    'no_data' => 'لا توجد بيانات متاحة',
    'refresh' => 'تحديث',
    'back_to_tickets' => 'العودة للتذاكر',
    'view_all_tickets' => 'عرض جميع التذاكر',
    'my_tickets' => 'تذاكري',
    'assigned_tickets' => 'التذاكر المخصصة',
    'unassigned_tickets' => 'التذاكر غير المخصصة',
];
