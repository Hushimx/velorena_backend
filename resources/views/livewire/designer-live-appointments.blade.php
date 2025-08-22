<div wire:poll.5s>
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Available Appointments -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-clock text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Available to Claim</h6>
                            <h3 class="mb-0 fw-bold">{{ $pendingCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-day text-success fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Today's Schedule</h6>
                            <h3 class="mb-0 fw-bold">{{ $todayCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-week text-info fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Upcoming</h6>
                            <h3 class="mb-0 fw-bold">{{ $upcomingAppointments->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Status -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-broadcast-tower text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">System Status</h6>
                            <h3 class="mb-0 fw-bold text-success">Live</h3>
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
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Available Appointments</h5>
                    <span class="badge bg-primary">{{ $pendingCount }} Available</span>
                </div>
                <div class="card-body">
                    @forelse($unassignedAppointments as $appointment)
                        <div class="border rounded p-3 mb-3 bg-light" wire:key="pending-{{ $appointment->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">{{ $appointment->user->name }}</h6>
                                    <p class="text-muted small mb-2">{{ $appointment->formatted_date }} at
                                        {{ $appointment->formatted_time }}</p>
                                    @if ($appointment->notes)
                                        <p class="small text-secondary mb-2">{{ Str::limit($appointment->notes, 50) }}
                                        </p>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <div class="d-grid gap-1">
                                        <button wire:click="acceptAppointment({{ $appointment->id }})"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-hand-paper me-1"></i>Claim
                                        </button>
                                        <button wire:click="rejectAppointment({{ $appointment->id }})"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-times me-1"></i>Pass
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No available appointments to claim</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Today's Schedule</h5>
                    <span class="badge bg-success">{{ $todayCount }} Today</span>
                </div>
                <div class="card-body">
                    @forelse($todayAppointments as $appointment)
                        <div class="border rounded p-3 mb-3 bg-light" wire:key="today-{{ $appointment->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">{{ $appointment->user->name }}</h6>
                                    <p class="text-muted small mb-2">{{ $appointment->formatted_time }} -
                                        {{ $appointment->formatted_end_time }}</p>
                                    <span
                                        class="badge
                                        @if ($appointment->status === 'pending') bg-warning
                                        @elseif($appointment->status === 'accepted') bg-success
                                        @elseif($appointment->status === 'completed') bg-info
                                        @else bg-secondary @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                @if ($appointment->isAccepted())
                                    <div class="ms-3">
                                        <button wire:click="completeAppointment({{ $appointment->id }})"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-check-double me-1"></i>Complete
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No appointments scheduled for today</p>
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
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Upcoming Appointments</h5>
                    <i class="fas fa-calendar-week text-info"></i>
                </div>
                <div class="card-body">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="border rounded p-3 mb-3 bg-light" wire:key="upcoming-{{ $appointment->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-semibold mb-1">{{ $appointment->user->name }}</h6>
                                    <p class="text-muted small mb-1">{{ $appointment->formatted_date }} at
                                        {{ $appointment->formatted_time }}</p>
                                    <span class="badge bg-info">{{ ucfirst($appointment->status) }}</span>
                                </div>
                                <div class="text-end">
                                    <p class="small mb-0 fw-medium">{{ $appointment->duration_minutes }} min</p>
                                    <p class="small text-muted mb-0">{{ $appointment->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-plus fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('appointment-accepted', (appointmentId) => {
            showNotification('Appointment accepted successfully!', 'success');
        });

        Livewire.on('appointment-rejected', (appointmentId) => {
            showNotification('Appointment rejected successfully!', 'success');
        });

        Livewire.on('appointment-completed', (appointmentId) => {
            showNotification('Appointment marked as completed!', 'success');
        });

        Livewire.on('appointments-refreshed', () => {
            console.log('Appointments refreshed');
        });
    });

    function showNotification(message, type = 'info') {
        // Create Bootstrap toast
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'primary'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '1055';
            document.body.appendChild(toastContainer);
        }

        // Add toast
        const toastElement = document.createElement('div');
        toastElement.innerHTML = toastHtml;
        toastContainer.appendChild(toastElement.firstElementChild);

        // Initialize and show toast
        const toast = new bootstrap.Toast(toastContainer.lastElementChild);
        toast.show();

        // Remove toast element after it's hidden
        toastContainer.lastElementChild.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
</script>
