@extends('admin.layouts.app')

@section('pageTitle', __('admin.appointments_management'))
@section('title', __('admin.appointments_management'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.appointments_management') }}</h1>
            <p class="text-gray-600">{{ __('admin.manage_appointments_platform') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.appointments.index', ['status' => 'pending']) }}" 
               class="btn {{ request()->get('status') === 'pending' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-clock"></i>
                <span>{{ __('admin.pending_appointments') }}</span>
            </a>
            <a href="{{ route('admin.appointments.index', ['status' => 'scheduled']) }}" 
               class="btn {{ request()->get('status') === 'scheduled' ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-calendar-check"></i>
                <span>{{ __('admin.scheduled_appointments') }}</span>
            </a>
            <a href="{{ route('admin.appointments.index') }}" 
               class="btn {{ !request()->get('status') ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fas fa-list"></i>
                <span>{{ __('admin.all_appointments') }}</span>
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
                            <th>{{ __('admin.appointment_id') }}</th>
                            <th>{{ __('admin.customer') }}</th>
                            <th>{{ __('admin.designer') }}</th>
                            <th>{{ __('admin.appointment_date') }}</th>
                            <th>{{ __('admin.status') }}</th>
                            <th>{{ __('admin.order_total') }}</th>
                            <th>{{ __('admin.actions') }}</th>
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
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $appointment->designer->name ?? __('admin.no_designer') }}
                                        </div>
                                        @if($appointment->designer)
                                            <div class="text-sm text-gray-500">{{ $appointment->designer->email }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : __('admin.not_scheduled') }}
                                        </div>
                                        @if($appointment->appointment_time)
                                            <div class="text-sm text-gray-500">{{ $appointment->appointment_time }}</div>
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
                                            {{ $appointment->order->items->count() }} {{ __('admin.items') }}
                                        </div>
                                    @else
                                        <span class="text-gray-500">{{ __('admin.no_order') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.appointments.show', $appointment) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.appointments.edit', $appointment) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($appointment->status === 'pending')
                                            <form action="{{ route('admin.appointments.destroy', $appointment) }}" 
                                                  method="POST" class="inline" 
                                                  onsubmit="return confirm('{{ __('admin.confirm_delete_appointment') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl mb-4" style="color: var(--brand-yellow);"></i>
                                        <p class="text-gray-500">{{ __('admin.no_appointments_found') }}</p>
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

