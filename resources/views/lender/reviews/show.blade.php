@extends('lender.layouts.app')

@section('title', 'تفاصيل التقييم')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تفاصيل التقييم</h1>
            <p class="text-gray-600">عرض تفاصيل التقييم</p>
        </div>
        <a href="{{ route('lender.reviews.index') }}" 
            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للتقييمات
        </a>
    </div>

    <!-- Review Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Review Header -->
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-12 w-12 rounded-full object-cover ml-4" 
                        src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random" 
                        alt="">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $review->user->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $review->user->email }}</p>
                        <div class="flex items-center mt-1">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <span class="mr-2 text-sm font-medium text-gray-700">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">تاريخ التقييم</div>
                    <div class="text-sm font-medium text-gray-900">{{ $review->created_at->format('Y-m-d') }}</div>
                </div>
            </div>
        </div>

        <!-- Review Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Customer Review -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تعليق العميل</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    </div>
                </div>

                <!-- Product Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل المنتج</h3>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">الاسم:</span>
                                <a href="{{ route('lender.listings.show', $review->listing) }}" 
                                    class="font-medium text-green-600 hover:text-green-700">
                                    {{ $review->listing->name }}
                                </a>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">العلامة التجارية:</span>
                                <span class="font-medium text-gray-900">{{ $review->listing->brand }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الموديل:</span>
                                <span class="font-medium text-gray-900">{{ $review->listing->model }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">السعر:</span>
                                <span class="font-medium text-gray-900">{{ number_format($review->listing->price) }} ريال</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الحالة:</span>
                                <span class="font-medium {{ $review->listing->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $review->listing->status === 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            @php
                $order = \App\Models\Order::where('user_id', $review->user_id)
                    ->where('listing_id', $review->listing_id)
                    ->first();
            @endphp
            
            @if($order)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الطلب</h3>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <span class="text-gray-600 text-sm">رقم الطلب:</span>
                                <div class="font-medium">
                                    <a href="{{ route('lender.orders.show', $order) }}" 
                                        class="text-green-600 hover:text-green-700">
                                        #{{ $order->id }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">تاريخ الطلب:</span>
                                <div class="font-medium">{{ $order->created_at->format('Y-m-d') }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">حالة الطلب:</span>
                                <div class="font-medium">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status === 'cancelled_by_user') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($order->status === 'completed') مكتمل
                                        @elseif($order->status === 'pending') في الانتظار
                                        @elseif($order->status === 'cancelled') ملغي
                                        @elseif($order->status === 'cancelled_by_user') ملغي من العميل
                                        @else {{ $order->status }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">المبلغ:</span>
                                <div class="font-medium">{{ number_format($order->total_amount) }} ريال</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
