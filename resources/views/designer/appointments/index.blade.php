@extends('designer.layouts.app')

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
                                <p class="card-text opacity-75 mb-0">Manage and view all your scheduled consultations with
                                    clients</p>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-medium">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label fw-medium">Date</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <a href="{{ route('designer.appointments.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
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
                                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                            <i class="fas fa-calendar text-success fa-lg"></i>
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
                                                        href="{{ route('designer.appointments.dashboard') }}">
                                                        <i class="fas fa-eye me-2"></i>View Details
                                                    </a>
                                                </li>
                                                @if ($appointment->canBeAccepted())
                                                    <li>
                                                        <form
                                                            action="{{ route('designer.appointments.accept', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check me-2"></i>Accept
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if ($appointment->canBeRejected())
                                                    <li>
                                                        <form
                                                            action="{{ route('designer.appointments.reject', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                onclick="return confirm('Are you sure you want to reject this appointment?')"
                                                                class="dropdown-item text-danger">
                                                                <i class="fas fa-times me-2"></i>Reject
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if ($appointment->canBeCompleted())
                                                    <li>
                                                        <form
                                                            action="{{ route('designer.appointments.complete', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-info">
                                                                <i class="fas fa-check-double me-2"></i>Complete
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client Information -->
                                <div class="alert alert-primary mb-3" role="alert">
                                    <h6 class="alert-heading mb-2">
                                        <i class="fas fa-user me-2"></i>Client Information
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1 fw-semibold">{{ $appointment->user->name }}</p>
                                            <p class="mb-0 small">{{ $appointment->user->email }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 small">Duration: {{ $appointment->duration_minutes }} minutes
                                            </p>
                                            <p class="mb-0 small">Booked: {{ $appointment->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client Notes -->
                                @if ($appointment->notes)
                                    <div class="alert alert-warning mb-3" role="alert">
                                        <h6 class="alert-heading mb-2">
                                            <i class="fas fa-sticky-note me-2"></i>Client Notes
                                        </h6>
                                        <p class="mb-0 small">{{ Str::limit($appointment->notes, 150) }}</p>
                                    </div>
                                @endif

                                <!-- Designer Response -->
                                @if ($appointment->designer_notes)
                                    <div class="alert alert-info mb-3" role="alert">
                                        <h6 class="alert-heading mb-2">
                                            <i class="fas fa-comment me-2"></i>Your Response
                                        </h6>
                                        <p class="mb-0 small">{{ Str::limit($appointment->designer_notes, 150) }}</p>
                                    </div>
                                @endif

                                <!-- Timeline -->
                                <div class="alert alert-light mb-0" role="alert">
                                    <h6 class="alert-heading mb-3">
                                        <i class="fas fa-history me-2"></i>Timeline
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Booked:</span>
                                                <span
                                                    class="fw-medium small">{{ $appointment->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        @if ($appointment->accepted_at)
                                            <div class="col-md-4">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-success small">Accepted:</span>
                                                    <span
                                                        class="fw-medium small">{{ $appointment->accepted_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($appointment->completed_at)
                                            <div class="col-md-4">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-info small">Completed:</span>
                                                    <span
                                                        class="fw-medium small">{{ $appointment->completed_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
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
                            <h4 class="fw-semibold mb-2">No appointments found</h4>
                            <p class="text-muted mb-4">You don't have any appointments matching your current filters. Try
                                adjusting your search criteria.</p>
                            <a href="{{ route('designer.appointments.index') }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>View All Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
