<?php

return [
  // Appointment Statuses
  'pending' => 'معلق',
  'accepted' => 'مقبول',
  'completed' => 'مكتمل',
  'cancelled' => 'ملغي',
  'rejected' => 'مرفوض',

  // Appointment Actions
  'view' => 'عرض',
  'cancel' => 'إلغاء',
  'reschedule' => 'إعادة جدولة',

  // Appointment Details
  'appointment_details' => 'تفاصيل الموعد',
  'consultation_details' => 'تفاصيل الاستشارة',
  'meeting_details' => 'تفاصيل الاجتماع',

  // Time and Date
  'appointment_time' => 'وقت الموعد',
  'appointment_date' => 'تاريخ الموعد',
  'duration' => 'المدة',
  'start_time' => 'وقت البداية',
  'end_time' => 'وقت النهاية',

  // Status Messages
  'status_pending' => 'موعدك معلق للموافقة',
  'status_accepted' => 'تم قبول موعدك',
  'status_completed' => 'تم إكمال موعدك',
  'status_cancelled' => 'تم إلغاء موعدك',
  'status_rejected' => 'تم رفض موعدك',

  // Messages
  'no_appointments_found' => 'لم يتم العثور على مواعيد',
  'appointment_not_found' => 'الموعد غير موجود',
  'appointment_successfully_created' => 'تم إنشاء الموعد بنجاح',
  'appointment_booked_successfully' => 'تم حجز الموعد بنجاح!',
  'appointment_success_message' => 'تم حجز موعدك بنجاح. سنتواصل معك قريباً لتأكيد التفاصيل.',
  'linked_order' => 'الطلب المرتبط',
  'next_steps' => 'الخطوات التالية',
  'step_1_title' => 'التأكيد',
  'step_1_description' => 'سنراجع طلب الموعد ونؤكد التفاصيل.',
  'step_2_title' => 'التحضير',
  'step_2_description' => 'سيقوم فريقنا بالتحضير لاستشارتك بناءً على متطلبات طلبك.',
  'step_3_title' => 'الاجتماع',
  'step_3_description' => 'ستلتقي مع مصممنا لمناقشة مشروعك بالتفصيل.',
  'view_my_appointments' => 'عرض مواعيدي',
  'continue_shopping' => 'متابعة التسوق',
  'minutes' => 'دقيقة',
  'appointment_successfully_cancelled' => 'تم إلغاء الموعد بنجاح',
  'appointment_successfully_rescheduled' => 'تم إعادة جدولة الموعد بنجاح',

  // Confirmations
  'confirm_cancel' => 'هل أنت متأكد من إلغاء هذا الموعد؟',
  'confirm_reschedule' => 'هل أنت متأكد من إعادة جدولة هذا الموعد؟',
  'confirm_delete' => 'هل أنت متأكد من حذف هذا الموعد؟',

  // Errors
  'appointment_cannot_be_cancelled' => 'لا يمكن إلغاء هذا الموعد',
  'appointment_cannot_be_rescheduled' => 'لا يمكن إعادة جدولة هذا الموعد',
  'invalid_appointment_time' => 'وقت الموعد غير صحيح',
  'appointment_time_in_past' => 'وقت الموعد لا يمكن أن يكون في الماضي',
  'appointment_time_conflict' => 'هذه الفترة الزمنية محجوزة بالفعل',

  // Labels
  'appointment_id' => 'رقم الموعد',
  'appointment_number' => 'رقم الموعد',
  'appointment_type' => 'نوع الموعد',
  'appointment_notes' => 'ملاحظات الموعد',
  'customer_notes' => 'ملاحظات العميل',
  'designer_notes' => 'ملاحظات المصمم',

  // Filters
  'filter_by_status' => 'تصفية حسب الحالة',
  'filter_by_date' => 'تصفية حسب التاريخ',
  'filter_by_designer' => 'تصفية حسب المصمم',
  'all_appointments' => 'جميع المواعيد',
  'today_appointments' => 'مواعيد اليوم',
  'upcoming_appointments' => 'المواعيد القادمة',
  'past_appointments' => 'المواعيد السابقة',

  // Admin Appointments Management
  'appointments_list' => 'قائمة المواعيد',
  'manage_appointments' => 'إدارة جميع مواعيد العملاء',
  'add_appointment' => 'إضافة موعد',
  'appointment_id' => 'رقم الموعد',
  'customer' => 'العميل',
  'designer' => 'المصمم',
  'order_total' => 'إجمالي الطلب',
  'minutes' => 'دقائق',
  'unassigned' => 'غير مخصص',
  'search' => 'بحث',
  'appointments_search_placeholder' => 'البحث في المواعيد بالرقم، الملاحظات، العميل، أو المصمم...',
  'status_filter' => 'تصفية الحالة',
  'all_statuses' => 'جميع الحالات',
  'created_at' => 'تاريخ الإنشاء',
  'actions' => 'الإجراءات',
  'show' => 'عرض',
  'edit' => 'تعديل',
  'delete' => 'حذف',
  'no_appointments_exist' => 'لا توجد مواعيد',
  'confirm_delete_title' => 'تأكيد الحذف',
  'confirm_delete_appointment' => 'هل أنت متأكد من حذف هذا الموعد؟ لا يمكن التراجع عن هذا الإجراء.',
  'cancel' => 'إلغاء',
  'appointment_deleted_successfully' => 'تم حذف الموعد بنجاح',
  'cannot_delete_non_pending_appointment' => 'يمكن حذف المواعيد المعلقة فقط',
  'delete_error' => 'حدث خطأ أثناء حذف الموعد',
];
