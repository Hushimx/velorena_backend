@extends('designer.layouts.app')

@section('pageTitle', __('dashboard.upcoming_appointments'))
@section('title', __('dashboard.upcoming_appointments'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('dashboard.upcoming_appointments') }}</h1>
            <p class="text-gray-600">{{ __('dashboard.manage_your_upcoming_schedule') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('designer.appointments.index', ['status' => 'pending']) }}" 
               class="btn btn-secondary">
                <i class="fas fa-clock"></i>
                <span>{{ __('dashboard.pending_appointments') }}</span>
            </a>
            <a href="{{ route('designer.appointments.index', ['status' => 'accepted']) }}" 
               class="btn btn-secondary">
                <i class="fas fa-calendar-check"></i>
                <span>{{ __('dashboard.accepted_appointments') }}</span>
            </a>
            <a href="{{ route('designer.appointments.index') }}" 
               class="btn btn-primary">
                <i class="fas fa-list"></i>
                <span>{{ __('dashboard.all_appointments') }}</span>
            </a>
            <button onclick="refreshAppointments()" 
                    class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i>
                <span>{{ __('dashboard.refresh') }}</span>
            </button>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-filter text-gray-400"></i>
                        <span class="text-sm font-medium text-gray-700">{{ __('dashboard.filter_by') }}:</span>
                    </div>
                    <select class="border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" 
                            onchange="filterAppointments(this.value)" id="filter-select">
                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>{{ __('dashboard.all_upcoming') }}</option>
                        <option value="tomorrow" {{ request('filter') == 'tomorrow' ? 'selected' : '' }}>{{ __('dashboard.tomorrow') }}</option>
                        <option value="this_week" {{ request('filter') == 'this_week' ? 'selected' : '' }}>{{ __('dashboard.this_week') }}</option>
                        <option value="next_week" {{ request('filter') == 'next_week' ? 'selected' : '' }}>{{ __('dashboard.next_week') }}</option>
                        <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>{{ __('dashboard.this_month') }}</option>
                    </select>
                    <div class="flex items-center gap-2">
                        <label for="status-filter" class="text-sm font-medium text-gray-700">{{ __('dashboard.status') }}:</label>
                        <select id="status-filter" class="border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                onchange="filterByStatus(this.value)">
                            <option value="">{{ __('dashboard.all_statuses') }}</option>
                            <option value="pending">{{ __('dashboard.pending') }}</option>
                            <option value="accepted">{{ __('dashboard.accepted') }}</option>
                            <option value="confirmed">{{ __('dashboard.confirmed') }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-medium text-green-600">{{ __('dashboard.live') }}</span>
                    </div>
                    <span class="text-sm text-gray-600">
                        {{ __('dashboard.total_upcoming') }}: <strong>{{ $upcomingAppointments->total() }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Information (if in debug mode) -->
    @if(config('app.debug') && isset($debug))
        <div class="card">
            <div class="card-body bg-blue-50">
                <h4 class="text-sm font-medium text-blue-800 mb-2">
                    <i class="fas fa-bug mr-1"></i>Debug Information
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 text-xs text-blue-700">
                    <div>
                        <span class="font-medium">Designer ID:</span><br>
                        {{ $debug['designer_id'] ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Total Appointments:</span><br>
                        {{ $debug['total_appointments'] ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Future Appointments:</span><br>
                        {{ $debug['total_future_appointments'] ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Current Time:</span><br>
                        {{ $debug['current_time'] ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Results:</span><br>
                        {{ $debug['query_results_count'] ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Appointments Table -->
    <div class="card">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('dashboard.appointment_id') }}</th>
                            <th>{{ __('dashboard.customer') }}</th>
                            <th>{{ __('dashboard.appointment_date') }}</th>
                            <th>{{ __('dashboard.status') }}</th>
                            <th>{{ __('dashboard.order_total') }}</th>
                            <th>{{ __('dashboard.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingAppointments as $appointment)
                            <tr class="hover:bg-gray-50 transition-colors" id="appointment-{{ $appointment->id }}">
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold" 
                                             style="background-color: var(--brand-brown);">
                                            #{{ $appointment->id }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $appointment->user->full_name ?? $appointment->user->email }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $appointment->user->email }}</div>
                                        @if($appointment->user->phone)
                                            <div class="text-sm text-gray-500">{{ $appointment->user->phone }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $appointment->appointment_date ? 
                                                (is_string($appointment->appointment_date) ? 
                                                    \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : 
                                                    $appointment->appointment_date->format('Y-m-d')) : 
                                                __('dashboard.not_scheduled') }}
                                        </div>
                                        @if($appointment->appointment_time)
                                            <div class="text-sm text-gray-500">{{ $appointment->appointment_time }}</div>
                                        @endif
                                        @if($appointment->duration_minutes)
                                            <div class="text-xs text-gray-400">{{ $appointment->duration_minutes }} {{ __('dashboard.minutes') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $appointment->status }}">
                                        {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($appointment->order)
                                        <div class="font-medium text-gray-900">
                                            {{ number_format($appointment->order->total, 2) }} ر.س
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $appointment->order->items->count() }} {{ __('dashboard.items') }}
                                        </div>
                                    @else
                                        <span class="text-gray-500">{{ __('dashboard.no_order') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('designer.appointments.show', $appointment) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors" 
                                           title="{{ __('dashboard.view_details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($appointment->status === 'pending')
                                            <form action="{{ route('designer.appointments.accept', $appointment) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-800 transition-colors"
                                                        title="{{ __('dashboard.accept_appointment') }}"
                                                        onclick="return confirm('{{ __('dashboard.confirm_accept_appointment') }}')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('designer.appointments.reject', $appointment) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 transition-colors"
                                                        title="{{ __('dashboard.reject_appointment') }}"
                                                        onclick="return confirm('{{ __('dashboard.confirm_reject_appointment') }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(in_array($appointment->status, ['accepted', 'confirmed']))
                                            <form action="{{ route('designer.appointments.complete', $appointment) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-purple-600 hover:text-purple-800 transition-colors"
                                                        title="{{ __('dashboard.complete_appointment') }}"
                                                        onclick="return confirm('{{ __('dashboard.confirm_complete_appointment') }}')">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($appointment->order)
                                            <a href="{{ route('designer.orders.edit', $appointment) }}" 
                                               class="text-yellow-600 hover:text-yellow-800 transition-colors"
                                               title="{{ __('dashboard.edit_order') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        <!-- Quick Add Products Button -->
                                        @if($appointment->order)
                                            <button onclick="openQuickAddModal({{ $appointment->id }}, {{ $appointment->order->id }})" 
                                                    class="text-indigo-600 hover:text-indigo-800 transition-colors"
                                                    title="{{ __('dashboard.quick_add_products') }}">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl mb-4" style="color: var(--brand-yellow);"></i>
                                        <p class="text-gray-500 mb-4">{{ __('dashboard.no_upcoming_appointments') }}</p>
                                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                            <a href="{{ route('designer.appointments.dashboard') }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-hand-paper mr-2"></i>
                                                {{ __('dashboard.claim_new_appointments') }}
                                            </a>
                                            <a href="{{ route('designer.appointments.index') }}" 
                                               class="btn btn-secondary">
                                                <i class="fas fa-list mr-2"></i>
                                                {{ __('dashboard.view_all_appointments') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($upcomingAppointments->hasPages())
                <div class="pagination mt-4">
                    {{ $upcomingAppointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Add Products Modal -->
<div id="quickAddModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-plus-circle mr-2"></i>
                    {{ __('dashboard.quick_add_products') }}
                </h3>
                <button onclick="closeQuickAddModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="quickAddContent">
                <!-- Content will be loaded dynamically -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">{{ __('dashboard.loading_products') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentAppointmentId = null;
let currentOrderId = null;

function filterAppointments(filter) {
    const currentUrl = new URL(window.location);
    if (filter === 'all') {
        currentUrl.searchParams.delete('filter');
    } else {
        currentUrl.searchParams.set('filter', filter);
    }
    window.location.href = currentUrl.toString();
}

function filterByStatus(status) {
    const currentUrl = new URL(window.location);
    if (status === '') {
        currentUrl.searchParams.delete('status');
    } else {
        currentUrl.searchParams.set('status', status);
    }
    window.location.href = currentUrl.toString();
}

function refreshAppointments() {
    const refreshBtn = event.target.closest('button');
    const originalHTML = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __("dashboard.refreshing") }}';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

function openQuickAddModal(appointmentId, orderId) {
    currentAppointmentId = appointmentId;
    currentOrderId = orderId;
    
    document.getElementById('quickAddModal').classList.remove('hidden');
    loadQuickAddContent();
}

function closeQuickAddModal() {
    document.getElementById('quickAddModal').classList.add('hidden');
    currentAppointmentId = null;
    currentOrderId = null;
}

function loadQuickAddContent() {
    // This would typically make an AJAX request to get popular products
    // For now, we'll show a simple interface
    document.getElementById('quickAddContent').innerHTML = `
        <div class="space-y-4">
            <div class="border rounded-lg p-4">
                <h4 class="font-medium mb-2">{{ __('dashboard.popular_products') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border rounded p-3 hover:bg-gray-50 cursor-pointer" onclick="quickAddProduct(1)">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Basic Design Service</span>
                            <span class="text-green-600">$50.00</span>
                        </div>
                        <p class="text-sm text-gray-500">Standard design consultation</p>
                    </div>
                    <div class="border rounded p-3 hover:bg-gray-50 cursor-pointer" onclick="quickAddProduct(2)">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Premium Design Package</span>
                            <span class="text-green-600">$150.00</span>
                        </div>
                        <p class="text-sm text-gray-500">Complete design solution</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeQuickAddModal()" class="btn btn-secondary">{{ __('dashboard.cancel') }}</button>
                <a href="/designer/appointments/'+currentAppointmentId+'/edit-order" class="btn btn-primary">
                    <i class="fas fa-edit mr-2"></i>{{ __('dashboard.full_editor') }}
                </a>
            </div>
        </div>
    `;
}

function quickAddProduct(productId) {
    // This would make an AJAX request to add the product to the order
    alert('Product would be added to order #' + currentOrderId);
}

// Auto-refresh functionality
let refreshInterval;

function startAutoRefresh() {
    refreshInterval = setInterval(() => {
        if (!document.hidden) {
            window.location.reload();
        }
    }, 300000); // 5 minutes
}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        clearInterval(refreshInterval);
    } else {
        startAutoRefresh();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    startAutoRefresh();
    
    // Add row animations
    const rows = document.querySelectorAll('tr[id^="appointment-"]');
    rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.05}s`;
        row.style.animation = 'fadeInUp 0.3s ease-out forwards';
    });
});

window.addEventListener('beforeunload', () => {
    clearInterval(refreshInterval);
});
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection