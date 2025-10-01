@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Edit Availability Slot')
@section('title', trans('admin.edit_availability_slot'))

@section('content')
    @push('styles')
        <style>
            .validation-message {
                margin-top: 0.25rem;
                font-size: 0.875rem;
                color: #ef4444;
            }

            .form-label-dark,
            .text-dark {
                color: #454545;
            }
        </style>
    @endpush
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('admin.edit_availability_slot') }}
                </h1>
                <p class="text-gray-600">{{ trans('admin.edit_availability_slot_description') }}</p>
            </div>
            <a href="{{ route('admin.availability-slots.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>{{ trans('admin.back_to_availability_slots') }}</span>
            </a>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.availability-slots.update', $availabilitySlot) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <!-- Day of Week -->
                        <div>
                            <label for="day_of_week" class="form-label form-label-dark">
                                {{ trans('admin.day_of_week') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="day_of_week" id="day_of_week" required
                                class="form-control @error('day_of_week')" style="border-color: #ef4444;" @enderror>
                            <option value="">{{ trans('admin.select_day') }}
                                </option>
                                <option value="monday"
                                    {{ old('day_of_week', $availabilitySlot->day_of_week) == 'monday' ? 'selected' : '' }}>
                                    {{ trans('admin.days.monday') }}
                                </option>
                                <option value="tuesday"
                                    {{ old('day_of_week', $availabilitySlot->day_of_week) == 'tuesday' ? 'selected' : '' }}>
                                    {{ trans('admin.days.tuesday') }}
                                </option>
                                <option value="wednesday"
                                    {{ old('day_of_week', $availabilitySlot->day_of_week) == 'wednesday' ? 'selected' : '' }}>
                                    {{ trans('admin.days.wednesday') }}
                                </option>
                                <option value="thursday"
                                    {{ old('day_of_week', $availabilitySlot->day_of_week) == 'thursday' ? 'selected' : '' }}>
                                    {{ trans('admin.days.thursday') }}
                                </option>
                                <option value="friday"
                                    {{ old('day_of_week', $availabilitySlot->day_of_week) == 'friday' ? 'selected' : '' }}>
                                    {{ trans('admin.days.friday') }}
                                </option>
                                <option value="saturday"
                                    {{ old('day_of_week', $availabilitySlot->day_of_week) == 'saturday' ? 'selected' : '' }}>
                                    {{ trans('admin.days.saturday') }}
                                </option>
                                <option value="sunday"
                                    {{ old('day_of_week', $availabilitySlot->day_of_week) == 'sunday' ? 'selected' : '' }}>
                                    {{ trans('admin.days.sunday') }}
                                </option>
                            </select>
                            @error('day_of_week')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slot Duration -->
                        <div>
                            <label for="slot_duration_minutes" class="form-label form-label-dark">
                                {{ trans('admin.slot_duration') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="slot_duration_minutes" id="slot_duration_minutes" required
                                class="form-control @error('slot_duration_minutes')" style="border-color: #ef4444;" @enderror>
                                <option value="">{{ trans('admin.select_duration') }}
                                </option>
                                <option value="15"
                                    {{ old('slot_duration_minutes', $availabilitySlot->slot_duration_minutes) == 15 ? 'selected' : '' }}>
                                    15 {{ trans('admin.minutes') }}</option>
                                <option value="30"
                                    {{ old('slot_duration_minutes', $availabilitySlot->slot_duration_minutes) == 30 ? 'selected' : '' }}>
                                    30 {{ trans('admin.minutes') }}</option>
                                <option value="45"
                                    {{ old('slot_duration_minutes', $availabilitySlot->slot_duration_minutes) == 45 ? 'selected' : '' }}>
                                    45 {{ trans('admin.minutes') }}</option>
                                <option value="60"
                                    {{ old('slot_duration_minutes', $availabilitySlot->slot_duration_minutes) == 60 ? 'selected' : '' }}>
                                    60 {{ trans('admin.minutes') }}</option>
                                <option value="90"
                                    {{ old('slot_duration_minutes', $availabilitySlot->slot_duration_minutes) == 90 ? 'selected' : '' }}>
                                    90 {{ trans('admin.minutes') }}</option>
                                <option value="120"
                                    {{ old('slot_duration_minutes', $availabilitySlot->slot_duration_minutes) == 120 ? 'selected' : '' }}>
                                    120 {{ trans('admin.minutes') }}</option>
                            </select>
                            @error('slot_duration_minutes')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="form-label form-label-dark">
                                {{ trans('admin.start_time') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="time" name="start_time" id="start_time" class="form-control"
                                value="{{ old('start_time', $availabilitySlot->formatted_start_time) }}" required
                                @error('start_time')" style="border-color: #ef4444;" @enderror />
                            @error('start_time')
                                <p class="validation-message">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="form-label form-label-dark">
                                {{ trans('admin.end_time') }} <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="time" name="end_time" id="end_time"
                                value="{{ old('end_time', $availabilitySlot->formatted_end_time) }}" required
                                class="form-control" @error('end_time')" style="border-color: #ef4444;" @enderror>
                            @error('end_time')
                                <p class="validation-message">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="form-label form-label-dark">{{ trans('admin.status') }}</label>
                            <div style="display: flex; align-items: center;">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $availabilitySlot->is_active) ? 'checked' : '' }}
                                    style="width: 1rem; height: 1rem; margin-left: 0.5rem;">
                                <label for="is_active" style="font-size: 0.875rem; color: var(--text-primary);">
                                    <span class="text-dark">{{ trans('admin.active') }}</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div style="grid-column: 1 / -1;">
                            <label for="notes" class="form-label form-label-dark">{{ trans('admin.notes') }}</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control"
                                @error('notes') style="border-color: #ef4444;" @enderror placeholder="{{ trans('admin.enter_notes_optional') }}">
                                {{ old('notes', $availabilitySlot->notes) }}
                            </textarea>
                            @error('notes')
                                <p class="validation-message">{{ $message }}</p>
                            @enderror
                            <p style="validation-message">
                                {{ trans('admin.notes_help') }}
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                        <a href="{{ route('admin.availability-slots.index') }}" class="btn btn-secondary">
                            {{ trans('admin.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            {{ trans('admin.update_availability_slot') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
