@extends('marketer.layouts.app')

@section('title', 'تعديل الـ Lead')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">تعديل الـ Lead</h1>
                <p class="mt-1" style="color: #ffde9f;">تعديل بيانات الـ lead: {{ $lead->company_name }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('marketer.leads.index') }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-arrow-right"></i>
                    <span class="hidden sm:block">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('marketer.leads.update', $lead) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            required>
                            <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>تم التواصل</option>
                            <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>مؤهل</option>
                            <option value="proposal_sent" {{ old('status', $lead->status) == 'proposal_sent' ? 'selected' : '' }}>تم إرسال العرض</option>
                            <option value="negotiation" {{ old('status', $lead->status) == 'negotiation' ? 'selected' : '' }}>مفاوضات</option>
                            <option value="closed_won" {{ old('status', $lead->status) == 'closed_won' ? 'selected' : '' }}>مكتمل - فوز</option>
                            <option value="closed_lost" {{ old('status', $lead->status) == 'closed_lost' ? 'selected' : '' }}>مكتمل - خسارة</option>
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
                            <option value="low" {{ old('priority', $lead->priority) == 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="medium" {{ old('priority', $lead->priority) == 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="high" {{ old('priority', $lead->priority) == 'high' ? 'selected' : '' }}>عالية</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Next Follow Up -->
                    <div>
                        <label for="next_follow_up" class="block text-sm font-medium text-gray-700 mb-2">موعد المتابعة التالية</label>
                        <input type="datetime-local" id="next_follow_up" name="next_follow_up" 
                            value="{{ old('next_follow_up', $lead->next_follow_up ? $lead->next_follow_up->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('next_follow_up') border-red-500 @enderror">
                        @error('next_follow_up')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $lead->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('marketer.leads.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        تحديث الـ Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
