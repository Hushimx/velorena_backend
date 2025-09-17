@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Availability Slots')
@section('title', trans('admin.availability_slots'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('admin.availability_slots') }}</h1>
                <p class="text-gray-600">{{ trans('admin.manage_availability_slots') }}</p>
            </div>
            <a href="{{ route('admin.availability-slots.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span>{{ trans('admin.add_availability_slot') }}</span>
            </a>
        </div>

        <!-- Availability Slots Table -->
        <div class="card">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ trans('admin.day_of_week') }}</th>
                            <th>{{ trans('admin.time_range') }}</th>
                            <th>{{ trans('admin.slot_duration') }}</th>
                            <th>{{ trans('admin.total_slots') }}</th>
                            <th>{{ trans('admin.status') }}</th>
                            <th>{{ trans('admin.notes') }}</th>
                            <th>{{ trans('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($availabilitySlots as $slot)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center ml-3">
                                            <i class="fas fa-calendar-day text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="font-medium text-gray-900">
                                            {{ trans('admin.days.' . $slot->day_of_week) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-gray-900">
                                        {{ $slot->formatted_start_time }} - {{ $slot->formatted_end_time }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-gray-900">
                                        {{ $slot->slot_duration_minutes }} {{ trans('admin.minutes') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-gray-900">
                                        {{ $slot->total_slots }} {{ trans('admin.slots') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $slot->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $slot->is_active ? trans('admin.active') : trans('admin.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-gray-900 max-w-xs truncate">
                                        {{ $slot->notes ?? trans('admin.no_notes') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.availability-slots.show', $slot) }}"
                                            class="text-blue-600 hover:text-blue-800 transition-colors"
                                            title="{{ trans('admin.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.availability-slots.edit', $slot) }}"
                                            class="text-indigo-600 hover:text-indigo-800 transition-colors"
                                            title="{{ trans('admin.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.availability-slots.toggle-status', $slot) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-{{ $slot->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $slot->is_active ? 'yellow' : 'green' }}-800 transition-colors"
                                                title="{{ $slot->is_active ? trans('admin.deactivate') : trans('admin.activate') }}">
                                                <i class="fas fa-{{ $slot->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.availability-slots.destroy', $slot) }}" method="POST" class="inline"
                                            onsubmit="return confirm('{{ trans('admin.confirm_delete_availability_slot') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition-colors"
                                                title="{{ trans('admin.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ trans('admin.no_availability_slots') }}</h3>
                                        <p class="text-gray-600 mb-4">{{ trans('admin.no_availability_slots_description') }}</p>
                                        <a href="{{ route('admin.availability-slots.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i>
                                            <span>{{ trans('admin.add_availability_slot') }}</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($availabilitySlots->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $availabilitySlots->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
