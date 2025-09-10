@extends('admin.layouts.app')

@section('pageTitle', __('admin.appointment_details'))
@section('title', __('admin.appointment_details'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.appointment_details') }}</h1>
            <p class="text-gray-600">{{ __('admin.appointment_id') }}: #{{ $appointment->id }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                <span>{{ __('admin.edit_appointment') }}</span>
            </a>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('admin.back') }}</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Appointment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.appointment_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('admin.status') }}</label>
                            <div>
                                <span class="badge badge-{{ $appointment->status }}">
                                    {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.appointment_date') }}</label>
                            <p class="text-gray-900">
                                {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : __('admin.not_scheduled') }}
                            </p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.appointment_time') }}</label>
                            <p class="text-gray-900">
                                {{ $appointment->appointment_time ?: __('admin.not_scheduled') }}
                            </p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.created_at') }}</label>
                            <p class="text-gray-900">{{ $appointment->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.customer_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('admin.customer_name') }}</label>
                            <p class="text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.email') }}</label>
                            <p class="text-gray-900">{{ $appointment->user->email }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.phone') }}</label>
                            <p class="text-gray-900">{{ $appointment->user->phone ?? __('admin.not_provided') }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('admin.registration_date') }}</label>
                            <p class="text-gray-900">{{ $appointment->user->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Designer Information -->
            @if($appointment->designer)
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.designer_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('admin.designer_name') }}</label>
                                <p class="text-gray-900">{{ $appointment->designer->name }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.email') }}</label>
                                <p class="text-gray-900">{{ $appointment->designer->email }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.phone') }}</label>
                                <p class="text-gray-900">{{ $appointment->designer->phone ?? __('admin.not_provided') }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.specialization') }}</label>
                                <p class="text-gray-900">{{ $appointment->designer->specialization ?? __('admin.not_specified') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($appointment->notes || $appointment->designer_notes)
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.notes') }}</h3>
                    </div>
                    <div class="card-body">
                        @if($appointment->notes)
                            <div class="mb-4">
                                <label class="form-label">{{ __('admin.customer_notes') }}</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->notes }}</p>
                            </div>
                        @endif
                        @if($appointment->designer_notes)
                            <div>
                                <label class="form-label">{{ __('admin.designer_notes') }}</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $appointment->designer_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Information -->
        <div class="lg:col-span-1">
            @if($appointment->order)
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold" style="color: var(--brand-brown);">{{ __('admin.order_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">{{ __('admin.order_number') }}</label>
                                <p class="text-gray-900">#{{ $appointment->order->order_number }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.order_status') }}</label>
                                <div>
                                    <span class="badge badge-{{ $appointment->order->status }}">
                                        {{ __('status.order.' . $appointment->order->status) ?: $appointment->order->status }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.order_total') }}</label>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ number_format($appointment->order->total, 2) }} ر.س
                                </p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.order_date') }}</label>
                                <p class="text-gray-900">{{ $appointment->order->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        @if($appointment->order->items->count() > 0)
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-3">{{ __('admin.order_items') }}</h4>
                                <div class="space-y-2">
                                    @foreach($appointment->order->items as $item)
                                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-500">{{ __('admin.quantity') }}: {{ $item->quantity }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-gray-900">{{ number_format($item->price, 2) }} ر.س</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-6">
                            <a href="{{ route('admin.orders.show', $appointment->order) }}" class="btn btn-primary w-full">
                                <i class="fas fa-eye"></i>
                                <span>{{ __('admin.view_order') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart text-4xl mb-4" style="color: var(--brand-yellow);"></i>
                        <p class="text-gray-500">{{ __('admin.no_order_associated') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
