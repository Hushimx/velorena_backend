@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Availability Slots')
@section('title', trans('admin.availability_slots'))

@section('content')
    <div style="margin-bottom: 2rem;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">{{ trans('admin.availability_slots') }}</h1>
                <p style="color: var(--text-secondary); font-size: 1rem;">{{ trans('admin.manage_availability_slots') }}</p>
            </div>
            <a href="{{ route('admin.availability-slots.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i>
                <span>{{ trans('admin.add_availability_slot') }}</span>
            </a>
        </div>

        <!-- Availability Slots Table -->
        <div class="card">
            <div style="overflow-x: auto;">
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
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 2rem; height: 2rem; border-radius: 50%; background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center; margin-left: 0.75rem;">
                                            <i class="fas fa-calendar-day" style="color: #3b82f6; font-size: 0.875rem;"></i>
                                        </div>
                                        <div style="font-weight: 500; color: var(--text-primary);">
                                            {{ trans('admin.days.' . $slot->day_of_week) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="color: var(--text-primary);">
                                        {{ $slot->formatted_start_time }} - {{ $slot->formatted_end_time }}
                                    </div>
                                </td>
                                <td>
                                    <div style="color: var(--text-primary);">
                                        {{ $slot->slot_duration_minutes }} {{ trans('admin.minutes') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="color: var(--text-primary);">
                                        {{ $slot->total_slots }} {{ trans('admin.slots') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $slot->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $slot->is_active ? trans('admin.active') : trans('admin.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div style="color: var(--text-primary); max-width: 12rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $slot->notes ?? trans('admin.no_notes') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.availability-slots.show', $slot) }}"
                                            style="color: #3b82f6; text-decoration: none; transition: color 0.2s;"
                                            title="{{ trans('admin.view') }}"
                                            onmouseover="this.style.color='#1d4ed8'" 
                                            onmouseout="this.style.color='#3b82f6'">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.availability-slots.edit', $slot) }}"
                                            style="color: #6366f1; text-decoration: none; transition: color 0.2s;"
                                            title="{{ trans('admin.edit') }}"
                                            onmouseover="this.style.color='#4f46e5'" 
                                            onmouseout="this.style.color='#6366f1'">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.availability-slots.toggle-status', $slot) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                style="background: none; border: none; color: {{ $slot->is_active ? '#f59e0b' : '#10b981' }}; cursor: pointer; transition: color 0.2s;"
                                                title="{{ $slot->is_active ? trans('admin.deactivate') : trans('admin.activate') }}"
                                                onmouseover="this.style.color='{{ $slot->is_active ? '#d97706' : '#059669' }}'" 
                                                onmouseout="this.style.color='{{ $slot->is_active ? '#f59e0b' : '#10b981' }}'">
                                                <i class="fas fa-{{ $slot->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.availability-slots.destroy', $slot) }}" method="POST" style="display: inline;"
                                            onsubmit="return confirm('{{ trans('admin.confirm_delete_availability_slot') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background: none; border: none; color: #ef4444; cursor: pointer; transition: color 0.2s;"
                                                title="{{ trans('admin.delete') }}"
                                                onmouseover="this.style.color='#dc2626'" 
                                                onmouseout="this.style.color='#ef4444'">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 3rem;">
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                        <i class="fas fa-calendar-times" style="color: var(--text-muted); font-size: 2.5rem; margin-bottom: 1rem;"></i>
                                        <h3 style="font-size: 1.125rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">{{ trans('admin.no_availability_slots') }}</h3>
                                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">{{ trans('admin.no_availability_slots_description') }}</p>
                                        <a href="{{ route('admin.availability-slots.create') }}" class="btn btn-success">
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
                <div style="padding: 1rem; border-top: 1px solid var(--border-light);">
                    {{ $availabilitySlots->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
