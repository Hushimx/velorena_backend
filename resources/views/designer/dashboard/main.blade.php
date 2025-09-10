@extends('designer.layouts.app')

@section('title', __('dashboard.designer_dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('dashboard.welcome_designer', ['name' => Auth::guard('designer')->user()->name]) }}</h1>
                <p class="mt-1" style="color: #ffde9f;">{{ __('dashboard.designer_overview') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('designer.appointments.dashboard') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-calendar-plus"></i>
                    <span class="hidden sm:block">{{ __('dashboard.view_appointments') }}</span>
                </a>
                <div class="hidden md:block">
                    <i class="fas fa-paint-brush text-4xl" style="color: #ffde9f;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Designer Specific -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Appointments -->
        <a href="{{ route('designer.appointments.index') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.total_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_appointments'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Pending Appointments -->
        <a href="{{ route('designer.appointments.index', ['status' => 'pending']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #f5d182; color: #2a1e1e;">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.pending_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_appointments'] ?? 0 }}</p>
                </div>
            </div>
        </a>

        <!-- Completed Appointments -->
        <a href="{{ route('designer.appointments.index', ['status' => 'completed']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #3a2e2e; color: #ffde9f;">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.completed_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed_appointments'] ?? 0 }}</p>
                </div>
                            </div>
        </a>

        <!-- Available Appointments -->
        <a href="{{ route('designer.appointments.dashboard') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                    <i class="fas fa-hand-paper text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.available_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['available_appointments'] ?? 0 }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Appointments Overview Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Appointments Chart -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.appointments_chart') }}</h3>
                <div class="flex items-center gap-3">
                    <select id="periodSelect" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:border-2a1e1e" style="focus:ring-color: #2a1e1e;">
                        <option value="today" selected>{{ __('dashboard.today_hours') }}</option>
                        <option value="7">{{ __('dashboard.last_7_days') }}</option>
                        <option value="30">{{ __('dashboard.last_30_days') }}</option>
                        <option value="90">{{ __('dashboard.last_3_months') }}</option>
                    </select>
                    <i class="fas fa-chart-line" style="color: #2a1e1e;"></i>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="appointmentsChart"></canvas>
            </div>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: #2a1e1e;"></div>
                        <span>{{ __('dashboard.appointments') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background-color: #ffde9f;"></div>
                        <span>{{ __('dashboard.completed') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div id="totalAppointments" class="font-semibold text-gray-700">
                        إجمالي المواعيد: {{ $stats['total_appointments'] ?? 0 }}
                    </div>
                    <div id="completedAppointments" class="font-semibold text-gray-700">
                        المكتملة: {{ $stats['completed_appointments'] ?? 0 }}
                    </div>
                                    </div>
                                </div>
                            </div>

        <!-- Live New Appointments Component -->
        @livewire('designer.new-appointments')
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.recent_appointments') }}</h3>
                <a href="{{ route('designer.appointments.index') }}" class="text-sm font-medium hover:underline" style="color: #2a1e1e;">
                    {{ __('dashboard.view_all') }}
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
                                {{ $appointment->service_type ?? 'خدمة عامة' }}
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
                    <p class="text-gray-500 text-center py-4">{{ __('dashboard.no_recent_appointments') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.quick_actions') }}</h3>
                <i class="fas fa-bolt" style="color: #2a1e1e;"></i>
            </div>
            <div class="space-y-3">
                <a href="{{ route('designer.appointments.dashboard') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="font-medium text-gray-900">{{ __('dashboard.appointments_dashboard') }}</span>
                    </div>
                    <i class="fas fa-chevron-left text-gray-400"></i>
                </a>
                
                <a href="{{ route('designer.profile.edit') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-full" style="background-color: #f5d182; color: #2a1e1e;">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <span class="font-medium text-gray-900">{{ __('dashboard.edit_profile') }}</span>
                    </div>
                    <i class="fas fa-chevron-left text-gray-400"></i>
                </a>
                
                <a href="{{ route('designer.portfolio.index') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-full" style="background-color: #3a2e2e; color: #ffde9f;">
                            <i class="fas fa-images"></i>
                        </div>
                        <span class="font-medium text-gray-900">{{ __('dashboard.manage_portfolio') }}</span>
                    </div>
                    <i class="fas fa-chevron-left text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Floating Add Button -->
    <div class="fixed bottom-6 left-6 z-50">
        <a href="{{ route('designer.appointments.dashboard') }}" class="text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center gap-3" style="background-color: #2a1e1e;">
            <i class="fas fa-calendar-plus text-xl"></i>
            <span class="hidden sm:block font-medium">عرض المواعيد</span>
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    // Enable real-time polling for new appointments
    Livewire.on('appointment-claimed', (appointmentId) => {
        console.log('Appointment claimed:', appointmentId);
        // The component will automatically refresh
    });

    Livewire.on('appointment-passed', (appointmentId) => {
        console.log('Appointment passed:', appointmentId);
        // The component will automatically refresh
    });

    Livewire.on('appointment-already-claimed', () => {
        showNotification('{{ __('dashboard.appointment_already_claimed') }}', 'warning');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Appointments Chart
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    const periodSelect = document.getElementById('periodSelect');
    const totalAppointmentsElement = document.getElementById('totalAppointments');
    const completedAppointmentsElement = document.getElementById('completedAppointments');
    let appointmentsChart = null;
    
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
                                return 'المواعيد: ' + new Intl.NumberFormat('ar-SA').format(context.parsed.y);
                            } else {
                                return 'المكتملة: ' + new Intl.NumberFormat('ar-SA').format(context.parsed.y);
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
                        maxTicksLimit: 12
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
    
    // Initialize chart with sample data
    function initializeChart(data) {
        if (appointmentsChart) {
            appointmentsChart.destroy();
        }
        
        appointmentsChart = new Chart(appointmentsCtx, {
            ...chartConfig,
            data: {
                labels: data.map(item => item.period),
                datasets: [{
                    label: 'المواعيد',
                    data: data.map(item => item.appointments),
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
                    label: 'المكتملة',
                    data: data.map(item => item.completed),
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
                    yAxisID: 'y'
                }]
            }
        });
    }
    
    // Initialize with sample data
    const sampleData = [
        { period: 'اليوم', appointments: {{ $stats['today_appointments'] ?? 0 }}, completed: {{ $stats['today_completed'] ?? 0 }} },
        { period: 'أمس', appointments: {{ $stats['yesterday_appointments'] ?? 0 }}, completed: {{ $stats['yesterday_completed'] ?? 0 }} },
        { period: 'قبل يومين', appointments: {{ $stats['day_before_appointments'] ?? 0 }}, completed: {{ $stats['day_before_completed'] ?? 0 }} },
        { period: 'قبل 3 أيام', appointments: {{ $stats['three_days_ago_appointments'] ?? 0 }}, completed: {{ $stats['three_days_ago_completed'] ?? 0 }} },
        { period: 'قبل 4 أيام', appointments: {{ $stats['four_days_ago_appointments'] ?? 0 }}, completed: {{ $stats['four_days_ago_completed'] ?? 0 }} },
        { period: 'قبل 5 أيام', appointments: {{ $stats['five_days_ago_appointments'] ?? 0 }}, completed: {{ $stats['five_days_ago_completed'] ?? 0 }} },
        { period: 'قبل أسبوع', appointments: {{ $stats['week_ago_appointments'] ?? 0 }}, completed: {{ $stats['week_ago_completed'] ?? 0 }} }
    ];
    initializeChart(sampleData);
    
    // Handle period change
    periodSelect.addEventListener('change', function() {
        // Here you would typically fetch new data based on the selected period
        // For now, we'll just show a loading state
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
