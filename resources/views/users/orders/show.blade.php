@extends('components.layout')

@section('pageTitle', trans('orders.order_details') . ' - ' . $order->order_number)
@section('title', trans('orders.order_details'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="order-show-page">
        <!-- Header Section -->
        <div class="order-show-header">
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
                                    <a href="{{ route('user.orders.index') }}" class="breadcrumb-link">
                                        {{ trans('orders.my_orders') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $order->order_number }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="order-show-title">{{ trans('orders.order_details') }}</h1>
                        <p class="order-show-subtitle">{{ $order->order_number }} - {{ trans('orders.order_date') }}:
                            {{ $order->created_at->format('F j, Y') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <a href="{{ route('user.orders.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ trans('orders.back_to_orders') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="order-details-section">
            <div class="container">

                <div class="row">
                    <!-- Order Information Card -->
                    <div class="col-lg-8 mb-4">
                        <div class="order-info-card">
                            <div class="order-info-content">
                                <!-- Order Header -->
                                <div class="order-header-section">
                                    <div class="order-number-badge">
                                        {{ $order->order_number }}
                                    </div>
                                    <div class="order-status-badge {{ $order->status }}">
                                        {{ trans('orders.' . $order->status) }}
                                    </div>
                                </div>

                                <!-- Order Details -->
                                <div class="order-details-grid">
                                    <div class="order-detail-item">
                                        <span class="order-detail-label">{{ trans('orders.order_date') }}:</span>
                                        <span
                                            class="order-detail-value">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</span>
                                    </div>
                                    <div class="order-detail-item">
                                        <span class="order-detail-label">{{ trans('orders.subtotal') }}:</span>
                                        <span class="order-detail-value">{{ number_format($order->subtotal, 2) }}
                                            {{ trans('orders.currency') }}</span>
                                    </div>
                                    <div class="order-detail-item">
                                        <span class="order-detail-label">{{ trans('orders.tax') }}:</span>
                                        <span class="order-detail-value">{{ number_format($order->tax, 2) }}
                                            {{ trans('orders.currency') }}</span>
                                    </div>
                                    <div class="order-detail-item total">
                                        <span class="order-detail-label">{{ trans('orders.total') }}:</span>
                                        <span class="order-detail-value">{{ number_format($order->total, 2) }}
                                            {{ trans('orders.currency') }}</span>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                @if ($order->phone)
                                    <div class="contact-info-section">
                                        <h3 class="section-title">{{ trans('orders.contact_information') }}</h3>
                                        <div class="contact-info-item">
                                            <span class="contact-label">{{ trans('orders.phone') }}:</span>
                                            <span class="contact-value">{{ $order->phone }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Addresses -->
                                @if ($order->shipping_address || $order->billing_address)
                                    <div class="addresses-section">
                                        <h3 class="section-title">{{ trans('orders.addresses') }}</h3>
                                        <div class="addresses-grid">
                                            @if ($order->shipping_address)
                                                <div class="address-card">
                                                    <h4 class="address-title">{{ trans('orders.shipping_address') }}</h4>
                                                    <p class="address-content">{{ $order->shipping_address }}</p>
                                                </div>
                                            @endif
                                            @if ($order->billing_address)
                                                <div class="address-card">
                                                    <h4 class="address-title">{{ trans('orders.billing_address') }}</h4>
                                                    <p class="address-content">{{ $order->billing_address }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Notes -->
                                @if ($order->notes)
                                    <div class="notes-section">
                                        <h3 class="section-title">{{ trans('orders.notes') }}</h3>
                                        <div class="notes-content">
                                            {{ $order->notes }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="col-lg-4 mb-4">
                        <div class="order-summary-card">
                            <div class="order-summary-content">
                                <h3 class="summary-title">{{ trans('orders.order_summary') }}</h3>

                                <div class="summary-item">
                                    <span class="summary-label">{{ trans('orders.order_number') }}:</span>
                                    <span class="summary-value">{{ $order->order_number }}</span>
                                </div>

                                <div class="summary-item">
                                    <span class="summary-label">{{ trans('orders.status') }}:</span>
                                    <span
                                        class="summary-status {{ $order->status }}">{{ trans('orders.' . $order->status) }}</span>
                                </div>

                                <div class="summary-item">
                                    <span class="summary-label">{{ trans('orders.items_count') }}:</span>
                                    <span class="summary-value">{{ $order->items->count() }}</span>
                                </div>

                                <div class="summary-item">
                                    <span class="summary-label">{{ trans('orders.subtotal') }}:</span>
                                    <span class="summary-value">{{ number_format($order->subtotal, 2) }}
                                        {{ trans('orders.currency') }}</span>
                                </div>

                                <div class="summary-item">
                                    <span class="summary-label">{{ trans('orders.tax') }}:</span>
                                    <span class="summary-value">{{ number_format($order->tax, 2) }}
                                        {{ trans('orders.currency') }}</span>
                                </div>

                                <div class="summary-total">
                                    <div class="summary-item">
                                        <span class="summary-label">{{ trans('orders.total') }}:</span>
                                        <span class="summary-total-value px-2">{{ number_format($order->total, 2) }}
                                            {{ trans('orders.currency') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="row">
                    <div class="col-12">
                        <div class="order-items-card">
                            <div class="order-items-content">
                                <h3 class="section-title">
                                    {{ trans('orders.order_items') }} ({{ $order->items->count() }})
                                </h3>
                                @livewire('order-items-manager', ['order' => $order])
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
        /* Order Show Page Styles - Based on Product Show Page Design */
        .order-show-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, #FFEBC6 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Styles */
        .order-show-header {
            background: linear-gradient(135deg, #2C2C2C 0%, #404040 100%);
            color: #FFEBC6;
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .order-show-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 235, 198, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Breadcrumb */
        .breadcrumb-nav {
            margin-bottom: 1rem;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '>';
            margin: 0 0.5rem;
            color: #FFEBC6;
            opacity: 0.7;
        }

        .breadcrumb-link {
            color: #FFEBC6;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .breadcrumb-link:hover {
            color: #FFD700;
        }

        .breadcrumb-item.active {
            color: #FFEBC6;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .order-show-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: #FFEBC6;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease forwards;
            position: relative;
            z-index: 1;
        }

        .order-show-subtitle {
            font-size: 1.2rem;
            color: #FFEBC6;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease 0.2s forwards;
            opacity: 0;
            position: relative;
            z-index: 1;
        }

        /* Back Button */
        .back-btn {
            background: #FFEBC6;
            color: #2C2C2C;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(255, 235, 198, 0.3);
            position: relative;
            z-index: 1;
        }

        .back-btn:hover {
            background: #FFD700;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 235, 198, 0.4);
            color: #2C2C2C;
        }

        /* Order Details Section */
        .order-details-section {
            padding: 2rem 0;
        }

        /* Order Info Card */
        .order-info-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 2rem;
            height: 100%;
        }

        .order-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #c4a700;
        }

        .order-info-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* Order Header Section */
        .order-header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .order-number-badge {
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            color: #2C2C2C;
            border: 1px solid #c4a700;
            font-weight: 700;
            font-size: 1.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 20px;
            display: inline-block;
            font-family: 'Cairo', cursive;
        }

        .order-status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 2px solid;
        }

        .order-status-badge.pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-color: #ffc107;
        }

        .order-status-badge.confirmed {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-color: #17a2b8;
        }

        .order-status-badge.processing {
            background: linear-gradient(135deg, #e2e3f0 0%, #c7c9e8 100%);
            color: #383d61;
            border-color: #6f42c1;
        }

        .order-status-badge.shipped {
            background: linear-gradient(135deg, #cce5ff 0%, #99d6ff 100%);
            color: #004085;
            border-color: #007bff;
        }

        .order-status-badge.delivered {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-color: #28a745;
        }

        .order-status-badge.cancelled {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-color: #dc3545;
        }

        /* Order Details Grid */
        .order-details-grid {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .order-detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-detail-item:last-child {
            border-bottom: none;
        }

        .order-detail-item.total {
            border-top: 2px solid #c4a700;
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .order-detail-label {
            font-weight: 600;
            color: #2C2C2C;
        }

        .order-detail-value {
            color: #666;
            font-weight: 500;
        }

        /* Section Titles */
        .section-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2C2C2C;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Contact Info Section */
        .contact-info-section {
            margin-top: 1.5rem;
        }

        .contact-info-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #e5e7eb;
        }

        .contact-label {
            font-weight: 600;
            color: #2C2C2C;
            margin-left: 0.5rem;
        }

        .contact-value {
            color: #666;
        }

        /* Addresses Section */
        .addresses-section {
            margin-top: 1.5rem;
        }

        .addresses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }

        .address-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .address-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .address-title {
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .address-content {
            color: #666;
            line-height: 1.6;
            white-space: pre-line;
        }

        /* Notes Section */
        .notes-section {
            margin-top: 1.5rem;
        }

        .notes-content {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            color: #666;
            line-height: 1.6;
            white-space: pre-line;
        }

        /* Order Summary Card */
        .order-summary-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 2rem;
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .order-summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #c4a700;
        }

        .order-summary-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .summary-title {
            font-family: 'Cairo', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2C2C2C;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .summary-label {
            font-weight: 600;
            color: #2C2C2C;
        }

        .summary-value {
            color: #666;
            font-weight: 500;
        }

        .summary-status {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid;
        }

        .summary-status.pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-color: #ffc107;
        }

        .summary-status.confirmed {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-color: #17a2b8;
        }

        .summary-status.processing {
            background: linear-gradient(135deg, #e2e3f0 0%, #c7c9e8 100%);
            color: #383d61;
            border-color: #6f42c1;
        }

        .summary-status.shipped {
            background: linear-gradient(135deg, #cce5ff 0%, #99d6ff 100%);
            color: #004085;
            border-color: #007bff;
        }

        .summary-status.delivered {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-color: #28a745;
        }

        .summary-status.cancelled {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-color: #dc3545;
        }

        .summary-total {
            border-top: 2px solid #e5e7eb;
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .summary-total-value {
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 1.3rem;
            font-weight: 700;
        }

        /* Order Items Card */
        .order-items-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 2rem;
            margin-top: 2rem;
        }

        .order-items-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #c4a700;
        }

        .order-items-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .order-show-title {
                font-size: 2.5rem;
            }

            .order-show-subtitle {
                font-size: 1rem;
            }

            .back-btn {
                padding: 0.6rem 1.2rem;
                font-size: 1rem;
            }

            .addresses-grid {
                grid-template-columns: 1fr;
            }

            .order-header-section {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .order-summary-card {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .order-show-header {
                padding: 2rem 0;
            }

            .order-show-title {
                font-size: 2rem;
            }

            .order-show-subtitle {
                font-size: 0.9rem;
            }

            .breadcrumb-list {
                flex-direction: column;
                align-items: flex-start;
            }

            .breadcrumb-item:not(:last-child)::after {
                display: none;
            }
        }
    </style>
@endsection
