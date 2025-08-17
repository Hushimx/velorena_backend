@extends('lender.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-green-500 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">مرحباً، {{ Auth::guard('lender')->user()->display_name }}</h1>
                <p class="text-green-100 mt-1">إليك نظرة عامة على أنشطتك اليوم</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-store text-4xl text-green-200"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Listings -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-list text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">إجمالي العروض</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_listings'] }}</p>
                </div>
            </div>
        </div>

        <!-- Active Listings -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">العروض النشطة</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_listings'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">إجمالي الطلبات</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">الطلبات المعلقة</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_orders'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">الإيرادات</h3>
                <i class="fas fa-chart-line text-green-600"></i>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">إجمالي الإيرادات</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_revenue'], 2) }} ر.س</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إيرادات هذا الشهر</p>
                    <p class="text-xl font-semibold text-green-600">{{ number_format($stats['monthly_revenue'], 2) }} ر.س</p>
                </div>
            </div>
        </div>

        <!-- Ratings -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">التقييمات</h3>
                <i class="fas fa-star text-yellow-500"></i>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">متوسط التقييم</p>
                    <div class="flex items-center">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</p>
                        <div class="flex mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $stats['average_rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي التقييمات</p>
                    <p class="text-xl font-semibold text-gray-600">{{ $stats['total_reviews'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">أحدث الطلبات</h3>
                <a href="{{ route('lender.orders.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                    عرض الكل
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $order->listing->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->name }}</p>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900">{{ number_format($order->total, 2) }} ر.س</p>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">لا توجد طلبات حديثة</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">أحدث التقييمات</h3>
                <a href="{{ route('lender.reviews.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                    عرض الكل
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentReviews as $review)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">{{ Str::limit($review->comment, 50) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $review->listing->name }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">لا توجد تقييمات حديثة</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
