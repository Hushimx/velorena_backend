@extends('admin.layouts.app')

@section('title', __('admin.dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('admin.welcome_admin', ['name' => Auth::guard('admin')->user()->name]) }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ __('admin.platform_overview') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.products.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:block">{{ __('admin.add_new_product') }}</span>
                </a>
                <div class="hidden md:block">
                    <i class="fas fa-cogs text-4xl" style="color: #ffde9f;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Most Important 4 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.total_users') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Pending Orders -->
        <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #f5d182; color: #2a1e1e;">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.pending_orders') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Total Orders -->
        <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #3a2e2e; color: #ffde9f;">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.total_orders') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Total Revenue -->
        <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('admin.total_revenue') }}</p>
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
                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.sales_chart') }}</h3>
                <div class="flex items-center gap-3">
                    <select id="periodSelect" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:border-2a1e1e" style="focus:ring-color: #2a1e1e;">
                        <option value="today" selected>{{ __('admin.today_hours') }}</option>
                        <option value="7">{{ __('admin.last_7_days') }}</option>
                        <option value="30">{{ __('admin.last_30_days') }}</option>
                        <option value="90">{{ __('admin.last_3_months') }}</option>
                        <option value="180">{{ __('admin.last_6_months') }}</option>
                        <option value="365">{{ __('admin.last_12_months') }}</option>
                    </select>
                    <i class="fas fa-chart-line" style="color: #2a1e1e;"></i>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="salesChart"></canvas>
            </div>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: #2a1e1e;"></div>
                        <span>{{ __('admin.revenue') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: #ffde9f;"></div>
                        <span>{{ __('admin.orders_count') }}</span>
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
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.revenue_details') }}</h3>
                <i class="fas fa-chart-line" style="color: #2a1e1e;"></i>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">{{ __('admin.monthly_revenue') }}</p>
                    <p class="text-xl font-semibold" style="color: #2a1e1e;">{{ number_format($stats['monthly_revenue'] ?? 0, 2) }} ر.س</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('admin.today_orders') }}</p>
                    <p class="text-lg font-semibold" style="color: #ffde9f;">{{ $stats['today_orders'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('admin.today_appointments') }}</p>
                    <p class="text-lg font-semibold" style="color: #3a2e2e;">{{ $stats['today_appointments'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.recent_orders') }}</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium hover:underline" style="color: #2a1e1e;">
                    {{ __('admin.view_all') }}
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">طلب #{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->full_name ?? $order->user->email }}</p>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900">{{ number_format($order->total, 2) }} ر.س</p>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ __('status.order.' . $order->status) ?: $order->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __('admin.no_recent_orders') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.recent_appointments') }}</h3>
                <a href="{{ route('admin.appointments.index') }}" class="text-sm font-medium hover:underline" style="color: #2a1e1e;">
                    {{ __('admin.view_all') }}
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentAppointments as $appointment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : 'غير محدد' }}
                                @if($appointment->appointment_time)
                                    - {{ $appointment->appointment_time->format('H:i') }}
                                @endif
                            </p>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900">
                                @if($appointment->designer)
                                    {{ $appointment->designer->name }}
                                @else
                                    بدون مصمم
                                @endif
                            </p>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __('admin.no_recent_appointments') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Floating Add Button -->
    <div class="fixed bottom-6 left-6 z-50">
        <a href="{{ route('admin.products.create') }}" class="text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center gap-3" style="background-color: #2a1e1e;">
            <i class="fas fa-plus text-xl"></i>
            <span class="hidden sm:block font-medium">إضافة منتج جديد</span>
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
                    borderColor: '#2a1e1e',
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
                        color: '#2a1e1e',
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
                        color: '#ffde9f',
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
                    hoverBackgroundColor: '#3a2e2e'
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
                    borderColor: '#2a1e1e',
                    backgroundColor: 'rgba(42, 30, 30, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2a1e1e',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#3a2e2e',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    yAxisID: 'y'
                }, {
                    label: 'عدد الطلبات',
                    data: data.map(item => item.orders),
                    borderColor: '#ffde9f',
                    backgroundColor: 'rgba(255, 222, 159, 0.1)',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#ffde9f',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#f5d182',
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
        const url = `{{ route('admin.dashboard.sales-data') }}?days=${encodeURIComponent(days)}`;
        
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
