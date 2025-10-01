@extends('admin.layouts.app')

@section('pageTitle', __('admin.create_appointment'))
@section('title', __('admin.create_appointment'))

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
                <h1 class="text-2xl font-bold text-gray-900">{{ __('admin.create_appointment') }}</h1>
                <p class="text-gray-600">{{ __('admin.create_new_appointment') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.back') }}</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Create Form -->
            <div class="lg:col-span-2">
                <div class="card bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">{{ __('admin.create_appointment_details') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.appointments.store') }}" method="POST">
                            @csrf

                            <div class="space-y-6">
                                <!-- User Selection -->
                                <div>
                                    <label for="user_id" class="form-label">
                                        {{ __('admin.user') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select name="user_id" id="user_id" required
                                        class="form-control @error('user_id') border-red-500 @enderror">
                                        <option value="">{{ __('admin.select_user') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Designer Selection -->
                                <div>
                                    <label for="designer_id" class="form-label">
                                        {{ __('admin.designer') }}
                                    </label>
                                    <select name="designer_id" id="designer_id"
                                        class="form-control @error('designer_id') border-red-500 @enderror">
                                        <option value="">{{ __('admin.select_designer') }}</option>
                                        @foreach ($designers as $designer)
                                            <option value="{{ $designer->id }}"
                                                {{ old('designer_id') == $designer->id ? 'selected' : '' }}>
                                                {{ $designer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('designer_id')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Order Selection -->
                                <div>
                                    <label for="order_id" class="form-label">
                                        {{ __('admin.order') }}
                                    </label>
                                    <select name="order_id" id="order_id"
                                        class="form-control @error('order_id') border-red-500 @enderror">
                                        <option value="">{{ __('admin.select_order') }}</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}"
                                                {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                {{ __('admin.order') }} #{{ $order->order_number }} -
                                                {{ $order->user->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('order_id')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Appointment Date -->
                                <div>
                                    <label for="appointment_date" class="form-label">
                                        {{ __('admin.appointment_date') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="appointment_date" id="appointment_date" required
                                        value="{{ old('appointment_date') }}"
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="form-control @error('appointment_date') border-red-500 @enderror">
                                    @error('appointment_date')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Appointment Time -->
                                <div>
                                    <label for="appointment_time" class="form-label">
                                        {{ __('admin.appointment_time') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" name="appointment_time" id="appointment_time" required
                                        value="{{ old('appointment_time') }}"
                                        class="form-control @error('appointment_time') border-red-500 @enderror">
                                    @error('appointment_time')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label for="duration_minutes" class="form-label">
                                        {{ __('admin.duration_minutes') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select name="duration_minutes" id="duration_minutes" required
                                        class="form-control @error('duration_minutes') border-red-500 @enderror">
                                        <option value="15" {{ old('duration_minutes') == '15' ? 'selected' : '' }}>15
                                            {{ __('admin.minutes') }}</option>
                                        <option value="30" {{ old('duration_minutes') == '30' ? 'selected' : '' }}>30
                                            {{ __('admin.minutes') }}</option>
                                        <option value="45" {{ old('duration_minutes') == '45' ? 'selected' : '' }}>45
                                            {{ __('admin.minutes') }}</option>
                                        <option value="60" {{ old('duration_minutes') == '60' ? 'selected' : '' }}>60
                                            {{ __('admin.minutes') }}</option>
                                        <option value="90" {{ old('duration_minutes') == '90' ? 'selected' : '' }}>90
                                            {{ __('admin.minutes') }}</option>
                                        <option value="120" {{ old('duration_minutes') == '120' ? 'selected' : '' }}>120
                                            {{ __('admin.minutes') }}</option>
                                    </select>
                                    @error('duration_minutes')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Customer Notes -->
                                <div>
                                    <label for="notes" class="form-label">{{ __('admin.customer_notes') }}</label>
                                    <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') border-red-500 @enderror"
                                        placeholder="{{ __('admin.appointment_notes_placeholder') }}">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Order Notes -->
                                <div>
                                    <label for="order_notes" class="form-label">{{ __('admin.order_notes') }}</label>
                                    <textarea name="order_notes" id="order_notes" rows="4"
                                        class="form-control @error('order_notes') border-red-500 @enderror"
                                        placeholder="{{ __('admin.order_notes_placeholder') }}">{{ old('order_notes') }}</textarea>
                                    @error('order_notes')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                                        {{ __('admin.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        <span>{{ __('admin.create_appointment') }}</span>
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
                        <h3 class="text-lg font-semibold text-white">{{ __('admin.new_appointment_info') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">{{ __('admin.status') }}</label>
                                <p class="text-gray-900">{{ __('admin.appointment_pending') }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.appointment_type') }}</label>
                                <p class="text-gray-900">{{ __('admin.new_appointment') }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.created_by') }}</label>
                                <p class="text-gray-900">{{ Auth::guard('admin')->user()->name ?? __('admin.admin') }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.created_at') }}</label>
                                <p class="text-gray-900">{{ now()->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="card mt-6 bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">{{ __('admin.help') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('admin.required_fields') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('admin.required_fields_description') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('admin.optional_fields') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('admin.optional_fields_description') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full bg-yellow-500 mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ __('admin.appointment_timing') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('admin.appointment_timing_description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
