@extends('lender.layouts.app')

@section('title', 'تعديل العنصر')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('lender.listings.show', $listing) }}" class="text-green-600 hover:underline mr-2">تفاصيل العرض</a>
        <span class="mx-2">/</span>
        <span class="text-gray-500">تعديل العرض</span>
	</div>

    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">تعديل العرض</h1>
        
		<form method="POST" action="{{ route('lender.listings.update', $listing) }}" enctype="multipart/form-data">
			@csrf
			@method('PUT')

            <!-- Basic Information Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">المعلومات الأساسية</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">الوصف</label>
                        <textarea name="description" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $listing->description) }}</textarea>
                        @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">تفاصيل المنتج</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الماركة</label>
                        <input type="text" name="brand" value="{{ old('brand', $listing->brand) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('brand') border-red-500 @enderror">
                        @error('brand')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الموديل</label>
                        <input type="text" name="model" value="{{ old('model', $listing->model) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('model') border-red-500 @enderror">
                        @error('model')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>

					<div>
                        <label class="block mb-2 font-semibold text-gray-700">الرقم التسلسلي</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number', $listing->serial_number) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('serial_number') border-red-500 @enderror">
                        @error('serial_number')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>

					<div>
                        <label class="block mb-2 font-semibold text-gray-700">سنة الصنع</label>
                        <input type="number" name="manufacturing_year" value="{{ old('manufacturing_year', $listing->manufacturing_year) }}" min="1900" max="{{ date('Y') + 1 }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('manufacturing_year') border-red-500 @enderror">
                        @error('manufacturing_year')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>


                    
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">العلامات (Tags)</label>
                        <input type="text" name="tags" value="{{ old('tags', is_array($listing->tags) ? implode(', ', $listing->tags) : $listing->tags) }}" placeholder="مثال: إلكترونيات، ترفيه، منزل"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tags') border-red-500 @enderror">
                        @error('tags')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">التسعير</h2>
                
                <!-- Price Activation Toggles -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div id="daily-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر اليومي</label>
                        <input type="number" name="base_price" value="{{ old('base_price', $listing->base_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('base_price') border-red-500 @enderror">
                        @error('base_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div id="weekly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر الأسبوعي</label>
                        <input type="number" name="weekly_price" value="{{ old('weekly_price', $listing->weekly_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('weekly_price') border-red-500 @enderror">
                        @error('weekly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div id="monthly-price-field">
                        <label class="block mb-2 font-semibold text-gray-700">السعر الشهري</label>
                        <input type="number" name="monthly_price" value="{{ old('monthly_price', $listing->monthly_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('monthly_price') border-red-500 @enderror">
                        @error('monthly_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">مبلغ الضمان</label>
                        <input type="number" name="deposit_amount" value="{{ old('deposit_amount', $listing->deposit_amount) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('deposit_amount') border-red-500 @enderror">
                        @error('deposit_amount')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">رسوم التوصيل</label>
                        <input type="number" name="delivery_fee" value="{{ old('delivery_fee', $listing->delivery_fee) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('delivery_fee') border-red-500 @enderror">
                        @error('delivery_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">رسوم الاستلام</label>
                        <input type="number" name="pickup_fee" value="{{ old('pickup_fee', $listing->pickup_fee) }}" step="0.01" min="0"
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
                        <input type="text" name="location" value="{{ old('location', $listing->location) }}" placeholder="أدخل العنوان"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('location') border-red-500 @enderror">
                        @error('location')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">المدينة</label>
                        <select name="city" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('city') border-red-500 @enderror">
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
                        <label class="block mb-2 font-semibold text-gray-700">الحي</label>
                        <input type="text" name="neighborhood" value="{{ old('neighborhood', $listing->neighborhood) }}" placeholder="أدخل اسم الحي"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('neighborhood') border-red-500 @enderror">
                        @error('neighborhood')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الرمز البريدي</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $listing->postal_code) }}" placeholder="أدخل الرمز البريدي"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('postal_code') border-red-500 @enderror">
                        @error('postal_code')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Simple Location Picker -->
                <div class="mt-4">
                    <label class="block mb-2 font-semibold text-gray-700">تحديد الموقع على الخريطة</label>
                    <div id="location-picker" class="w-full h-64 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg relative cursor-crosshair">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-500">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <p>انقر على الخريطة لتحديد الموقع</p>
                            </div>
                        </div>
                        <div id="marker" class="absolute hidden w-6 h-6 bg-red-500 rounded-full border-2 border-white transform -translate-x-1/2 -translate-y-full cursor-pointer">
                            <div class="w-2 h-2 bg-white rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">انقر على الخريطة لتحديد موقع العرض</p>
                </div>
            </div>

            <!-- Rental Settings Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">إعدادات الإيجار</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">الحد الأدنى للأيام</label>
                        <input type="number" name="minimum_rental_days" value="{{ old('minimum_rental_days', $listing->minimum_rental_days) }}" min="1"
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
                        <input type="time" name="pickup_time_start" value="{{ old('pickup_time_start', $listing->pickup_time_start) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('pickup_time_start') border-red-500 @enderror">
                        @error('pickup_time_start')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>

					<div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الاستلام إلى</label>
                        <input type="time" name="pickup_time_end" value="{{ old('pickup_time_end', $listing->pickup_time_end) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('pickup_time_end') border-red-500 @enderror">
                        @error('pickup_time_end')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>

					<div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الإرجاع من</label>
                        <input type="time" name="return_time_start" value="{{ old('return_time_start', $listing->return_time_start) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('return_time_start') border-red-500 @enderror">
                        @error('return_time_start')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>

					<div>
                        <label class="block mb-2 font-semibold text-gray-700">وقت الإرجاع إلى</label>
                        <input type="time" name="return_time_end" value="{{ old('return_time_end', $listing->return_time_end) }}"
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
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('usage_instructions') border-red-500 @enderror">{{ old('usage_instructions', $listing->usage_instructions) }}</textarea>
                        @error('usage_instructions')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>

					<div>
                        <label class="block mb-2 font-semibold text-gray-700">تعليمات السلامة</label>
                        <textarea name="safety_instructions" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('safety_instructions') border-red-500 @enderror">{{ old('safety_instructions', $listing->safety_instructions) }}</textarea>
                        @error('safety_instructions')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">ملاحظات الصيانة</label>
                        <textarea name="maintenance_notes" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('maintenance_notes') border-red-500 @enderror">{{ old('maintenance_notes', $listing->maintenance_notes) }}</textarea>
                        @error('maintenance_notes')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>
				</div>
			</div>

            <!-- Stock Management Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">إدارة المخزون</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
                        <label class="block mb-2 font-semibold text-gray-700">إجمالي المخزون *</label>
                        <input type="number" name="total_stock" value="{{ old('total_stock', $listing->total_stock ?? 1) }}" min="1" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('total_stock') border-red-500 @enderror">
                        @error('total_stock')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
					</div>
                    
					<div class="flex items-center">
                        <input type="checkbox" name="enable_stock_management" value="1" {{ old('enable_stock_management', $listing->enable_stock_management) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label class="mr-2 font-semibold text-gray-700">تفعيل إدارة المخزون</label>
					</div>
				</div>
			</div>

            <!-- Status Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الحالة</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $listing->is_available) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label class="mr-2 font-semibold text-gray-700">متاح للإيجار</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $listing->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label class="mr-2 font-semibold text-gray-700">مميز</label>
                    </div>
                    
				<div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $listing->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label class="mr-2 font-semibold text-gray-700">نشط</label>
                    </div>
				</div>
			</div>

            <!-- Images Section -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">الصور</h2>
                
                <!-- Current Images -->
				@if($listing->images && count($listing->images) > 0)
                <div class="mb-6">
                    <label class="block mb-3 font-semibold text-gray-700">الصور الحالية</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach($listing->images as $index => $img)
                            <div class="relative group">
                                <img src="{{ $img }}" class="h-24 w-24 object-cover rounded-lg border shadow-sm cursor-pointer" onclick="openModal('{{ $img }}')">
                                <button type="button" onclick="removeImage(this)" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 cursor-pointer" title="حذف">&times;</button>
                                <input type="checkbox" name="remove_images[]" value="{{ $img }}" class="hidden remove-image-checkbox">
                            </div>
						@endforeach
                    </div>
					</div>
				@endif
                <div id="imgModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:1000; align-items:center; justify-content:center;">
                    <span onclick="closeModal()" style="position:absolute;top:30px;right:40px;font-size:40px;color:white;cursor:pointer;">&times;</span>
                    <img id="modalImg" src="" style="max-width:90vw;max-height:80vh;border-radius:12px;">
                </div>
                <script>
                function openModal(src) {
                    document.getElementById('imgModal').style.display = 'flex';
                    document.getElementById('modalImg').src = src;
                }
                function closeModal() {
                    document.getElementById('imgModal').style.display = 'none';
                }
                function removeImage(btn) {
                    btn.nextElementSibling.checked = true;
                    btn.closest('.group').style.display = 'none';
                }
                function previewImages(event) {
                    let preview = document.getElementById('image-preview');
                    preview.innerHTML = '';
                    for (let file of event.target.files) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            let img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'h-24 w-24 object-cover rounded-lg border shadow-sm';
                            preview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                }
                </script>
                
                <!-- Add New Images -->
                <div>
                    <label class="block mb-2 font-semibold text-gray-700">إضافة صور جديدة</label>
				<input type="file" name="images[]" multiple accept="image/*"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('images') border-red-500 @enderror" 
                            onchange="previewImages(event)">
                    @error('images')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    @error('images.*')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    <div id="image-preview" class="flex flex-wrap gap-4 mt-4"></div>
                </div>
			</div>

			<!-- Submit Buttons -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('lender.listings.show', $listing) }}" 
                   class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                    إلغاء
                </a>
                <button type="submit" 
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                    حفظ التغييرات
                </button>
			</div>
		</form>
	</div>
