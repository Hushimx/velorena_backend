@extends('lender.layouts.app')

@section('title', 'تعديل العرض')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 bg-white rounded-xl shadow-lg p-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('lender.listings.index') }}" class="text-gray-600 hover:text-gray-900 transition duration-150 transform hover:scale-110 bg-gray-100 hover:bg-gray-200 p-2 rounded-lg">
                        <i class="fas fa-arrow-right text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">تعديل العرض: {{ $listing->name }}</h1>
                        <p class="text-sm text-gray-500">قم بتعديل معلومات العرض</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <a href="{{ route('lender.listings.show', $listing) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-150 transform hover:scale-105 shadow-md">
                        <i class="fas fa-eye ml-2"></i>
                        عرض
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center text-green-700">
                    <i class="fas fa-check-circle ml-2"></i>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
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

        <form method="POST" action="{{ route('lender.listings.update', $listing) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 ml-2"></i>
                    المعلومات الأساسية
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">اسم العنصر *</label>
                        <input type="text" name="name" value="{{ old('name', $listing->name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">التصنيف *</label>
                        <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الماركة <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="brand" value="{{ old('brand', $listing->brand) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('brand') border-red-500 @enderror">
                        @error('brand')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الموديل <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="model" value="{{ old('model', $listing->model) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('model') border-red-500 @enderror">
                        @error('model')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">الوصف *</label>
                        <textarea name="description" id="description" rows="4" maxlength="1000" required
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="اكتب وصفاً مفصلاً عن العنصر (حد أقصى 1000 حرف)"
                                  oninput="updateCharCount()">{{ old('description', $listing->description) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <div id="char-count" class="text-sm text-gray-500">0 / 1000 حرف</div>
                            <div class="text-xs text-gray-400">يُفضل كتابة وصف واضح ومفصل</div>
                        </div>
                        @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">العلامات (Tags) <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="tags" value="{{ old('tags', $listing->tags) }}" placeholder="مثال: إلكترونيات، ترفيه، منزل"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tags') border-red-500 @enderror">
                        @error('tags')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-images text-purple-500 ml-2"></i>
                    الصور
                </h2>
                
                @include('components.image-upload', [
                    'isEdit' => true, 
                    'existingImages' => $listing->images ?? []
                ])
            </div>

            <!-- Pricing Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-calculator text-green-500 ml-2"></i>
                    التسعير والإعدادات
                </h2>
                
                <!-- Price Activation Toggles -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="daily_price_active" value="1" {{ old('daily_price_active', $listing->daily_price_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('daily')">
                        <label class="mr-2 font-semibold text-gray-700">تفعيل السعر اليومي</label>
                    </div>
                    
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="weekly_price_active" value="1" {{ old('weekly_price_active', $listing->weekly_price_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('weekly')">
                        <label class="mr-2 font-semibold text-gray-700">تفعيل السعر الأسبوعي</label>
                    </div>
                    
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="monthly_price_active" value="1" {{ old('monthly_price_active', $listing->monthly_price_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="togglePriceField('monthly')">
                        <label class="mr-2 font-semibold text-gray-700">تفعيل السعر الشهري</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div id="daily-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر اليومي (قبل الضريبة)</label>
                        <input type="number" name="base_price" value="{{ old('base_price', $listing->base_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('base_price') border-red-500 @enderror"
                               onchange="calculateCommission('daily')" onkeyup="calculateCommission('daily')">
                        @error('base_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        
                        <!-- VAT Inclusive Price Display -->
                        <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                            <div class="text-xs text-green-700">
                                <div class="flex justify-between items-center">
                                    <span>السعر شامل الضريبة:</span>
                                    <span id="daily-vat-inclusive-display" class="font-medium">0 ر.س</span>
                                </div>
                            </div>
                        </div>
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
                                     <div class="flex justify-between">
                                         <span class="text-gray-600">عمولة المنصة (<span id="daily-commission-rate">0</span>%):</span>
                                         <span id="daily-commission-amount" class="font-medium text-gray-800">0 ر.س</span>
                                     </div>
                                     <div class="flex justify-between font-semibold pt-2 border-t border-blue-200">
                                         <span class="text-blue-800">السعر شامل الضريبة:</span>
                                         <span id="daily-customer-price" class="text-blue-600">0 ر.س</span>
                                     </div>
                                     <div class="flex justify-between font-semibold pt-1">
                                         <span class="text-green-700">صافي الربح :</span>
                                         <span id="daily-your-price" class="text-green-600">0 ر.س</span>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="weekly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر الأسبوعي (قبل الضريبة) <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="number" name="weekly_price" value="{{ old('weekly_price', $listing->weekly_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('weekly_price') border-red-500 @enderror"
                               onchange="calculateCommission('weekly')" onkeyup="calculateCommission('weekly')">
                        @error('weekly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        
                        <!-- VAT Inclusive Price Display -->
                        <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                            <div class="text-xs text-green-700">
                                <div class="flex justify-between items-center">
                                    <span>السعر شامل الضريبة:</span>
                                    <span id="weekly-vat-inclusive-display" class="font-medium">0 ر.س</span>
                                </div>
                            </div>
                        </div>
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
                                     <div class="flex justify-between">
                                         <span class="text-gray-600">عمولة المنصة (<span id="weekly-commission-rate">0</span>%):</span>
                                         <span id="weekly-commission-amount" class="font-medium text-gray-800">0 ر.س</span>
                                     </div>
                                     <div class="flex justify-between font-semibold pt-2 border-t border-blue-200">
                                         <span class="text-blue-800">السعر شامل الضريبة:</span>
                                         <span id="weekly-customer-price" class="text-blue-600">0 ر.س</span>
                                     </div>
                                     <div class="flex justify-between font-semibold pt-1">
                                         <span class="text-green-700">صافي ربح المؤجر:</span>
                                         <span id="weekly-your-price" class="text-green-600">0 ر.س</span>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="monthly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر الشهري (قبل الضريبة) <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="number" name="monthly_price" value="{{ old('monthly_price', $listing->monthly_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('monthly_price') border-red-500 @enderror"
                               onchange="calculateCommission('monthly')" onkeyup="calculateCommission('monthly')">
                        @error('monthly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        
                        <!-- VAT Inclusive Price Display -->
                        <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                            <div class="text-xs text-green-700">
                                <div class="flex justify-between items-center">
                                    <span>السعر شامل الضريبة:</span>
                                    <span id="monthly-vat-inclusive-display" class="font-medium">0 ر.س</span>
                                </div>
                            </div>
                        </div>
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
                                     <div class="flex justify-between">
                                         <span class="text-gray-600">عمولة المنصة (<span id="monthly-commission-rate">0</span>%):</span>
                                         <span id="monthly-commission-amount" class="font-medium text-gray-800">0 ر.س</span>
                                     </div>
                                     <div class="flex justify-between font-semibold pt-2 border-t border-blue-200">
                                         <span class="text-blue-800">السعر شامل الضريبة:</span>
                                         <span id="monthly-customer-price" class="text-blue-600">0 ر.س</span>
                                     </div>
                                     <div class="flex justify-between font-semibold pt-1">
                                         <span class="text-green-700">صافي ربح المؤجر:</span>
                                         <span id="monthly-your-price" class="text-green-600">0 ر.س</span>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="deposit_active" value="1" {{ old('deposit_active', $listing->deposit_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 ml-2" onchange="toggleDepositField()">
                            <label class="font-semibold text-gray-700">تفعيل مبلغ الضمان</label>
                        </div>
                        <div id="deposit-field">
                            <input type="number" name="deposit_amount" value="{{ old('deposit_amount', $listing->deposit_amount) }}" step="0.01" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('deposit_amount') border-red-500 @enderror">
                            @error('deposit_amount')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">طريقة التوصيل</label>
                        <select name="delivery_method" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('delivery_method') border-red-500 @enderror" onchange="toggleDeliveryFeeField()">
                            <option value="">اختر طريقة التوصيل</option>
                            <option value="pickup" {{ old('delivery_method', $listing->delivery_method) == 'pickup' ? 'selected' : '' }}>الاستلام من موقع المؤجر</option>
                            <option value="paid_delivery" {{ old('delivery_method', $listing->delivery_method) == 'paid_delivery' ? 'selected' : '' }}>التوصيل برسوم</option>
                            <option value="free_delivery" {{ old('delivery_method', $listing->delivery_method) == 'free_delivery' ? 'selected' : '' }}>توصيل مجاني</option>
                        </select>
                        @error('delivery_method')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        
                        <div id="delivery-fee-field" class="mt-2 hidden">
                            <label class="block mb-2 font-semibold text-gray-700">رسوم التوصيل</label>
                            <input type="number" name="delivery_fee" value="{{ old('delivery_fee', $listing->delivery_fee ?? 0) }}" step="0.01" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('delivery_fee') border-red-500 @enderror">
                            @error('delivery_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-500 ml-2"></i>
                    الموقع
                </h2>
                
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
                    
                    <!-- Hidden fields for coordinates -->
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $listing->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $listing->longitude) }}">
                </div>

                <!-- Location Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">العنوان</label>
                        <input type="text" name="location" value="{{ old('location', $listing->location) }}" placeholder="أدخل العنوان"
                               class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent" disabled>
                        @error('location')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">المدينة</label>
                        <select name="city" class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent" disabled>
                            <option value="">اختر المدينة</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->name }}" {{ old('city', $listing->city) == $city->name ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">الحي <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="neighborhood" value="{{ old('neighborhood', $listing->neighborhood) }}" placeholder="أدخل اسم الحي"
                               class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent" disabled>
                        @error('neighborhood')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700 text-sm sm:text-base">الرمز البريدي <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $listing->postal_code) }}" placeholder="أدخل الرمز البريدي"
                               class="w-full border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-2 focus:ring-green-500 focus:border-transparent" disabled>
                        @error('postal_code')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Rental Settings Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-clock text-orange-500 ml-2"></i>
                    إعدادات الإيجار
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الحد الأدنى للأيام</label>
                        <input type="number" name="minimum_rental_days" value="{{ old('minimum_rental_days', $listing->minimum_rental_days ?? 1) }}" min="1"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('minimum_rental_days') border-red-500 @enderror">
                        @error('minimum_rental_days')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الحد الأقصى للأيام</label>
                        <input type="number" name="maximum_rental_days" value="{{ old('maximum_rental_days', $listing->maximum_rental_days) }}" min="1"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('maximum_rental_days') border-red-500 @enderror">
                        @error('maximum_rental_days')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الاستلام من</label>
                        <input type="time" name="pickup_time_start" value="{{ old('pickup_time_start', $listing->pickup_time_start ?? '09:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('pickup_time_start') border-red-500 @enderror">
                        @error('pickup_time_start')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الاستلام إلى</label>
                        <input type="time" name="pickup_time_end" value="{{ old('pickup_time_end', $listing->pickup_time_end ?? '18:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('pickup_time_end') border-red-500 @enderror">
                        @error('pickup_time_end')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الإرجاع من</label>
                        <input type="time" name="return_time_start" value="{{ old('return_time_start', $listing->return_time_start ?? '09:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('return_time_start') border-red-500 @enderror">
                        @error('return_time_start')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الإرجاع إلى</label>
                        <input type="time" name="return_time_end" value="{{ old('return_time_end', $listing->return_time_end ?? '18:00') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('return_time_end') border-red-500 @enderror">
                        @error('return_time_end')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Instructions Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-clipboard-list text-indigo-500 ml-2"></i>
                    التعليمات والملاحظات
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">تعليمات الاستخدام <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <textarea name="usage_instructions" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('usage_instructions') border-red-500 @enderror">{{ old('usage_instructions', $listing->usage_instructions) }}</textarea>
                        @error('usage_instructions')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">تعليمات السلامة <span class="text-gray-500 font-normal">(اختياري)</span></label>
                        <textarea name="safety_instructions" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('safety_instructions') border-red-500 @enderror">{{ old('safety_instructions', $listing->safety_instructions) }}</textarea>
                        @error('safety_instructions')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('lender.listings.show', $listing) }}" 
                       class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200 text-center">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Image Modal -->
<div id="imgModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeModal()" class="absolute -top-12 right-0 text-white text-4xl hover:text-gray-300 transition-colors duration-200">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImg" src="" class="max-w-full max-h-full rounded-lg shadow-2xl">
    </div>
</div>

<!-- Enhanced styling for better appearance -->
<style>
/* Enhanced styling for better appearance */
.bg-gradient-to-br {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Better form styling */
input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Enhanced button styling */
button:hover, .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

/* Card hover effects removed */
</style>

<script>
// Global variables
let map;
let marker;

// Character count function
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



// Image modal functions (enhanced)
function openModal(src) {
    console.log('Opening modal with src:', src); // Debug log
    const modal = document.getElementById('imgModal');
    const modalImg = document.getElementById('modalImg');
    
    if (modal && modalImg) {
        modal.classList.remove('hidden');
        modalImg.src = src;
        console.log('Modal opened successfully'); // Debug log
    } else {
        console.error('Modal elements not found'); // Debug log
    }
}

function closeModal() {
    console.log('Closing modal'); // Debug log
    const modal = document.getElementById('imgModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Price field toggle functionality
function togglePriceField(type) {
    const checkbox = document.querySelector(`input[name="${type}_price_active"]`);
    const field = document.getElementById(`${type}-price-field`);
    const input = field ? field.querySelector('input') : null;
    
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

// Deposit field toggle functionality
function toggleDepositField() {
    const checkbox = document.querySelector('input[name="deposit_active"]');
    const field = document.getElementById('deposit-field');
    const input = field ? field.querySelector('input') : null;
    
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
    const input = field ? field.querySelector('input') : null;
    
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

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize character count
    updateCharCount();
    
    // Initialize price fields based on existing data
    togglePriceField('daily');
    togglePriceField('weekly');
    togglePriceField('monthly');
    
    // Initialize commission calculators for any existing values
    setTimeout(() => {
        calculateCommission('daily');
        calculateCommission('weekly');
        calculateCommission('monthly');
    }, 100);
    
    // Initialize deposit and delivery fields
    toggleDepositField();
    toggleDeliveryFeeField();
    
    // Initialize map
    initMap();
    
    // Initialize modal functionality
    initModal();
});

// Initialize modal functionality
function initModal() {
    const modal = document.getElementById('imgModal');
    
    if (modal) {
        // Close modal when clicking outside the image
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        console.log('Modal functionality initialized'); // Debug log
    }
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
    
    // Initialize with existing coordinates if available
    const existingLat = document.getElementById('latitude').value;
    const existingLng = document.getElementById('longitude').value;
    
    if (existingLat && existingLng) {
        placeMarker(parseFloat(existingLat), parseFloat(existingLng), true);
    }
    
    // Search functionality
    document.getElementById('search-btn').addEventListener('click', function() {
        const searchTerm = document.getElementById('search-location').value.trim();
        if (searchTerm) {
            searchLocation(searchTerm);
        }
    });
    
    // Current location functionality
    document.getElementById('current-location-btn').addEventListener('click', function() {
        getCurrentLocation();
    });
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

function findBestMatchingCity(searchCity) {
    if (!searchCity) return null;
    
    const citySelect = document.querySelector('select[name="city"]');
    if (!citySelect) return null;
    
    const options = Array.from(citySelect.options);
    const searchLower = searchCity.toLowerCase();
    
    // First try exact match
    for (const option of options) {
        if (option.value && option.text.toLowerCase() === searchLower) {
            return option.value;
        }
    }
    
    // Then try partial match
    for (const option of options) {
        if (option.value && option.text.toLowerCase().includes(searchLower)) {
            return option.value;
        }
    }
    
    // Then try reverse partial match
    for (const option of options) {
        if (option.value && searchLower.includes(option.text.toLowerCase())) {
            return option.value;
        }
    }
    
    return null;
}

function showMapMessage(message, type = 'info') {
    // Remove existing message
    const existingMessage = document.getElementById('map-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.id = 'map-message';
    messageDiv.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg text-white text-sm font-medium transition-all duration-300 transform translate-x-full`;
    
    // Set color based on type
    switch (type) {
        case 'success':
            messageDiv.classList.add('bg-green-500');
            break;
        case 'error':
            messageDiv.classList.add('bg-red-500');
            break;
        case 'warning':
            messageDiv.classList.add('bg-yellow-500');
            break;
        default:
            messageDiv.classList.add('bg-blue-500');
    }
    
    messageDiv.textContent = message;
    document.body.appendChild(messageDiv);
    
    // Animate in
    setTimeout(() => {
        messageDiv.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        messageDiv.classList.add('translate-x-full');
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 300);
    }, 3000);
}

function searchLocation(query) {
    // Show loading state
    const searchBtn = document.getElementById('search-btn');
    const originalText = searchBtn.innerHTML;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جار البحث...';
    searchBtn.disabled = true;
    
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
                
            } else {
                // If no results found in Saudi Arabia, try global search
                const globalUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=10&accept-language=ar,en`;
                
                fetch(globalUrl)
                    .then(response => response.json())
                    .then(globalData => {
                        if (globalData && globalData.length > 0) {
                            // Filter for Saudi Arabia results
                            const saudiResults = globalData.filter(result => {
                                const displayName = result.display_name.toLowerCase();
                                return displayName.includes('saudi arabia') || 
                                       displayName.includes('السعودية') ||
                                       displayName.includes('riyadh') ||
                                       displayName.includes('jeddah') ||
                                       displayName.includes('dammam') ||
                                       displayName.includes('الرياض') ||
                                       displayName.includes('جدة') ||
                                       displayName.includes('الدمام');
                            });
                            
                            if (saudiResults.length > 0) {
                                const result = saudiResults[0];
                                const lat = parseFloat(result.lat);
                                const lng = parseFloat(result.lon);
                                
                                placeMarker(lat, lng);
                                
                                const locationName = result.display_name.split(',')[0];
                                showMapMessage(`تم العثور على: ${locationName}`, 'success');
                                
                            } else {
                                showMapMessage('لم يتم العثور على الموقع المطلوب في المملكة العربية السعودية', 'error');
                            }
                        } else {
                            showMapMessage('لم يتم العثور على الموقع المطلوب', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Global search error:', error);
                        showMapMessage('حدث خطأ في البحث', 'error');
                    });
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            showMapMessage('حدث خطأ في البحث', 'error');
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
                        message = 'تم رفض طلب الوصول للموقع. يرجى السماح بالوصول للموقع.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'معلومات الموقع غير متاحة.';
                        break;
                    case error.TIMEOUT:
                        message = 'انتهت مهلة طلب الموقع.';
                        break;
                }
                showMapMessage(message, 'error');
            }
        );
    } else {
        showMapMessage('المتصفح لا يدعم تحديد الموقع.', 'error');
    }
    
    // Restore button state after 3 seconds
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 3000);
}
</script>
@endsection
