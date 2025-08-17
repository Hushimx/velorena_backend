@extends('lender.layouts.app')

@section('title', 'الكوبونات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">الكوبونات</h1>
            <p class="text-gray-600">إدارة كوبونات الخصم الخاصة بك</p>
        </div>
        <a href="{{ route('lender.coupons.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">إنشاء كوبون</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">الكود</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">النوع</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">القيمة</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">البداية</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">الانتهاء</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-3 font-mono text-gray-900">{{ $coupon->coupon_id }}</td>
                        <td class="px-4 py-3">
                            @if($coupon->is_percentage)
                                <span class="inline-flex px-2 py-1 text-xs rounded bg-blue-50 text-blue-700">نسبة</span>
                            @elseif($coupon->is_fixed)
                                <span class="inline-flex px-2 py-1 text-xs rounded bg-purple-50 text-purple-700">مبلغ ثابت</span>
                            @else
                                <span class="text-xs text-gray-500">غير محدد</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {{ $coupon->is_percentage ? ($coupon->percentage . '%') : ($coupon->fixed_discount . ' ر.س') }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ optional($coupon->start_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ optional($coupon->expire_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('lender.coupons.edit', $coupon) }}" class="text-blue-600 hover:text-blue-800">تعديل</a>
                                <form method="POST" action="{{ route('lender.coupons.destroy', $coupon) }}" onsubmit="return confirm('هل أنت متأكد من حذف الكوبون؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">لا توجد كوبونات بعد</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $coupons->links() }}</div>
    </div>
</div>
@endsection









