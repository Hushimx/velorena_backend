@extends('lender.layouts.app')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">الطلب #{{ $order->id }}</h1>
            <p class="text-gray-600">تفاصيل الطلب</p>
        </div>
        <div class="flex space-x-3">
            @if($order->status === 'pending')
                <form action="{{ route('lender.orders.confirm', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-check ml-2"></i>تأكيد الطلب
                    </button>
                </form>
                <form action="{{ route('lender.orders.cancel', $order) }}" method="POST" 
                    class="inline" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-times ml-2"></i>إلغاء الطلب
                    </button>
                </form>
            @elseif($order->status === 'active')
                <form action="{{ route('lender.orders.complete', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-check-circle ml-2"></i>إكمال الطلب
                    </button>
                </form>
            @endif
            <a href="{{ route('lender.orders.index') }}" 
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-right ml-2"></i>العودة
            </a>
        </div>
    </div>

    <!-- Order Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Order Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الطلب</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">رقم الطلب:</span>
                    <span class="font-medium">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">تاريخ البداية:</span>
                    <span class="font-medium">{{ $order->start_date }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">تاريخ النهاية:</span>
                    <span class="font-medium">{{ $order->end_date }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">مدة الإيجار:</span>
                    <span class="font-medium">{{ $order->rental_days }} أيام</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الكمية:</span>
                    <span class="font-medium">{{ $order->quantity ?? 1 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الحالة:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'approved') bg-blue-100 text-blue-800
                        @elseif($order->status === 'paid') bg-purple-100 text-purple-800
                        @elseif($order->status === 'active') bg-indigo-100 text-indigo-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @elseif($order->status === 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات العميل</h3>
            <div class="space-y-3">
                <div class="flex items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&background=random" 
                        alt="" class="w-12 h-12 rounded-full ml-3">
                    <div>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                    </div>
                </div>
                @if($order->user->phone)
                    <div class="flex justify-between">
                        <span class="text-gray-600">رقم الهاتف:</span>
                        <span class="font-medium">{{ $order->user->phone }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Listing Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات العرض</h3>
            <div class="flex items-start space-x-4">
                @if($listing->images && count($listing->images) > 0)
                    <img src="{{ $listing->images[0] }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                @endif
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">{{ $listing->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $listing->brand }} {{ $listing->model }}</p>
                    <p class="text-sm text-gray-500">{{ $listing->category->name ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Pricing Details -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل التكلفة</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">سعر القطعة:</span>
                    <span class="font-medium">{{ number_format($order->item_price, 2) }} ر.س</span>
                </div>
                @if($order->deposit_amount)
                    <div class="flex justify-between">
                        <span class="text-gray-600">مبلغ التأمين:</span>
                        <span class="font-medium">{{ number_format($order->deposit_amount, 2) }} ر.س</span>
                    </div>
                @endif
                @if($order->delivery_fee > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">رسوم التوصيل:</span>
                        <span class="font-medium">{{ number_format($order->delivery_fee, 2) }} ر.س</span>
                    </div>
                @endif
                @if($order->pickup_fee > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">رسوم الاستلام:</span>
                        <span class="font-medium">{{ number_format($order->pickup_fee, 2) }} ر.س</span>
                    </div>
                @endif
                <div class="flex justify-between text-lg font-semibold border-t pt-3">
                    <span class="text-gray-900">الإجمالي:</span>
                    <span class="text-green-600">{{ number_format($order->total, 2) }} ر.س</span>
                </div>
            </div>
        </div>
    </div>

    @if($order->description)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">ملاحظات العميل</h3>
            <p class="text-gray-700">{{ $order->description }}</p>
        </div>
    @endif
</div>
@endsection