</div>

<script>
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

// Simple Location Picker - Fixed Version
function initLocationPicker() {
    const picker = document.getElementById('location-picker');
    const marker = document.getElementById('marker');
    const locationInput = document.querySelector('input[name="location"]');
    
    if (!picker || !marker || !locationInput) {
        console.log('Location picker elements not found');
        return;
    }
    
    let isDragging = false;
    
    // Click to place marker
    picker.addEventListener('click', function(e) {
        if (isDragging) return;
        
        const rect = picker.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // Show and position marker
        marker.style.display = 'block';
        marker.style.left = x + 'px';
        marker.style.top = y + 'px';
        
        // Calculate coordinates
        const lat = ((y / rect.height) * 180 - 90).toFixed(6);
        const lng = ((x / rect.width) * 360 - 180).toFixed(6);
        
        // Update location input if empty or contains coordinates
        if (!locationInput.value.trim() || locationInput.value.includes('موقع محدد:')) {
            locationInput.value = `موقع محدد: ${lat}, ${lng}`;
        }
        
        console.log('Marker placed at:', lat, lng);
    });
    
    // Drag marker functionality
    marker.addEventListener('mousedown', function(e) {
        isDragging = true;
        e.stopPropagation();
        e.preventDefault();
    });
    
    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        
        const rect = picker.getBoundingClientRect();
        const x = Math.max(0, Math.min(e.clientX - rect.left, rect.width));
        const y = Math.max(0, Math.min(e.clientY - rect.top, rect.height));
        
        marker.style.left = x + 'px';
        marker.style.top = y + 'px';
        
        // Calculate new coordinates
        const lat = ((y / rect.height) * 180 - 90).toFixed(6);
        const lng = ((x / rect.width) * 360 - 180).toFixed(6);
        
        // Update location if was set by map
        if (locationInput.value.includes('موقع محدد:')) {
            locationInput.value = `موقع محدد: ${lat}, ${lng}`;
        }
    });
    
    document.addEventListener('mouseup', function() {
        if (isDragging) {
            isDragging = false;
            console.log('Marker drag ended');
        }
    });
    
    console.log('Location picker initialized successfully');
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Edit Page');
    
    // Initialize price fields
    togglePriceField('daily');
    togglePriceField('weekly');
    togglePriceField('monthly');
    
    // Initialize location picker
    initLocationPicker();
    
    // Form validation before submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nameInput = document.querySelector('input[name="name"]');
            const categorySelect = document.querySelector('select[name="category_id"]');
            const dailyActive = document.querySelector('input[name="daily_price_active"]');
            const weeklyActive = document.querySelector('input[name="weekly_price_active"]');
            const monthlyActive = document.querySelector('input[name="monthly_price_active"]');
            const dailyPrice = document.querySelector('input[name="base_price"]');
            const weeklyPrice = document.querySelector('input[name="weekly_price"]');
            const monthlyPrice = document.querySelector('input[name="monthly_price"]');
            
            let hasError = false;
            
            // Check required fields
            if (!nameInput.value.trim()) {
                alert('يرجى إدخال اسم العنصر');
                nameInput.focus();
                hasError = true;
            } else if (!categorySelect.value) {
                alert('يرجى اختيار التصنيف');
                categorySelect.focus();
                hasError = true;
            }
            
            // Check if at least one price type is active
            if (!dailyActive.checked && !weeklyActive.checked && !monthlyActive.checked) {
                alert('يرجى تفعيل نوع واحد على الأقل من التسعير');
                hasError = true;
            } else {
                // Check if active price types have values
                if (dailyActive.checked && !dailyPrice.value) {
                    alert('يرجى إدخال السعر اليومي');
                    dailyPrice.focus();
                    hasError = true;
                } else if (weeklyActive.checked && !weeklyPrice.value) {
                    alert('يرجى إدخال السعر الأسبوعي');
                    weeklyPrice.focus();
                    hasError = true;
                } else if (monthlyActive.checked && !monthlyPrice.value) {
                    alert('يرجى إدخال السعر الشهري');
                    monthlyPrice.focus();
                    hasError = true;
                }
            }
            
            if (hasError) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>

@endsection





