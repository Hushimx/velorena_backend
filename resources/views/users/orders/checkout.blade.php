@extends('components.layout')

@section('pageTitle', trans('orders.checkout') . ' - ' . $order->order_number)
@section('title', trans('orders.checkout'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="checkout-page">
        <!-- Header Section -->
        <div class="checkout-header">
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
                                        {{ trans('orders.orders') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('user.orders.show', $order) }}" class="breadcrumb-link">
                                        {{ trans('orders.order_details') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('orders.checkout') }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="checkout-title">{{ trans('orders.checkout') }}</h1>
                        <p class="checkout-subtitle">{{ trans('orders.complete_payment_for_order') }} #{{ $order->order_number }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <a href="{{ route('user.orders.show', $order) }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ trans('orders.back_to_order') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Content -->
        <div class="checkout-content-section">
            <div class="container">
                <form action="{{ route('user.orders.process-payment', $order) }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="row">
                        <!-- Left Column - Address and Contact Info -->
                        <div class="col-lg-8 mb-4">
                            <!-- Address Selection -->
                            <div class="checkout-section">
                                <div class="section-header">
                                    <h3 class="section-title">{{ trans('orders.shipping_address') }}</h3>
                                </div>
                                
                                <div class="address-selection">
                                    @if($addresses->count() > 0)
                                        <div class="existing-addresses">
                                            <h4 class="subsection-title">{{ trans('orders.choose_existing_address') }}</h4>
                                            @foreach($addresses as $address)
                                                <div class="address-option">
                                                    <input type="radio" name="address_id" value="{{ $address->id }}" id="address_{{ $address->id }}" class="address-radio">
                                                    <label for="address_{{ $address->id }}" class="address-label">
                                                        <div class="address-info">
                                                            <div class="address-name">{{ $address->name }}</div>
                                                            <div class="address-details">{{ $address->full_address }}</div>
                                                            @if($address->is_default)
                                                                <span class="default-badge">{{ trans('orders.default') }}</span>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="address-divider">
                                            <span>{{ trans('orders.or') }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="new-address">
                                        <h4 class="subsection-title">{{ trans('orders.add_new_address') }}</h4>
                                        <div class="form-group">
                                            <label for="shipping_address">{{ trans('orders.shipping_address') }} *</label>
                                            <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" placeholder="{{ trans('orders.enter_shipping_address') }}">{{ old('shipping_address') }}</textarea>
                                            @error('shipping_address')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="billing_address">{{ trans('orders.billing_address') }}</label>
                                            <textarea name="billing_address" id="billing_address" class="form-control" rows="3" placeholder="{{ trans('orders.enter_billing_address') }}">{{ old('billing_address') }}</textarea>
                                            @error('billing_address')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="checkout-section">
                                <div class="section-header">
                                    <h3 class="section-title">{{ trans('orders.contact_information') }}</h3>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">{{ trans('orders.phone_number') }} *</label>
                                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone', $order->phone) }}" placeholder="{{ trans('orders.enter_phone_number') }}" required>
                                    @error('phone')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Order Summary -->
                        <div class="col-lg-4 mb-4">
                            <div class="order-summary-card">
                                <div class="summary-header">
                                    <h3 class="summary-title">{{ trans('orders.order_summary') }}</h3>
                                    <div class="order-number">{{ trans('orders.order') }} #{{ $order->order_number }}</div>
                                </div>

                                <!-- Order Items -->
                                <div class="order-items">
                                    <h4 class="items-title">{{ trans('orders.order_items') }} ({{ $order->items->count() }})</h4>
                                    @foreach($order->items as $item)
                                        <div class="order-item">
                                            <div class="item-info">
                                                <div class="item-name">{{ $item->product->name }}</div>
                                                <div class="item-quantity">{{ trans('orders.quantity') }}: {{ $item->quantity }}</div>
                                                @if($item->notes)
                                                    <div class="item-notes">{{ $item->notes }}</div>
                                                @endif
                                            </div>
                                            <div class="item-price">{{ number_format($item->total_price, 2) }} {{ trans('orders.currency') }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Price Breakdown -->
                                <div class="price-breakdown">
                                    <div class="price-row">
                                        <span class="price-label">{{ trans('orders.subtotal') }}</span>
                                        <span class="price-value">{{ number_format($order->subtotal, 2) }} {{ trans('orders.currency') }}</span>
                                    </div>
                                    <div class="price-row">
                                        <span class="price-label">{{ trans('orders.tax') }} (15%)</span>
                                        <span class="price-value">{{ number_format($order->tax, 2) }} {{ trans('orders.currency') }}</span>
                                    </div>
                                    <div class="price-row total">
                                        <span class="price-label">{{ trans('orders.total') }}</span>
                                        <span class="price-value">{{ number_format($order->total, 2) }} {{ trans('orders.currency') }}</span>
                                    </div>
                                </div>

                                <!-- Payment Button -->
                                <div class="payment-section">
                                    <button type="submit" class="pay-now-btn">
                                        <i class="fas fa-credit-card"></i>
                                        <span>{{ trans('orders.pay_now') }} - {{ number_format($order->total, 2) }} {{ trans('orders.currency') }}</span>
                                    </button>
                                    <div class="payment-note">
                                        {{ trans('orders.secure_payment_note') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Checkout Page Styles - Professional Theme */
        .checkout-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Styles */
        .checkout-header {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: var(--brand-yellow);
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .checkout-header::before {
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

        .checkout-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--brand-yellow);
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease forwards;
            position: relative;
            z-index: 1;
        }

        .checkout-subtitle {
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

        /* Checkout Content */
        .checkout-content-section {
            padding: 2rem 0;
        }

        /* Checkout Sections */
        .checkout-section {
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border: 1px solid rgba(255, 222, 159, 0.2);
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        .checkout-section::before {
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

        .checkout-section:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.08);
            border-color: rgba(255, 222, 159, 0.4);
        }

        .checkout-section:hover::before {
            opacity: 1;
        }

        .section-header {
            margin-bottom: 2rem;
        }

        .section-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2C2C2C;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .subsection-title {
            font-family: 'Cairo', cursive;
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        /* Address Selection */
        .address-selection {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .existing-addresses {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .address-option {
            position: relative;
        }

        .address-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .address-label {
            display: block;
            padding: 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .address-radio:checked + .address-label {
            border-color: var(--brand-yellow);
            background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
        }

        .address-info {
            position: relative;
        }

        .address-name {
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .address-details {
            color: #666;
            line-height: 1.6;
        }

        .default-badge {
            position: absolute;
            top: 0;
            left: 0;
            background: var(--brand-yellow);
            color: var(--brand-brown);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .address-divider {
            text-align: center;
            position: relative;
            margin: 1.5rem 0;
        }

        .address-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .address-divider span {
            background: #ffffff;
            padding: 0 1rem;
            color: #666;
            font-weight: 500;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2C2C2C;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand-yellow);
            box-shadow: 0 0 0 3px rgba(255, 222, 159, 0.2);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
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

        .summary-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .summary-title {
            font-family: 'Cairo', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2C2C2C;
            margin-bottom: 0.5rem;
        }

        .order-number {
            color: #666;
            font-size: 0.9rem;
        }

        /* Order Items */
        .order-items {
            margin-bottom: 2rem;
        }

        .items-title {
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
            margin-left: 1rem;
        }

        .item-name {
            font-weight: 600;
            color: #2C2C2C;
            margin-bottom: 0.25rem;
        }

        .item-quantity {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .item-notes {
            color: #666;
            font-size: 0.85rem;
            font-style: italic;
        }

        .item-price {
            font-weight: 600;
            color: #2C2C2C;
        }

        /* Price Breakdown */
        .price-breakdown {
            margin-bottom: 2rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .price-row:last-child {
            border-bottom: none;
        }

        .price-row.total {
            border-top: 2px solid #c4a700;
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .price-label {
            color: #2C2C2C;
        }

        .price-value {
            color: #2C2C2C;
            font-weight: 600;
        }

        .price-row.total .price-value {
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 1.3rem;
        }

        /* Payment Section */
        .payment-section {
            padding-top: 1rem;
            border-top: 2px solid #e5e7eb;
        }

        .pay-now-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            width: 100%;
            margin-bottom: 1rem;
        }

        .pay-now-btn:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .pay-now-btn:active {
            transform: translateY(0);
        }

        .payment-note {
            font-size: 0.85rem;
            color: #666;
            text-align: center;
            line-height: 1.5;
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
            .checkout-title {
                font-size: 2.5rem;
            }

            .checkout-subtitle {
                font-size: 1rem;
            }

            .back-btn {
                padding: 0.6rem 1.2rem;
                font-size: 1rem;
            }

            .order-summary-card {
                position: static;
            }

            .address-selection {
                gap: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .checkout-header {
                padding: 2rem 0;
            }

            .checkout-title {
                font-size: 2rem;
            }

            .checkout-subtitle {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle address selection
            const addressRadios = document.querySelectorAll('input[name="address_id"]');
            const newAddressFields = document.querySelectorAll('#shipping_address, #billing_address');
            
            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        newAddressFields.forEach(field => {
                            field.required = false;
                            field.value = '';
                        });
                    }
                });
            });
            
            // Handle new address input
            newAddressFields.forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        addressRadios.forEach(radio => {
                            radio.checked = false;
                        });
                        this.required = true;
                    }
                });
            });
            
            // Form validation
            const form = document.getElementById('checkout-form');
            form.addEventListener('submit', function(e) {
                const hasSelectedAddress = Array.from(addressRadios).some(radio => radio.checked);
                const hasNewAddress = document.getElementById('shipping_address').value.trim() !== '';
                
                if (!hasSelectedAddress && !hasNewAddress) {
                    e.preventDefault();
                    alert('{{ trans("orders.please_select_or_add_address") }}');
                    return false;
                }
            });
        });
    </script>
@endsection

