@extends('designer.layouts.app')

@section('pageTitle', __('dashboard.upcoming_appointments'))
@section('title', __('dashboard.upcoming_appointments'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('dashboard.upcoming_appointments') }}</h1>
            <p class="text-gray-600">{{ __('dashboard.your_upcoming_appointments') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('designer.appointments.dashboard') }}" 
               class="btn btn-secondary">
                <i class="fas fa-hand-paper"></i>
                <span>{{ __('dashboard.new_appointments') }}</span>
            </a>
            <a href="{{ route('designer.appointments.index') }}" 
               class="btn btn-secondary">
                <i class="fas fa-list"></i>
                <span>{{ __('dashboard.all_appointments') }}</span>
            </a>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-gray-400"></i>
                    <span class="text-sm font-medium text-gray-700">{{ __('dashboard.filter_by') }}:</span>
                </div>
                <select class="border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" 
                        onchange="filterAppointments(this.value)">
                    <option value="all">{{ __('dashboard.all_upcoming') }}</option>
                    <option value="tomorrow">{{ __('dashboard.tomorrow') }}</option>
                    <option value="this_week">{{ __('dashboard.this_week') }}</option>
                    <option value="next_week">{{ __('dashboard.next_week') }}</option>
                    <option value="this_month">{{ __('dashboard.this_month') }}</option>
                </select>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i class="fas fa-calendar text-gray-400"></i>
                <span>{{ __('dashboard.total_upcoming') }}: <strong>{{ $upcomingAppointments->count() }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.upcoming_appointments') }}</h3>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium text-green-600">{{ __('dashboard.live') }}</span>
            </div>
        </div>
        
        @if($upcomingAppointments->count() > 0)
            <div class="space-y-4">
                @foreach($upcomingAppointments as $appointment)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-green-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}</h4>
                                        <span class="badge badge-{{ $appointment->status }}">
                                            {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-day text-gray-400"></i>
                                            <span>{{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : 'غير محدد' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clock text-gray-400"></i>
                                            <span>{{ $appointment->appointment_time ? $appointment->appointment_time->format('H:i') : 'غير محدد' }}</span>
                                        </div>
                                        @if($appointment->duration_minutes)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-hourglass-half text-gray-400"></i>
                                                <span>{{ $appointment->duration_minutes }} {{ __('dashboard.minutes') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if($appointment->notes)
                                        <div class="mt-2 p-2 bg-gray-50 rounded text-sm text-gray-600">
                                            <i class="fas fa-sticky-note text-gray-400 mr-1"></i>
                                            {{ $appointment->notes }}
                                        </div>
                                    @endif
                                    @if($appointment->order)
                                        <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                                            <i class="fas fa-shopping-cart text-gray-400"></i>
                                            <span>{{ __('dashboard.order_total') }}: {{ number_format($appointment->order->total, 2) }} ر.س</span>
                                            <span class="text-gray-400">•</span>
                                            <span>{{ $appointment->order->items->count() }} {{ __('dashboard.items') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($appointment->status === 'pending')
                                    <form action="{{ route('designer.appointments.accept', $appointment) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors hover:shadow-sm"
                                                style="background-color: #2a1e1e;"
                                                onclick="return confirm('{{ __('dashboard.confirm_accept_appointment') }}')">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ __('dashboard.accept') }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('designer.appointments.reject', $appointment) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded-lg transition-colors hover:bg-gray-300"
                                                onclick="return confirm('{{ __('dashboard.confirm_reject_appointment') }}')">
                                            <i class="fas fa-times mr-1"></i>
                                            {{ __('dashboard.reject') }}
                                        </button>
                                    </form>
                                @elseif(in_array($appointment->status, ['accepted', 'confirmed']))
                                    <form action="{{ route('designer.appointments.complete', $appointment) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg transition-colors hover:bg-blue-700"
                                                onclick="return confirm('{{ __('dashboard.confirm_complete_appointment') }}')">
                                            <i class="fas fa-flag-checkered mr-1"></i>
                                            {{ __('dashboard.complete') }}
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('designer.appointments.show', $appointment) }}" 
                                   class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ __('dashboard.view') }}
                                </a>
                                
                                @if($appointment->order)
                                    <a href="{{ route('designer.orders.edit', $appointment) }}" 
                                       class="px-4 py-2 text-sm font-medium text-yellow-600 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        {{ __('dashboard.edit_order') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-alt text-4xl text-gray-400"></i>
                </div>
                <p class="text-xl font-semibold text-gray-700 mb-2">{{ __('dashboard.no_upcoming_appointments') }}</p>
                <p class="text-gray-500 mb-6">{{ __('dashboard.no_upcoming_appointments_description') }}</p>
                <a href="{{ route('designer.appointments.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white transition-colors"
                   style="background-color: #2a1e1e;">
                    <i class="fas fa-hand-paper mr-2"></i>
                    {{ __('dashboard.claim_new_appointments') }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function filterAppointments(filter) {
    // This would typically make an AJAX request to filter appointments
    // For now, we'll just show all appointments
    console.log('Filtering by:', filter);
    
    // You can implement AJAX filtering here
    // Example:
    // fetch(`/designer/appointments/upcoming?filter=${filter}`)
    //     .then(response => response.json())
    //     .then(data => {
    //         // Update the appointments list
    //     });
}
</script>
@endsection
