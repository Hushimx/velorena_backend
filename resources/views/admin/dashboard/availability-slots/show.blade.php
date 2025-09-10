@extends('admin.layouts.app')

@section('pageTitle', 'Admin | View Availability Slot')
@section('title', trans('admin.view_availability_slot'))

@section('content')
    <div style="margin-bottom: 2rem;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">{{ trans('admin.view_availability_slot') }}</h1>
                <p style="color: var(--text-secondary); font-size: 1rem;">{{ trans('admin.availability_slot_details') }}</p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
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
                <h3 style="font-size: 1.125rem; font-weight: 500; color: var(--text-primary);">{{ trans('admin.availability_slot_information') }}</h3>
            </div>
            
            <div class="card-body">
                <dl style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Day of Week -->
                    <div>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ trans('admin.day_of_week') }}</dt>
                        <dd style="display: flex; align-items: center; color: var(--text-primary);">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center; margin-left: 0.75rem;">
                                <i class="fas fa-calendar-day" style="color: #3b82f6; font-size: 0.875rem;"></i>
                            </div>
                            {{ trans('admin.days.' . $availabilitySlot->day_of_week) }}
                        </dd>
                    </div>

                    <!-- Time Range -->
                    <div>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ trans('admin.time_range') }}</dt>
                        <dd style="display: flex; align-items: center; color: var(--text-primary);">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center; margin-left: 0.75rem;">
                                <i class="fas fa-clock" style="color: #10b981; font-size: 0.875rem;"></i>
                            </div>
                            {{ $availabilitySlot->formatted_start_time }} - {{ $availabilitySlot->formatted_end_time }}
                        </dd>
                    </div>

                    <!-- Slot Duration -->
                    <div>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ trans('admin.slot_duration') }}</dt>
                        <dd style="display: flex; align-items: center; color: var(--text-primary);">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background: rgba(147, 51, 234, 0.1); display: flex; align-items: center; justify-content: center; margin-left: 0.75rem;">
                                <i class="fas fa-stopwatch" style="color: #9333ea; font-size: 0.875rem;"></i>
                            </div>
                            {{ $availabilitySlot->slot_duration_minutes }} {{ trans('admin.minutes') }}
                        </dd>
                    </div>

                    <!-- Total Slots -->
                    <div>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ trans('admin.total_slots') }}</dt>
                        <dd style="display: flex; align-items: center; color: var(--text-primary);">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center; margin-left: 0.75rem;">
                                <i class="fas fa-list-ol" style="color: #f59e0b; font-size: 0.875rem;"></i>
                            </div>
                            {{ $availabilitySlot->total_slots }} {{ trans('admin.slots') }}
                        </dd>
                    </div>

                    <!-- Status -->
                    <div>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ trans('admin.status') }}</dt>
                        <dd>
                            <span class="badge {{ $availabilitySlot->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $availabilitySlot->is_active ? trans('admin.active') : trans('admin.inactive') }}
                            </span>
                        </dd>
                    </div>

                    <!-- Created At -->
                    <div>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ trans('admin.created_at') }}</dt>
                        <dd style="color: var(--text-primary);">
                            {{ $availabilitySlot->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>

                    <!-- Notes -->
                    @if($availabilitySlot->notes)
                        <div style="grid-column: 1 / -1;">
                            <dt style="font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem;">{{ trans('admin.notes') }}</dt>
                            <dd>
                                <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.75rem; color: var(--text-primary);">
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
                <h3 style="font-size: 1.125rem; font-weight: 500; color: var(--text-primary);">{{ trans('admin.generated_time_slots') }}</h3>
                <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">{{ trans('admin.time_slots_preview_description') }}</p>
            </div>
            
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.5rem;">
                    @foreach($availabilitySlot->generateTimeSlots() as $timeSlot)
                        <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: var(--radius-md); padding: 0.75rem; text-align: center;">
                            <span style="font-size: 0.875rem; font-weight: 500; color: #1d4ed8;">{{ $timeSlot }}</span>
                        </div>
                    @endforeach
                </div>
                
                @if(empty($availabilitySlot->generateTimeSlots()))
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-exclamation-triangle" style="color: var(--text-muted); font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <p style="color: var(--text-secondary);">{{ trans('admin.no_time_slots_generated') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
            <form action="{{ route('admin.availability-slots.toggle-status', $availabilitySlot) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $availabilitySlot->is_active ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas fa-{{ $availabilitySlot->is_active ? 'pause' : 'play' }}"></i>
                    <span>{{ $availabilitySlot->is_active ? trans('admin.deactivate') : trans('admin.activate') }}</span>
                </button>
            </form>
            
            <form action="{{ route('admin.availability-slots.destroy', $availabilitySlot) }}" method="POST" style="display: inline;"
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
