@extends('lender.layouts.app')

@section('title', 'إضافة عنصر جديد')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

<div class="container mx-auto px-4 py-6">
    <!-- Profile Completion Notice -->
    <x-profile-completion-notice />
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
            

            <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
                <div class="flex items-center">
                    <div id="step1-indicator" class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">1</div>
                    <span id="step1-text" class="mr-2 text-xs sm:text-sm font-medium text-green-600">المعلومات الأساسية</span>
                </div>
                <div class="hidden sm:block flex-1 h-1 bg-gray-200">
                    <div id="progress-bar-1" class="h-1 bg-green-600" style="width: 0%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step2-indicator" class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">2</div>
                    <span id="step2-text" class="mr-2 text-xs sm:text-sm font-medium text-gray-600">التسعير والإعدادات</span>
                </div>
                <div class="hidden sm:block flex-1 h-1 bg-gray-200">
                    <div id="progress-bar-2" class="h-1 bg-green-600" style="width: 0%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step3-indicator" class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">3</div>
                    <span id="step3-text" class="mr-2 text-xs sm:text-sm font-medium text-gray-600">الموقع</span>
                </div>
                <div class="hidden sm:block flex-1 h-1 bg-gray-200">
                    <div id="progress-bar-3" class="h-1 bg-green-600" style="width: 0%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step4-indicator" class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">4</div>
                    <span id="step4-text" class="mr-2 text-xs sm:text-sm font-medium text-gray-600">المراجعة والإنهاء</span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('lender.listings.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Step 1: Basic Information -->
            <div id="step-1" class="step-content">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الخطوة 1: المعلومات الأساسية</h2>
                
                <!-- Images Section - First thing in Step 1 -->
                <div class="mb-8">
                    @include('components.image-upload', ['isEdit' => false, 'existingImages' => []])
                </div>

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

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الماركة <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="brand" value="{{ old('brand') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('brand') border-red-500 @enderror">
                        @error('brand')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الموديل <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="model" value="{{ old('model') }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('model') border-red-500 @enderror">
                        @error('model')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">الوصف *</label>
                        <textarea name="description" id="description" rows="4" maxlength="1000" required
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="اكتب وصفاً مفصلاً عن العنصر (حد أقصى 1000 حرف)"
                                  oninput="updateCharCount()">{{ old('description') }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <div id="char-count" class="text-sm text-gray-500">0 / 1000 حرف</div>
                            <div class="text-xs text-gray-400">يُفضل كتابة وصف واضح ومفصل</div>
                        </div>
                        @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">العلامات (Tags) <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="tags" value="{{ old('tags') }}" placeholder="مثال: إلكترونيات، ترفيه، منزل"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tags') border-red-500 @enderror">
                        @error('tags')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <!-- Step 1 Navigation -->
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="nextStep(1)" class="bg-green-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200 text-sm sm:text-base">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 2: Product Details, Pricing & Rental Settings -->
            <div id="step-2" class="step-content hidden">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الخطوة 2: التسعير وإعدادات الإيجار</h2>
                

                
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                    <div class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="daily_price_active" value="1" {{ old('daily_price_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('daily')">
                        <label class="mr-2 font-semibold text-gray-700 text-sm sm:text-base">تفعيل السعر اليومي</label>
                    </div>
                    
                    <div class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="weekly_price_active" value="1" {{ old('weekly_price_active') ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('weekly')">
                        <label class="mr-2 font-semibold text-gray-700 text-sm sm:text-base">تفعيل السعر الأسبوعي</label>
                    </div>
                    
                    <div class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg sm:col-span-2 lg:col-span-1">
                        <input type="checkbox" name="monthly_price_active" value="1" {{ old('monthly_price_active') ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('monthly')">
                        <label class="mr-2 font-semibold text-gray-700 text-sm sm:text-base">تفعيل السعر الشهري</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div id="daily-price-field">
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">السعر اليومي (قبل الضريبة)</label>
                        <input type="number" name="base_price" value="{{ old('base_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('base_price') border-red-500 @enderror"
                               onchange="calculateCommission('daily')" onkeyup="calculateCommission('daily')">
                        @error('base_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        

                        <!-- Commission Calculator for Daily -->
                        <div id="daily-commission" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <div class="text-xs sm:text-sm">
                                <div class="flex justify-between items-center mb-2 pb-1 border-b border-blue-200">
                                    <span class="font-medium text-blue-800">تفاصيل التسعير</span>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">الضريبة (15%):</span>
                                        <span id="daily-vat-amount" class="font-medium text-gray-800">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between font-semibold pt-2 ">
                                        <span class="text-blue-800">السعر الظاهر للعميل:</span>
                                        <span id="daily-customer-price" class="text-blue-600">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between border-t border-blue-200">
                                        <span class="text-gray-600">رسوم المنصة (<span id="daily-commission-rate">0</span>%):</span>
                                        <span id="daily-commission-amount" class="font-medium text-gray-800">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between font-semibold pt-1">
                                        <span class="text-green-700">صافي الربح:</span>
                                        <span id="daily-your-price" class="text-green-600">0 ر.س</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="weekly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">السعر الأسبوعي (قبل الضريبة) <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="number" name="weekly_price" value="{{ old('weekly_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('weekly_price') border-red-500 @enderror"
                               onchange="calculateCommission('weekly')" onkeyup="calculateCommission('weekly')">
                        @error('weekly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        

                        <!-- Commission Calculator for Weekly -->
                        <div id="weekly-commission" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <div class="text-xs sm:text-sm">
                                <div class="flex justify-between items-center mb-2 pb-1 border-b border-blue-200">
                                    <span class="font-medium text-blue-800">تفاصيل التسعير</span>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">الضريبة (15%):</span>
                                        <span id="weekly-vat-amount" class="font-medium text-gray-800">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between font-semibold pt-2 ">
                                        <span class="text-blue-800">السعر الظاهر للعميل:</span>
                                        <span id="weekly-customer-price" class="text-blue-600">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between border-t border-blue-200">
                                        <span class="text-gray-600">رسوم المنصة (<span id="weekly-commission-rate">0</span>%):</span>
                                        <span id="weekly-commission-amount" class="font-medium text-gray-800">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between font-semibold pt-1">
                                        <span class="text-green-700">صافي الربح:</span>
                                        <span id="weekly-your-price" class="text-green-600">0 ر.س</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="monthly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">السعر الشهري (قبل الضريبة) <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="number" name="monthly_price" value="{{ old('monthly_price') }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('monthly_price') border-red-500 @enderror"
                               onchange="calculateCommission('monthly')" onkeyup="calculateCommission('monthly')">
                        @error('monthly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        

                        <!-- Commission Calculator for Monthly -->
                        <div id="monthly-commission" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <div class="text-xs sm:text-sm">
                                <div class="flex justify-between items-center mb-2 pb-1 border-b border-blue-200">
                                    <span class="font-medium text-blue-800">تفاصيل التسعير</span>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">الضريبة (15%):</span>
                                        <span id="monthly-vat-amount" class="font-medium text-gray-800">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between font-semibold pt-2 ">
                                        <span class="text-blue-800">السعر الظاهر للعميل:</span>
                                        <span id="monthly-customer-price" class="text-blue-600">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between border-t border-blue-200">
                                        <span class="text-gray-600">رسوم المنصة (<span id="monthly-commission-rate">0</span>%):</span>
                                        <span id="monthly-commission-amount" class="font-medium text-gray-800">0 ر.س</span>
                                    </div>
                                    <div class="flex justify-between font-semibold pt-1">
                                        <span class="text-green-700">صافي الربح:</span>
                                        <span id="monthly-your-price" class="text-green-600">0 ر.س</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="deposit_active" value="1" {{ old('deposit_active') ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 ml-2" onchange="toggleDepositField()">
                            <label class="font-semibold text-gray-700 text-sm sm:text-base">تفعيل مبلغ الضمان <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        </div>
                        <div id="deposit-field">
                            <input type="number" name="deposit_amount" value="{{ old('deposit_amount') }}" step="0.01" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('deposit_amount') border-red-500 @enderror">
                            @error('deposit_amount')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">طريقة التوصيل</label>
                        <select name="delivery_method" class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('delivery_method') border-red-500 @enderror" onchange="toggleDeliveryFeeField()">
                            <option value="pickup" {{ old('delivery_method') == 'pickup' ? 'selected' : '' }}>الاستلام من موقع المؤجر</option>
                            <option value="paid_delivery" {{ old('delivery_method') == 'paid_delivery' ? 'selected' : '' }}>التوصيل برسوم</option>
                            <option value="free_delivery" {{ old('delivery_method') == 'free_delivery' ? 'selected' : '' }}>توصيل مجاني</option>
                        </select>
                        @error('delivery_method')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        
                        <div id="delivery-fee-field" class="mt-2 hidden">
                            <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">رسوم التوصيل</label>
                            <input type="number" name="delivery_fee" value="{{ old('delivery_fee', 0) }}" step="0.01" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent @error('delivery_fee') border-red-500 @enderror">
                            @error('delivery_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
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

                <!-- Step 2 Navigation -->
                <div class="flex flex-col sm:flex-row justify-between gap-3 sm:gap-0 mt-6">
                    <button type="button" onclick="prevStep(2)" class="bg-gray-300 text-gray-800 px-4 sm:px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200 text-sm sm:text-base">
                        السابق
                    </button>
                    <button type="button" onclick="nextStep(2)" class="bg-green-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200 text-sm sm:text-base">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 3: Location -->
            <div id="step-3" class="step-content hidden">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الخطوة 3: الموقع</h2>
                
                <!-- Location Section -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold mb-4 text-gray-700">الموقع</h3>
                    
                    <!-- Enhanced Map with Search -->
                    <div class="mb-6">
                        <label class="block mb-2 font-semibold text-gray-700">تحديد الموقع على الخريطة</label>
                        
                        <!-- Map with embedded search -->
                        <div class="relative">
                            <div id="map" class="w-full h-80 sm:h-96 rounded-lg border border-gray-300 z-10"></div>
                            
                            <!-- Search overlay on map -->
                            <div class="absolute top-2 sm:top-4 left-2 sm:left-4 right-2 sm:right-4 z-20">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="flex-1 relative">
                                        <input type="text" id="search-location" placeholder="ابحث في مدن السعودية (مثال: الرياض، جدة، الدمام)"
                                               class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white shadow-lg"
                                               autocomplete="off">
                                        
                                        <!-- Search suggestions dropdown -->
                                        <div id="search-suggestions" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-30 max-h-60 overflow-y-auto hidden">
                                            <!-- Suggestions will be populated here -->
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" id="search-btn" class="flex-1 sm:flex-none bg-blue-500 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors shadow-lg text-sm sm:text-base">
                                            <i class="fas fa-search ml-1"></i> <span class="hidden sm:inline">بحث</span>
                                        </button>
                                        <button type="button" id="current-location-btn" class="flex-1 sm:flex-none bg-green-500 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-green-600 transition-colors shadow-lg text-sm sm:text-base">
                                            <i class="fas fa-crosshairs ml-1"></i> <span class="hidden sm:inline">موقعي</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start text-sm text-blue-700">
                                <i class="fas fa-info-circle mt-0.5 ml-2 flex-shrink-0"></i>
                                <div>
                                    <p class="font-medium">كيفية استخدام الخريطة:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1 text-xs sm:text-sm">
                                        <li>اكتب اسم المدينة في مربع البحث وستظهر لك اقتراحات من مدن السعودية</li>
                                        <li>اختر من الاقتراحات أو اضغط Enter للبحث</li>
                                        <li>انقر على الخريطة لتحديد الموقع (داخل السعودية فقط)</li>
                                        <li>اسحب العلامة لتحريك الموقع</li>
                                        <li>ستتم تعبئة بيانات العنوان والمدينة والحي والرمز البريدي تلقائياً</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields for coordinates -->
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    </div>

                    <!-- Location Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">العنوان</label>
                            <input type="text" name="location" value="{{ old('location') }}" placeholder="سيتم تعبئته تلقائياً من الخريطة"
                                   readonly
                                   class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base bg-gray-50 text-gray-600 cursor-not-allowed @error('location') border-red-500 bg-red-50 @enderror">
                            @error('location')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">المدينة</label>
                            <select name="city" readonly disabled class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base bg-gray-50 text-gray-600 cursor-not-allowed @error('city') border-red-500 bg-red-50 @enderror">
                                <option value="">سيتم تعبئته تلقائياً من الخريطة</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->name }}" {{ old('city') == $city->name ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">الحي <span class="text-gray-500 font-normal">(اختياري)</span></label>
                            <input type="text" name="neighborhood" value="{{ old('neighborhood') }}" placeholder="سيتم تعبئته تلقائياً من الخريطة"
                                   readonly
                                   class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base bg-gray-50 text-gray-600 cursor-not-allowed @error('neighborhood') border-red-500 bg-red-50 @enderror">
                            @error('neighborhood')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">الرمز البريدي <span class="text-gray-500 font-normal">(اختياري)</span></label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="سيتم تعبئته تلقائياً من الخريطة"
                                   readonly
                                   class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base bg-gray-50 text-gray-600 cursor-not-allowed @error('postal_code') border-red-500 bg-red-50 @enderror">
                            @error('postal_code')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 Navigation -->
                <div class="flex flex-col sm:flex-row justify-between gap-3 sm:gap-0 mt-6">
                    <button type="button" onclick="prevStep(3)" class="bg-gray-300 text-gray-800 px-4 sm:px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200 text-sm sm:text-base">
                        السابق
                    </button>
                    <button type="button" onclick="nextStep(3)" class="bg-green-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200 text-sm sm:text-base">
                        التالي
                    </button>
                </div>
            </div>

            <!-- Step 4: Final Details and Review -->
            <div id="step-4" class="step-content hidden">
                <!-- Header with Progress Indicator -->
                <div class="text-center mb-6 sm:mb-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full mb-3 sm:mb-4">
                        <i class="fas fa-check-circle text-2xl sm:text-3xl text-green-600"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">مراجعة النهائية</h2>
                    <p class="text-base sm:text-lg text-gray-600">راجع جميع المعلومات وتأكد من صحتها قبل إنشاء العرض</p>
                </div>

                <!-- Success Message -->
                <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center text-green-700">
                        <div class="flex-shrink-0 mb-3 sm:mb-0">
                            <i class="fas fa-check-circle text-xl sm:text-2xl"></i>
                        </div>
                        <div class="mr-0 sm:mr-4 text-center sm:text-right">
                            <h3 class="text-base sm:text-lg font-semibold">أحسنت! لقد أكملت جميع الخطوات</h3>
                            <p class="text-sm mt-1">الآن يمكنك مراجعة المعلومات وإضافة أي تعليمات إضافية قبل إنشاء العرض</p>
                        </div>
                    </div>
                </div>
                


                <!-- Instructions Section -->
                <div class="mb-6 sm:mb-8 bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-clipboard-list text-blue-600 ml-3"></i>
                            التعليمات والملاحظات الإضافية
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">أضف أي تعليمات مهمة للمستأجرين</p>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block mb-3 font-semibold text-gray-700">
                                    <i class="fas fa-info-circle text-blue-500 ml-2"></i>
                                    تعليمات الاستخدام <span class="text-gray-500 font-normal">(اختياري)</span>
                                </label>
                                <textarea name="usage_instructions" rows="4" 
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('usage_instructions') border-red-500 @enderror" 
                                          placeholder="اكتب تعليمات واضحة لاستخدام المنتج...">{{ old('usage_instructions') }}</textarea>
                                @error('usage_instructions')<div class="text-red-600 text-sm mt-2">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="block mb-3 font-semibold text-gray-700">
                                    <i class="fas fa-shield-alt text-orange-500 ml-2"></i>
                                    تعليمات السلامة <span class="text-gray-500 font-normal">(اختياري)</span>
                                </label>
                                <textarea name="safety_instructions" rows="4" 
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none @error('safety_instructions') border-red-500 @enderror" 
                                          placeholder="أكتب تعليمات السلامة المهمة...">{{ old('safety_instructions') }}</textarea>
                                @error('safety_instructions')<div class="text-red-600 text-sm mt-2">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Review Summary -->
                <div class="mb-6 sm:mb-8">
                    <div class="text-center mb-4 sm:mb-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">مراجعة البيانات</h3>
                        <p class="text-sm sm:text-base text-gray-600">تأكد من صحة جميع المعلومات المدخلة</p>
                    </div>
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 sm:p-6 lg:p-8 rounded-2xl border border-gray-200 shadow-lg">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                            <!-- Basic Information -->
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center ml-3">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>
                                    المعلومات الأساسية
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div id="review-name" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">الاسم:</span>
                                        <span id="review-name-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-category" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">التصنيف:</span>
                                        <span id="review-category-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-brand" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">الماركة:</span>
                                        <span id="review-brand-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-model" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">الموديل:</span>
                                        <span id="review-model-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-description" class="py-1">
                                        <span class="text-gray-600 block mb-1">الوصف:</span>
                                        <span id="review-description-value" class="text-gray-800 text-xs leading-relaxed">-</span>
                                    </div>
                                    <div id="review-tags" class="py-1">
                                        <span class="text-gray-600 block mb-1">العلامات:</span>
                                        <span id="review-tags-value" class="text-gray-800 text-xs">-</span>
                                    </div>
                                    <div id="review-condition" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">الحالة:</span>
                                        <span id="review-condition-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-year" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">سنة الصنع:</span>
                                        <span id="review-year-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Information -->
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center ml-3">
                                        <i class="fas fa-calculator text-green-600"></i>
                                    </div>
                                    التسعير والإعدادات
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div id="review-daily" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">السعر اليومي:</span>
                                        <div class="text-right">
                                            <span id="review-daily-value" class="font-medium text-green-600">-</span>
                                            <span class="text-xs text-gray-500 block">شامل الضريبة (السعر + 15%)</span>
                                        </div>
                                    </div>
                                    <div id="review-weekly" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">السعر الأسبوعي:</span>
                                        <div class="text-right">
                                            <span id="review-weekly-value" class="font-medium text-green-600">-</span>
                                            <span class="text-xs text-gray-500 block">شامل الضريبة (السعر + 15%)</span>
                                        </div>
                                    </div>
                                    <div id="review-monthly" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">السعر الشهري:</span>
                                        <div class="text-right">
                                            <span id="review-monthly-value" class="font-medium text-green-600">-</span>
                                            <span class="text-xs text-gray-500 block">شامل الضريبة (السعر + 15%)</span>
                                        </div>
                                    </div>
                                    <div id="review-delivery" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">التوصيل:</span>
                                        <span id="review-delivery-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-deposit" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">الضمان:</span>
                                        <span id="review-deposit-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-rental-settings" class="py-1">
                                        <span class="text-gray-600 block mb-1">إعدادات الإيجار:</span>
                                        <div id="review-rental-settings-value" class="text-gray-800 text-xs space-y-1">-</div>
                                    </div>
                                    <div id="review-min-rental" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">أقل مدة إيجار:</span>
                                        <span id="review-min-rental-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-max-rental" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">أقصى مدة إيجار:</span>
                                        <span id="review-max-rental-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center ml-3">
                                        <i class="fas fa-map-marker-alt text-red-600"></i>
                                    </div>
                                    الموقع
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div id="review-location" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">العنوان:</span>
                                        <span id="review-location-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-city" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">المدينة:</span>
                                        <span id="review-city-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-neighborhood" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">الحي:</span>
                                        <span id="review-neighborhood-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-postal" class="flex justify-between items-center py-1 border-b border-gray-100">
                                        <span class="text-gray-600">الرمز البريدي:</span>
                                        <span id="review-postal-value" class="font-medium text-gray-800">-</span>
                                    </div>
                                    <div id="review-coordinates" class="flex justify-between items-center py-1">
                                        <span class="text-gray-600">الإحداثيات:</span>
                                        <span id="review-coordinates-value" class="font-medium text-gray-800 text-xs">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Images Section -->
                            <div class="bg-white p-4 sm:p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center ml-3">
                                        <i class="fas fa-images text-purple-600"></i>
                                    </div>
                                    الصور
                                </h4>
                                <div class="space-y-4 text-sm">
                                    <div>
                                        <span class="text-gray-600 block mb-3 font-medium">الصور المرفوعة:</span>
                                        <div id="review-images" class="text-gray-800 font-medium mb-3">لم يتم رفع صور</div>
                                        <div id="image-preview-container" class="mt-4 hidden">
                                            <!-- Enhanced image preview will be displayed here -->
                                        </div>
                                        
                                        <style>
                                        #image-preview-container .grid {
                                            display: grid;
                                            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                                            gap: 1rem;
                                        }
                                        
                                        #image-preview-container .image-preview-container {
                                            aspect-ratio: 1;
                                            min-height: 120px;
                                        }
                                        </style>
                                        <div id="existing-images-container" class="mt-4">
                                            @if(isset($listing) && $listing->images)
                                                @include('components.image-preview-grid', [
                                                    'images' => $listing->images,
                                                    'isEdit' => false,
                                                    'showRemoveButtons' => false
                                                ])
                                            @endif
                                        </div>
                                        <div id="review-instructions" class="mt-4 pt-4 border-t border-gray-100">
                                            <span class="text-gray-600 block mb-2 font-medium">تعليمات الإيجار:</span>
                                            <span id="review-instructions-value" class="text-gray-800 text-sm leading-relaxed">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 Navigation -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 sm:gap-0 mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-gray-200">
                    <button type="button" onclick="prevStep(4)" 
                            class="bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-xl hover:bg-gray-200 transition-all duration-200 text-sm sm:text-base font-medium border border-gray-200 hover:border-gray-300">
                        <i class="fas fa-arrow-right ml-2"></i>
                        السابق
                    </button>
                    <div class="flex justify-center">
                        <button type="submit" 
                                class="bg-green-600 text-white px-6 sm:px-8 py-2 sm:py-3 rounded-xl hover:bg-green-700 transition-all duration-200 text-sm sm:text-base font-medium shadow-lg hover:shadow-md">
                            <i class="fas fa-check ml-2"></i>
                            إنشاء العرض
                        </button>
                    </div>
                </div>
            </div>
            <!-- End of Step 4 -->
        </form>
    </div>
</div>

<!-- Image Modal for Review Step -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-16 right-0 text-white text-5xl hover:text-gray-300 transition-colors duration-200 z-10 bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" class="max-w-full max-h-full rounded-2xl shadow-2xl">
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

// Word count function for description
function updateCharCount() {
    const textarea = document.getElementById('description');
    const charCountDiv = document.getElementById('char-count');
    
    if (textarea && charCountDiv) {
        const text = textarea.value;
        const charCount = text.length;
        const maxChars = 1000;
        
        charCountDiv.textContent = `${charCount} / ${maxChars} حرف`;
        
        // Change color based on character count
        if (charCount > maxChars * 0.9) {
            charCountDiv.className = 'text-sm text-red-500';
        } else if (charCount > maxChars * 0.7) {
            charCountDiv.className = 'text-sm text-yellow-500';
        } else {
            charCountDiv.className = 'text-sm text-gray-500';
        }
    }
}

// Step Management
function showStep(step) {
    // Hide all steps
    for (let i = 1; i <= 4; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        if (stepElement) {
            stepElement.classList.add('hidden');
        }
    }
    
    // Show current step
    const currentStepElement = document.getElementById(`step-${step}`);
    if (currentStepElement) {
        currentStepElement.classList.remove('hidden');
    }
    
    // Update step indicators
    updateStepIndicators(step);
    
    // Initialize map when reaching step 3
    if (step === 3 && !map) {
        setTimeout(initMap, 100);
    }
    
    // Update review when reaching step 4
    if (step === 4) {
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
            indicator.className = 'w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold';
            text.className = 'mr-2 text-xs sm:text-sm font-medium text-green-600';
            if (progressBar) progressBar.style.width = '100%';
        } else if (i === step) {
            // Current step
            indicator.className = 'w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold';
            text.className = 'mr-2 text-xs sm:text-sm font-medium text-green-600';
            if (progressBar) progressBar.style.width = '50%';
        } else {
            // Future steps
            indicator.className = 'w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold';
            text.className = 'mr-2 text-xs sm:text-sm font-medium text-gray-600';
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
            const description = document.querySelector('textarea[name="description"]').value.trim();
            
            if (!name) {
                showValidationError('input[name="name"]', 'يرجى إدخال اسم العنصر');
                return false;
            }
            if (!category) {
                showValidationError('select[name="category_id"]', 'يرجى اختيار التصنيف');
                return false;
            }
            if (!description) {
                showValidationError('textarea[name="description"]', 'يرجى إدخال وصف العنصر');
                return false;
            }
            
            // Validate images (mandatory)
            const fileInput = document.getElementById('file-input');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                showValidationError('#file-input', 'يرجى رفع صورة واحدة على الأقل');
                return false;
            }
            break;
            
        case 2:
            // Validate pricing (step 2 is pricing step)
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
            
            // Validate delivery method
            const deliveryMethod = document.querySelector('select[name="delivery_method"]').value;
            if (!deliveryMethod) {
                showValidationError('select[name="delivery_method"]', 'يرجى اختيار طريقة التوصيل');
                return false;
            }
            
            // Validate delivery fee if paid delivery is selected
            if (deliveryMethod === 'paid_delivery') {
                const deliveryFee = document.querySelector('input[name="delivery_fee"]').value;
                if (!deliveryFee || parseFloat(deliveryFee) <= 0) {
                    showValidationError('input[name="delivery_fee"]', 'يرجى إدخال رسوم التوصيل');
                    return false;
                }
            }
            
            // Validate deposit amount if deposit is active
            const depositActive = document.querySelector('input[name="deposit_active"]');
            if (depositActive && depositActive.checked) {
                const depositAmount = document.querySelector('input[name="deposit_amount"]').value;
                if (!depositAmount || parseFloat(depositAmount) <= 0) {
                    showValidationError('input[name="deposit_amount"]', 'يرجى إدخال مبلغ الضمان');
                    return false;
                }
            }
            break;
            
        case 3:
            // Validate location (step 3 is location step)
            const location = document.querySelector('input[name="location"]').value.trim();
            const city = document.querySelector('select[name="city"]').value;
            
            if (!location) {
                showValidationError('input[name="location"]', 'يرجى تحديد الموقع على الخريطة');
                return false;
            }
            if (!city) {
                showValidationError('select[name="city"]', 'يرجى تحديد المدينة');
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
    
    // Special handling for file input
    if (selector === '#file-input') {
        // Add error styling to the drop area
        const dropArea = document.getElementById('drop-area');
        if (dropArea) {
            dropArea.classList.add('border-red-500', 'bg-red-50');
            dropArea.classList.remove('border-gray-300');
        }
        
        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'validation-error text-red-600 text-sm mt-1 flex items-center';
        errorDiv.innerHTML = `
            <svg class="w-4 h-4 ml-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            ${message}
        `;
        
        // Insert error message after the drop area
        const dropAreaParent = dropArea.parentNode;
        dropAreaParent.insertBefore(errorDiv, dropArea.nextSibling);
        
        // Scroll to the error if needed
        dropArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    
    // Regular input handling
    element.classList.add('border-red-500', 'bg-red-50', 'focus:ring-red-500', 'focus:border-red-500');
    element.classList.remove('border-gray-300', 'bg-gray-50', 'focus:ring-green-500', 'focus:border-transparent');
    
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
        input.classList.remove('border-red-500', 'bg-red-50', 'focus:ring-red-500', 'focus:border-red-500');
        input.classList.add('border-gray-300', 'bg-gray-50', 'focus:ring-green-500', 'focus:border-transparent');
    });
    
    // Remove error styling from drop area
    const dropArea = document.getElementById('drop-area');
    if (dropArea) {
        dropArea.classList.remove('border-red-500', 'bg-red-50');
        dropArea.classList.add('border-gray-300');
    }
    
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
    
    // Auto-search functionality with suggestions
    const searchInput = document.getElementById('search-location');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Hide suggestions if input is empty
        if (!searchTerm) {
            hideSearchSuggestions();
            return;
        }
        
        // Show loading in suggestions
        showSearchSuggestions([{ display_name: 'جار البحث...', loading: true }]);
        
        // Debounce search requests
        searchTimeout = setTimeout(() => {
            searchLocationSuggestions(searchTerm);
        }, 300);
    });
    
    // Enter key for search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchTerm = this.value.trim();
            if (searchTerm) {
                searchLocation(searchTerm);
                hideSearchSuggestions();
            }
        }
    });
    
    // Handle clicks outside search suggestions
    document.addEventListener('click', function(e) {
        const suggestions = document.getElementById('search-suggestions');
        const searchInput = document.getElementById('search-location');
        
        if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
            hideSearchSuggestions();
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
                
                // Check if location is in Saudi Arabia
                const country = address.country || '';
                if (country.toLowerCase() !== 'saudi arabia' && country.toLowerCase() !== 'السعودية') {
                    showMapMessage('يرجى اختيار موقع داخل المملكة العربية السعودية', 'error');
                    return;
                }
                
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
                
                // Find the best matching city from our available cities
                const matchedCity = findBestMatchingCity(city);
                
                // Update form fields
                updateAddressFields({
                    location: fullAddress,
                    neighborhood: neighborhood,
                    city: matchedCity,
                    postal_code: postcode
                });
                
                if (matchedCity) {
                    showMapMessage(`تم تحديد المدينة: ${matchedCity}`, 'success');
                } else {
                    showMapMessage('لم يتم العثور على المدينة في قائمة المدن المتاحة', 'warning');
                }
            } else {
                showMapMessage('لم يتمكن من العثور على بيانات العنوان لهذا الموقع', 'warning');
            }
        })
        .catch(error => {
            console.error('Reverse geocoding error:', error);
            showMapMessage('حدث خطأ في تحديث بيانات العنوان', 'error');
        });
}

// Function to find the best matching city from available cities
function findBestMatchingCity(osmCity) {
    if (!osmCity) return null;
    
    const cityField = document.querySelector('select[name="city"]');
    if (!cityField) return null;
    
    const cityOptions = Array.from(cityField.options);
    const searchCity = osmCity.toLowerCase().trim();
    
    // Available Saudi cities with their variations
    const cityVariations = {
        'الرياض': ['رياض', 'riyadh', 'الرِّيَاض'],
        'جدة': ['جده', 'jeddah', 'jiddah'],
        'مكة المكرمة': ['مكة', 'مكه', 'makkah', 'mecca'],
        'المدينة المنورة': ['المدينة', 'مدينة', 'madinah', 'medina'],
        'الدمام': ['dammam'],
        'الخبر': ['khobar', 'al khobar'],
        'الظهران': ['dhahran'],
        'تبوك': ['tabuk'],
        'أبها': ['abha'],
        'جازان': ['jazan', 'jizan'],
        'نجران': ['najran'],
        'الباحة': ['الباحه', 'al baha'],
        'الجوف': ['al jouf'],
        'حائل': ['hail'],
        'القصيم': ['al qassim'],
        'حفر الباطن': ['hafr al batin'],
        'بريدة': ['buraydah'],
        'خميس مشيط': ['khamis mushait'],
        'الطائف': ['taif'],
        'الرس': ['ar rass'],
        'عنيزة': ['unaizah'],
        'بقيق': ['buqayq'],
        'رابغ': ['rabigh'],
        'رنية': ['ranyah'],
        'تربة': ['turbah'],
        'بيشة': ['bishah'],
        'أبو عريش': ['abu arish'],
        'صامطة': ['samtah'],
        'أحد رفيدة': ['ahad rifaydah'],
        'ظهران الجنوب': ['dhahran al janub'],
        'القطيف': ['al qatif'],
        'الأحساء': ['al ahsa'],
        'الجبيل': ['jubail'],
        'ينبع': ['yanbu'],
        'القنفذة': ['al qunfudhah'],
        'الليث': ['al lith']
    };
    
    // First try exact match
    let matchingOption = cityOptions.find(option => 
        option.value.toLowerCase().trim() === searchCity
    );
    
    // If no exact match, try partial match
    if (!matchingOption) {
        matchingOption = cityOptions.find(option => 
            option.value.toLowerCase().includes(searchCity) ||
            searchCity.includes(option.value.toLowerCase())
        );
    }
    
    // If still no match, try fuzzy matching with city variations
    if (!matchingOption) {
        for (const [cityName, variations] of Object.entries(cityVariations)) {
            if (variations.some(variation => 
                searchCity.includes(variation) || 
                variation.includes(searchCity) ||
                searchCity === variation
            )) {
                matchingOption = cityOptions.find(option => option.value === cityName);
                break;
            }
        }
    }
    
    return matchingOption ? matchingOption.value : null;
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
    
    // Update city field (select dropdown) - city is already matched
    const cityField = document.querySelector('select[name="city"]');
    if (cityField && addressData.city) {
        const cityOptions = Array.from(cityField.options);
        const matchingOption = cityOptions.find(option => 
            option.value === addressData.city
        );
        
        if (matchingOption) {
            cityField.value = matchingOption.value;
            // Animate city field update
            cityField.classList.add('bg-green-50', 'border-green-300');
            setTimeout(() => {
                cityField.classList.remove('bg-green-50', 'border-green-300');
            }, 2000);
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
    
    // Enhanced search that works for any location in Saudi Arabia
    const searchQuery = `${query}, Saudi Arabia`;
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&countrycodes=sa&limit=5&accept-language=ar,en`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                // Use the first result (most relevant)
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                
                placeMarker(lat, lng);
                
                // Show success message with location name
                const locationName = result.display_name.split(',')[0]; // Get first part of display name
                showMapMessage(`تم العثور على: ${locationName}`, 'success');
                
                // If we have multiple results, show them in suggestions
                if (data.length > 1) {
                    showSearchSuggestions(data.map(item => ({
                        display_name: item.display_name,
                        name: item.name,
                        type: item.type || 'موقع',
                        lat: item.lat,
                        lon: item.lon
                    })));
                }
            } else {
                // If no results with Saudi Arabia restriction, try broader search
                const broaderUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&accept-language=ar,en`;
                
                fetch(broaderUrl)
                    .then(broaderResponse => broaderResponse.json())
                    .then(broaderData => {
                        if (broaderData && broaderData.length > 0) {
                            // Filter for Saudi Arabia results
                            const saudiResults = broaderData.filter(item => {
                                const displayName = item.display_name.toLowerCase();
                                return displayName.includes('saudi arabia') || 
                                       displayName.includes('السعودية') ||
                                       displayName.includes('saudi');
                            });
                            
                            if (saudiResults.length > 0) {
                                const result = saudiResults[0];
                                const lat = parseFloat(result.lat);
                                const lng = parseFloat(result.lon);
                                
                                placeMarker(lat, lng);
                                
                                const locationName = result.display_name.split(',')[0];
                                showMapMessage(`تم العثور على: ${locationName}`, 'success');
                                
                                // Show all Saudi results as suggestions
                                if (saudiResults.length > 1) {
                                    showSearchSuggestions(saudiResults.map(item => ({
                                        display_name: item.display_name,
                                        name: item.name,
                                        type: item.type || 'موقع',
                                        lat: item.lat,
                                        lon: item.lon
                                    })));
                                }
                            } else {
                                showMapMessage('لم يتم العثور على مواقع في السعودية. جرب البحث في مدن السعودية.', 'warning');
                            }
                        } else {
                            showMapMessage('لم يتم العثور على الموقع. جرب كلمات مختلفة.', 'error');
                        }
                    })
                    .catch(broaderError => {
                        console.error('Broader search error:', broaderError);
                        showMapMessage('حدث خطأ في البحث. تأكد من الاتصال بالإنترنت.', 'error');
                    });
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

// Search location suggestions function - works for any location
function searchLocationSuggestions(query) {
    const searchQuery = query.trim();
    
    if (searchQuery.length < 2) {
        hideSearchSuggestions();
        return;
    }
    
    // Search OpenStreetMap for any location in Saudi Arabia
    const osmUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery + ', Saudi Arabia')}&countrycodes=sa&limit=5&accept-language=ar,en`;
    
    fetch(osmUrl)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const suggestions = data.map(item => ({
                    display_name: item.display_name,
                    name: item.name,
                    type: item.type || 'موقع',
                    lat: item.lat,
                    lon: item.lon
                }));
                showSearchSuggestions(suggestions);
            } else {
                // If no results with Saudi Arabia restriction, try broader search
                const broaderUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=10&accept-language=ar,en`;
                
                fetch(broaderUrl)
                    .then(broaderResponse => broaderResponse.json())
                    .then(broaderData => {
                        if (broaderData && broaderData.length > 0) {
                            // Filter for Saudi Arabia results
                            const saudiResults = broaderData.filter(item => {
                                const displayName = item.display_name.toLowerCase();
                                return displayName.includes('saudi arabia') || 
                                       displayName.includes('السعودية') ||
                                       displayName.includes('saudi');
                            });
                            
                            if (saudiResults.length > 0) {
                                const suggestions = saudiResults.slice(0, 5).map(item => ({
                                    display_name: item.display_name,
                                    name: item.name,
                                    type: item.type || 'موقع',
                                    lat: item.lat,
                                    lon: item.lon
                                }));
                                showSearchSuggestions(suggestions);
                            } else {
                                showSearchSuggestions([{ display_name: 'لم يتم العثور على مواقع في السعودية', no_results: true }]);
                            }
                        } else {
                            showSearchSuggestions([{ display_name: 'لم يتم العثور على مواقع مطابقة', no_results: true }]);
                        }
                    })
                    .catch(broaderError => {
                        console.error('Broader search error:', broaderError);
                        showSearchSuggestions([{ display_name: 'حدث خطأ في البحث', error: true }]);
                    });
            }
        })
        .catch(error => {
            console.error('OSM search error:', error);
            showSearchSuggestions([{ display_name: 'حدث خطأ في البحث', error: true }]);
        });
}

// Sort search results to prioritize Arabic or English based on query
function sortSearchResults(results, isArabicQuery) {
    return results.sort((a, b) => {
        const aHasArabic = /[\u0600-\u06FF]/.test(a.display_name);
        const bHasArabic = /[\u0600-\u06FF]/.test(b.display_name);
        
        if (isArabicQuery) {
            // If searching in Arabic, prioritize results with Arabic text
            if (aHasArabic && !bHasArabic) return -1;
            if (!aHasArabic && bHasArabic) return 1;
        } else {
            // If searching in English, prioritize results with English text
            if (!aHasArabic && bHasArabic) return -1;
            if (aHasArabic && !bHasArabic) return 1;
        }
        
        // If both have same language preference, sort by relevance
        return 0;
    });
}

// Show search suggestions dropdown
function showSearchSuggestions(suggestions) {
    const suggestionsDiv = document.getElementById('search-suggestions');
    const searchInput = document.getElementById('search-location');
    
    if (!suggestionsDiv || !searchInput) return;
    
    // Clear previous suggestions
    suggestionsDiv.innerHTML = '';
    
    suggestions.forEach((suggestion, index) => {
        const suggestionItem = document.createElement('div');
        suggestionItem.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors';
        
        if (suggestion.loading) {
            suggestionItem.innerHTML = `
                <div class="flex items-center text-gray-500">
                    <i class="fas fa-spinner fa-spin ml-2"></i>
                    <span>${suggestion.display_name}</span>
                </div>
            `;
        } else if (suggestion.no_results) {
            suggestionItem.innerHTML = `
                <div class="flex items-center text-gray-500">
                    <i class="fas fa-info-circle ml-2"></i>
                    <span>${suggestion.display_name}</span>
                </div>
            `;
        } else if (suggestion.error) {
            suggestionItem.innerHTML = `
                <div class="flex items-center text-red-500">
                    <i class="fas fa-exclamation-triangle ml-2"></i>
                    <span>${suggestion.display_name}</span>
                </div>
            `;
        } else {
            // Format the display name for better readability
            const displayName = formatLocationName(suggestion.display_name);
            
            const languageIndicator = getLanguageIndicator(suggestion.display_name);
            const languageBadge = languageIndicator ? `<span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded mr-2">${languageIndicator}</span>` : '';
            
            suggestionItem.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-500 ml-2"></i>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">${displayName}</div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">${suggestion.type || 'موقع'}</div>
                            ${languageBadge}
                        </div>
                    </div>
                </div>
            `;
            
            // Add click event to select this suggestion
            suggestionItem.addEventListener('click', function() {
                searchInput.value = suggestion.display_name;
                hideSearchSuggestions();
                
                // For local cities, search for the city coordinates
                if (suggestion.type === 'مدينة') {
                    searchLocation(suggestion.display_name);
                } else if (suggestion.lat && suggestion.lon) {
                    placeMarker(parseFloat(suggestion.lat), parseFloat(suggestion.lon));
                    showMapMessage('تم تحديد الموقع بنجاح!', 'success');
                } else {
                    searchLocation(suggestion.display_name);
                }
            });
        }
        
        suggestionsDiv.appendChild(suggestionItem);
    });
    
    // Show the suggestions dropdown
    suggestionsDiv.classList.remove('hidden');
}

// Hide search suggestions dropdown
function hideSearchSuggestions() {
    const suggestionsDiv = document.getElementById('search-suggestions');
    if (suggestionsDiv) {
        suggestionsDiv.classList.add('hidden');
    }
}

// Format location name for better display
function formatLocationName(displayName) {
    // Split by comma and take the first few parts
    const parts = displayName.split(',').map(part => part.trim());
    
    // For Saudi Arabia locations, prioritize Arabic names and key parts
    if (parts.length > 3) {
        // Take first 3 parts for better readability
        return parts.slice(0, 3).join(', ');
    }
    
    return displayName;
}

// Detect if text contains Arabic characters
function containsArabic(text) {
    return /[\u0600-\u06FF]/.test(text);
}

// Get language indicator for display
function getLanguageIndicator(text) {
    const hasArabic = containsArabic(text);
    const hasEnglish = /[a-zA-Z]/.test(text);
    
    if (hasArabic && hasEnglish) {
        return 'عربي/إنجليزي';
    } else if (hasArabic) {
        return 'عربي';
    } else if (hasEnglish) {
        return 'إنجليزي';
    }
    return '';
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

// Deposit field toggle functionality
function toggleDepositField() {
    const checkbox = document.querySelector('input[name="deposit_active"]');
    const field = document.getElementById('deposit-field');
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

// Delivery fee field toggle functionality
function toggleDeliveryFeeField() {
    const select = document.querySelector('select[name="delivery_method"]');
    const field = document.getElementById('delivery-fee-field');
    const input = field.querySelector('input');
    
    if (select && field && input) {
        if (select.value === 'paid_delivery') {
            field.classList.remove('hidden');
            input.disabled = false;
            input.required = true;
        } else {
            field.classList.add('hidden');
            input.disabled = true;
            input.required = false;
            input.value = '';
        }
    }
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
            // Show commission calculator when field is activated
            const commissionDiv = document.getElementById(`${type}-commission`);
            if (commissionDiv) {
                commissionDiv.classList.remove('hidden');
                calculateCommission(type);
            }
        } else {
            field.style.opacity = '0.5';
            input.disabled = true;
            input.required = false;
            input.value = '';
            // Hide commission calculator when field is deactivated
            const commissionDiv = document.getElementById(`${type}-commission`);
            if (commissionDiv) {
                commissionDiv.classList.add('hidden');
            }
        }
    }
}

// Commission calculator function
function calculateCommission(type) {
    const input = document.querySelector(`input[name="${type === 'daily' ? 'base_price' : type + '_price'}"]`);
    const commissionDiv = document.getElementById(`${type}-commission`);
    
    if (!input || !commissionDiv) return;
    
    const basePrice = parseFloat(input.value) || 0;
    
    // Get commission rate from authenticated lender route
    fetch('/lender/commission-rate')
        .then(response => response.json())
        .then(data => {
            const commissionRate = data.commission_rate / 100; // Convert percentage to decimal
            const vatRate = 0.15; // 15% VAT
            
            const commissionAmount = basePrice * commissionRate;
            const priceAfterCommission = basePrice - commissionAmount; // Lender gets price minus commission
            const vatAmount = basePrice * vatRate; // VAT is calculated on the original price
            const customerPrice = basePrice + vatAmount; // Customer pays original price + VAT
            const yourPrice = priceAfterCommission; // Lender gets price minus commission
            
            // Update commission calculator display
            const commissionAmountElement = document.getElementById(`${type}-commission-amount`);
            const commissionRateElement = document.getElementById(`${type}-commission-rate`);
            const vatAmountElement = document.getElementById(`${type}-vat-amount`);
            const customerPriceElement = document.getElementById(`${type}-customer-price`);
            const yourPriceElement = document.getElementById(`${type}-your-price`);
            
            if (commissionAmountElement) commissionAmountElement.textContent = `${commissionAmount.toFixed(2)} ر.س`;
            if (commissionRateElement) commissionRateElement.textContent = data.commission_rate;
            if (vatAmountElement) vatAmountElement.textContent = `${vatAmount.toFixed(2)} ر.س`;
            if (customerPriceElement) customerPriceElement.textContent = `${customerPrice.toFixed(2)} ر.س`;
            if (yourPriceElement) yourPriceElement.textContent = `${yourPrice.toFixed(2)} ر.س`;
            
            // Update VAT inclusive price display
            const vatInclusiveDisplay = document.getElementById(`${type}-vat-inclusive-display`);
            if (vatInclusiveDisplay) {
                vatInclusiveDisplay.textContent = `${customerPrice.toFixed(2)} ر.س`;
            }
            
            // Show commission calculator if price is entered
            if (basePrice > 0) {
                commissionDiv.classList.remove('hidden');
            } else {
                commissionDiv.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error fetching commission rate:', error);
            // Fallback to default 10% commission rate
            const commissionRate = 0.10;
            const vatRate = 0.15;
            
            const commissionAmount = basePrice * commissionRate;
            const priceAfterCommission = basePrice - commissionAmount;
            const vatAmount = basePrice * vatRate;
            const customerPrice = basePrice + vatAmount;
            const yourPrice = priceAfterCommission;
            
            // Update commission calculator display
            const commissionAmountElement = document.getElementById(`${type}-commission-amount`);
            const commissionRateElement = document.getElementById(`${type}-commission-rate`);
            const vatAmountElement = document.getElementById(`${type}-vat-amount`);
            const customerPriceElement = document.getElementById(`${type}-customer-price`);
            const yourPriceElement = document.getElementById(`${type}-your-price`);
            
            if (commissionAmountElement) commissionAmountElement.textContent = `${commissionAmount.toFixed(2)} ر.س`;
            if (commissionRateElement) commissionRateElement.textContent = '10';
            if (vatAmountElement) vatAmountElement.textContent = `${vatAmount.toFixed(2)} ر.س`;
            if (customerPriceElement) customerPriceElement.textContent = `${customerPrice.toFixed(2)} ر.س`;
            if (yourPriceElement) yourPriceElement.textContent = `${yourPrice.toFixed(2)} ر.س`;
            
            // Update VAT inclusive price display
            const vatInclusiveDisplay = document.getElementById(`${type}-vat-inclusive-display`);
            if (vatInclusiveDisplay) {
                vatInclusiveDisplay.textContent = `${customerPrice.toFixed(2)} ر.س`;
            }
            
            if (basePrice > 0) {
                commissionDiv.classList.remove('hidden');
            } else {
                commissionDiv.classList.add('hidden');
            }
        });
}

// Update review summary
function updateReview() {
    try {
        // Basic info
        const name = document.querySelector('input[name="name"]').value || '-';
        const categorySelect = document.querySelector('select[name="category_id"]');
        const category = categorySelect.options[categorySelect.selectedIndex]?.text || '-';
        const brand = document.querySelector('input[name="brand"]').value || '-';
        const model = document.querySelector('input[name="model"]').value || '-';
        const description = document.querySelector('textarea[name="description"]').value || '-';
        const tags = document.querySelector('input[name="tags"]').value || '-';
        
        // Update basic info
        const reviewNameValue = document.getElementById('review-name-value');
        const reviewCategoryValue = document.getElementById('review-category-value');
        const reviewBrandValue = document.getElementById('review-brand-value');
        const reviewModelValue = document.getElementById('review-model-value');
        const reviewDescriptionValue = document.getElementById('review-description-value');
        const reviewTagsValue = document.getElementById('review-tags-value');
        
        if (reviewNameValue) reviewNameValue.textContent = name;
        if (reviewCategoryValue) reviewCategoryValue.textContent = category;
        if (reviewBrandValue) reviewBrandValue.textContent = brand;
        if (reviewModelValue) reviewModelValue.textContent = model;
        if (reviewDescriptionValue) reviewDescriptionValue.textContent = description;
        if (reviewTagsValue) reviewTagsValue.textContent = tags;
        
        // Pricing
        const dailyActive = document.querySelector('input[name="daily_price_active"]').checked;
        const weeklyActive = document.querySelector('input[name="weekly_price_active"]').checked;
        const monthlyActive = document.querySelector('input[name="monthly_price_active"]').checked;
        
        const dailyPrice = dailyActive ? (document.querySelector('input[name="base_price"]').value || '0') : '-';
        const weeklyPrice = weeklyActive ? (document.querySelector('input[name="weekly_price"]').value || '0') : '-';
        const monthlyPrice = monthlyActive ? (document.querySelector('input[name="monthly_price"]').value || '0') : '-';
        
        const reviewDailyValue = document.getElementById('review-daily-value');
        const reviewWeeklyValue = document.getElementById('review-weekly-value');
        const reviewMonthlyValue = document.getElementById('review-monthly-value');
        
        if (reviewDailyValue) reviewDailyValue.textContent = dailyPrice === '-' ? 'غير مفعل' : `${+dailyPrice + (0.15 * dailyPrice)} ر.س`;
        if (reviewWeeklyValue) reviewWeeklyValue.textContent = weeklyPrice === '-' ? 'غير مفعل' : `${+weeklyPrice + (0.15 * weeklyPrice)} ر.س`;
        if (reviewMonthlyValue) reviewMonthlyValue.textContent = monthlyPrice === '-' ? 'غير مفعل' : `${+monthlyPrice + (0.15 * monthlyPrice)} ر.س`;
        
        // Delivery and Deposit info
        const deliveryMethod = document.querySelector('select[name="delivery_method"]');
        const deliveryMethodText = deliveryMethod.options[deliveryMethod.selectedIndex]?.text || '-';
        const deliveryFee = document.querySelector('input[name="delivery_fee"]').value || '0';
        const depositActive = document.querySelector('input[name="deposit_active"]').checked;
        const depositAmount = document.querySelector('input[name="deposit_amount"]').value || '0';
        
        const reviewDeliveryValue = document.getElementById('review-delivery-value');
        const reviewDepositValue = document.getElementById('review-deposit-value');
        
        if (reviewDeliveryValue) {
            if (deliveryMethodText === 'التوصيل برسوم') {
                reviewDeliveryValue.textContent = `${deliveryMethodText} (${deliveryFee} ر.س)`;
            } else {
                reviewDeliveryValue.textContent = deliveryMethodText;
            }
        }
        if (reviewDepositValue) {
            if (depositActive) {
                reviewDepositValue.textContent = `${depositAmount} ر.س`;
            } else {
                reviewDepositValue.textContent = 'غير مفعل';
            }
        }
        
        // Location
        const location = document.querySelector('input[name="location"]').value || '-';
        const citySelect = document.querySelector('select[name="city"]');
        const city = citySelect.options[citySelect.selectedIndex]?.text || '-';
        const neighborhood = document.querySelector('input[name="neighborhood"]').value || '-';
        const postalCode = document.querySelector('input[name="postal_code"]').value || '-';
        
        const reviewLocationValue = document.getElementById('review-location-value');
        const reviewCityValue = document.getElementById('review-city-value');
        const reviewNeighborhoodValue = document.getElementById('review-neighborhood-value');
        const reviewPostalValue = document.getElementById('review-postal-value');
        
        if (reviewLocationValue) reviewLocationValue.textContent = location;
        if (reviewCityValue) reviewCityValue.textContent = city;
        if (reviewNeighborhoodValue) reviewNeighborhoodValue.textContent = neighborhood;
        if (reviewPostalValue) reviewPostalValue.textContent = postalCode;
        
        // Images
        const fileInput = document.getElementById('file-input');
        const imageCount = fileInput && fileInput.files ? fileInput.files.length : 0;
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const reviewImages = document.getElementById('review-images');
        
        if (reviewImages) {
            if (imageCount > 0) {
                reviewImages.textContent = `${imageCount} ${imageCount === 1 ? 'صورة مرفوعة' : 'صور مرفوعة'}`;
                
                // Display enhanced image previews
                if (imagePreviewContainer) {
                    imagePreviewContainer.innerHTML = '';
                    imagePreviewContainer.classList.remove('hidden');
                    
                    for (let i = 0; i < Math.min(imageCount, 8); i++) {
                        const file = fileInput.files[i];
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'relative group';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-full h-24 sm:h-28 object-cover rounded-lg border border-gray-200 shadow-sm cursor-pointer transition-transform hover:scale-105';
                            img.alt = `صورة ${i + 1}`;
                            img.onclick = function() { openModal(e.target.result); };
                            
                            // Add image number badge
                            const badge = document.createElement('div');
                            badge.className = 'absolute top-1 right-1 bg-blue-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center';
                            badge.textContent = i + 1;
                            
                            imgContainer.appendChild(img);
                            imgContainer.appendChild(badge);
                            imagePreviewContainer.appendChild(imgContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            } else {
                reviewImages.textContent = 'لم يتم رفع صور';
                if (imagePreviewContainer) {
                    imagePreviewContainer.classList.add('hidden');
                }
            }
        }
        
        // Instructions
        const instructions = document.querySelector('textarea[name="instructions"]').value || '-';
        const reviewInstructionsValue = document.getElementById('review-instructions-value');
        if (reviewInstructionsValue) reviewInstructionsValue.textContent = instructions;
        
    } catch (error) {
        console.error('Error updating review:', error);
    }
}

// Image modal functions for review step
function openModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    
    if (modal && modalImg) {
        modal.style.display = 'flex';
        modalImg.src = src;
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });
        }
});

    // Track form submission state to prevent beforeunload warning
    let isSubmitting = false;
    
    // Function to mark form as submitting
    function markFormSubmitting() {
        isSubmitting = true;
    }
    
    // Reset form submission state
    function resetFormSubmission() {
        isSubmitting = false;
        // Re-enable submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'إنشاء العرض';
        }
    }
    
    // Function to handle server-side errors and navigate to earliest step with error
    function handleServerErrors() {
        const errorElements = document.querySelectorAll('.text-red-600');
        if (errorElements.length === 0) return;
        
        let earliestStep = 4; // Start with the last step
        
        // Check which steps have errors
        errorElements.forEach(error => {
            const input = error.previousElementSibling;
            if (!input) return;
            
            // Find which step this input belongs to
            let stepElement = input.closest('.step-content');
            if (stepElement) {
                const stepId = stepElement.id;
                const stepNumber = parseInt(stepId.split('-')[1]);
                if (stepNumber < earliestStep) {
                    earliestStep = stepNumber;
                }
            }
        });
        
        // Navigate to the earliest step with error
        if (earliestStep < 4) {
            showStep(earliestStep);
        }
        
        // Highlight all error fields with red background
        errorElements.forEach(error => {
            const input = error.previousElementSibling;
            if (input) {
                input.classList.add('border-red-500', 'bg-red-50');
                input.classList.remove('border-gray-300', 'bg-gray-50');
            }
        });
    }

    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
    
    // Handle server-side errors first
    handleServerErrors();
    
    // Initialize first step (only if no errors)
    if (document.querySelectorAll('.text-red-600').length === 0) {
        showStep(1);
    }
    
    // Initialize character count
    updateCharCount();
    
    // Initialize review data update
    initializeReviewDataUpdate();
    
    // Function to update review data
    function initializeReviewDataUpdate() {
        // Update basic information
        const nameInput = document.getElementById('name');
        const categorySelect = document.getElementById('category_id');
        const brandInput = document.getElementById('brand');
        const modelInput = document.getElementById('model');
        const descriptionInput = document.getElementById('description');
        const tagsInput = document.getElementById('tags');
        const conditionInput = document.getElementById('condition');
        const yearInput = document.getElementById('year');
        
        if (nameInput) nameInput.addEventListener('input', updateReviewData);
        if (categorySelect) categorySelect.addEventListener('change', updateReviewData);
        if (brandInput) brandInput.addEventListener('input', updateReviewData);
        if (modelInput) modelInput.addEventListener('input', updateReviewData);
        if (descriptionInput) descriptionInput.addEventListener('input', updateReviewData);
        if (tagsInput) tagsInput.addEventListener('input', updateReviewData);
        if (conditionInput) conditionInput.addEventListener('change', updateReviewData);
        if (yearInput) yearInput.addEventListener('input', updateReviewData);
        
        // Update pricing information
        const dailyPriceInput = document.getElementById('daily_price');
        const weeklyPriceInput = document.getElementById('weekly_price');
        const monthlyPriceInput = document.getElementById('monthly_price');
        const deliveryInput = document.getElementById('delivery_option');
        const depositInput = document.getElementById('deposit_amount');
        const minRentalInput = document.getElementById('min_rental_days');
        const maxRentalInput = document.getElementById('max_rental_days');
        
        if (dailyPriceInput) dailyPriceInput.addEventListener('input', updateReviewData);
        if (weeklyPriceInput) weeklyPriceInput.addEventListener('input', updateReviewData);
        if (monthlyPriceInput) monthlyPriceInput.addEventListener('input', updateReviewData);
        if (deliveryInput) deliveryInput.addEventListener('change', updateReviewData);
        if (depositInput) depositInput.addEventListener('input', updateReviewData);
        if (minRentalInput) minRentalInput.addEventListener('input', updateReviewData);
        if (maxRentalInput) maxRentalInput.addEventListener('input', updateReviewData);
        
        // Update location information
        const addressInput = document.getElementById('address');
        const citySelect = document.getElementById('city_id');
        const neighborhoodInput = document.getElementById('neighborhood');
        const postalInput = document.getElementById('postal_code');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (addressInput) addressInput.addEventListener('input', updateReviewData);
        if (citySelect) citySelect.addEventListener('change', updateReviewData);
        if (neighborhoodInput) neighborhoodInput.addEventListener('input', updateReviewData);
        if (postalInput) postalInput.addEventListener('input', updateReviewData);
        if (latInput) latInput.addEventListener('input', updateReviewData);
        if (lngInput) lngInput.addEventListener('input', updateReviewData);
        
        // Update instructions
        const usageInstructionsInput = document.getElementById('usage_instructions');
        const safetyInstructionsInput = document.getElementById('safety_instructions');
        
        if (usageInstructionsInput) usageInstructionsInput.addEventListener('input', updateReviewData);
        if (safetyInstructionsInput) safetyInstructionsInput.addEventListener('input', updateReviewData);
        
        // Update images when they change
        const fileInput = document.getElementById('file-input');
        if (fileInput) {
            fileInput.addEventListener('change', updateReviewImages);
        }
    }
    
    function updateReviewData() {
        // Update basic information
        updateReviewField('review-name-value', 'name');
        updateReviewField('review-category-value', 'category_id', true);
        updateReviewField('review-brand-value', 'brand');
        updateReviewField('review-model-value', 'model');
        updateReviewField('review-description-value', 'description');
        updateReviewField('review-tags-value', 'tags');
        updateReviewField('review-condition-value', 'condition', true);
        updateReviewField('review-year-value', 'year');
        
        // Update pricing information with VAT calculation
        updateReviewFieldWithVAT('review-daily-value', 'base_price', ' ريال');
        updateReviewFieldWithVAT('review-weekly-value', 'weekly_price', ' ريال');
        updateReviewFieldWithVAT('review-monthly-value', 'monthly_price', ' ريال');
        updateReviewField('review-delivery-value', 'delivery_option', true);
        updateReviewField('review-deposit-value', 'deposit_amount', false, ' ريال');
        updateReviewField('review-min-rental-value', 'min_rental_days', false, ' يوم');
        updateReviewField('review-max-rental-value', 'max_rental_days', false, ' يوم');
        
        // Update location information
        updateReviewField('review-location-value', 'address');
        updateReviewField('review-city-value', 'city_id', true);
        updateReviewField('review-neighborhood-value', 'neighborhood');
        updateReviewField('review-postal-value', 'postal_code');
        
        // Update coordinates
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        if (latInput && lngInput && latInput.value && lngInput.value) {
            document.getElementById('review-coordinates-value').textContent = `${latInput.value}, ${lngInput.value}`;
        } else {
            document.getElementById('review-coordinates-value').textContent = '-';
        }
        
        // Update instructions
        const usageInstructionsInput = document.getElementById('usage_instructions');
        const safetyInstructionsInput = document.getElementById('safety_instructions');
        let instructionsText = '';
        
        if (usageInstructionsInput && usageInstructionsInput.value) {
            instructionsText += `الاستخدام: ${usageInstructionsInput.value}`;
        }
        if (safetyInstructionsInput && safetyInstructionsInput.value) {
            if (instructionsText) instructionsText += '\n';
            instructionsText += `السلامة: ${safetyInstructionsInput.value}`;
        }
        
        document.getElementById('review-instructions-value').textContent = instructionsText || '-';
    }
    
    function updateReviewField(reviewElementId, inputElementId, isSelect = false, suffix = '') {
        const reviewElement = document.getElementById(reviewElementId);
        const inputElement = document.getElementById(inputElementId);
        
        if (reviewElement && inputElement) {
            let value = inputElement.value;
            
            if (isSelect && inputElement.selectedOptions.length > 0) {
                value = inputElement.selectedOptions[0].text;
            }
            
            if (value) {
                reviewElement.textContent = value + suffix;
            } else {
                reviewElement.textContent = '-';
            }
        }
    }
    
    function updateReviewFieldWithVAT(reviewElementId, inputElementId, suffix = '') {
        const reviewElement = document.getElementById(reviewElementId);
        const inputElement = document.getElementById(inputElementId);
        
        if (reviewElement && inputElement) {
            let value = parseFloat(inputElement.value) || 0;
            
            if (value > 0) {
                // Calculate VAT (15%)
                const vatAmount = value * 0.15;
                const totalWithVAT = value + vatAmount;
                
                // Display the tax-inclusive price
                reviewElement.textContent = totalWithVAT.toFixed(2) + suffix;
            } else {
                reviewElement.textContent = '-';
            }
        }
    }
    
    function updateReviewImages() {
        const fileInput = document.getElementById('file-input');
        const reviewImages = document.getElementById('review-images');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        
        if (fileInput && reviewImages && imagePreviewContainer) {
            const files = Array.from(fileInput.files);
            
            if (files.length > 0) {
                reviewImages.textContent = `تم رفع ${files.length} ${files.length === 1 ? 'صورة' : 'صور'}`;
                imagePreviewContainer.classList.remove('hidden');
                
                // Clear existing previews
                imagePreviewContainer.innerHTML = '';
                
                // Create grid container
                const gridContainer = document.createElement('div');
                gridContainer.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4';
                
                // Create image previews for review
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'relative group image-preview-container';
                        
                        const imgDiv = document.createElement('div');
                        imgDiv.className = 'aspect-square rounded-xl overflow-hidden border-2 border-gray-200 hover:border-green-400 transition-all duration-300 shadow-sm hover:shadow-md bg-gray-50';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-full w-full object-contain cursor-pointer transition-transform hover:scale-110 duration-300 p-1';
                        img.onclick = function() { openImageModal(e.target.result); };
                        img.title = 'اضغط لعرض الصورة';
                        
                        imgDiv.appendChild(img);
                        imgContainer.appendChild(imgDiv);
                        gridContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
                
                imagePreviewContainer.appendChild(gridContainer);
            } else {
                reviewImages.textContent = 'لم يتم رفع صور';
                imagePreviewContainer.classList.add('hidden');
            }
        }
    }
    
    // Initialize price fields
    togglePriceField('daily');
    togglePriceField('weekly');
    togglePriceField('monthly');
    
    // Initialize commission calculators for any existing values
    calculateCommission('daily');
    calculateCommission('weekly');
    calculateCommission('monthly');
    
    // Initialize deposit and delivery fields
    toggleDepositField();
    toggleDeliveryFeeField();
    
    // Form validation before submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if this is actually a form submission (not navigation)
            const submitter = e.submitter;
            
            // Allow navigation actions to proceed without validation
            if (!submitter || submitter.type !== 'submit' || submitter.classList.contains('nav-link')) {
                return; // Allow navigation actions to proceed
            }
            
            // Only validate if it's the actual "إنشاء العرض" button
            if (submitter.textContent.trim() !== 'إنشاء العرض') {
                return; // Allow other actions to proceed
            }
            
            // Set submitting flag to prevent beforeunload warning
            markFormSubmitting();
            
            // Clear any previous validation errors
            clearValidationErrors();
            
            // Final validation
            if (!validateStep(1)) {
                e.preventDefault();
                showStep(1); // Go back to step with error
                resetFormSubmission(); // Reset form state
                return false;
            }
            
            if (!validateStep(2)) {
                e.preventDefault();
                showStep(2); // Go back to step with error
                resetFormSubmission(); // Reset form state
                return false;
            }
            
            if (!validateStep(3)) {
                e.preventDefault();
                showStep(3); // Go back to step with error
                resetFormSubmission(); // Reset form state
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
    
    // Disable form for lenders with incomplete verification
    @if(Auth::guard('lender')->user()->verification_status !== 'verified')
        // Disable all form inputs, selects, and textareas
        const formElements = document.querySelectorAll('input, select, textarea, button[type="submit"]');
        formElements.forEach(element => {
            if (element.type !== 'button' && !element.classList.contains('nav-link')) {
                element.disabled = true;
                element.style.opacity = '0.5';
                element.style.cursor = 'not-allowed';
            }
        });
        
        // Add overlay to prevent interaction
        const formContainer = document.querySelector('.bg-white.rounded-lg.shadow');
        if (formContainer) {
            formContainer.style.position = 'relative';
            const overlay = document.createElement('div');
            overlay.style.position = 'absolute';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.right = '0';
            overlay.style.bottom = '0';
            overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
            overlay.style.zIndex = '10';
            overlay.style.cursor = 'not-allowed';
            formContainer.appendChild(overlay);
        }
        
        // Show tooltip on form interaction attempt
        document.addEventListener('click', function(e) {
            if (e.target.closest('.bg-white.rounded-lg.shadow')) {
                e.preventDefault();
                e.stopPropagation();
                alert('يجب إكمال بيانات ملفك الشخصي أولاً لتتمكن من إضافة المنتجات');
                return false;
            }
        });
    @endif
});
</script>
@endsection



