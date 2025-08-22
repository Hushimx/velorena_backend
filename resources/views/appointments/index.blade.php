@extends('layouts.app')

@section('pageTitle', 'My Appointments')
@section('title', 'My Appointments')

@section('content')
    <div class="container">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="card-title mb-1">My Appointments</h2>
                                <p class="card-text opacity-75 mb-0">Manage your scheduled consultations with expert
                                    designers</p>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
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

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Appointments: <span
                                class="fw-semibold text-dark">{{ $appointments->total() }}</span></small>
                    </div>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Book New Appointment
                    </a>
                </div>
            </div>
        </div>

        <!-- Appointments List -->
        @if ($appointments->count() > 0)
            <div class="row">
                @foreach ($appointments as $appointment)
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                            <i class="fas fa-calendar text-primary fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1 fw-semibold">{{ $appointment->formatted_date }}</h5>
                                            <p class="text-muted mb-0">{{ $appointment->formatted_time }} -
                                                {{ $appointment->formatted_end_time }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
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
                                        <!-- Action Buttons -->
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('appointments.show', $appointment) }}">
                                                        <i class="fas fa-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                @if ($appointment->canBeCancelled())
                                                    <li>
                                                        <form action="{{ route('appointments.cancel', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                                class="dropdown-item text-danger">
                                                                <i class="fas fa-times me-2"></i>Cancel
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Appointment Details -->
                                <div class="row mb-3">
                                    <!-- Designer Info -->
                                    <div class="col-md-4">
                                        <h6 class="text-muted mb-1">Designer</h6>
                                        @if ($appointment->designer)
                                            <p class="mb-0 fw-medium">{{ $appointment->designer->name }}</p>
                                            <p class="mb-0 small text-muted">{{ $appointment->designer->email }}</p>
                                        @else
                                            <p class="mb-0 text-warning fw-medium">Pending Assignment</p>
                                        @endif
                                    </div>

                                    <!-- Duration -->
                                    <div class="col-md-4">
                                        <h6 class="text-muted mb-1">Duration</h6>
                                        <p class="mb-0 fw-medium">{{ $appointment->duration_minutes }} minutes</p>
                                    </div>

                                    <!-- Booked Date -->
                                    <div class="col-md-4">
                                        <h6 class="text-muted mb-1">Booked</h6>
                                        <p class="mb-0 fw-medium">{{ $appointment->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if ($appointment->notes)
                                    <div class="alert alert-light mb-3">
                                        <h6 class="alert-heading mb-2">
                                            <i class="fas fa-sticky-note me-2"></i>Your Notes
                                        </h6>
                                        <p class="mb-0 small">{{ Str::limit($appointment->notes, 100) }}</p>
                                    </div>
                                @endif

                                <!-- Designer Response -->
                                @if ($appointment->designer_notes)
                                    <div class="alert alert-info mb-0">
                                        <h6 class="alert-heading mb-2">
                                            <i class="fas fa-comment me-2"></i>Designer Response
                                        </h6>
                                        <p class="mb-0 small">{{ Str::limit($appointment->designer_notes, 100) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    {{ $appointments->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-calendar-times fa-2x text-muted"></i>
                            </div>
                            <h4 class="fw-semibold mb-2">No appointments yet</h4>
                            <p class="text-muted mb-4">You haven't booked any appointments yet. Start your journey by
                                scheduling your first consultation.</p>
                            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Book Your First Appointment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
