# دليل اختبار صفحة المنتج بعد الترحيل من Livewire

## الإعداد الأولي

### 1. التأكد من وجود API Routes
تحقق من وجود المسار في `routes/api.php`:

```php
// Add to Cart API
Route::post('/cart', [CartController::class, 'store'])->name('api.cart.store');
```

### 2. التأكد من وجود Translations
في `resources/lang/ar/messages.php`:

```php
return [
    'cart_added' => 'تمت إضافة المنتج للسلة بنجاح',
    'cart_updated' => 'تم تحديث السلة بنجاح',
    'cart_add_error' => 'حدث خطأ أثناء إضافة المنتج للسلة',
    'purchase_error' => 'حدث خطأ أثناء عملية الشراء',
    'success' => 'نجاح',
    'error' => 'خطأ',
];
```

في `resources/lang/ar/products.php`:

```php
return [
    'currency' => 'ر.س',
    'reviews' => 'تقييم',
    'default_description' => 'وصف المنتج',
];
```

في `resources/lang/ar/cart.php`:

```php
return [
    'add_to_cart' => 'أضف للسلة',
    'buy_now' => 'اشتري الآن',
    'notes' => 'ملاحظات',
    'notes_placeholder' => 'أضف ملاحظاتك هنا...',
];
```

## سيناريوهات الاختبار

### ✅ السيناريو 1: تحديث السعر التلقائي

**الخطوات:**
1. افتح صفحة منتج يحتوي على خيارات
2. اختر خيارات مختلفة
3. غيّر الكمية

**النتيجة المتوقعة:**
- السعر يتحدث فوراً في شريط السلة الثابت
- السعر = (سعر الأساس + مجموع تعديلات الخيارات) × الكمية

**كود الفحص في Console:**
```javascript
// افتح Developer Tools (F12)
// Console Tab
updatePrice(); // يجب أن يعرض السعر المحدث
```

---

### ✅ السيناريو 2: إضافة للسلة (مستخدم مسجل)

**الخطوات:**
1. سجل دخول كمستخدم
2. افتح صفحة منتج
3. اختر الخيارات والكمية
4. اضغط "أضف للسلة"

**النتيجة المتوقعة:**
- رسالة نجاح باللون الأخضر
- زر يظهر Spinner أثناء التحميل
- المنتج يُضاف للسلة

**فحص Network:**
```
Request URL: /api/cart
Method: POST
Status: 200 OK

Request Payload:
{
  "product_id": 1,
  "quantity": 2,
  "selected_options": {"1": "5", "2": ["8", "9"]},
  "notes": "ملاحظة تجريبية"
}

Response:
{
  "success": true,
  "message": "تمت إضافة المنتج للسلة بنجاح"
}
```

---

### ✅ السيناريو 3: إضافة للسلة (زائر)

**الخطوات:**
1. تأكد من عدم تسجيل الدخول
2. افتح صفحة منتج
3. اختر الخيارات والكمية
4. اضغط "أضف للسلة"

**النتيجة المتوقعة:**
- يعمل بدون Authorization header
- يستخدم Session للسلة
- رسالة نجاح تظهر

**فحص Network:**
```
Request Headers:
- لا يوجد Authorization header
- X-CSRF-TOKEN موجود

Response: نفس الاستجابة للمستخدم المسجل
```

---

### ✅ السيناريو 4: الشراء الفوري

**الخطوات:**
1. افتح صفحة منتج
2. اختر الخيارات والكمية
3. اضغط "اشتري الآن"

**النتيجة المتوقعة:**
- المنتج يُضاف للسلة
- توجيه تلقائي لصفحة السلة (`/cart`)

**كود الفحص:**
```javascript
// يجب أن يحدث redirect بعد إضافة ناجحة
window.location.href = '/cart';
```

---

### ✅ السيناريو 5: التعامل مع الأخطاء

**الخطوات:**
1. افصل الإنترنت (Offline Mode)
2. حاول إضافة منتج للسلة
3. أعد الاتصال

**النتيجة المتوقعة:**
- رسالة خطأ باللون الأحمر
- الزر يعود لحالته الطبيعية
- لا يحدث redirect

**فحص Console:**
```javascript
// يجب أن تظهر رسالة خطأ
Error: Failed to fetch
```

---

### ✅ السيناريو 6: الاستجابة للشاشات المختلفة

**الخطوات:**
1. افتح صفحة المنتج على:
   - Desktop (> 991px)
   - Tablet (768px - 991px)
   - Mobile (< 768px)

