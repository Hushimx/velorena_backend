@extends('lender.layouts.app')

@section('title', 'إضافة عنصر جديد')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('lender.listings.index') }}" class="text-green-600 hover:underline mr-2">العروض</a>
        <span class="mx-2">/</span>
        <span class="text-gray-500">إضافة عرض جديد</span>
    </div>

    <div class="bg-white rounded-lg shadow">
        <!-- Progress Steps -->
        <div class="border-b border-gray-200 px-6 py-4">
            <h1 class="text-2xl font-bold mb-4">إضافة عرض جديد</h1>
            <!-- Debug info for testing -->
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center text-green-700">
                        <i class="fas fa-check-circle ml-2"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="text-red-700">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        <span class="text-sm font-semibold">يوجد أخطاء في النموذج:</span>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            <!-- Debug buttons - remove in production -->
            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-700 mb-2">أزرار اختبار (للتطوير فقط):</p>
                <div class="flex gap-2">
                    <button type="button" onclick="showStep(1)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">خطوة 1</button>
                    <button type="button" onclick="showStep(2)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">خطوة 2</button>
                    <button type="button" onclick="showStep(3)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">خطوة 3</button>
                    <button type="button" onclick="showStep(4)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">خطوة 4</button>
                </div>
            </div>
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <div class="flex items-center">
                    <div id="step1-indicator" class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                    <span id="step1-text" class="mr-2 text-sm font-medium text-green-600">المعلومات الأساسية</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200">
                    <div id="progress-bar-1" class="h-1 bg-green-600" style="width: 0%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step2-indicator" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                    <span id="step2-text" class="mr-2 text-sm font-medium text-gray-600">تفاصيل المنتج</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200">
                    <div id="progress-bar-2" class="h-1 bg-green-600" style="width: 0%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step3-indicator" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                    <span id="step3-text" class="mr-2 text-sm font-medium text-gray-600">التسعير والموقع</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200">
                    <div id="progress-bar-3" class="h-1 bg-green-600" style="width: 0%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step4-indicator" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">4</div>
                    <span id="step4-text" class="mr-2 text-sm font-medium text-gray-600">المراجعة والإنهاء</span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('lender.listings.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Step 1: Basic Information -->
            <div id="step-1" class="step-content">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الخطوة 1: المعلومات الأساسية</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">اسم العنصر *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">التصنيف *</label>
                        <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">الوصف</label>
                        <textarea name="description" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <!-- Step 1 Navigation -->
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="nextStep(1)" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 2: Product Details -->
            <div id="step-2" class="step-content hidden">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الخطوة 2: تفاصيل المنتج</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الماركة</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('brand') border-red-500 @enderror">
                        @error('brand')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الموديل</label>
                        <input type="text" name="model" value="{{ old('model') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('model') border-red-500 @enderror">
                        @error('model')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الرقم التسلسلي</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('serial_number') border-red-500 @enderror">
                        @error('serial_number')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">سنة الصنع</label>
                        <input type="number" name="manufacturing_year" value="{{ old('manufacturing_year') }}" min="1900" max="{{ date('Y') + 1 }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('manufacturing_year') border-red-500 @enderror">
                        @error('manufacturing_year')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>



                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">العلامات (Tags)</label>
                        <input type="text" name="tags" value="{{ old('tags') }}" placeholder="مثال: إلكترونيات، ترفيه، منزل"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tags') border-red-500 @enderror">
                        @error('tags')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Step 2 Navigation -->
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="prevStep(2)" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                        السابق
                    </button>
                    <button type="button" onclick="nextStep(2)" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 3: Pricing and Location -->
            <div id="step-3" class="step-content hidden">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الخطوة 3: التسعير والموقع</h2>
                
                <!-- Pricing Section -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold mb-4 text-gray-700">التسعير</h3>
                
                <!-- Show pricing validation error -->
                @error('pricing')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center text-red-700">
                            <i class="fas fa-exclamation-triangle ml-2"></i>
                            <span class="text-sm">{{ $message }}</span>
                        </div>
                    </div>
                @enderror
                
                <!-- Price Activation Toggles -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="daily_price_active" value="1" {{ old('daily_price_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('daily')">
                        <label class="mr-2 font-semibold text-gray-700">تفعيل السعر اليومي</label>
                    </div>
                    
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="weekly_price_active" value="1" {{ old('weekly_price_active') ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('weekly')">
                        <label class="mr-2 font-semibold text-gray-700">تفعيل السعر الأسبوعي</label>
                    </div>
                    
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="monthly_price_active" value="1" {{ old('monthly_price_active') ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('monthly')">
                        <label class="mr-2 font-semibold text-gray-700">تفعيل السعر الشهري</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div id="daily-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر اليومي</label>
                        <input type="number" name="base_price" value="{{ old('base_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('base_price') border-red-500 @enderror">
                        @error('base_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div id="weekly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر الأسبوعي</label>
                        <input type="number" name="weekly_price" value="{{ old('weekly_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('weekly_price') border-red-500 @enderror">
                        @error('weekly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div id="monthly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر الشهري</label>
                        <input type="number" name="monthly_price" value="{{ old('monthly_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('monthly_price') border-red-500 @enderror">
                        @error('monthly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">مبلغ الضمان</label>
                        <input type="number" name="deposit_amount" value="{{ old('deposit_amount') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('deposit_amount') border-red-500 @enderror">
                        @error('deposit_amount')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">رسوم التوصيل</label>
                        <input type="number" name="delivery_fee" value="{{ old('delivery_fee', 0) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('delivery_fee') border-red-500 @enderror">
                        @error('delivery_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">رسوم الاستلام</label>
                        <input type="number" name="pickup_fee" value="{{ old('pickup_fee', 0) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('pickup_fee') border-red-500 @enderror">
                        @error('pickup_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Location Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الموقع</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">العنوان</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="أدخل العنوان"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('location') border-red-500 @enderror">
                        @error('location')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">المدينة</label>
                        <select name="city" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('city') border-red-500 @enderror">
                            <option value="">اختر المدينة</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->name }}" {{ old('city') == $city->name ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الحي</label>
                        <input type="text" name="neighborhood" value="{{ old('neighborhood') }}" placeholder="أدخل اسم الحي"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('neighborhood') border-red-500 @enderror">
                        @error('neighborhood')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الرمز البريدي</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="أدخل الرمز البريدي"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('postal_code') border-red-500 @enderror">
                        @error('postal_code')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Enhanced Map with Search -->
                <div class="mt-4">
                    <label class="block mb-2 font-semibold text-gray-700">تحديد الموقع على الخريطة</label>
                    
                    <!-- Map Controls -->
                    <div class="mb-4 flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" id="search-location" placeholder="ابحث عن مكان (مثال: الرياض، جدة)"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="search-btn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-search ml-1"></i> بحث
                            </button>
                            <button type="button" id="current-location-btn" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-crosshairs ml-1"></i> موقعي الحالي
                            </button>
                        </div>
                    </div>

                    <div id="map" class="w-full h-96 rounded-lg border border-gray-300 z-10"></div>
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start text-sm text-blue-700">
                            <i class="fas fa-info-circle mt-0.5 ml-2 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium">كيفية استخدام الخريطة:</p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>انقر على الخريطة لتحديد الموقع</li>
                                    <li>اسحب العلامة لتحريك الموقع</li>
                                    <li>ستتم تعبئة بيانات العنوان والحي والرمز البريدي تلقائياً</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden fields for coordinates -->
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                </div>
                </div>
                
                <!-- Step 3 Navigation -->
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="prevStep(3)" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                        السابق
                    </button>
                    <button type="button" onclick="nextStep(3)" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 4: Final Details and Review -->
            <div id="step-4" class="step-content hidden">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الخطوة 4: التفاصيل الأخيرة والمراجعة</h2>
                
                <!-- Debug message to confirm step 4 is visible -->
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center text-blue-700">
                        <i class="fas fa-info-circle ml-2"></i>
                        <span class="text-sm">أنت الآن في الخطوة الأخيرة! راجع المعلومات وتأكد من صحتها قبل الإرسال.</span>
                    </div>
                </div>
                
                <!-- Rental Settings Section -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold mb-4 text-gray-700">إعدادات الإيجار</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الحد الأدنى للأيام</label>
                        <input type="number" name="minimum_rental_days" value="{{ old('minimum_rental_days', 1) }}" min="1"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('minimum_rental_days') border-red-500 @enderror">
                        @error('minimum_rental_days')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الحد الأقصى للأيام</label>
                        <input type="number" name="maximum_rental_days" value="{{ old('maximum_rental_days') }}" min="1"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('maximum_rental_days') border-red-500 @enderror">
                        @error('maximum_rental_days')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الاستلام من</label>
                        <input type="time" name="pickup_time_start" value="{{ old('pickup_time_start', '09:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('pickup_time_start') border-red-500 @enderror">
                        @error('pickup_time_start')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الاستلام إلى</label>
                        <input type="time" name="pickup_time_end" value="{{ old('pickup_time_end', '18:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('pickup_time_end') border-red-500 @enderror">
                        @error('pickup_time_end')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الإرجاع من</label>
                        <input type="time" name="return_time_start" value="{{ old('return_time_start', '09:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('return_time_start') border-red-500 @enderror">
                        @error('return_time_start')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الإرجاع إلى</label>
                        <input type="time" name="return_time_end" value="{{ old('return_time_end', '18:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('return_time_end') border-red-500 @enderror">
                        @error('return_time_end')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Instructions Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">التعليمات والملاحظات</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">تعليمات الاستخدام</label>
                        <textarea name="usage_instructions" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('usage_instructions') border-red-500 @enderror">{{ old('usage_instructions') }}</textarea>
                        @error('usage_instructions')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">تعليمات السلامة</label>
                        <textarea name="safety_instructions" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('safety_instructions') border-red-500 @enderror">{{ old('safety_instructions') }}</textarea>
                        @error('safety_instructions')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">ملاحظات الصيانة</label>
                        <textarea name="maintenance_notes" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('maintenance_notes') border-red-500 @enderror">{{ old('maintenance_notes') }}</textarea>
                        @error('maintenance_notes')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            @include('components.image-upload')

                <!-- Review Summary -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold mb-4 text-gray-700">مراجعة البيانات</h3>
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">المعلومات الأساسية</h4>
                                <div class="space-y-1 text-sm">
                                    <div id="review-name" class="text-gray-600">الاسم: -</div>
                                    <div id="review-category" class="text-gray-600">التصنيف: -</div>
                                    <div id="review-brand" class="text-gray-600">الماركة: -</div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">التسعير</h4>
                                <div class="space-y-1 text-sm">
                                    <div id="review-daily" class="text-gray-600">يومي: -</div>
                                    <div id="review-weekly" class="text-gray-600">أسبوعي: -</div>
                                    <div id="review-monthly" class="text-gray-600">شهري: -</div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">الموقع</h4>
                                <div class="space-y-1 text-sm">
                                    <div id="review-location" class="text-gray-600">العنوان: -</div>
                                    <div id="review-city" class="text-gray-600">المدينة: -</div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">الصور</h4>
                                <div id="review-images" class="text-sm text-gray-600">لم يتم رفع صور</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 Navigation -->
                <div class="flex justify-between mt-6 pt-6 border-t">
                    <button type="button" onclick="prevStep(4)" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                        السابق
                    </button>
                    <div class="flex gap-4">
                <a href="{{ route('lender.listings.index') }}" 
                           class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                    إلغاء
                </a>
                <button type="submit" 
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                            إنشاء العرض
                </button>
                    </div>
                </div>
            </div>
            <!-- End of Step 4 -->
        </form>
    </div>
</div>

<style>
.step-content {
    display: block;
}
.step-content.hidden {
    display: none !important;
}
</style>

<script>
// Global variables
let currentStep = 1;
let map;
let marker;

// Step Management
function showStep(step) {
    console.log('Showing step:', step);
    
    // Hide all steps
    for (let i = 1; i <= 4; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        if (stepElement) {
            stepElement.classList.add('hidden');
            console.log('Hiding step', i);
        } else {
            console.log('Step element not found:', `step-${i}`);
        }
    }
    
    // Show current step
    const currentStepElement = document.getElementById(`step-${step}`);
    if (currentStepElement) {
        currentStepElement.classList.remove('hidden');
        console.log('Showing step element:', `step-${step}`);
    } else {
        console.log('Current step element not found:', `step-${step}`);
    }
    
    // Update step indicators
    updateStepIndicators(step);
    
    // Initialize map when reaching step 3
    if (step === 3 && !map) {
        setTimeout(initMap, 100);
    }
    
    // Update review when reaching step 4
    if (step === 4) {
        console.log('Updating review for step 4');
        setTimeout(() => {
            updateReview();
        }, 100); // Small delay to ensure DOM is ready
    }
}

function updateStepIndicators(step) {
    for (let i = 1; i <= 4; i++) {
        const indicator = document.getElementById(`step${i}-indicator`);
        const text = document.getElementById(`step${i}-text`);
        const progressBar = document.getElementById(`progress-bar-${i}`);
        
        if (i < step) {
            // Completed steps
            indicator.className = 'w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold';
            text.className = 'mr-2 text-sm font-medium text-green-600';
            if (progressBar) progressBar.style.width = '100%';
        } else if (i === step) {
            // Current step
            indicator.className = 'w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold';
            text.className = 'mr-2 text-sm font-medium text-green-600';
            if (progressBar) progressBar.style.width = '50%';
        } else {
            // Future steps
            indicator.className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold';
            text.className = 'mr-2 text-sm font-medium text-gray-600';
            if (progressBar) progressBar.style.width = '0%';
        }
    }
}

function nextStep(from) {
    if (validateStep(from)) {
        currentStep = from + 1;
        showStep(currentStep);
    }
}

function prevStep(from) {
    currentStep = from - 1;
    showStep(currentStep);
}

function validateStep(step) {
    // Clear previous validation errors
    clearValidationErrors();
    
    switch(step) {
        case 1:
            // Validate basic information
            const name = document.querySelector('input[name="name"]').value.trim();
            const category = document.querySelector('select[name="category_id"]').value;
            
            if (!name) {
                showValidationError('input[name="name"]', 'يرجى إدخال اسم العنصر');
                return false;
            }
            if (!category) {
                showValidationError('select[name="category_id"]', 'يرجى اختيار التصنيف');
                return false;
            }
            break;
            
        case 2:
            // Step 2 validation can be added here if needed
            break;
            
        case 3:
            // Validate pricing
            const dailyActive = document.querySelector('input[name="daily_price_active"]');
            const weeklyActive = document.querySelector('input[name="weekly_price_active"]');
            const monthlyActive = document.querySelector('input[name="monthly_price_active"]');
            
            if (!dailyActive.checked && !weeklyActive.checked && !monthlyActive.checked) {
                showValidationError('input[name="daily_price_active"]', 'يرجى تفعيل نوع واحد على الأقل من التسعير');
                return false;
            }
            
            // Check prices for active types
            if (dailyActive.checked && !document.querySelector('input[name="base_price"]').value) {
                showValidationError('input[name="base_price"]', 'يرجى إدخال السعر اليومي');
                return false;
            }
            if (weeklyActive.checked && !document.querySelector('input[name="weekly_price"]').value) {
                showValidationError('input[name="weekly_price"]', 'يرجى إدخال السعر الأسبوعي');
                return false;
            }
            if (monthlyActive.checked && !document.querySelector('input[name="monthly_price"]').value) {
                showValidationError('input[name="monthly_price"]', 'يرجى إدخال السعر الشهري');
                return false;
            }
            break;
    }
    return true;
}

// Function to show validation error under input
function showValidationError(selector, message) {
    const element = document.querySelector(selector);
    if (!element) return;
    
    // Add error styling to the input
    element.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    element.classList.remove('border-gray-300', 'focus:ring-green-500', 'focus:border-transparent');
    
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'validation-error text-red-600 text-sm mt-1 flex items-center';
    errorDiv.innerHTML = `
        <svg class="w-4 h-4 ml-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        ${message}
    `;
    
    // Insert error message after the input
    element.parentNode.insertBefore(errorDiv, element.nextSibling);
    
    // Focus on the problematic field
    element.focus();
    
    // Scroll to the error if needed
    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Function to clear all validation errors
function clearValidationErrors() {
    // Remove error styling from all inputs
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        input.classList.add('border-gray-300', 'focus:ring-green-500', 'focus:border-transparent');
    });
    
    // Remove all error messages
    const errorMessages = document.querySelectorAll('.validation-error');
    errorMessages.forEach(error => error.remove());
}

// Enhanced Map functionality with search and location
function initMap() {
    if (map) return;
    
    // Default location (Riyadh, Saudi Arabia)
    const defaultLat = 24.7136;
    const defaultLng = 46.6753;
    
    // Initialize map
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add click event to map
    map.on('click', function(e) {
        placeMarker(e.latlng.lat, e.latlng.lng);
    });
    
    // Search functionality
    document.getElementById('search-btn').addEventListener('click', function() {
        const searchTerm = document.getElementById('search-location').value.trim();
        if (searchTerm) {
            searchLocation(searchTerm);
        }
    });
    
    // Enter key for search
    document.getElementById('search-location').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchTerm = this.value.trim();
            if (searchTerm) {
                searchLocation(searchTerm);
            }
        }
    });
    
    // Current location functionality
    document.getElementById('current-location-btn').addEventListener('click', function() {
        getCurrentLocation();
    });
    
    console.log('Enhanced map initialized successfully');
}

function placeMarker(lat, lng, skipReverseGeocode = false) {
    // Remove existing marker
    if (marker) {
        map.removeLayer(marker);
    }
    
    // Add new marker
    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);
    
    // Update hidden fields
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    
    // Add drag event to marker
    marker.on('dragend', function(e) {
        const newLat = e.target.getLatLng().lat;
        const newLng = e.target.getLatLng().lng;
        document.getElementById('latitude').value = newLat;
        document.getElementById('longitude').value = newLng;
        
        // Update address fields when marker is dragged
        reverseGeocode(newLat, newLng);
    });
    
    // Center map on marker
    map.setView([lat, lng], 15);
    
    // Perform reverse geocoding to get address information
    if (!skipReverseGeocode) {
        reverseGeocode(lat, lng);
    }
}

function reverseGeocode(lat, lng) {
    // Show loading indicator
    showMapMessage('جار تحديث بيانات العنوان...', 'info');
    
    // Use Nominatim reverse geocoding API
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=ar,en`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const address = data.address;
                
                // Extract address components
                const streetNumber = address.house_number || '';
                const streetName = address.road || address.street || '';
                const neighborhood = address.neighbourhood || address.suburb || address.quarter || address.district || '';
                const city = address.city || address.town || address.village || address.state || '';
                const postcode = address.postcode || '';
                
                // Build full address
                let fullAddress = '';
                if (streetNumber && streetName) {
                    fullAddress = `${streetNumber} ${streetName}`;
                } else if (streetName) {
                    fullAddress = streetName;
                } else if (data.display_name) {
                    // Use the first part of display_name as fallback
                    const addressParts = data.display_name.split(',');
                    fullAddress = addressParts[0] || '';
                }
                
                // Update form fields
                updateAddressFields({
                    location: fullAddress,
                    neighborhood: neighborhood,
                    city: city,
                    postal_code: postcode
                });
                
                showMapMessage('تم تحديث بيانات العنوان بنجاح!', 'success');
            } else {
                showMapMessage('لم يتمكن من العثور على بيانات العنوان لهذا الموقع', 'warning');
            }
        })
        .catch(error => {
            console.error('Reverse geocoding error:', error);
            showMapMessage('حدث خطأ في تحديث بيانات العنوان', 'error');
        });
}

function updateAddressFields(addressData) {
    // Helper function to animate field update
    function animateFieldUpdate(field, value) {
        if (field && value) {
            // Add animation class
            field.classList.add('bg-green-50', 'border-green-300');
            field.value = value;
            
            // Remove animation class after 2 seconds
            setTimeout(() => {
                field.classList.remove('bg-green-50', 'border-green-300');
            }, 2000);
        }
    }
    
    // Update location field
    const locationField = document.querySelector('input[name="location"]');
    animateFieldUpdate(locationField, addressData.location);
    
    // Update neighborhood field
    const neighborhoodField = document.querySelector('input[name="neighborhood"]');
    animateFieldUpdate(neighborhoodField, addressData.neighborhood);
    
    // Update postal code field
    const postalCodeField = document.querySelector('input[name="postal_code"]');
    animateFieldUpdate(postalCodeField, addressData.postal_code);
    
    // Update city field (select dropdown)
    const cityField = document.querySelector('select[name="city"]');
    if (cityField && addressData.city) {
        // Try to find matching city in dropdown
        const cityOptions = Array.from(cityField.options);
        const matchingOption = cityOptions.find(option => 
            option.value.toLowerCase().includes(addressData.city.toLowerCase()) ||
            addressData.city.toLowerCase().includes(option.value.toLowerCase())
        );
        
        if (matchingOption) {
            cityField.value = matchingOption.value;
            // Animate city field update
            cityField.classList.add('bg-green-50', 'border-green-300');
            setTimeout(() => {
                cityField.classList.remove('bg-green-50', 'border-green-300');
            }, 2000);
        } else {
            // If no exact match, try to set the first option that contains the city name
            const partialMatch = cityOptions.find(option => 
                option.text.toLowerCase().includes(addressData.city.toLowerCase())
            );
            if (partialMatch) {
                cityField.value = partialMatch.value;
                // Animate city field update
                cityField.classList.add('bg-green-50', 'border-green-300');
                setTimeout(() => {
                    cityField.classList.remove('bg-green-50', 'border-green-300');
                }, 2000);
            }
        }
    }
    
    // Trigger change events to update any dependent fields
    [locationField, neighborhoodField, postalCodeField, cityField].forEach(field => {
        if (field) {
            field.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });
}

function searchLocation(query) {
    // Show loading state
    const searchBtn = document.getElementById('search-btn');
    const originalText = searchBtn.innerHTML;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جار البحث...';
    searchBtn.disabled = true;
    
    // Use Nominatim (OpenStreetMap) search API
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=sa&limit=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                placeMarker(lat, lng);
                
                // Show success message
                showMapMessage('تم العثور على الموقع بنجاح!', 'success');
            } else {
                showMapMessage('لم يتم العثور على الموقع. جرب كلمات مختلفة.', 'error');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            showMapMessage('حدث خطأ في البحث. تأكد من الاتصال بالإنترنت.', 'error');
        })
        .finally(() => {
            // Restore button state
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        });
}

function getCurrentLocation() {
    const btn = document.getElementById('current-location-btn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جار التحديد...';
    btn.disabled = true;
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                placeMarker(lat, lng);
                showMapMessage('تم تحديد موقعك الحالي بنجاح!', 'success');
            },
            function(error) {
                let message = 'تعذر الحصول على موقعك الحالي.';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'يرجى السماح للموقع بالوصول لموقعك الجغرافي.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'معلومات الموقع غير متوفرة.';
                        break;
                    case error.TIMEOUT:
                        message = 'انتهت مهلة الحصول على الموقع.';
                        break;
                }
                showMapMessage(message, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        showMapMessage('متصفحك لا يدعم تحديد الموقع الجغرافي.', 'error');
    }
    
    // Restore button state after timeout
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 10000);
}

function showMapMessage(message, type) {
    // Remove existing message
    const existingMessage = document.querySelector('.map-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Define message styles based on type
    let bgClass, textClass, borderClass, iconClass;
    switch(type) {
        case 'success':
            bgClass = 'bg-green-100';
            textClass = 'text-green-700';
            borderClass = 'border-green-200';
            iconClass = 'fa-check-circle';
            break;
        case 'error':
            bgClass = 'bg-red-100';
            textClass = 'text-red-700';
            borderClass = 'border-red-200';
            iconClass = 'fa-exclamation-triangle';
            break;
        case 'warning':
            bgClass = 'bg-yellow-100';
            textClass = 'text-yellow-700';
            borderClass = 'border-yellow-200';
            iconClass = 'fa-exclamation-circle';
            break;
        case 'info':
            bgClass = 'bg-blue-100';
            textClass = 'text-blue-700';
            borderClass = 'border-blue-200';
            iconClass = 'fa-info-circle';
            break;
        default:
            bgClass = 'bg-gray-100';
            textClass = 'text-gray-700';
            borderClass = 'border-gray-200';
            iconClass = 'fa-info-circle';
    }
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = `map-message mt-2 p-3 rounded-lg text-sm ${bgClass} ${textClass} border ${borderClass}`;
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${iconClass} ml-2"></i>
            ${message}
        </div>
    `;
    
    // Insert after map
    const mapContainer = document.getElementById('map');
    mapContainer.parentNode.insertBefore(messageDiv, mapContainer.nextSibling);
    
    // Auto remove after appropriate time based on type
    const autoRemoveTime = type === 'info' ? 3000 : 5000;
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, autoRemoveTime);
}

// Price field toggle functionality
function togglePriceField(type) {
    const checkbox = document.querySelector(`input[name="${type}_price_active"]`);
    const field = document.getElementById(`${type}-price-field`);
    const input = field.querySelector('input');
    
    if (checkbox && field && input) {
        if (checkbox.checked) {
            field.style.opacity = '1';
            input.disabled = false;
            input.required = true;
        } else {
            field.style.opacity = '0.5';
            input.disabled = true;
            input.required = false;
            input.value = '';
        }
    }
}



// Update review summary
function updateReview() {
    console.log('updateReview called');
    
    try {
        // Basic info
        const name = document.querySelector('input[name="name"]').value || '-';
        const categorySelect = document.querySelector('select[name="category_id"]');
        const category = categorySelect.options[categorySelect.selectedIndex]?.text || '-';
        const brand = document.querySelector('input[name="brand"]').value || '-';
        
        console.log('Basic info:', { name, category, brand });
        
        const reviewName = document.getElementById('review-name');
        const reviewCategory = document.getElementById('review-category');
        const reviewBrand = document.getElementById('review-brand');
        
        if (reviewName) reviewName.textContent = `الاسم: ${name}`;
        if (reviewCategory) reviewCategory.textContent = `التصنيف: ${category}`;
        if (reviewBrand) reviewBrand.textContent = `الماركة: ${brand}`;
        
        // Pricing
        const dailyActive = document.querySelector('input[name="daily_price_active"]').checked;
        const weeklyActive = document.querySelector('input[name="weekly_price_active"]').checked;
        const monthlyActive = document.querySelector('input[name="monthly_price_active"]').checked;
        
        const dailyPrice = dailyActive ? (document.querySelector('input[name="base_price"]').value || '0') : '-';
        const weeklyPrice = weeklyActive ? (document.querySelector('input[name="weekly_price"]').value || '0') : '-';
        const monthlyPrice = monthlyActive ? (document.querySelector('input[name="monthly_price"]').value || '0') : '-';
        
        console.log('Pricing info:', { dailyActive, weeklyActive, monthlyActive, dailyPrice, weeklyPrice, monthlyPrice });
        
        const reviewDaily = document.getElementById('review-daily');
        const reviewWeekly = document.getElementById('review-weekly');
        const reviewMonthly = document.getElementById('review-monthly');
        
        if (reviewDaily) reviewDaily.textContent = `يومي: ${dailyPrice === '-' ? 'غير مفعل' : dailyPrice + ' ر.س'}`;
        if (reviewWeekly) reviewWeekly.textContent = `أسبوعي: ${weeklyPrice === '-' ? 'غير مفعل' : weeklyPrice + ' ر.س'}`;
        if (reviewMonthly) reviewMonthly.textContent = `شهري: ${monthlyPrice === '-' ? 'غير مفعل' : monthlyPrice + ' ر.س'}`;
        
        // Location
        const location = document.querySelector('input[name="location"]').value || '-';
        const citySelect = document.querySelector('select[name="city"]');
        const city = citySelect.options[citySelect.selectedIndex]?.text || '-';
        
        console.log('Location info:', { location, city });
        
        const reviewLocation = document.getElementById('review-location');
        const reviewCity = document.getElementById('review-city');
        
        if (reviewLocation) reviewLocation.textContent = `العنوان: ${location}`;
        if (reviewCity) reviewCity.textContent = `المدينة: ${city}`;
        
        // Images
        const fileInput = document.getElementById('file-input');
        const imageCount = fileInput && fileInput.files ? fileInput.files.length : 0;
        
        console.log('Images info:', { imageCount });
        
        const reviewImages = document.getElementById('review-images');
        if (reviewImages) reviewImages.textContent = imageCount > 0 ? `${imageCount} ${imageCount === 1 ? 'صورة مرفوعة' : 'صور مرفوعة'}` : 'لم يتم رفع صور';
        
        console.log('Review updated successfully');
    } catch (error) {
        console.error('Error updating review:', error);
    }
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Step Wizard');
    
    // Check if all step elements exist
    for (let i = 1; i <= 4; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        if (stepElement) {
            console.log(`Step ${i} element found`);
        } else {
            console.error(`Step ${i} element NOT found`);
        }
    }
    
    // Check step 4 specific elements
    const imagePreview = document.getElementById('image-preview');
    const reviewName = document.getElementById('review-name');
    console.log('Image preview element:', imagePreview ? 'Found' : 'NOT FOUND');
    console.log('Review name element:', reviewName ? 'Found' : 'NOT FOUND');
    
    // Initialize first step
    showStep(1);
    
    // Initialize price fields
    togglePriceField('daily');
    togglePriceField('weekly');
    togglePriceField('monthly');
    
    // Form validation before submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Clear any previous validation errors
            clearValidationErrors();
            
            // Final validation
            if (!validateStep(1)) {
                e.preventDefault();
                showStep(1); // Go back to step with error
                return false;
            }
            
            if (!validateStep(3)) {
                e.preventDefault();
                showStep(3); // Go back to step with error
                return false;
            }
            
            // Show loading state on submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جار الإرسال...';
            }
        });
    }
});
</script>
@endsection



