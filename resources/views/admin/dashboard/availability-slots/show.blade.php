@extends('admin.layouts.app')

@section('pageTitle', 'Admin | View Availability Slot')
@section('title', trans('admin.view_availability_slot'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('admin.view_availability_slot') }}
                </h1>
                <p class="text-gray-600">{{ trans('admin.availability_slot_details') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.availability-slots.edit', $availabilitySlot) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    <span>{{ trans('admin.edit') }}</span>
                </a>
                <a href="{{ route('admin.availability-slots.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ trans('admin.back_to_availability_slots') }}</span>
                </a>
            </div>
        </div>

        <!-- Availability Slot Details -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-white">
                    {{ trans('admin.availability_slot_information') }}</h3>
            </div>

            <div class="card-body">
                <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Day of Week -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2 text-white">{{ trans('admin.day_of_week') }}</dt>
                        <dd class="flex items-center text-gray-900">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center ml-3">
                                <i class="fas fa-calendar-day text-blue-600 text-sm"></i>
                            </div>
                            {{ trans('admin.days.' . $availabilitySlot->day_of_week) }}
                        </dd>
                    </div>

                    <!-- Time Range -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">{{ trans('admin.time_range') }}</dt>
                        <dd class="flex items-center text-gray-900">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center ml-3">
                                <i class="fas fa-clock text-green-600 text-sm"></i>
                            </div>
                            {{ $availabilitySlot->formatted_start_time }} - {{ $availabilitySlot->formatted_end_time }}
                        </dd>
                    </div>

                    <!-- Slot Duration -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">{{ trans('admin.slot_duration') }}</dt>
                        <dd class="flex items-center text-gray-900">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center ml-3">
                                <i class="fas fa-stopwatch text-purple-600 text-sm"></i>
                            </div>
                            {{ $availabilitySlot->slot_duration_minutes }} {{ trans('admin.minutes') }}
                        </dd>
                    </div>

                    <!-- Total Slots -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">{{ trans('admin.total_slots') }}</dt>
                        <dd class="flex items-center text-gray-900">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center ml-3">
                                <i class="fas fa-list-ol text-yellow-600 text-sm"></i>
                            </div>
                            {{ $availabilitySlot->total_slots }} {{ trans('admin.slots') }}
                        </dd>
                    </div>

                    <!-- Status -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">{{ trans('admin.status') }}</dt>
                        <dd>
                            <span class="badge {{ $availabilitySlot->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $availabilitySlot->is_active ? trans('admin.active') : trans('admin.inactive') }}
                            </span>
                        </dd>
                    </div>

                    <!-- Created At -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">{{ trans('admin.created_at') }}</dt>
                        <dd class="text-gray-900">
                            {{ $availabilitySlot->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>

                    <!-- Notes -->
                    @if ($availabilitySlot->notes)
                        <div class="md:col-span-2 lg:col-span-3">
                            <dt class="text-sm font-medium text-gray-500 mb-2">{{ trans('admin.notes') }}</dt>
                            <dd>
                                <div class="bg-gray-50 rounded-lg p-4 text-gray-900">
                                    {{ $availabilitySlot->notes }}
                                </div>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Generated Time Slots Preview -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-white">{{ trans('admin.generated_time_slots') }}</h3>
                <p class="text-sm text-white mt-1">{{ trans('admin.time_slots_preview_description') }}</p>
            </div>

            <div class="card-body">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach ($availabilitySlot->generateTimeSlots() as $timeSlot)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                            <span class="text-sm font-medium text-blue-800">{{ $timeSlot }}</span>
                        </div>
                    @endforeach
                </div>

                @if (empty($availabilitySlot->generateTimeSlots()))
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-600">{{ trans('admin.no_time_slots_generated') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <form action="{{ route('admin.availability-slots.toggle-status', $availabilitySlot) }}" method="POST"
                class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $availabilitySlot->is_active ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas fa-{{ $availabilitySlot->is_active ? 'pause' : 'play' }}"></i>
                    <span>{{ $availabilitySlot->is_active ? trans('admin.deactivate') : trans('admin.activate') }}</span>
                </button>
            </form>

            <form action="{{ route('admin.availability-slots.destroy', $availabilitySlot) }}" method="POST" class="inline"
                onsubmit="return confirm('{{ trans('admin.confirm_delete_availability_slot') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    <span>{{ trans('admin.delete') }}</span>
                </button>
            </form>
        </div>
    </div>
@endsection