**النتيجة المتوقعة:**

**Desktop:**
- شريط السلة ثابت في الأسفل
- الخيارات على اليمين
- الصورة على اليسار

**Tablet:**
- شريط السلة ثابت
- العناصر متكيفة

**Mobile:**
- شريط السلة عمودي
- الأزرار عرض كامل

---

### ✅ السيناريو 7: اختبار الخيارات المختلفة

#### Radio Buttons:
```blade
<input type="radio" name="option_1" value="5" checked>
```
- يمكن اختيار واحد فقط
- السعر يتحدث عند التغيير

#### Checkboxes:
```blade
<input type="checkbox" name="option_2[]" value="8">
<input type="checkbox" name="option_2[]" value="9">
```
- يمكن اختيار متعدد
- كل اختيار يضيف للسعر

#### Select Dropdown:
```blade
<select id="option_3">
  <option value="10">خيار 1</option>
  <option value="11">خيار 2</option>
</select>
```
- اختيار من قائمة
- السعر يتحدث عند التغيير

---

## اختبارات الأداء

### قياس سرعة التحميل

**قبل (مع Livewire):**
```
DOMContentLoaded: ~1.5s
Load Complete: ~2.2s
Requests: ~25
```

**بعد (Blade نقي):**
```
DOMContentLoaded: ~0.8s
Load Complete: ~1.3s
Requests: ~18
```

**تحسين متوقع: ~40% أسرع** ⚡

---

## حل المشاكل الشائعة

### Problem 1: السعر لا يتحدث

**الحل:**
1. افتح Console
2. تحقق من وجود أخطاء JavaScript
3. تأكد من أن `basePrice` محدد:
```javascript
console.log(basePrice); // يجب أن يعرض رقم
```

### Problem 2: زر "أضف للسلة" لا يعمل

**الحل:**
1. تحقق من Network Tab
2. ابحث عن طلب `/api/cart`
3. افحص الـ Status Code:
   - 200: نجح
   - 401: غير مصرح
   - 419: CSRF token expired
   - 500: خطأ في السيرفر

### Problem 3: الإشعارات لا تظهر

**الحل:**
```javascript
// اختبر دالة الإشعارات مباشرة
showNotification('اختبار', 'success');
```

إذا لم تظهر، تحقق من CSS للـ `.notification`

### Problem 4: خطأ 419 (CSRF Token)

**الحل:**
```html
<!-- تأكد من وجود -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

```javascript
// في الـ fetch
headers: {
    'X-CSRF-TOKEN': '{{ csrf_token() }}'
}
```

---

## Checklist النهائي

قبل الإطلاق للإنتاج:

- [ ] جميع الخيارات تعمل بشكل صحيح
- [ ] السعر يتحدث تلقائياً
- [ ] إضافة للسلة تعمل (مستخدم مسجل)
- [ ] إضافة للسلة تعمل (زائر)
- [ ] الشراء الفوري يعمل
- [ ] الإشعارات تظهر بشكل صحيح
- [ ] حالات التحميل تعمل
- [ ] الاستجابة للشاشات مختلفة
- [ ] لا توجد أخطاء في Console
- [ ] لا توجد أخطاء في Network
- [ ] الترجمات تعمل (AR/EN)
- [ ] التوافق مع المتصفحات الرئيسية

---

## أدوات الاختبار الموصى بها

### 1. Browser DevTools
- Chrome DevTools (F12)
- Firefox Developer Tools
- Safari Web Inspector

### 2. Network Throttling
اختبر مع سرعات إنترنت مختلفة:
- Fast 3G
- Slow 3G
- Offline

### 3. Responsive Testing
- Chrome Device Mode
- Firefox Responsive Design Mode
- Real devices testing

### 4. Performance Testing
```javascript
// قياس زمن تنفيذ updatePrice
console.time('updatePrice');
updatePrice();
console.timeEnd('updatePrice');
// يجب أن يكون < 10ms
```

---

## الخلاصة

إذا نجحت جميع الاختبارات أعلاه، فإن الترحيل من Livewire إلى Blade تم بنجاح! 🎉

**الفوائد:**
- ✅ أداء أفضل
- ✅ كود أبسط
- ✅ صيانة أسهل
- ✅ تحكم أكبر

**Next Steps:**
1. اختبر على staging environment
2. راقب الأداء
3. اجمع feedback من المستخدمين
4. احذف ملفات Livewire القديمة (اختياري)

