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
                                    <span class="summary-label">{{ trans('orders.payment_status') }}:</span>
                                    <span class="summary-payment-status {{ $order->getPaymentStatus() }}">
                                        {{ trans('orders.' . $order->getPaymentStatus()) }}
                                    </span>
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

                                <!-- Payment Action Button -->
                                @if($order->canMakePayment())
                                    <div class="payment-action-section">
                                        <form action="{{ route('user.orders.initiate-payment', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="pay-btn">
                                                <i class="fas fa-credit-card"></i>
                                                <span>{{ trans('orders.pay_now') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                @elseif($order->isPaid())
                                    <div class="payment-status-section">
                                        <div class="payment-completed-badge">
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ trans('orders.payment_processed') }}</span>
                                        </div>
                                    </div>
                                @elseif($order->payments()->exists())
                                    <div class="payment-action-section">
                                        <form action="{{ route('user.orders.check-payment', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="check-payment-btn">
                                                <i class="fas fa-sync-alt"></i>
                                                <span>{{ trans('orders.check_payment_status') }}</span>
                                            </button>
                                        </form>
                                        <small class="text-muted d-block mt-2">
                                            {{ trans('orders.payment_status_help') }}
                                        </small>
                                    </div>
                                @endif
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
        /* Order Show Page Styles - Professional Theme */
        .order-show-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Styles */
        .order-show-header {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: var(--brand-yellow);
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .order-show-header::before {
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
            color: var(--brand-yellow);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .breadcrumb-link:hover {
            color: #fff;
        }

        .breadcrumb-item.active {
            color: var(--brand-yellow);
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .order-show-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--brand-yellow);
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease forwards;
            position: relative;
            z-index: 1;
        }

        .order-show-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            opacity: 0.9;
            animation: fadeInUp 0.8s ease 0.2s forwards;
            opacity: 0;
            position: relative;
            z-index: 1;
        }

        /* Back Button */
        .back-btn {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            color: var(--brand-brown);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
            position: relative;
            z-index: 1;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow-dark) 0%, var(--brand-yellow) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 222, 159, 0.4);
            color: var(--brand-brown);
        }

        /* Order Details Section */
        .order-details-section {
            padding: 2rem 0;
        }

        /* Order Info Card */
        .order-info-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border: 1px solid rgba(255, 222, 159, 0.2);
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 2.5rem;
            height: 100%;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        .order-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brand-yellow) 0%, var(--brand-brown) 50%, var(--brand-yellow) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .order-info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.08);
            border-color: rgba(255, 222, 159, 0.4);
        }

        .order-info-card:hover::before {
            opacity: 1;
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
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            color: var(--brand-brown);
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-weight: 700;
            font-size: 1.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 20px;
            display: inline-block;
            font-family: 'Cairo', cursive;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
        }

        .order-status-badge {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .order-status-badge.pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border-color: #f59e0b;
        }

        .order-status-badge.confirmed {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border-color: #3b82f6;
        }

        .order-status-badge.processing {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border-color: #22c55e;
        }

        .order-status-badge.shipped {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #3730a3;
            border-color: #6366f1;
        }

        .order-status-badge.delivered {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-color: #10b981;
        }

        .order-status-badge.cancelled {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-color: #ef4444;
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
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border: 1px solid rgba(255, 222, 159, 0.2);
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 2.5rem;
            height: fit-content;
            position: sticky;
            top: 2rem;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        .order-summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brand-yellow) 0%, var(--brand-brown) 50%, var(--brand-yellow) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .order-summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.08);
            border-color: rgba(255, 222, 159, 0.4);
        }

        .order-summary-card:hover::before {
            opacity: 1;
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

        /* Payment Status Styles */
        .summary-payment-status {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid;
        }

        .summary-payment-status.paid {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-color: #28a745;
        }

        .summary-payment-status.unpaid {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-color: #ffc107;
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

        /* Payment Action Styles */
        .payment-action-section {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 2px solid #e5e7eb;
        }

        .pay-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Cairo', cursive;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            width: 100%;
            justify-content: center;
        }

        .pay-btn:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .pay-btn:active {
            transform: translateY(0);
        }

        .check-payment-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-family: 'Cairo', cursive;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        .check-payment-btn:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }

        .payment-status-section {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 2px solid #e5e7eb;
        }

        .payment-completed-badge {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #28a745;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            justify-content: center;
            font-family: 'Cairo', cursive;
        }

        .payment-completed-badge i {
            font-size: 1.2rem;
        }

        /* Order Items Card */
        .order-items-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border: 1px solid rgba(255, 222, 159, 0.2);
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 2.5rem;
            margin-top: 2rem;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        .order-items-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--brand-yellow) 0%, var(--brand-brown) 50%, var(--brand-yellow) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .order-items-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.08);
            border-color: rgba(255, 222, 159, 0.4);
        }

        .order-items-card:hover::before {
            opacity: 1;
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
