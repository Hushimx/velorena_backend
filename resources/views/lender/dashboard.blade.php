@extends('lender.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <!-- Profile Completion Alert -->
    <x-profile-completion-notice />

    <!-- Welcome Section -->
    <div class="bg-green-500 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">مرحباً، {{ Auth::guard('lender')->user()->display_name }}</h1>
                <p class="text-green-100 mt-1">إليك نظرة عامة على أنشطتك اليوم</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('lender.listings.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:block">إضافة عرض جديد</span>
                </a>
                <div class="hidden md:block">
                    <i class="fas fa-store text-4xl text-green-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Most Important 4 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Listings -->
        <a href="{{ route('lender.listings.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-list text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">إجمالي العروض</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_listings'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Pending Orders -->
        <a href="{{ route('lender.orders.index', ['status' => 'pending']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">الطلبات المعلقة</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Active Orders -->
        <a href="{{ route('lender.orders.index', ['status' => 'active']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-play-circle text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">الطلبات النشطة</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_orders'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Total Revenue -->
        <a href="{{ route('lender.balance.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">إجمالي الإيرادات</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_revenue'] ?? 0, 0) }} ر.س</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Revenue Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">مخطط المبيعات</h3>
                <div class="flex items-center gap-3">
                    <select id="periodSelect" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="today" selected>اليوم (ساعات)</option>
                        <option value="7">آخر 7 أيام</option>
                        <option value="30">آخر 30 يوم</option>
                        <option value="90">آخر 3 أشهر</option>
                        <option value="180">آخر 6 أشهر</option>
                        <option value="365">آخر 12 شهر</option>
                    </select>
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="salesChart"></canvas>
            </div>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span>الإيرادات</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span>عدد الطلبات</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div id="totalRevenue" class="font-semibold text-gray-700">
                        إجمالي الإيرادات: {{ number_format($stats['total_revenue'] ?? 0, 0) }} ر.س
                    </div>
                    <div id="totalOrders" class="font-semibold text-gray-700">
                        إجمالي الطلبات: {{ $stats['total_orders'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Details -->
        <a href="{{ route('lender.balance.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">تفاصيل الإيرادات</h3>
                <i class="fas fa-chart-line text-green-600"></i>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">إيرادات هذا الشهر</p>
                    <p class="text-xl font-semibold text-green-600">{{ number_format($stats['monthly_revenue'] ?? 0, 2) }} ر.س</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إيرادات معلقة</p>
                    <p class="text-lg font-semibold text-yellow-600">{{ number_format($stats['pending_revenue'] ?? 0, 2) }} ر.س</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إيرادات نشطة</p>
                    <p class="text-lg font-semibold text-blue-600">{{ number_format($stats['active_revenue'] ?? 0, 2) }} ر.س</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reviews -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">أحدث التقييمات</h3>
                <a href="{{ route('lender.reviews.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                    عرض الكل
                </a>
            </div>
            
            <!-- Rating Summary -->
            <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-500">متوسط التقييم</p>
                    <div class="flex items-center">
                        <p class="text-xl font-bold text-gray-900">{{ number_format($stats['average_rating'] ?? 0, 1) }}</p>
                        <div class="flex mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= ($stats['average_rating'] ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="text-left">
                    <p class="text-sm text-gray-500">إجمالي التقييمات</p>
                    <p class="text-lg font-semibold text-gray-600">{{ $stats['total_reviews'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="space-y-3">
                @forelse($recentReviews as $review)
                    <div class="border-b border-gray-100 pb-3 last:border-b-0 last:pb-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ $review->user->name }}</p>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($review->comment, 80) }}</p>
                                @endif
                                @if($review->listing)
                                    <p class="text-xs text-gray-500 mt-1">للعرض: {{ $review->listing->name }}</p>
                                @endif
                            </div>
                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">لا توجد تقييمات حديثة</p>
                @endforelse
            </div>
        </div>

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
                                @elseif($order->status === 'active') bg-blue-100 text-blue-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @elseif($order->status === 'cancelled_by_user') bg-orange-100 text-orange-800
                                @elseif($order->status === 'rejected') bg-red-100 text-red-800
                                @elseif($order->status === 'late') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($order->status === 'pending') معلق
                                @elseif($order->status === 'active') نشط
                                @elseif($order->status === 'completed') مكتمل
                                @elseif($order->status === 'cancelled') ملغي
                                @elseif($order->status === 'cancelled_by_user') ملغي من العميل
                                @elseif($order->status === 'rejected') مرفوض
                                @elseif($order->status === 'late') متأخر
                                @else {{ $order->status }}
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">لا توجد طلبات حديثة</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Floating Add Listing Button -->
    <div class="fixed bottom-6 left-6 z-50">
        <a href="{{ route('lender.listings.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center gap-3">
            <i class="fas fa-plus text-xl"></i>
            <span class="hidden sm:block font-medium">إضافة عرض جديد</span>
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const periodSelect = document.getElementById('periodSelect');
    const totalRevenueElement = document.getElementById('totalRevenue');
    const totalOrdersElement = document.getElementById('totalOrders');
    let salesChart = null;
    
    // Chart configuration
    const chartConfig = {
        type: 'line',
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: 'Cairo',
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#059669',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'الإيرادات: ' + new Intl.NumberFormat('ar-SA').format(context.parsed.y) + ' ر.س';
                            } else {
                                return 'عدد الطلبات: ' + new Intl.NumberFormat('ar-SA').format(context.parsed.y);
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            family: 'Cairo',
                            size: 12
                        },
                        maxTicksLimit: 12,
                        callback: function(value, index, ticks) {
                            const label = this.getLabelForValue(value);
                            // For hourly data, show every 4th hour to avoid crowding
                            if (label && label.includes(':00') && ticks.length > 12) {
                                const hour = parseInt(label.split(':')[0]);
                                return hour % 4 === 0 ? label : '';
                            }
                            return label;
                        }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#059669',
                        font: {
                            family: 'Cairo',
                            size: 12
                        },
                        callback: function(value) {
                            return new Intl.NumberFormat('ar-SA').format(value) + ' ر.س';
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        color: '#3b82f6',
                        font: {
                            family: 'Cairo',
                            size: 12
                        },
                        callback: function(value) {
                            return new Intl.NumberFormat('ar-SA').format(value);
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#047857'
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    };
    
    // Initialize chart with default data
    function initializeChart(data) {
        if (salesChart) {
            salesChart.destroy();
        }
        
        salesChart = new Chart(salesCtx, {
            ...chartConfig,
            data: {
                labels: data.map(item => item.period),
                datasets: [{
                    label: 'الإيرادات (ر.س)',
                    data: data.map(item => item.revenue),
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#059669',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#047857',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    yAxisID: 'y'
                }, {
                    label: 'عدد الطلبات',
                    data: data.map(item => item.orders),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#2563eb',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    yAxisID: 'y1'
                }]
            }
        });
    }
    
    // Load sales data for specific period
    function loadSalesData(days) {
        // Show loading state
        if (salesChart) {
            salesChart.data.labels = ['جاري التحميل...'];
            salesChart.data.datasets[0].data = [0];
            salesChart.update();
        }
        
        // Build URL with proper parameter
        const url = `{{ route('lender.dashboard.sales-data') }}?days=${encodeURIComponent(days)}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error loading sales data:', data.error);
                    return;
                }
                
                // Update chart
                initializeChart(data.data);
                
                // Update totals
                const totalRevenue = data.total_revenue || 0;
                const totalOrders = data.total_orders || 0;
                totalRevenueElement.textContent = `إجمالي الإيرادات: ${new Intl.NumberFormat('ar-SA').format(totalRevenue)} ر.س`;
                totalOrdersElement.textContent = `إجمالي الطلبات: ${new Intl.NumberFormat('ar-SA').format(totalOrders)}`;
            })
            .catch(error => {
                console.error('Error loading sales data:', error);
                // Show error state
                if (salesChart) {
                    salesChart.data.labels = ['خطأ في التحميل'];
                    salesChart.data.datasets[0].data = [0];
                    salesChart.update();
                }
            });
    }
    
    // Initialize with default data
    const initialData = @json($salesData);
    initializeChart(initialData);
    
    // Handle period change
    periodSelect.addEventListener('change', function() {
        const selectedDays = this.value;
        loadSalesData(selectedDays);
    });
    
    // Add loading animation
    periodSelect.addEventListener('change', function() {
        this.style.opacity = '0.6';
        this.disabled = true;
        
        setTimeout(() => {
            this.style.opacity = '1';
            this.disabled = false;
        }, 1000);
    });
});
</script>
@endpush
@endsection
