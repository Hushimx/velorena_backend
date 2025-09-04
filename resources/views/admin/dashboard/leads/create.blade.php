@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Create Lead')
@section('title', 'إضافة Lead جديد')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">إضافة Lead جديد</h1>
                <p class="text-gray-600">إضافة lead جديد إلى المنصة</p>
            </div>
            <a href="{{ route('admin.leads.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-right pl-2"></i>
                <span>العودة للقائمة</span>
            </a>
        </div>

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.leads.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الشركة</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_name') border-red-500 @enderror"
                            required>
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">الشخص المسؤول</label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contact_person') border-red-500 @enderror"
                            required>
                        @error('contact_person')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            required>
                            <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>تم التواصل</option>
                            <option value="qualified" {{ old('status') == 'qualified' ? 'selected' : '' }}>مؤهل</option>
                            <option value="proposal_sent" {{ old('status') == 'proposal_sent' ? 'selected' : '' }}>تم إرسال العرض</option>
                            <option value="negotiation" {{ old('status') == 'negotiation' ? 'selected' : '' }}>مفاوضات</option>
                            <option value="closed_won" {{ old('status') == 'closed_won' ? 'selected' : '' }}>مكتمل - فوز</option>
                            <option value="closed_lost" {{ old('status') == 'closed_lost' ? 'selected' : '' }}>مكتمل - خسارة</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">الأولوية</label>
                        <select id="priority" name="priority"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror"
                            required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marketer -->
                    <div>
                        <label for="marketer_id" class="block text-sm font-medium text-gray-700 mb-2">المسوق المسؤول</label>
                        <select id="marketer_id" name="marketer_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('marketer_id') border-red-500 @enderror">
                            <option value="">اختر مسوق</option>
                            @foreach($marketers as $marketer)
                                <option value="{{ $marketer->id }}" {{ old('marketer_id') == $marketer->id ? 'selected' : '' }}>
                                    {{ $marketer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('marketer_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Next Follow Up -->
                    <div>
                        <label for="next_follow_up" class="block text-sm font-medium text-gray-700 mb-2">موعد المتابعة التالية</label>
                        <input type="datetime-local" id="next_follow_up" name="next_follow_up" value="{{ old('next_follow_up') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('next_follow_up') border-red-500 @enderror">
                        @error('next_follow_up')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('admin.leads.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        إضافة الـ Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
