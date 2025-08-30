@extends('designer.layouts.app')

@section('pageTitle', trans('dashboard.my_appointments'))
@section('title', trans('dashboard.my_appointments'))

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient text-white border-0 shadow-lg"
                    style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="card-title mb-2 fw-bold">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    {{ trans('dashboard.my_appointments') }}
                                </h2>
                                <p class="card-text opacity-90 mb-0 fs-6">
                                    {{ trans('dashboard.manage_appointments') }}
                                </p>
                            </div>
                            <div class="col-md-4 text-center d-none d-md-block">
                                <i class="fas fa-calendar-alt fa-4x opacity-75"></i>
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
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2 fs-5"></i>
                        <div class="fw-medium">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                        <div class="fw-medium">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold text-primary">
                            <i class="fas fa-filter me-2"></i>{{ trans('dashboard.filter_appointments') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="status"
                                    class="form-label fw-medium">{{ trans('dashboard.appointment_status') }}</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">{{ trans('dashboard.all_statuses') }}</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                        {{ trans('dashboard.pending') }}</option>
                                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>
                                        {{ trans('dashboard.accepted') }}</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                        {{ trans('dashboard.completed') }}</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                        {{ trans('dashboard.rejected') }}</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                        {{ trans('dashboard.cancelled') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label fw-medium">{{ trans('dashboard.date') }}</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>{{ trans('dashboard.filter') }}
                                    </button>
                                    <a href="{{ route('designer.appointments.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>{{ trans('dashboard.clear') }}
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
                            <div class="card-body p-4">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
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
                                                        href="{{ route('designer.appointments.dashboard') }}">
                                                        <i
                                                            class="fas fa-eye me-2"></i>{{ trans('dashboard.view_details') }}
                                                    </a>
                                                </li>
                                                @if ($appointment->canBeAccepted())
                                                    <li>
                                                        <form
                                                            action="{{ route('designer.appointments.accept', $appointment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i
                                                                    class="fas fa-check me-2"></i>{{ trans('dashboard.accept') }}
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
                                                                onclick="return confirm('{{ trans('dashboard.confirm_cancel') }}')"
                                                                class="dropdown-item text-danger">
                                                                <i
                                                                    class="fas fa-times me-2"></i>{{ trans('dashboard.reject') }}
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
                                                                <i
                                                                    class="fas fa-check-double me-2"></i>{{ trans('dashboard.complete') }}
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
                                    <h6 class="alert-heading mb-3 fw-semibold">
                                        <i class="fas fa-user me-2"></i>{{ trans('dashboard.client_information') }}
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1 fw-semibold">{{ $appointment->user->full_name }}</p>
                                            <p class="mb-0 small text-muted">{{ $appointment->user->email }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 small fw-medium">{{ trans('dashboard.duration') }}:
                                                {{ $appointment->duration_minutes }}
                                                {{ trans('dashboard.minutes') }}</p>
                                            <p class="mb-0 small text-muted">{{ trans('dashboard.booked') }}:
                                                {{ $appointment->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Client Notes -->
                                @if ($appointment->notes)
                                    <div class="alert alert-warning mb-3" role="alert">
                                        <h6 class="alert-heading mb-2 fw-semibold">
                                            <i class="fas fa-sticky-note me-2"></i>{{ trans('dashboard.client_notes') }}
                                        </h6>
                                        <p class="mb-0 small">{{ Str::limit($appointment->notes, 150) }}</p>
                                    </div>
                                @endif

                                <!-- Designer Response -->
                                @if ($appointment->designer_notes)
                                    <div class="alert alert-info mb-3" role="alert">
                                        <h6 class="alert-heading mb-2 fw-semibold">
                                            <i class="fas fa-comment me-2"></i>{{ trans('dashboard.your_response') }}
                                        </h6>
                                        <p class="mb-0 small">{{ Str::limit($appointment->designer_notes, 150) }}</p>
                                    </div>
                                @endif

                                <!-- Timeline -->
                                <div class="alert alert-light mb-0" role="alert">
                                    <h6 class="alert-heading mb-3 fw-semibold">
                                        <i class="fas fa-history me-2"></i>{{ trans('dashboard.timeline') }}
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">{{ trans('dashboard.booked') }}:</span>
                                                <span
                                                    class="fw-medium small">{{ $appointment->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        @if ($appointment->accepted_at)
                                            <div class="col-md-4">
                                                <div class="d-flex justify-content-between">
                                                    <span
                                                        class="text-success small">{{ trans('dashboard.accepted') }}:</span>
                                                    <span
                                                        class="fw-medium small">{{ $appointment->accepted_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($appointment->completed_at)
                                            <div class="col-md-4">
                                                <div class="d-flex justify-content-between">
                                                    <span
                                                        class="text-info small">{{ trans('dashboard.completed') }}:</span>
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
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                style="width: 100px; height: 100px;">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                            <h4 class="fw-semibold mb-2">{{ trans('dashboard.no_appointments_found') }}</h4>
                            <p class="text-muted mb-4">{{ trans('dashboard.no_appointments_matching_filters') }}</p>
                            <a href="{{ route('designer.appointments.index') }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>{{ trans('dashboard.view_all_appointments') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
