@extends('admin.layouts.app')

@section('pageTitle', __('admin.edit_appointment'))
@section('title', __('admin.edit_appointment'))

@section('content')
    <style>
        .form-label {
            color: #1f2937 !important;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.edit_appointment') }}</h1>
                <p class="text-gray-600">{{ __('admin.appointment_id') }}: #{{ $appointment->id }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-secondary">
                    <i class="fas fa-eye"></i>
                    <span>{{ __('admin.view_appointment') }}</span>
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.back') }}</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Edit Form -->
            <div class="lg:col-span-2">
                <div class="card bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">{{ __('admin.edit_appointment_details') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="form-label">{{ __('admin.status') }}</label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') border-red-500 @enderror" required>
                                        <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>
                                            {{ __('status.appointment.pending') }}
                                        </option>
                                        <option value="scheduled"
                                            {{ $appointment->status === 'scheduled' ? 'selected' : '' }}>
                                            {{ __('status.appointment.scheduled') }}
                                        </option>
                                        <option value="completed"
                                            {{ $appointment->status === 'completed' ? 'selected' : '' }}>
                                            {{ __('status.appointment.completed') }}
                                        </option>
                                        <option value="cancelled"
                                            {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>
                                            {{ __('status.appointment.cancelled') }}
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Appointment Date -->
                                <div>
                                    <label for="appointment_date"
                                        class="form-label">{{ __('admin.appointment_date') }}</label>
                                    <input type="date" name="appointment_date" id="appointment_date"
                                        value="{{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : '' }}"
                                        class="form-control @error('appointment_date') border-red-500 @enderror">
                                    @error('appointment_date')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Appointment Time -->
                                <div>
                                    <label for="appointment_time"
                                        class="form-label">{{ __('admin.appointment_time') }}</label>
                                    <input type="time" name="appointment_time" id="appointment_time"
                                        value="{{ $appointment->appointment_time }}"
                                        class="form-control @error('appointment_time') border-red-500 @enderror">
                                    @error('appointment_time')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Customer Notes -->
                                <div>
                                    <label for="notes" class="form-label">{{ __('admin.customer_notes') }}</label>
                                    <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') border-red-500 @enderror"
                                        placeholder="{{ __('admin.enter_customer_notes') }}">{{ old('notes', $appointment->notes) }}</textarea>
                                    @error('notes')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Designer Notes -->
                                <div>
                                    <label for="designer_notes" class="form-label">{{ __('admin.designer_notes') }}</label>
                                    <textarea name="designer_notes" id="designer_notes" rows="4"
                                        class="form-control @error('designer_notes') border-red-500 @enderror"
                                        placeholder="{{ __('admin.enter_designer_notes') }}">{{ old('designer_notes', $appointment->designer_notes) }}</textarea>
                                    @error('designer_notes')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.appointments.show', $appointment) }}"
                                        class="btn btn-secondary">
                                        {{ __('admin.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        <span>{{ __('admin.save_changes') }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Appointment Information -->
            <div class="lg:col-span-1">
                <div class="card bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">
                            {{ __('admin.appointment_info') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">{{ __('admin.customer') }}</label>
                                <p class="text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}
                                </p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.designer') }}</label>
                                <p class="text-gray-900">{{ $appointment->designer->name ?? __('admin.no_designer') }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.created_at') }}</label>
                                <p class="text-gray-900">{{ $appointment->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            @if ($appointment->order)
                                <div>
                                    <label class="form-label">{{ __('admin.order_total') }}</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format($appointment->order->total, 2) }} ر.س
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status History -->
                <div class="card mt-6 bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">
                            {{ __('admin.status_history') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            @if ($appointment->created_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ __('admin.created') }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $appointment->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($appointment->accepted_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ __('admin.accepted') }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $appointment->accepted_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($appointment->completed_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-green-600"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ __('admin.completed') }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $appointment->completed_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($appointment->cancelled_at)
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ __('admin.cancelled') }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $appointment->cancelled_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
