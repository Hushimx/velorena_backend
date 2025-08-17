@extends('lender.layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">تعديل الملف الشخصي</h1>
        <p class="text-gray-600">قم بتحديث معلوماتك الشخصية والتجارية</p>
    </div>

    <!-- Profile Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('lender.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">المعلومات الشخصية</h3>
                    
                    <!-- Profile Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الشخصية</label>
                        <div class="flex items-center space-x-4">
                            @if($lender->image)
                                <img src="{{ $lender->image_url }}" alt="Profile" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($lender->name) }}&background=random" 
                                     alt="Profile" class="w-16 h-16 rounded-full">
                            @endif
                            <input type="file" name="image" accept="image/*" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $lender->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $lender->email) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $lender->phone) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('address', $lender->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة (اختياري)</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <!-- Business Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">المعلومات التجارية</h3>
                    
                    <!-- Business Name -->
                    <div>
                        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">اسم المؤسسة</label>
                        <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $lender->business_name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('business_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Business License -->
                    <div>
                        <label for="business_license" class="block text-sm font-medium text-gray-700 mb-1">رقم السجل التجاري</label>
                        <input type="text" name="business_license" id="business_license" value="{{ old('business_license', $lender->business_license) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('business_license')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Business Description -->
                    <div>
                        <label for="business_description" class="block text-sm font-medium text-gray-700 mb-1">وصف النشاط التجاري</label>
                        <textarea name="business_description" id="business_description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('business_description', $lender->business_description) }}</textarea>
                        @error('business_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6 pt-4 border-t">
                <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

