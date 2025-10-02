@extends('components.layout')

@section('pageTitle', trans('appointments.appointment_booked_successfully'))
@section('title', trans('appointments.appointment_booked_successfully'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="appointment-success-page">
        <!-- Success Hero Section -->
        <div class="success-hero-section">
            <div class="container">
                <div class="success-content">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="success-title">{{ trans('appointments.appointment_booked_successfully') }}</h1>
                    <p class="success-subtitle">{{ trans('appointments.appointment_success_message') }}</p>
                </div>
            </div>
        </div>

        <!-- Success Details -->
        <div class="success-details-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Appointment Info Card -->
                        <div class="appointment-info-card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-check"></i>
                                    {{ trans('appointments.appointment_details') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="appointment-details-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-calendar"></i>
                                            {{ trans('appointments.appointment_date') }}
                                        </div>
                                        <div class="detail-value">{{ $appointment->appointment_date->format('F j, Y') }}</div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-clock"></i>
                                            {{ trans('appointments.appointment_time') }}
                                        </div>
                                        <div class="detail-value">{{ $appointment->appointment_time }}</div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-hourglass-half"></i>
                                            {{ trans('appointments.duration') }}
                                        </div>
                                        <div class="detail-value">{{ $appointment->duration_minutes }} {{ trans('appointments.minutes') }}</div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <div class="detail-label">
                                            <i class="fas fa-info-circle"></i>
                                            {{ trans('appointments.status') }}
                                        </div>
                                        <div class="detail-value">
                                            <span class="status-badge status-{{ $appointment->status }}">
                                                {{ trans('appointments.' . $appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($appointment->notes)
                                    <div class="appointment-notes">
                                        <h4 class="notes-title">{{ trans('appointments.notes') }}</h4>
                                        <p class="notes-content">{{ $appointment->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Order Info Card (if linked) -->
                        @if($appointment->order)
                            <div class="order-info-card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-shopping-cart"></i>
                                        {{ trans('appointments.linked_order') }}
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="order-summary">
                                        <div class="order-number">
                                            <strong>{{ trans('orders.order_number') }}:</strong> {{ $appointment->order->order_number }}
                                        </div>
                                        <div class="order-total">
                                            <strong>{{ trans('orders.total') }}:</strong> 
                                            {{ number_format($appointment->order->total, 2) }} {{ trans('orders.currency') }}
                                        </div>
                                        <div class="order-items-count">
                                            <strong>{{ trans('orders.items_count') }}:</strong> {{ $appointment->order->items->count() }}
                                        </div>
                                    </div>
                                    
                                    <div class="order-actions">
                                        <a href="{{ route('user.orders.show', $appointment->order) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                            {{ trans('orders.view_order') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Next Steps Card -->
                        <div class="next-steps-card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-list-check"></i>
                                    {{ trans('appointments.next_steps') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="steps-list">
                                    <div class="step-item">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h4 class="step-title">{{ trans('appointments.step_1_title') }}</h4>
                                            <p class="step-description">{{ trans('appointments.step_1_description') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="step-item">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h4 class="step-title">{{ trans('appointments.step_2_title') }}</h4>
                                            <p class="step-description">{{ trans('appointments.step_2_description') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="step-item">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h4 class="step-title">{{ trans('appointments.step_3_title') }}</h4>
                                            <p class="step-description">{{ trans('appointments.step_3_description') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons-section">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('appointments.index') }}" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ trans('appointments.view_my_appointments') }}
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('user.orders.index') }}" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="fas fa-shopping-bag"></i>
                                        {{ trans('orders.view_my_orders') }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('user.products.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                                        <i class="fas fa-arrow-left"></i>
                                        {{ trans('appointments.continue_shopping') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Brand Colors */
        :root {
            --brand-primary: #2c3e50;
            --brand-secondary: #3498db;
            --brand-accent: #e74c3c;
            --brand-success: #27ae60;
            --brand-warning: #f39c12;
            --brand-light: #ecf0f1;
            --brand-dark: #34495e;
        }

        .appointment-success-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Success Hero Section */
        .success-hero-section {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }

        .success-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .success-icon {
            font-size: 4rem;
            color: var(--brand-success);
            margin-bottom: 1.5rem;
            animation: bounceIn 0.8s ease-out;
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .success-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Success Details Section */
        .success-details-section {
            padding: 3rem 0;
        }

        /* Cards */
        .appointment-info-card,
        .order-info-card,
        .next-steps-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--brand-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Appointment Details Grid */
        .appointment-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-label {
            font-weight: 600;
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-value {
            font-size: 1.1rem;
            color: #495057;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        /* Appointment Notes */
        .appointment-notes {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border-left: 4px solid var(--brand-primary);
        }

        .notes-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--brand-primary);
            margin-bottom: 0.75rem;
        }

        .notes-content {
            color: #495057;
            margin: 0;
            line-height: 1.6;
        }

        /* Order Info */
        .order-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .order-number,
        .order-total,
        .order-items-count {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
        }

        .order-actions {
            text-align: center;
        }

        /* Next Steps */
        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .step-number {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--brand-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--brand-primary);
            margin-bottom: 0.5rem;
        }

        .step-description {
            color: #6c757d;
            margin: 0;
            line-height: 1.5;
        }

        /* Action Buttons */
        .action-buttons-section {
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--brand-primary);
            color: white;
            border-color: var(--brand-primary);
        }

        .btn-primary:hover {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--brand-primary);
            border-color: var(--brand-primary);
        }

        .btn-outline-primary:hover {
            background: var(--brand-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
        }

        .btn-outline-secondary {
            background: transparent;
            color: #6c757d;
            border-color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }

        /* Animations */
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .success-title {
                font-size: 2rem;
            }

            .success-subtitle {
                font-size: 1rem;
            }

            .appointment-details-grid {
                grid-template-columns: 1fr;
            }

            .order-summary {
                grid-template-columns: 1fr;
            }

            .step-item {
                flex-direction: column;
                text-align: center;
            }

            .step-number {
                align-self: center;
            }
        }
    </style>
@endsection