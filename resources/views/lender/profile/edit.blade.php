@extends('lender.layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">تعديل الملف الشخصي</h1>
        <p class="text-gray-600">قم بتحديث معلوماتك الشخصية والتجارية</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-green-800">تم بنجاح!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if($errors->has('password_reset') || $errors->has('email'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-red-800">خطأ</h3>
                    <div class="mt-2 text-sm text-red-700">
                        @if($errors->has('password_reset'))
                            <p>{{ $errors->first('password_reset') }}</p>
                        @endif
                        @if($errors->has('email'))
                            <p>{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Verification Status -->
    @php
        $lender = Auth::guard('lender')->user();
        
        // Get missing fields for pending status
        $missingFields = [];
        if ($lender->verification_status === 'pending') {
            if ($lender->type === 'company') {
                $requiredFields = [
                    'business_name' => 'اسم المؤسسة',
                    'business_license' => 'رقم السجل التجاري',
                    'commercial_record' => 'السجل التجاري (PDF)',
                    'address' => 'العنوان',
                    'iban_number' => 'رقم الحساب البنكي (IBAN)',
                    'bank_account_statement' => 'شهادة IBAN'
                ];
            } else {
                $requiredFields = [
                    'name' => 'الاسم الكامل',
                    'email' => 'البريد الإلكتروني',
                    'phone' => 'رقم الهاتف',
                    'address' => 'العنوان',
                    'iban_number' => 'رقم الحساب البنكي (IBAN)',
                    'bank_account_statement' => 'شهادة IBAN'
                ];
            }
            
            foreach ($requiredFields as $field => $label) {
                if (empty($lender->$field)) {
                    $missingFields[] = $label;
                }
            }
        }
        
        $statusConfig = [
            'pending' => [
                'color' => 'yellow',
                'title' => 'يرجى إكمال بيانات ملفك الشخصي',
                'message' => !empty($missingFields) ? 'البيانات المطلوبة لإكمال ملفك الشخصي: ' . implode('، ', $missingFields) : 'يرجى إكمال بيانات ملفك الشخصي لتفعيل حساب المؤجر الخاص بك',
                'icon' => 'fas fa-clock'
            ],
            'under_review' => [
                'color' => 'blue',
                'title' => 'قيد المراجعة',
                'message' => 'نحن نراجع ملفك الشخصي حالياً. سيتم إشعارك عند اكتمال المراجعة.',
                'icon' => 'fas fa-search'
            ],
            'verified' => [
                'color' => 'green',
                'title' => 'تم التحقق',
                'message' => 'تم التحقق من ملفك الشخصي بنجاح. يمكنك الآن إضافة الإعلانات.',
                'icon' => 'fas fa-check-circle'
            ],
            'rejected' => [
                'color' => 'red',
                'title' => 'تم الرفض',
                'message' => 'تم رفض ملفك الشخصي.',
                'icon' => 'fas fa-times-circle'
            ]
        ];
        $currentStatus = $statusConfig[$lender->verification_status] ?? $statusConfig['pending'];
        
        // Check if form should be disabled
        $formDisabled = in_array($lender->verification_status, ['under_review', 'verified']);
    @endphp
        
    <div class="bg-{{ $currentStatus['color'] }}-50 border border-{{ $currentStatus['color'] }}-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="{{ $currentStatus['icon'] }} text-{{ $currentStatus['color'] }}-400 text-xl"></i>
            </div>
            <div class="mr-3">
                <h3 class="text-lg font-semibold text-{{ $currentStatus['color'] }}-900">
                    {{ $currentStatus['title'] }}
                </h3>
                <div class="mt-2 text-sm text-{{ $currentStatus['color'] }}-800">
                    @if($lender->verification_status === 'pending' && !empty($missingFields))
                        <p class="font-medium mb-2">البيانات المطلوبة لإكمال ملفك الشخصي:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($missingFields as $field)
                                <li>{{ $field }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ $currentStatus['message'] }}</p>
                    @endif
                    @if($lender->verification_status === 'rejected' && $lender->verification_notes)
                        <p class="mt-2 font-medium">السبب: {{ $lender->verification_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($formDisabled)
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-lock text-gray-400 text-xl"></i>
            </div>
            <div class="mr-3">

                <div class="mt-2 text-sm text-gray-600">
                    <p>لا يمكن تعديل الملف الشخصي حالياً لأنه قيد المراجعة أو تم التحقق منه.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Profile Form -->
    <div class="bg-white rounded-lg shadow-sm {{ $formDisabled ? 'opacity-75' : '' }}">
        <form action="{{ route('lender.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="border-b border-gray-200">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        المعلومات الشخصية
                    </h3>
                </div>
                
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile Image -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                            <div class="flex items-center ">
                                @if($lender->image)
                                    <img src="{{ $lender->image_url }}" alt="Profile" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-4">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($lender->name) }}&background=random&size=80" 
                                         alt="Profile" class="w-20 h-20 rounded-full border-2 border-gray-200 mx-4">
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="image" accept="image/*" 
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        {{ $formDisabled ? 'disabled' : '' }}>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF حتى 2MB</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $lender->name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $formDisabled ? 'disabled' : '' }}>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $lender->email) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $formDisabled ? 'disabled' : '' }}>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone - Locked/Readonly -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف <span class="text-gray-400">(غير قابل للتعديل)</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <div class="flex items-center bg-gray-100 rounded-lg px-2 py-1 border-l border-gray-300">
                                        <img src="https://flagcdn.com/w20/sa.png" alt="SA" class="w-4 h-3 ml-1">
                                        <span class="text-xs font-medium text-gray-700">+966</span>
                                    </div>
                                </div>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $lender->phone_without_code) }}"
                                    maxlength="9"
                                    readonly
                                    class="w-full pr-20 pl-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-right cursor-not-allowed"
                                    placeholder="596000912">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">رقم الهاتف محمي ولا يمكن تغييره</p>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">العنوان <span class="text-red-500">*</span></label>
                            <input name="address" id="address"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}" 
                                value="{{ old('address', $lender->address) }}" 
                                {{ $formDisabled ? 'disabled' : '' }} />
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">المدينة</label>
                            <x-searchable-city-select 
                                name="city_id" 
                                :value="old('city_id', $lender->city_id)"
                                placeholder="ابحث واختر المدينة"
                                class="w-full"
                            />
                            @error('city_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information Section (Only for Company Accounts) -->
            @if($lender->type === 'company')
            <div class="border-b border-gray-200">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h10M7 15h10"></path>
                        </svg>
                        معلومات الشركة
                    </h3>
                </div>
                
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">اسم الشركة <span class="text-red-500">*</span></label>
                            <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $lender->business_name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $formDisabled ? 'disabled' : '' }}>
                            @error('business_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Commercial Record Number and PDF in same row -->
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Business License -->
                                <div>
                                    <label for="business_license" class="block text-sm font-medium text-gray-700 mb-1">رقم السجل التجاري <span class="text-red-500">*</span></label>
                                    <input type="text" name="business_license" id="business_license" value="{{ old('business_license', $lender->business_license) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ $formDisabled ? 'disabled' : '' }}>
                                    @error('business_license')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Commercial Record PDF File -->
                                <div>
                                    <label for="commercial_record" class="block text-sm font-medium text-gray-700 mb-1">السجل التجاري (PDF) <span class="text-red-500">*</span></label>
                                    <input type="file" name="commercial_record" id="commercial_record" accept=".pdf"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                                        {{ $formDisabled ? 'disabled' : '' }}>
                                    <p class="text-xs text-gray-500 mt-1">PDF فقط، حتى 5MB</p>
                                    @if($lender->commercial_record)
                                        <div class="mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800">السجل التجاري</p>
                                                        <p class="text-xs text-gray-500">تم رفع الملف بنجاح</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ Storage::url($lender->commercial_record) }}" target="_blank" 
                                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        عرض
                                                    </a>
                                                    <a href="{{ Storage::url($lender->commercial_record) }}" download
                                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700 transition-colors">
                                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        تحميل
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @error('commercial_record')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tax Record Number and PDF in same row -->
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Tax Record Number (Optional) -->
                                <div>
                                    <label for="tax_record" class="block text-sm font-medium text-gray-700 mb-1">رقم السجل الضريبي <span class="text-gray-500">(اختياري)</span></label>
                                    <input type="text" name="tax_record" id="tax_record" value="{{ old('tax_record', $lender->tax_record) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                        {{ $formDisabled ? 'disabled' : '' }}>
                                    @error('tax_record')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tax Record PDF File -->
                                <div>
                                    <label for="tax_record_pdf" class="block text-sm font-medium text-gray-700 mb-1">السجل الضريبي (PDF) <span class="text-gray-500">(اختياري)</span></label>
                                    <input type="file" name="tax_record_pdf" id="tax_record_pdf" accept=".pdf"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                                        {{ $formDisabled ? 'disabled' : '' }}>
                                    <p class="text-xs text-gray-500 mt-1">PDF فقط، حتى 5MB</p>
                                    @if($lender->tax_record_pdf)
                                        <div class="mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800">السجل الضريبي</p>
                                                        <p class="text-xs text-gray-500">تم رفع الملف بنجاح</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ Storage::url($lender->tax_record_pdf) }}" target="_blank" 
                                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        عرض
                                                    </a>
                                                    <a href="{{ Storage::url($lender->tax_record_pdf) }}" download
                                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700 transition-colors">
                                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        تحميل
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @error('tax_record_pdf')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Description -->
                        <div class="md:col-span-2">
                            <label for="business_description" class="block text-sm font-medium text-gray-700 mb-1">وصف النشاط التجاري</label>
                            <textarea name="business_description" id="business_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $formDisabled ? 'disabled' : '' }}>{{ old('business_description', $lender->business_description) }}</textarea>
                            @error('business_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Banking Information Section -->
            <div class="border-b border-gray-200">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        المعلومات البنكية
                    </h3>
                </div>
                
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- IBAN Number -->
                        <div>
                            <label for="iban_number" class="block text-sm font-medium text-gray-700 mb-1">رقم الحساب البنكي (IBAN) <span class="text-red-500">*</span></label>
                            <input type="text" name="iban_number" id="iban_number" value="{{ old('iban_number', $lender->iban_number) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 {{ $formDisabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                placeholder="مثال: SA0380000000608010167519"
                                {{ $formDisabled ? 'disabled' : '' }}>
                            @error('iban_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Account Statement -->
                        <div>
                            <label for="bank_account_statement" class="block text-sm font-medium text-gray-700 mb-1">شهادة IBAN <span class="text-red-500">*</span></label>
                            <input type="file" name="bank_account_statement" id="bank_account_statement" accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                                {{ $formDisabled ? 'disabled' : '' }}>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG حتى 5MB</p>
                            @if($lender->bank_account_statement)
                                <div class="mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-5">
                                                <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800">شهادة IBAN</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ Storage::url($lender->bank_account_statement) }}" target="_blank" 
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                عرض
                                            </a>
                                            <a href="{{ Storage::url($lender->bank_account_statement) }}" download
                                               class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700 transition-colors">
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                تحميل
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @error('bank_account_statement')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div>
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        الأمان
                    </h3>
                </div>
                
                <div class="px-6 pb-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="mr-3">
                                <h3 class="text-sm font-medium text-yellow-800">تغيير كلمة المرور</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>لحماية حسابك، يتم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني المسجل.</p>
                                </div>
                                
                                <!-- Password Reset Success Message -->
                                @if(session('password_reset_success'))
                                    <div class="mt-3 p-3 bg-green-100 border border-green-300 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-green-600 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-sm text-green-800 font-medium">{{ session('password_reset_success') }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Password Reset Error Message -->
                                @if($errors->has('password_reset'))
                                    <div class="mt-3 p-3 bg-red-100 border border-red-300 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-red-600 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-sm text-red-800 font-medium">{{ $errors->first('password_reset') }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="mt-4">
                                    <button type="submit" name="request_password_reset" value="1" id="passwordResetBtn"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                        @if(!$lender->canRequestPasswordReset()) disabled @endif>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span id="btnText">
                                            @if(!$lender->canRequestPasswordReset())
                                                @php
                                                    $remainingMinutes = $lender->getPasswordResetCooldownRemaining();
                                                @endphp
                                                انتظر {{ $remainingMinutes }} دقيقة
                                            @else
                                                إرسال رابط إعادة تعيين كلمة المرور
                                            @endif
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <svg class="w-4 h-4 inline ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <strong>ملاحظة:</strong> سيتم إرسال رابط آمن إلى بريدك الإلكتروني المسجل ({{ $lender->email }}) لإعادة تعيين كلمة المرور. الرابط صالح لمدة ساعة واحدة فقط.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-end">
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition-colors font-medium {{ $formDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $formDisabled ? 'disabled' : '' }}>
                        {{ $formDisabled ? 'الملف مقفل' : 'حفظ التغييرات' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordResetBtn = document.getElementById('passwordResetBtn');
    const btnText = document.getElementById('btnText');
    
    if (passwordResetBtn) {
        passwordResetBtn.addEventListener('click', function(e) {
            console.log('Password reset button clicked');
            
            // Prevent multiple clicks
            if (this.disabled) {
                e.preventDefault();
                console.log('Button is disabled, preventing submission');
                return false;
            }
            
            console.log('Button is enabled, proceeding with submission');
            
            // Show loading state but don't prevent form submission
            btnText.textContent = 'جاري الإرسال...';
            
            // Add loading class for visual feedback
            this.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Disable button after a short delay to allow form submission
            setTimeout(() => {
                this.disabled = true;
                console.log('Button disabled after delay');
            }, 100);
            
            // Re-enable after 5 seconds as fallback
            setTimeout(() => {
                this.disabled = false;
                this.classList.remove('opacity-75', 'cursor-not-allowed');
                btnText.textContent = 'إرسال رابط إعادة تعيين كلمة المرور';
                console.log('Button re-enabled after timeout');
            }, 5000);
        });
    } else {
        console.log('Password reset button not found');
    }
    
    // Add form submission debugging
    const form = document.querySelector('form[action*="profile"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form is being submitted');
            const formData = new FormData(this);
            console.log('Form data:', Object.fromEntries(formData));
        });
    } else {
        console.log('Profile form not found');
    }
    
    // Auto-hide success messages after 10 seconds
    const successMessages = document.querySelectorAll('.bg-green-50, .bg-green-100');
    successMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 500);
        }, 10000);
    });
    
    // Auto-hide error messages after 15 seconds
    const errorMessages = document.querySelectorAll('.bg-red-50, .bg-red-100');
    errorMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 500);
        }, 15000);
    });
});
</script>
@endsection

