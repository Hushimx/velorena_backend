@extends('lender.layouts.app')

@section('title', 'إنشاء كوبون')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">إنشاء كوبون جديد</h1>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('lender.coupons.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الكود</label>
                <input type="text" name="coupon_id" value="{{ old('coupon_id') }}" required class="w-full border rounded px-3 py-2">
                @error('coupon_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_percentage" value="1" {{ old('is_percentage') ? 'checked' : '' }}>
                        <span>خصم نسبة مئوية</span>
                    </label>
                    <input type="number" name="percentage" value="{{ old('percentage') }}" step="0.01" min="0" max="100" placeholder="%" class="w-full border rounded px-3 py-2 mt-2">
                    @error('percentage')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_fixed" value="1" {{ old('is_fixed') ? 'checked' : '' }}>
                        <span>خصم بمبلغ ثابت</span>
                    </label>
                    <input type="number" name="fixed_discount" value="{{ old('fixed_discount') }}" step="0.01" min="0" placeholder="ر.س" class="w-full border rounded px-3 py-2 mt-2">
                    @error('fixed_discount')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ البداية</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border rounded px-3 py-2">
                    @error('start_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الانتهاء</label>
                    <input type="date" name="expire_date" value="{{ old('expire_date') }}" class="w-full border rounded px-3 py-2">
                    @error('expire_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection















