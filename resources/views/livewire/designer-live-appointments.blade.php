<div wire:poll.5s>
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Available Appointments -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-clock text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-medium">{{ trans('dashboard.available_to_claim') }}</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $pendingCount }}</h3>
                            <small class="text-muted">{{ trans('dashboard.unassigned_appointments') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-day text-success fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-medium">{{ trans('dashboard.todays_schedule') }}</h6>
                            <h3 class="mb-0 fw-bold text-success">{{ $todayCount }}</h3>
                            <small class="text-muted">{{ trans('dashboard.confirmed_appointments') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-week text-info fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-medium">{{ trans('dashboard.upcoming') }}</h6>
                            <h3 class="mb-0 fw-bold text-info">{{ $upcomingAppointments->count() }}</h3>
                            <small class="text-muted">{{ trans('dashboard.future_appointments') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Status -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-broadcast-tower text-warning fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1 fw-medium">{{ trans('dashboard.system_status') }}</h6>
                            <h3 class="mb-0 fw-bold text-success">
                                <i class="fas fa-circle text-success"
                                    style="font-size: 0.5rem; animation: pulse 2s infinite;"></i>
                                {{ trans('dashboard.real_time') }}
                            </h3>
                            <small class="text-muted">{{ trans('dashboard.real_time_updates') }}</small>
                            <div class="text-muted small mt-1">Last update: {{ $lastUpdate }}</div>
                        </div>
                        <div class="ms-auto">
                            <button wire:click="manualRefresh" wire:loading.attr="disabled" wire:target="manualRefresh"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-sync-alt" wire:loading.remove wire:target="manualRefresh"></i>
                                <i class="fas fa-spinner fa-spin" wire:loading wire:target="manualRefresh"></i>
                            </button>
                            <button wire:click="testEvent" class="btn btn-sm btn-outline-warning ms-1">
                                <i class="fas fa-bell"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Available Appointments -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-semibold text-primary">
                        <i class="fas fa-hand-paper me-2"></i>{{ trans('dashboard.available_appointments') }}
                    </h5>
                    <span class="badge bg-primary fs-6">{{ $pendingCount }} {{ trans('dashboard.available') }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($unassignedAppointments as $appointment)
                        <div class="border-bottom p-4" wire:key="pending-{{ $appointment->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $appointment->user->full_name }}</h6>
                                            <p class="text-muted small mb-0">{{ $appointment->formatted_date }} at
                                                {{ $appointment->formatted_time }}</p>
                                        </div>
                                    </div>
                                    @if ($appointment->notes)
                                        <div class="alert alert-light py-2 px-3 mb-2">
                                            <small class="text-secondary">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                {{ Str::limit($appointment->notes, 80) }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <div class="d-grid gap-2">
                                        <button wire:click="acceptAppointment({{ $appointment->id }})"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-hand-paper me-1"></i>{{ trans('dashboard.claim') }}
                                        </button>
                                        <button wire:click="rejectAppointment({{ $appointment->id }})"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-times me-1"></i>{{ trans('dashboard.pass') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-calendar-check fa-2x text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted mb-2">{{ trans('dashboard.no_available_appointments') }}
                            </h6>
                            <p class="text-muted small mb-0">{{ trans('dashboard.all_appointments_claimed') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-semibold text-success">
                        <i class="fas fa-calendar-day me-2"></i>{{ trans('dashboard.todays_appointments') }}
                    </h5>
                    <span class="badge bg-success fs-6">{{ $todayCount }} {{ trans('dashboard.today') }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($todayAppointments as $appointment)
                        <div class="border-bottom p-4" wire:key="today-{{ $appointment->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-user text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $appointment->user->full_name }}</h6>
                                            <p class="text-muted small mb-0">{{ $appointment->formatted_time }} -
                                                {{ $appointment->formatted_end_time }}</p>
                                        </div>
                                    </div>
                                    <span class="badge bg-success">{{ ucfirst($appointment->status) }}</span>
                                </div>
                                @if ($appointment->isAccepted())
                                    <div class="ms-3">
                                        <button wire:click="completeAppointment({{ $appointment->id }})"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-check-double me-1"></i>{{ trans('dashboard.complete') }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-calendar-times fa-2x text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted mb-2">{{ trans('dashboard.no_appointments_today') }}
                            </h6>
                            <p class="text-muted small mb-0">{{ trans('dashboard.free_schedule_today') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-semibold text-info">
                        <i class="fas fa-calendar-week me-2"></i>{{ trans('dashboard.upcoming_appointments') }}
                    </h5>
                    <span class="badge bg-info fs-6">{{ $upcomingAppointments->count() }}
                        {{ trans('dashboard.upcoming_count') }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="border-bottom p-4" wire:key="upcoming-{{ $appointment->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-user text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-1">{{ $appointment->user->full_name }}</h6>
                                        <p class="text-muted small mb-1">{{ $appointment->formatted_date }} at
                                            {{ $appointment->formatted_time }}</p>
                                        <span class="badge bg-info">{{ ucfirst($appointment->status) }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <p class="small mb-0 fw-medium">{{ $appointment->duration_minutes }} min</p>
                                    <p class="small text-muted mb-0">{{ $appointment->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-calendar-plus fa-2x text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-muted mb-2">{{ trans('dashboard.no_upcoming_appointments') }}
                            </h6>
                            <p class="text-muted small mb-0">{{ trans('dashboard.no_future_appointments') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


</div>
