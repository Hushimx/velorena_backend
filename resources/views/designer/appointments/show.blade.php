@extends('designer.layouts.app')

@section('pageTitle', 'Appointment Details')
@section('title', 'Appointment Details')

@section('content')
    <style>
        .hover-shadow {
            transition: box-shadow 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            Appointment Details
                        </h2>
                        <p class="text-muted mb-0">Complete information about this appointment</p>
                    </div>
                    <div>
                        <a href="{{ route('designer.appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Appointment Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Appointment Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Appointment ID</label>
                                    <p class="mb-0">#{{ $appointment->id }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Date & Time</label>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $appointment->appointment_date->format('l, F j, Y') }}
                                        <br>
                                        <i class="fas fa-clock text-primary me-1"></i>
                                        {{ $appointment->appointment_time->format('g:i A') }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Duration</label>
                                    <p class="mb-0">
                                        <i class="fas fa-hourglass-half text-primary me-1"></i>
                                        {{ $appointment->duration_minutes }} minutes
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Status</label>
                                    <p class="mb-0">
                                        <span class="badge {{ $appointment->status_badge }} fs-6">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Created</label>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar-plus text-primary me-1"></i>
                                        {{ $appointment->created_at->format('M j, Y g:i A') }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Last Updated</label>
                                    <p class="mb-0">
                                        <i class="fas fa-edit text-primary me-1"></i>
                                        {{ $appointment->updated_at->format('M j, Y g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Client Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>
                            Client Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Full Name</label>
                                    <p class="mb-0">
                                        <i class="fas fa-user text-success me-1"></i>
                                        {{ $appointment->user->full_name }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Email</label>
                                    <p class="mb-0">
                                        <i class="fas fa-envelope text-success me-1"></i>
                                        <a href="mailto:{{ $appointment->user->email }}" class="text-decoration-none">
                                            {{ $appointment->user->email }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Phone</label>
                                    <p class="mb-0">
                                        <i class="fas fa-phone text-success me-1"></i>
                                        <a href="tel:{{ $appointment->user->phone }}" class="text-decoration-none">
                                            {{ $appointment->user->phone }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information Card -->
                @if ($appointment->hasOrder())
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Order Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Order Number</label>
                                    <p class="mb-0">
                                        <i class="fas fa-hashtag text-info me-1"></i>
                                        {{ $appointment->order->order_number }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Order Status</label>
                                    <p class="mb-0">
                                        <span class="badge bg-secondary">{{ ucfirst($appointment->order->status) }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Total Value</label>
                                    <p class="mb-0">
                                        <i class="fas fa-dollar-sign text-info me-1"></i>
                                        <strong
                                            class="text-success">${{ number_format($appointment->order->total, 2) }}</strong>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Total Products</label>
                                    <p class="mb-0">
                                        <i class="fas fa-box text-info me-1"></i>
                                        {{ $appointment->getTotalProductsCount() }} items
                                    </p>
                                </div>
                            </div>

                            @if ($appointment->order_notes)
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Order Notes</label>
                                    <div class="alert alert-light border">
                                        <i class="fas fa-sticky-note text-info me-2"></i>
                                        {{ $appointment->order_notes }}
                                    </div>
                                </div>
                            @endif

                            <!-- Products List -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Products in Order</label>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($appointment->order->items as $item)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $item->product->name ?? 'Unknown Product' }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $item->quantity }}</span>
                                                    </td>
                                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                                    <td>
                                                        <strong
                                                            class="text-success">${{ number_format($item->total_price, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Designs Card -->
                    @php
                        $productDesigns = collect();
                        foreach ($appointment->order->items as $item) {
                            $designs = $item->designs()->with('design')->get();
                            if ($designs->count() > 0) {
                                $productDesigns->push([
                                    'product' => $item->product,
                                    'designs' => $designs,
                                ]);
                            }
                        }
                    @endphp

                    @if ($productDesigns->count() > 0)
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-purple text-white" style="background-color: #6f42c1 !important;">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-palette me-2"></i>
                                    Client's Design Inspirations
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-0">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>Design Context:</strong> The client has selected these designs as inspiration
                                    for their products. Use these to understand their vision and preferences.
                                </div>

                                @foreach ($productDesigns as $productDesignData)
                                    <div class="mb-4">
                                        <div class="bg-light rounded p-3 mb-3">
                                            <h6 class="mb-0 text-primary">
                                                <i class="fas fa-box me-2"></i>
                                                {{ $productDesignData['product']->name }}
                                            </h6>
                                        </div>

                                        <div class="row g-4">
                                            @foreach ($productDesignData['designs'] as $productDesign)
                                                @if ($productDesign->design)
                                                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                                        <div
                                                            class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                                            <div class="card-body p-3">
                                                                <!-- Image Section -->
                                                                <div class="text-center mb-3">
                                                                    <div class="position-relative d-inline-block">
                                                                        <img src="{{ $productDesign->design->thumbnail_url ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjY2NjY2NjIi8+PHRleHQgeD0iNDAiIHk9IjQwIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IiM2NjY2NjYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5EZXNpZ248L3RleHQ+PC9zdmc+' }}"
                                                                            alt="{{ $productDesign->design->title }}"
                                                                            class="rounded shadow-sm"
                                                                            style="width: 80px; height: 80px; object-fit: cover;">
                                                                        <div
                                                                            class="position-absolute top-0 end-0 translate-middle">
                                                                            <span class="badge bg-primary rounded-pill"
                                                                                style="font-size: 0.65rem; min-width: 20px;">
                                                                                {{ $productDesign->priority }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Content Section -->
                                                                <div class="text-center">
                                                                    <h6 class="card-title mb-2 fw-semibold text-dark text-truncate"
                                                                        title="{{ $productDesign->design->title }}"
                                                                        style="font-size: 0.9rem; line-height: 1.2;">
                                                                        {{ $productDesign->design->title }}
                                                                    </h6>

                                                                    @if ($productDesign->design->description)
                                                                        <p class="text-muted small mb-2"
                                                                            style="font-size: 0.75rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                                            {{ $productDesign->design->description }}
                                                                        </p>
                                                                    @endif

                                                                    @if ($productDesign->notes)
                                                                        <div class="bg-light rounded p-2 mb-2">
                                                                            <p class="text-muted small mb-0"
                                                                                style="font-size: 0.7rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                                                <i
                                                                                    class="fas fa-comment me-1 text-primary"></i>
                                                                                <strong>Note:</strong>
                                                                                {{ $productDesign->notes }}
                                                                            </p>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Badges Section -->
                                                                    <div class="d-flex flex-column gap-1">
                                                                        <span class="badge bg-warning text-dark mx-auto"
                                                                            style="font-size: 0.7rem;">
                                                                            <i class="fas fa-star me-1"></i>
                                                                            Priority {{ $productDesign->priority }}
                                                                        </span>
                                                                        @if ($productDesign->design->category)
                                                                            <span class="badge bg-secondary mx-auto"
                                                                                style="font-size: 0.7rem;">
                                                                                {{ $productDesign->design->category }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-palette me-2"></i>
                                    Design Inspirations
                                </h5>
                            </div>
                            <div class="card-body text-center py-4">
                                <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No Design Inspirations</h6>
                                <p class="text-muted mb-0">The client hasn't selected any design inspirations for this
                                    order.</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No Order Linked
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning border-0 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                This appointment is not linked to any order.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Client Notes Card -->
                @if ($appointment->notes)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-sticky-note me-2"></i>
                                Client Notes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border">
                                <i class="fas fa-quote-left text-muted me-2"></i>
                                {{ $appointment->notes }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($appointment->isPending())
                            <form method="POST" action="{{ route('designer.appointments.accept', $appointment) }}"
                                class="d-inline mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-check me-2"></i>Accept Appointment
                                </button>
                            </form>
                            <form method="POST" action="{{ route('designer.appointments.reject', $appointment) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Are you sure you want to reject this appointment?')">
                                    <i class="fas fa-times me-2"></i>Reject Appointment
                                </button>
                            </form>
                        @elseif ($appointment->isAccepted())
                            <form method="POST" action="{{ route('designer.appointments.complete', $appointment) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100"
                                    onclick="return confirm('Mark this appointment as completed?')">
                                    <i class="fas fa-check-circle me-2"></i>Mark as Complete
                                </button>
                            </form>
                        @elseif ($appointment->isCompleted())
                            <div class="alert alert-success border-0 mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Completed!</strong> This appointment has been marked as completed.
                            </div>
                        @elseif ($appointment->isCancelled())
                            <div class="alert alert-danger border-0 mb-0">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Cancelled!</strong> This appointment has been cancelled.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Client Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-phone me-2"></i>
                            Contact Client
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $appointment->user->email }}" class="btn btn-outline-success">
                                <i class="fas fa-envelope me-2"></i>Send Email
                            </a>
                            <a href="tel:{{ $appointment->user->phone }}" class="btn btn-outline-success">
                                <i class="fas fa-phone me-2"></i>Call Client
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Appointment Summary Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1">{{ $appointment->duration_minutes }}</h4>
                                    <small class="text-muted">Minutes</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-success mb-1">
                                    @if ($appointment->hasOrder())
                                        ${{ number_format($appointment->order->total, 0) }}
                                    @else
                                        N/A
                                    @endif
                                </h4>
                                <small class="text-muted">Order Value</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-info mb-1">
                                    @if ($appointment->hasOrder())
                                        {{ $appointment->getTotalProductsCount() }}
                                    @else
                                        0
                                    @endif
                                </h4>
                                <small class="text-muted">Products</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning mb-1">
                                    @php
                                        $totalDesigns = 0;
                                        if ($appointment->hasOrder()) {
                                            foreach ($appointment->order->items as $item) {
                                                $totalDesigns += $item->product
                                                    ->designsForUser($appointment->user_id)
                                                    ->count();
                                            }
                                        }
                                    @endphp
                                    {{ $totalDesigns }}
                                </h4>
                                <small class="text-muted">Designs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
