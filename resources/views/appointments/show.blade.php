@extends('layouts.app')

@section('pageTitle', 'Appointment Details')
@section('title', 'Appointment Details')

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 fw-bold text-dark">Appointment Details</h1>
                        <p class="text-muted mb-0">View your consultation details</p>
                    </div>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <!-- Card Header -->
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="fas fa-calendar-check text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-semibold">Consultation Information</h3>
                                <p class="text-muted mb-0">Appointment #{{ $appointment->id }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Status Banner -->
                        <div class="alert alert-light mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span
                                        class="badge fs-6
                                    @if ($appointment->status === 'pending') bg-warning
                                    @elseif($appointment->status === 'accepted') bg-success
                                    @elseif($appointment->status === 'completed') bg-info
                                    @elseif($appointment->status === 'cancelled') bg-danger
                                    @elseif($appointment->status === 'rejected') bg-danger
                                    @else bg-secondary @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">
                                        @if ($appointment->status === 'pending')
                                            Appointment Pending
                                        @elseif($appointment->status === 'accepted')
                                            Appointment Confirmed
                                        @elseif($appointment->status === 'completed')
                                            Consultation Completed
                                        @elseif($appointment->status === 'cancelled')
                                            Appointment Cancelled
                                        @elseif($appointment->status === 'rejected')
                                            Appointment Rejected
                                        @else
                                            Appointment Status
                                        @endif
                                    </h6>
                                    <p class="mb-0 text-muted">
                                        @if ($appointment->status === 'pending')
                                            Your appointment is waiting for designer assignment
                                        @elseif($appointment->status === 'accepted')
                                            Your appointment has been confirmed by the designer
                                        @elseif($appointment->status === 'completed')
                                            Your consultation has been completed successfully
                                        @elseif($appointment->status === 'cancelled')
                                            This appointment has been cancelled
                                        @elseif($appointment->status === 'rejected')
                                            This appointment has been rejected by the designer
                                        @else
                                            Appointment status information
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Main Information Grid -->
                        <div class="row g-4 mb-4">
                            <!-- Designer Information -->
                            <div class="col-lg-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex align-items-center mb-3">
                                            <i class="fas fa-user text-primary me-2"></i>Designer Information
                                        </h5>
                                        @if ($appointment->designer)
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                                    <i class="fas fa-user text-primary fa-lg"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $appointment->designer->name }}</h6>
                                                    @if ($appointment->designer->specialization)
                                                        <p class="mb-1 text-muted">
                                                            {{ $appointment->designer->specialization }}</p>
                                                    @endif
                                                    <p class="mb-0 small text-muted">{{ $appointment->designer->email }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                                    <i class="fas fa-clock text-warning fa-lg"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold text-warning">Pending Assignment</h6>
                                                    <p class="mb-0 text-muted">A designer will be assigned to your
                                                        appointment soon</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div class="col-lg-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex align-items-center mb-3">
                                            <i class="fas fa-calendar-alt text-success me-2"></i>Appointment Details
                                        </h5>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted fw-medium">Date</small>
                                                    <span
                                                        class="fw-semibold">{{ $appointment->formatted_date ?? 'Not set' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted fw-medium">Time</small>
                                                    <span
                                                        class="fw-semibold">{{ $appointment->formatted_time ?? 'Not set' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted fw-medium">Duration</small>
                                                    <span class="fw-semibold">{{ $appointment->duration_minutes }}
                                                        minutes</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted fw-medium">End Time</small>
                                                    <span
                                                        class="fw-semibold">{{ $appointment->formatted_end_time ?? 'Not set' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        @if ($appointment->notes)
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title d-flex align-items-center mb-3">
                                        <i class="fas fa-sticky-note text-warning me-2"></i>Your Notes
                                    </h5>
                                    <div class="bg-white rounded p-3">
                                        <p class="mb-0">{{ $appointment->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Designer Response -->
                        @if ($appointment->designer_notes)
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title d-flex align-items-center mb-3">
                                        <i class="fas fa-comment text-info me-2"></i>Designer Response
                                    </h5>
                                    <div class="bg-info bg-opacity-10 rounded p-3">
                                        <p class="mb-0">{{ $appointment->designer_notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Timeline -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title d-flex align-items-center mb-3">
                                    <i class="fas fa-history text-secondary me-2"></i>Timeline
                                </h5>
                                <div class="bg-white rounded p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Booked:</span>
                                                <span
                                                    class="fw-medium small">{{ $appointment->created_at->format('M d, Y H:i') }}</span>
                                            </div>
                                        </div>
                                        @if ($appointment->accepted_at)
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-success small">Accepted:</span>
                                                    <span
                                                        class="fw-medium small">{{ $appointment->accepted_at->format('M d, Y H:i') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($appointment->rejected_at)
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-danger small">Rejected:</span>
                                                    <span
                                                        class="fw-medium small">{{ $appointment->rejected_at->format('M d, Y H:i') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($appointment->completed_at)
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-info small">Completed:</span>
                                                    <span
                                                        class="fw-medium small">{{ $appointment->completed_at->format('M d, Y H:i') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            @if ($appointment->canBeCancelled())
                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                        class="btn btn-outline-danger">
                                        <i class="fas fa-times me-2"></i>Cancel Appointment
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>

                            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Book New Appointment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
