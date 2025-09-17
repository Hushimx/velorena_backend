@extends('components.layout')

@section('pageTitle', trans('dashboard.my_appointments'))
@section('title', trans('dashboard.my_appointments'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="appointments-page">
        <!-- Header Section -->
        <div class="appointments-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <!-- Breadcrumb -->
                        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                            <ol class="breadcrumb-list">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}" class="breadcrumb-link">
                                        <i class="fas fa-home"></i>
                                        {{ trans('dashboard.dashboard') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.index') }}" class="breadcrumb-link">
                                        <i class="fas fa-user-circle"></i>
                                        {{ trans('dashboard.my_account') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('dashboard.my_appointments') }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="appointments-title">{{ trans('dashboard.my_appointments') }}</h1>
                        <p class="appointments-subtitle">{{ trans('dashboard.manage_consultations') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <div class="header-actions">
                            <a href="{{ route('appointments.create') }}" class="new-appointment-btn">
                                <i class="fas fa-plus"></i>
                                <span>{{ trans('dashboard.book_new_appointment') }}</span>
                            </a>
                            <a href="{{ route('client.index') }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                <span>{{ trans('dashboard.back_to_account') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments Content -->
        <div class="appointments-content-section">
            <div class="container">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $appointments->total() }}</span>
                            <span class="stat-label">{{ trans('dashboard.total_appointments') }}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $appointments->where('status', 'pending')->count() }}</span>
                            <span class="stat-label">{{ trans('dashboard.pending') }}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $appointments->where('status', 'completed')->count() }}</span>
                            <span class="stat-label">{{ trans('dashboard.completed') }}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $appointments->whereNotNull('zoom_meeting_url')->count() }}</span>
                            <span class="stat-label">{{ trans('dashboard.zoom_meetings') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Appointments List -->
                @if ($appointments->count() > 0)
                    <div class="appointments-list">
                        @foreach ($appointments as $appointment)
                            <div class="appointment-card">
                                <div class="appointment-header">
                                    <div class="appointment-info">
                                        <div class="appointment-icon">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div class="appointment-details">
                                            <h5 class="appointment-title">{{ $appointment->formatted_date ?? $appointment->appointment_date->format('M d, Y') }}</h5>
                                            <p class="appointment-time">
                                                {{ $appointment->formatted_time ?? $appointment->appointment_time->format('H:i') }} - 
                                                {{ $appointment->formatted_end_time ?? \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes($appointment->duration_minutes)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="appointment-status">
                                        <span class="status-badge status-{{ $appointment->status }}">
                                            {{ trans('appointments.' . $appointment->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="appointment-content">
                                    <!-- Appointment Details Grid -->
                                    <div class="appointment-details-grid">
                                        <!-- Designer Info -->
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="detail-content">
                                                <span class="detail-label">{{ trans('dashboard.designer') }}</span>
                                                @if ($appointment->designer)
                                                    <span class="detail-value">{{ $appointment->designer->name }}</span>
                                                    <span class="detail-sub">{{ $appointment->designer->email }}</span>
                                                @else
                                                    <span class="detail-value pending">{{ trans('dashboard.pending_assignment') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Duration -->
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="detail-content">
                                                <span class="detail-label">{{ trans('dashboard.duration') }}</span>
                                                <span class="detail-value">{{ $appointment->duration_minutes }} {{ trans('dashboard.minutes') }}</span>
                                            </div>
                                        </div>

                                        <!-- Booked Date -->
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-calendar-plus"></i>
                                            </div>
                                            <div class="detail-content">
                                                <span class="detail-label">{{ trans('dashboard.booked') }}</span>
                                                <span class="detail-value">{{ $appointment->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>

                                        <!-- Meeting Type -->
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-video"></i>
                                            </div>
                                            <div class="detail-content">
                                                <span class="detail-label">{{ trans('dashboard.meeting_type') }}</span>
                                                @if ($appointment->zoom_meeting_url)
                                                    <span class="detail-value zoom">{{ trans('dashboard.zoom_meeting') }}</span>
                                                @else
                                                    <span class="detail-value">{{ trans('dashboard.in_person_meeting') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Linked Order -->
                                    @if ($appointment->order)
                                        <div class="linked-order-section">
                                            <div class="section-header">
                                                <div class="section-icon">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </div>
                                                <h6 class="section-title">{{ trans('dashboard.linked_order') }}</h6>
                                            </div>
                                            <div class="order-info">
                                                <div class="order-summary">
                                                    <div class="order-details">
                                                        <span class="order-number">{{ trans('dashboard.order_number') }} #{{ $appointment->order->order_number }}</span>
                                                        <span class="order-items">{{ $appointment->order->items->count() }} {{ trans('dashboard.items') }}</span>
                                                    </div>
                                                    <div class="order-total">${{ number_format($appointment->order->total, 2) }}</div>
                                                </div>
                                                @if ($appointment->order_notes)
                                                    <div class="order-notes">
                                                        {{ Str::limit($appointment->order_notes, 50) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Notes Section -->
                                    @if ($appointment->notes)
                                        <div class="notes-section">
                                            <div class="section-header">
                                                <div class="section-icon">
                                                    <i class="fas fa-sticky-note"></i>
                                                </div>
                                                <h6 class="section-title">{{ trans('dashboard.your_notes') }}</h6>
                                            </div>
                                            <div class="notes-content">
                                                {{ Str::limit($appointment->notes, 100) }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Designer Response -->
                                    @if ($appointment->designer_notes)
                                        <div class="designer-response-section">
                                            <div class="section-header">
                                                <div class="section-icon">
                                                    <i class="fas fa-comment"></i>
                                                </div>
                                                <h6 class="section-title">{{ trans('dashboard.designer_response') }}</h6>
                                            </div>
                                            <div class="response-content">
                                                {{ Str::limit($appointment->designer_notes, 100) }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Appointment Actions -->
                                    <div class="appointment-actions">
                                        <a href="{{ route('client.appointment.details', $appointment->id) }}" class="btn-primary">
                                            <i class="fas fa-eye me-2"></i>{{ trans('dashboard.view_details') }}
                                        </a>
                                        @if($appointment->zoom_meeting_url)
                                            <a href="{{ $appointment->zoom_meeting_url }}" target="_blank" class="btn-zoom">
                                                <i class="fas fa-video me-2"></i>{{ trans('dashboard.join_meeting') }}
                                            </a>
                                        @endif
                                        @if($appointment->canBeCancelled())
                                            <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-danger" 
                                                        onclick="return confirm('{{ trans('dashboard.confirm_cancel') }}')">
                                                    <i class="fas fa-times me-2"></i>{{ trans('dashboard.cancel_appointment') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($appointments->hasPages())
                        <div class="pagination-section">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-content">
                            <div class="empty-state-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h4 class="empty-state-title">{{ trans('dashboard.no_appointments_yet') }}</h4>
                            <p class="empty-state-message">{{ trans('dashboard.no_appointments_description') }}</p>
                            <a href="{{ route('appointments.create') }}" class="empty-state-action">
                                <i class="fas fa-plus me-2"></i>
                                {{ trans('dashboard.book_first_appointment') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Appointments Page Styles - Using Brand Colors */
        .appointments-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, #FFEBC6 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Section */
        .appointments-header {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        .appointments-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffde9f" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateX(0px) translateY(0px) rotate(0deg); }
            33% { transform: translateX(30px) translateY(-30px) rotate(120deg); }
            66% { transform: translateX(-20px) translateY(20px) rotate(240deg); }
        }

        /* Breadcrumb Navigation */
        .breadcrumb-nav {
            margin-bottom: 1.5rem;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '›';
            margin: 0 0.5rem;
            color: #FFEBC6;
            font-weight: 700;
        }

        .breadcrumb-link {
            color: #FFEBC6;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #fff;
            font-weight: 700;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        /* Title and Subtitle */
        .appointments-title {
            font-size: 3rem;
            font-weight: 900;
            color: #FFEBC6;
            margin: 0 0 1rem 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .appointments-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            z-index: 3;
        }

        .new-appointment-btn {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .new-appointment-btn:hover {
            background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
            transform: translateY(-2px);
        }

        .back-btn {
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            color: #8B4513;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #F4D03F 0%, #FFEBC6 100%);
            color: #8B4513;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(255, 222, 159, 0.4);
            transform: translateY(-2px);
        }

        /* Content Section */
        .appointments-content-section {
            padding: 3rem 0;
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4513;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 900;
            color: #8B4513;
            line-height: 1;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Appointments List */
        .appointments-list {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .appointment-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .appointment-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .appointment-header {
            background: linear-gradient(135deg, #FFEBC6 0%, rgba(255, 222, 159, 0.1) 100%);
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 222, 159, 0.3);
        }

        .appointment-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .appointment-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4513;
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
        }

        .appointment-details {
            display: flex;
            flex-direction: column;
        }

        .appointment-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #8B4513;
            margin: 0 0 0.25rem 0;
        }

        .appointment-time {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-accepted {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-completed {
            background: linear-gradient(135deg, #cce5ff 0%, #b3d9ff 100%);
            color: #004085;
            border: 1px solid #b3d9ff;
        }

        .status-cancelled,
        .status-rejected {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Appointment Content */
        .appointment-content {
            padding: 2rem;
        }

        /* Details Grid */
        .appointment-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-item {
            background: linear-gradient(135deg, rgba(255, 222, 159, 0.1) 0%, rgba(255, 222, 159, 0.05) 100%);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 222, 159, 0.3);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4513;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .detail-content {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: #8B4513;
            font-weight: 700;
            font-size: 1rem;
        }

        .detail-value.pending {
            color: #856404;
        }

        .detail-value.zoom {
            color: #007bff;
        }

        .detail-sub {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Section Styles */
        .linked-order-section,
        .notes-section,
        .designer-response-section {
            background: linear-gradient(135deg, rgba(255, 222, 159, 0.1) 0%, rgba(255, 222, 159, 0.05) 100%);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 222, 159, 0.3);
            margin-bottom: 1.5rem;
        }

        .linked-order-section {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.1) 100%);
            border-color: rgba(40, 167, 69, 0.2);
        }

        .designer-response-section {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
            border-color: rgba(0, 123, 255, 0.2);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .section-icon {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4513;
            font-size: 0.875rem;
        }

        .linked-order-section .section-icon {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(40, 167, 69, 0.3) 100%);
            color: #28a745;
        }

        .designer-response-section .section-icon {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.2) 0%, rgba(0, 123, 255, 0.3) 100%);
            color: #007bff;
        }

        .section-title {
            color: #8B4513;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Order Info */
        .order-info {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }

        .order-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .order-details {
            display: flex;
            flex-direction: column;
        }

        .order-number {
            font-weight: 700;
            color: #8B4513;
            font-size: 1.1rem;
        }

        .order-items {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .order-total {
            font-weight: 700;
            color: #28a745;
            font-size: 1.25rem;
        }

        .order-notes {
            color: #6c757d;
            font-size: 0.875rem;
            font-style: italic;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding-top: 0.5rem;
        }

        /* Notes Content */
        .notes-content,
        .response-content {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            color: #495057;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Appointment Actions */
        .appointment-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-primary, .btn-zoom, .btn-danger {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
        }

        .btn-zoom {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-zoom:hover {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        /* Pagination */
        .pagination-section {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }

        /* Empty State */
        .empty-state {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .empty-state-content {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #FFEBC6 0%, rgba(255, 222, 159, 0.3) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: #8B4513;
            font-size: 3rem;
        }

        .empty-state-title {
            color: #8B4513;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .empty-state-message {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-state-action {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
            transition: all 0.3s ease;
        }

        .empty-state-action:hover {
            background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .appointments-title {
                font-size: 2rem;
            }

            .appointments-subtitle {
                font-size: 1rem;
            }

            .header-actions {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }

            .new-appointment-btn,
            .back-btn {
                width: 100%;
                justify-content: center;
                padding: 0.875rem 1.5rem;
                font-size: 0.9rem;
            }

            .stats-cards {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .appointment-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .appointment-details-grid {
                grid-template-columns: 1fr;
            }

            .appointment-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn-primary, .btn-zoom, .btn-danger {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .appointments-header {
                padding: 2rem 0;
            }

            .appointments-content-section {
                padding: 2rem 0;
            }

            .appointments-title {
                font-size: 1.75rem;
            }

            .breadcrumb-list {
                flex-direction: column;
                align-items: flex-start;
            }

            .breadcrumb-item:not(:last-child)::after {
                display: none;
            }

            .appointment-content {
                padding: 1.5rem;
            }

            .detail-item {
                padding: 1rem;
            }
        }
    </style>
@endsection
