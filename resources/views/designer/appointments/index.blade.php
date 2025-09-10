@extends('designer.layouts.app')

@section('pageTitle', __('dashboard.appointments'))
@section('title', __('dashboard.appointments'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('dashboard.appointments') }}</h1>
            <p class="text-gray-600">{{ __('dashboard.manage_your_appointments') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('designer.appointments.index', ['status' => 'pending']) }}" 
               class="btn {{ request()->get('status') === 'pending' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-clock"></i>
                <span>{{ __('dashboard.pending_appointments') }}</span>
            </a>
            <a href="{{ route('designer.appointments.index', ['status' => 'accepted']) }}" 
               class="btn {{ request()->get('status') === 'accepted' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-calendar-check"></i>
                <span>{{ __('dashboard.accepted_appointments') }}</span>
            </a>
            <a href="{{ route('designer.appointments.index', ['status' => 'completed']) }}" 
               class="btn {{ request()->get('status') === 'completed' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-check-circle"></i>
                <span>{{ __('dashboard.completed_appointments') }}</span>
            </a>
            <a href="{{ route('designer.appointments.index') }}" 
               class="btn {{ !request()->get('status') ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-list"></i>
                <span>{{ __('dashboard.all_appointments') }}</span>
            </a>
        </div>
    </div>

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
                        @forelse($appointments as $appointment)
                            <tr>
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
                                            {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : __('dashboard.not_scheduled') }}
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
                                                        class="text-blue-600 hover:text-blue-800 transition-colors"
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl mb-4" style="color: var(--brand-yellow);"></i>
                                        <p class="text-gray-500">{{ __('dashboard.no_appointments_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($appointments->hasPages())
                <div class="pagination">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection