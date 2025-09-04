# نظام إدارة المسوقيين والـ Leads

## نظرة عامة
تم إنشاء نظام شامل لإدارة المسوقيين والـ leads في المنصة، يتضمن:

### 1. المسوقيين (Marketers)
- جدول منفصل للمسوقيين مع معلومات أساسية
- نظام تسجيل دخول منفصل للمسوقيين
- لوحة تحكم مخصصة للمسوقيين
- إدارة المسوقيين من لوحة الإدمن

### 2. الـ Leads
- جدول شامل للـ leads مع معلومات الشركات
- ربط الـ leads بالمسوقيين المسؤولين عنها
- تتبع حالة الـ leads (جديد، تم التواصل، مؤهل، إلخ)
- نظام أولويات (منخفضة، متوسطة، عالية)

### 3. سجل التواصل (Lead Communications)
- جدول منفصل لتسجيل جميع محاولات التواصل
- أنواع مختلفة للتواصل (مكالمة، إيميل، اجتماع، واتساب)
- ربط التواصل بالمسوق والـ lead
- تتبع تاريخ التواصل

## الملفات المُنشأة

### Migrations
- `2025_09_04_090732_create_marketers_table.php`
- `2025_09_04_090741_create_leads_table.php`
- `2025_09_04_090751_create_lead_communications_table.php`

### Models
- `app/Models/Marketer.php`
- `app/Models/Lead.php`
- `app/Models/LeadCommunication.php`

### Controllers
#### لوحة الإدمن
- `app/Http/Controllers/Admin/MarketerController.php`
- `app/Http/Controllers/Admin/LeadController.php`

#### لوحة المسوقيين
- `app/Http/Controllers/Marketer/MarketerController.php`
- `app/Http/Controllers/Marketer/LeadController.php`

#### Authentication
- `app/Http/Controllers/Marketer/Auth/LoginController.php`
- `app/Http/Controllers/Marketer/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/Marketer/Auth/ResetPasswordController.php`

### Livewire Components
- `app/Livewire/MarketersTable.php`
- `app/Livewire/LeadsTable.php`
- `app/Livewire/MarketerLeadsTable.php`

### Views
#### لوحة الإدمن
- `resources/views/admin/dashboard/marketers/` (index, create, edit, show)
- `resources/views/admin/dashboard/leads/` (index, create, edit, show)
- `resources/views/livewire/marketers-table.blade.php`
- `resources/views/livewire/leads-table.blade.php`

#### لوحة المسوقيين
- `resources/views/marketer/layouts/app.blade.php`
- `resources/views/marketer/dashboard/main.blade.php`
- `resources/views/marketer/leads/` (index, show, edit)
- `resources/views/marketer/auth/login.blade.php`
- `resources/views/marketer/passwords/` (email, reset)
- `resources/views/livewire/marketer-leads-table.blade.php`

### Middleware
- `app/Http/Middleware/RedirectIfNotMarketer.php`
- `app/Http/Middleware/RedirectIfMarketer.php`

### Routes
- `routes/marketer_routes.php`
- تحديث `routes/admin_routes.php`
- تحديث `routes/web.php`

### Seeders
- `database/seeders/MarketerSeeder.php`
- `database/seeders/LeadSeeder.php`
- تحديث `database/seeders/DatabaseSeeder.php`

### Configuration
- تحديث `config/auth.php` لإضافة guard و provider للمسوقيين
- تحديث `bootstrap/app.php` لإضافة middleware aliases

## الميزات

### لوحة الإدمن
1. **إدارة المسوقيين**
   - عرض قائمة المسوقيين
   - إضافة مسوق جديد
   - تعديل بيانات المسوق
   - حذف المسوق (مع التحقق من وجود leads مسندة)
   - عرض تفاصيل المسوق مع إحصائيات

2. **إدارة الـ Leads**
   - عرض قائمة جميع الـ leads
   - إضافة lead جديد
   - تعديل بيانات الـ lead
   - حذف الـ lead
   - عرض تفاصيل الـ lead مع سجل التواصل
   - فلترة حسب الحالة، الأولوية، والمسوق المسؤول

### لوحة المسوقيين
1. **لوحة التحكم الرئيسية**
   - إحصائيات الـ leads المسندة
   - عرض الـ leads الأخيرة
   - إحصائيات حسب الحالة

2. **إدارة الـ Leads**
   - عرض الـ leads المسندة للمسوق فقط
   - تعديل حالة الـ lead
   - إضافة ملاحظات
   - تحديد موعد المتابعة التالية

3. **سجل التواصل**
   - إضافة تواصل جديد
   - عرض سجل جميع التواصل
   - أنواع مختلفة للتواصل

## البيانات التجريبية

تم إنشاء بيانات تجريبية تتضمن:
- 3 مسوقيين
- 5 leads مسندة للمسوقيين

### بيانات تسجيل الدخول للمسوقيين
- **أحمد المسوق**: ahmed@marketer.com / password
- **فاطمة المسوقة**: fatima@marketer.com / password  
- **محمد المسوق**: mohammed@marketer.com / password

## الأمان

1. **Middleware Protection**
   - حماية routes المسوقيين بـ middleware مخصص
   - التحقق من صلاحيات الوصول للـ leads

2. **Data Isolation**
   - كل مسوق يرى فقط الـ leads المسندة إليه
   - لا يمكن للمسوق الوصول لبيانات مسوق آخر

3. **Authentication**
   - نظام تسجيل دخول منفصل للمسوقيين
   - استعادة كلمة المرور
   - session management منفصل

## الاستخدام

### للإدمن
1. تسجيل الدخول للوحة الإدمن
2. الانتقال لقسم "المسوقيين" لإدارة المسوقيين
3. الانتقال لقسم "الـ Leads" لإدارة الـ leads
4. إسناد الـ leads للمسوقيين المناسبين

### للمسوقيين
1. تسجيل الدخول من `/marketer/login`
2. عرض الـ leads المسندة في لوحة التحكم
3. إدارة الـ leads وتحديث حالتها
4. إضافة سجل التواصل

## التطوير المستقبلي

يمكن إضافة الميزات التالية:
1. **تقارير وإحصائيات متقدمة**
2. **نظام إشعارات**
3. **تكامل مع أنظمة CRM خارجية**
4. **نظام مهام ومتابعة**
5. **تصدير البيانات**
6. **API endpoints للـ mobile apps**
