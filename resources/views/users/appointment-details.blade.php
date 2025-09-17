@extends('components.layout')

@section('pageTitle', trans('dashboard.appointment_details'))
@section('title', trans('dashboard.appointment_details'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

<div class="appointment-details-page">
        <!-- Header Section -->
        <div class="appointment-details-header">
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
                                    <a href="{{ route('client.appointments') }}" class="breadcrumb-link">
                                        {{ trans('dashboard.my_appointments') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('dashboard.appointment_details') }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="appointment-details-title">{{ trans('dashboard.appointment_details') }}</h1>
                        <p class="appointment-details-subtitle">{{ trans('dashboard.view_consultation_details') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <div class="header-actions">
                            <a href="{{ route('appointments.create') }}" class="new-appointment-btn">
                                <i class="fas fa-plus"></i>
                                <span>{{ trans('dashboard.book_new_appointment') }}</span>
                            </a>
            <a href="{{ route('client.appointments') }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                <span>{{ trans('dashboard.back_to_appointments') }}</span>
            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Content -->
        <div class="appointment-content-section">
            <div class="container">

                <!-- Success/Error Messages -->
                <x-session-message type="message" />
                <x-session-message type="error" />

                <!-- Main Appointment Card -->
                <div class="appointment-main-card">
                    <div class="appointment-card-content">
                        <!-- Appointment Header -->
                        <div class="appointment-card-header">
                            <div class="appointment-info">
                                <div class="appointment-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="appointment-details">
                                    <h3 class="appointment-title">{{ trans('dashboard.consultation_information') }}</h3>
                                    <p class="appointment-id">Appointment #{{ $appointment->id }}</p>
                            </div>
                            </div>
                        </div>

                        <!-- Status Banner -->
                        <div class="status-banner {{ $appointment->status }}">
                            <div class="status-content">
                                <span class="status-badge {{ $appointment->status }}">
                                    {{ trans('appointments.' . $appointment->status) }}
                                </span>
                                <div class="status-info">
                                    <h6 class="status-title">
                                        @if ($appointment->status === 'pending')
                                            {{ trans('dashboard.appointment_pending') }}
                                        @elseif($appointment->status === 'accepted')
                                            {{ trans('dashboard.appointment_confirmed') }}
                                        @elseif($appointment->status === 'completed')
                                            {{ trans('dashboard.consultation_completed') }}
                                        @elseif($appointment->status === 'cancelled')
                                            {{ trans('dashboard.appointment_cancelled') }}
                                        @elseif($appointment->status === 'rejected')
                                            {{ trans('dashboard.appointment_rejected') }}
                                        @else
                                            {{ trans('dashboard.appointment_status') }}
                                        @endif
                                    </h6>
                                    <p class="status-description">
                                        @if ($appointment->status === 'pending')
                                            {{ trans('dashboard.waiting_for_assignment') }}
                                        @elseif($appointment->status === 'accepted')
                                            {{ trans('dashboard.confirmed_by_designer') }}
                                        @elseif($appointment->status === 'completed')
                                            {{ trans('dashboard.completed_successfully') }}
                                        @elseif($appointment->status === 'cancelled')
                                            {{ trans('dashboard.appointment_cancelled_desc') }}
                                        @elseif($appointment->status === 'rejected')
                                            {{ trans('dashboard.rejected_by_designer') }}
                                        @else
                                            {{ trans('dashboard.status_information') }}
                                        @endif
                                    </p>
                            </div>
                            </div>
                        </div>

                        <!-- Information Grid -->
                        <div class="information-grid">
                            <!-- Designer Information -->
                            <div class="info-card designer-card">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <h5 class="card-title">{{ trans('dashboard.designer_information') }}</h5>
                                </div>
                                @if ($appointment->designer)
                                    <div class="designer-info">
                                        <div class="designer-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="designer-details">
                                            <h6 class="designer-name">{{ $appointment->designer->name }}</h6>
                                            @if ($appointment->designer->specialization)
                                                <p class="designer-specialization">
                                                    {{ $appointment->designer->specialization }}</p>
                                            @endif
                                            <p class="designer-email">{{ $appointment->designer->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="pending-assignment">
                                        <div class="pending-icon">
                                            <i class="fas fa-clock"></i>
                            </div>
                                        <div class="pending-details">
                                            <h6 class="pending-title">{{ trans('dashboard.pending_assignment') }}</h6>
                                            <p class="pending-message">{{ trans('dashboard.designer_assigned_soon') }}</p>
                            </div>
                        </div>
                                @endif
                            </div>

                            <!-- Appointment Details -->
                            <div class="info-card appointment-details-card">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <h5 class="card-title">{{ trans('dashboard.appointment_details_title') }}</h5>
                                </div>
                                <div class="appointment-details-grid">
                                    <div class="detail-item">
                                        <span class="detail-label">{{ trans('dashboard.date') }}</span>
                                        <span
                                            class="detail-value">{{ $appointment->formatted_date ?? $appointment->appointment_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">{{ trans('dashboard.time') }}</span>
                                        <span
                                            class="detail-value">{{ $appointment->formatted_time ?? $appointment->appointment_time->format('H:i') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">{{ trans('dashboard.duration') }}</span>
                                        <span class="detail-value">{{ $appointment->duration_minutes }}
                                            {{ trans('dashboard.minutes') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">{{ trans('dashboard.end_time') }}</span>
                                        <span
                                            class="detail-value">{{ $appointment->formatted_end_time ?? \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes($appointment->duration_minutes)->format('H:i') }}</span>
                                    </div>
                                </div>
                        </div>
                    </div>

                        <!-- Notes Section -->
                        @if ($appointment->notes)
                    <div class="notes-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                    <h5 class="section-title">{{ trans('dashboard.your_notes') }}</h5>
                                </div>
                        <div class="notes-content">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                    @endif

                        <!-- Linked Order Information -->
                        @if ($appointment->order)
                            <div class="linked-order-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-shopping-cart"></i>
                        </div>
                                    <h5 class="section-title">{{ trans('dashboard.linked_order') }}</h5>
                                </div>
                                <div class="order-info">
                                    <div class="order-details-grid">
                                        <div class="order-detail-item">
                                            <span class="order-detail-label">{{ trans('dashboard.order_number') }}</span>
                                            <span class="order-detail-value">{{ $appointment->order->order_number }}</span>
                                        </div>
                                        <div class="order-detail-item">
                                            <span class="order-detail-label">{{ trans('dashboard.order_status') }}</span>
                                            <span
                                                class="order-detail-value">{{ trans('orders.' . $appointment->order->status) }}</span>
                                        </div>
                                        <div class="order-detail-item">
                                            <span class="order-detail-label">{{ trans('dashboard.order_total') }}</span>
                                            <span
                                                class="order-detail-value">${{ number_format($appointment->order->total, 2) }}</span>
                                        </div>
                                        <div class="order-detail-item">
                                            <span class="order-detail-label">{{ trans('dashboard.items_count') }}</span>
                                            <span
                                                class="order-detail-value">{{ $appointment->order->items->count() }}</span>
                                        </div>
                                    </div>

                                    @if ($appointment->order_notes)
                                        <div class="order-notes">
                                            <span class="order-notes-label">{{ trans('dashboard.order_notes') }}</span>
                                            <p class="order-notes-content">{{ $appointment->order_notes }}</p>
                                        </div>
                                    @endif
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
                                    <h5 class="section-title">{{ trans('dashboard.designer_response') }}</h5>
                                </div>
                                <div class="response-content">
                                    {{ $appointment->designer_notes }}
                                </div>
                            </div>
                        @endif

                <!-- Timeline -->
                <div class="timeline-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <h5 class="section-title">{{ trans('dashboard.timeline') }}</h5>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-grid">
                                    <div class="timeline-item">
                                        <span class="timeline-label">{{ trans('dashboard.booked') }}:</span>
                                        <span
                                            class="timeline-value">{{ $appointment->created_at->format('M d, Y H:i') }}</span>
                            </div>
                                    @if ($appointment->accepted_at)
                                        <div class="timeline-item accepted">
                                            <span class="timeline-label">{{ trans('dashboard.accepted') }}:</span>
                                            <span
                                                class="timeline-value">{{ $appointment->accepted_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                                    @if ($appointment->rejected_at)
                        <div class="timeline-item rejected">
                                            <span class="timeline-label">{{ trans('dashboard.rejected') }}:</span>
                                            <span
                                                class="timeline-value">{{ $appointment->rejected_at->format('M d, Y H:i') }}</span>
                            </div>
                                    @endif
                                    @if ($appointment->completed_at)
                                        <div class="timeline-item completed">
                                            <span class="timeline-label">{{ trans('dashboard.completed') }}:</span>
                                            <span
                                                class="timeline-value">{{ $appointment->completed_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="actions-section">
                            @if ($appointment->canBeCancelled())
                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST"
                                    class="action-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        onclick="return confirm('{{ trans('dashboard.confirm_cancel') }}')"
                                        class="cancel-btn">
                                        <i class="fas fa-times"></i>
                                        {{ trans('dashboard.cancel_appointment') }}
                                    </button>
                                </form>
                        @endif

                            <a href="{{ route('client.appointments') }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                {{ trans('dashboard.back_to_list') }}
                            </a>

                            <a href="{{ route('appointments.create') }}" class="new-appointment-btn">
                                <i class="fas fa-calendar-plus"></i>
                                {{ trans('dashboard.book_new_appointment_action') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

<style>
        /* Appointment Details Page Styles - Based on Product Show Page Design */
.appointment-details-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, #FFEBC6 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Section */
        .appointment-details-header {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        .appointment-details-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
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
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '›';
            margin: 0 0.5rem;
            color: rgba(139, 69, 19, 0.6);
            font-weight: 700;
        }

        .breadcrumb-link {
            color: rgba(139, 69, 19, 0.8);
            text-decoration: none;
    display: flex;
    align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: #8B4513;
            background: rgba(255, 255, 255, 0.2);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #8B4513;
            font-weight: 700;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        /* Title and Subtitle */
        .appointment-details-title {
            font-size: 3rem;
            font-weight: 900;
            color: #8B4513;
            margin: 0 0 1rem 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .appointment-details-subtitle {
            font-size: 1.2rem;
            color: rgba(139, 69, 19, 0.8);
            margin: 0;
            font-weight: 500;
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
        }

        .new-appointment-btn:hover {
            background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.back-btn {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
    color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
    text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
            transition: all 0.3s ease;
}

.back-btn:hover {
            background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
    color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
        }

        /* Content Section */
        .appointment-content-section {
            padding: 3rem 0;
        }

        /* Main Appointment Card */
        .appointment-main-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .appointment-card-content {
            padding: 2rem;
        }

        /* Appointment Header */
        .appointment-card-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(255, 235, 198, 0.3);
        }

        .appointment-info {
    display: flex;
    align-items: center;
    gap: 1rem;
        }

        .appointment-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
            color: #8B4513;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.3);
        }

        .appointment-details {
            display: flex;
            flex-direction: column;
        }

        .appointment-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 0.25rem 0;
        }

        .appointment-id {
            color: #6c757d;
    font-size: 1rem;
            margin: 0;
        }

        /* Status Banner */
        .status-banner {
            background: linear-gradient(135deg, rgba(255, 235, 198, 0.1) 0%, rgba(244, 208, 63, 0.05) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 235, 198, 0.3);
            margin-bottom: 2rem;
        }

        .status-banner.pending {
            background: linear-gradient(135deg, rgba(255, 243, 205, 0.1) 0%, rgba(255, 234, 167, 0.05) 100%);
            border-color: rgba(255, 234, 167, 0.3);
        }

        .status-banner.accepted {
            background: linear-gradient(135deg, rgba(212, 237, 218, 0.1) 0%, rgba(195, 230, 203, 0.05) 100%);
            border-color: rgba(195, 230, 203, 0.3);
        }

        .status-banner.completed {
            background: linear-gradient(135deg, rgba(204, 231, 255, 0.1) 0%, rgba(179, 217, 255, 0.05) 100%);
            border-color: rgba(179, 217, 255, 0.3);
        }

        .status-banner.cancelled,
        .status-banner.rejected {
            background: linear-gradient(135deg, rgba(248, 215, 218, 0.1) 0%, rgba(245, 198, 203, 0.05) 100%);
            border-color: rgba(245, 198, 203, 0.3);
        }

        .status-content {
    display: flex;
            align-items: center;
    gap: 1rem;
}

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-badge.accepted {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-badge.completed {
            background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%);
            color: #004085;
            border: 1px solid #b3d9ff;
        }

        .status-badge.cancelled,
        .status-badge.rejected {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-info {
            flex: 1;
        }

        .status-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0 0 0.25rem 0;
        }

        .status-description {
            color: #6c757d;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Information Grid */
        .information-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: linear-gradient(135deg, rgba(255, 235, 198, 0.1) 0%, rgba(244, 208, 63, 0.05) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 235, 198, 0.3);
        }

        .designer-card {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
            border-color: rgba(0, 123, 255, 0.2);
        }

        .appointment-details-card {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.1) 100%);
            border-color: rgba(40, 167, 69, 0.2);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .card-icon {
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

        .designer-card .card-icon {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.2) 0%, rgba(0, 123, 255, 0.3) 100%);
            color: #007bff;
        }

        .appointment-details-card .card-icon {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(40, 167, 69, 0.3) 100%);
            color: #28a745;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Designer Info */
        .designer-info,
        .pending-assignment {
    display: flex;
    align-items: center;
    gap: 1rem;
        }

        .designer-avatar,
        .pending-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .pending-icon {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
        }

        .designer-details,
        .pending-details {
            display: flex;
            flex-direction: column;
        }

        .designer-name,
        .pending-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1rem;
            margin: 0 0 0.25rem 0;
        }

        .pending-title {
            color: #856404;
        }

        .designer-specialization,
        .designer-email,
        .pending-message {
            color: #6c757d;
            font-size: 0.875rem;
            margin: 0;
        }

        .designer-specialization {
            margin-bottom: 0.25rem;
        }

        /* Appointment Details Grid */
        .appointment-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            background: white;
            padding: 1rem;
    border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .detail-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Section Styles */
        .notes-section,
        .linked-order-section,
        .designer-response-section,
        .timeline-section {
            background: linear-gradient(135deg, rgba(255, 235, 198, 0.1) 0%, rgba(244, 208, 63, 0.05) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 235, 198, 0.3);
            margin-bottom: 2rem;
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
            color: #2c3e50;
    font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
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

        /* Order Info */
        .order-info {
            background: white;
    border-radius: 8px;
            padding: 1rem;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }

        .order-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .order-detail-item {
    display: flex;
    flex-direction: column;
        }

        .order-detail-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .order-detail-value {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1rem;
        }

        .order-notes {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding-top: 1rem;
        }

        .order-notes-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .order-notes-content {
            color: #495057;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Timeline */
        .timeline-content {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .timeline-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
}

.timeline-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            background: rgba(255, 235, 198, 0.1);
            border: 1px solid rgba(255, 235, 198, 0.2);
        }

        .timeline-item.accepted {
            background: rgba(212, 237, 218, 0.1);
            border-color: rgba(195, 230, 203, 0.2);
        }

        .timeline-item.rejected {
            background: rgba(248, 215, 218, 0.1);
            border-color: rgba(245, 198, 203, 0.2);
        }

        .timeline-item.completed {
            background: rgba(204, 231, 255, 0.1);
            border-color: rgba(179, 217, 255, 0.2);
        }

        .timeline-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .timeline-value {
            color: #2c3e50;
            font-weight: 700;
            font-size: 0.875rem;
        }

        /* Actions Section */
        .actions-section {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            align-items: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid rgba(255, 235, 198, 0.3);
        }

        .action-form {
            display: inline-block;
        }

        .cancel-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .cancel-btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .appointment-details-title {
                font-size: 2rem;
            }

            .appointment-details-subtitle {
    font-size: 1rem;
}

            .header-actions {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
                z-index: 3;
            }

            .new-appointment-btn,
            .back-btn {
                width: 100%;
                justify-content: center;
                padding: 0.875rem 1.5rem;
    font-size: 0.9rem;
}

            .information-grid {
                grid-template-columns: 1fr;
            }

            .appointment-details-grid {
                grid-template-columns: 1fr;
            }

            .order-details-grid {
                grid-template-columns: 1fr;
            }

            .timeline-grid {
        grid-template-columns: 1fr;
    }
    
            .actions-section {
        flex-direction: column;
                align-items: stretch;
            }

            .cancel-btn,
            .back-btn,
            .new-appointment-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .appointment-details-header {
                padding: 2rem 0;
            }

            .appointment-content-section {
                padding: 2rem 0;
            }

            .appointment-details-title {
                font-size: 1.75rem;
            }

            .breadcrumb-nav {
                margin-bottom: 1rem;
            }

            .appointment-card-content {
                padding: 1.5rem;
            }

            .info-card {
                padding: 1rem;
    }
}
</style>
@endsection
