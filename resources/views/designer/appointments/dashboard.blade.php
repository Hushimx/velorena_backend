@extends('designer.layouts.app')

@section('pageTitle', __('dashboard.new_appointments'))
@section('title', __('dashboard.new_appointments'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('dashboard.new_appointments') }}</h1>
            <p class="text-gray-600">{{ __('dashboard.claim_new_appointments') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('designer.appointments.index') }}" 
               class="btn btn-secondary">
                <i class="fas fa-list"></i>
                <span>{{ __('dashboard.all_appointments') }}</span>
            </a>
            <a href="{{ route('designer.appointments.upcoming') }}" 
               class="btn btn-secondary">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ __('dashboard.upcoming_appointments') }}</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.total_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalAppointments }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.pending_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingCount }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.completed_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $completedAppointments }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('dashboard.cancelled_appointments') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $cancelledCount }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Live New Appointments Section -->
    @livewire('designer.new-appointments')

    <!-- Today's Appointments -->
    @if($todayAppointments->count() > 0)
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.todays_appointments') }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $todayAppointments->count() }} {{ __('dashboard.appointments') }}
                </span>
            </div>
            
            <div class="space-y-3">
                @foreach($todayAppointments as $appointment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->appointment_time ? $appointment->appointment_time->format('H:i') : 'غير محدد' }}
                                    @if($appointment->duration_minutes)
                                        - {{ $appointment->duration_minutes }} {{ __('dashboard.minutes') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge badge-{{ $appointment->status }}">
                                {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                            </span>
                            <a href="{{ route('designer.appointments.show', $appointment) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Upcoming Appointments -->
    @if($upcomingAppointments->count() > 0)
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.upcoming_appointments') }}</h3>
                <a href="{{ route('designer.appointments.upcoming') }}" 
                   class="text-sm font-medium hover:underline flex items-center gap-2"
                   style="color: #2a1e1e;">
                    <span>{{ __('dashboard.view_all') }}</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            
            <div class="space-y-3">
                @foreach($upcomingAppointments as $appointment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : 'غير محدد' }}
                                    @if($appointment->appointment_time)
                                        - {{ $appointment->appointment_time->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge badge-{{ $appointment->status }}">
                                {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                            </span>
                            <a href="{{ route('designer.appointments.show', $appointment) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Pending Appointments -->
    @if($pendingAppointments->count() > 0)
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.pending_appointments') }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingAppointments->count() }} {{ __('dashboard.pending') }}
                </span>
            </div>
            
            <div class="space-y-3">
                @foreach($pendingAppointments as $appointment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : 'غير محدد' }}
                                    @if($appointment->appointment_time)
                                        - {{ $appointment->appointment_time->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('designer.appointments.accept', $appointment) }}" 
                                  method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-1.5 text-xs font-medium text-white rounded-lg transition-colors hover:shadow-sm"
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
                                        class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-200 rounded-lg transition-colors hover:bg-gray-300"
                                        onclick="return confirm('{{ __('dashboard.confirm_reject_appointment') }}')">
                                    <i class="fas fa-times mr-1"></i>
                                    {{ __('dashboard.reject') }}
                                </button>
                            </form>
                            
                            <a href="{{ route('designer.appointments.show', $appointment) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection