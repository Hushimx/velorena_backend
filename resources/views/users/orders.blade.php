@extends('components.layout')

@section('pageTitle', trans('طلباتي'))
@section('title', trans('طلباتي'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="orders-page">
        <!-- Header Section -->
        <div class="orders-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <!-- Breadcrumb -->
                        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                            <ol class="breadcrumb-list">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}" class="breadcrumb-link">
                                        <i class="fas fa-home"></i>
                                        {{ trans('الرئيسية') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.index') }}" class="breadcrumb-link">
                                        <i class="fas fa-user-circle"></i>
                                        {{ trans('حسابي') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('طلباتي') }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="orders-title">{{ trans('طلباتي') }}</h1>
                        <p class="orders-subtitle">{{ trans('عرض وإدارة جميع طلباتك') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <div class="header-actions">
                            <a href="{{ route('client.index') }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                <span>{{ trans('العودة للحساب') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Content -->
        <div class="orders-content-section">
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
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $orders->total() }}</span>
                            <span class="stat-label">{{ trans('إجمالي الطلبات') }}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $orders->where('status', 'pending')->count() }}</span>
                            <span class="stat-label">{{ trans('في الانتظار') }}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $orders->where('status', 'shipped')->count() }}</span>
                            <span class="stat-label">{{ trans('قيد الشحن') }}</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $orders->where('status', 'delivered')->count() }}</span>
                            <span class="stat-label">{{ trans('مكتملة') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                @if ($orders->count() > 0)
                    <div class="orders-list">
                        @foreach ($orders as $order)
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="order-info">
                                        <div class="order-icon">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div class="order-details">
                                            <h5 class="order-title">{{ trans('طلب رقم') }} #{{ $order->order_number }}</h5>
                                            <p class="order-date">{{ $order->created_at->format('d/m/Y - H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge status-{{ $order->status }}">
                                            @switch($order->status)
                                                @case('pending')
                                                    {{ trans('في الانتظار') }}
                                                    @break
                                                @case('confirmed')
                                                    {{ trans('مؤكد') }}
                                                    @break
                                                @case('processing')
                                                    {{ trans('قيد المعالجة') }}
                                                    @break
                                                @case('shipped')
                                                    {{ trans('تم الشحن') }}
                                                    @break
                                                @case('delivered')
                                                    {{ trans('تم التسليم') }}
                                                    @break
                                                @case('cancelled')
                                                    {{ trans('ملغي') }}
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>

                                <div class="order-content">
                                    <!-- Order Items -->
                                    <div class="order-items-section">
                                        <h6 class="section-title">
                                            <i class="fas fa-box me-2"></i>{{ trans('عناصر الطلب') }}
                                        </h6>
                                        <div class="items-list">
                                            @foreach($order->items as $item)
                                                <div class="item-card">
                                                    <div class="item-image">
                                                        @if($item->product->images && count($item->product->images) > 0)
                                                            <img src="{{ Storage::url($item->product->images[0]) }}" alt="{{ $item->product->name }}">
                                                        @else
                                                            <div class="no-image">
                                                                <i class="fas fa-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="item-details">
                                                        <h6 class="item-name">{{ $item->product->name }}</h6>
                                                        <p class="item-quantity">{{ trans('الكمية') }}: {{ $item->quantity }}</p>
                                                        <p class="item-price">{{ number_format($item->total_price, 2) }} {{ trans('ريال') }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="order-summary">
                                        <div class="summary-row">
                                            <span>{{ trans('المجموع الفرعي') }}:</span>
                                            <span>{{ number_format($order->subtotal, 2) }} {{ trans('ريال') }}</span>
                                        </div>
                                        <div class="summary-row">
                                            <span>{{ trans('الضريبة') }}:</span>
                                            <span>{{ number_format($order->tax, 2) }} {{ trans('ريال') }}</span>
                                        </div>
                                        <div class="summary-row total">
                                            <span>{{ trans('المجموع الكلي') }}:</span>
                                            <span>{{ number_format($order->total, 2) }} {{ trans('ريال') }}</span>
                                        </div>
                                    </div>

                                    <!-- Order Actions -->
                                    <div class="order-actions">
                                        <a href="{{ route('client.order.details', $order->id) }}" class="btn-primary">
                                            <i class="fas fa-eye me-2"></i>{{ trans('عرض التفاصيل') }}
                                        </a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('user.orders.destroy', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger" 
                                                        onclick="return confirm('{{ trans('هل أنت متأكد من إلغاء هذا الطلب؟') }}')">
                                                    <i class="fas fa-times me-2"></i>{{ trans('إلغاء الطلب') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($orders->hasPages())
                        <div class="pagination-section">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-content">
                            <div class="empty-state-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h4 class="empty-state-title">{{ trans('لا توجد طلبات حتى الآن') }}</h4>
                            <p class="empty-state-message">{{ trans('ابدأ بالتسوق وإنشاء طلبك الأول') }}</p>
                            <a href="{{ route('user.products.index') }}" class="empty-state-action">
                                <i class="fas fa-shopping-cart me-2"></i>
                                {{ trans('تصفح المنتجات') }}
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
        /* Orders Page Styles - Using Brand Colors */
        .orders-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Section */
        .orders-header {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        .orders-header::before {
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
            color: var(--brand-yellow);
            font-weight: 700;
        }

        .breadcrumb-link {
            color: var(--brand-yellow);
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
        .orders-title {
            font-size: 3rem;
            font-weight: 900;
            color: var(--brand-yellow);
            margin: 0 0 1rem 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .orders-subtitle {
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

        .back-btn {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            color: var(--brand-brown);
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
            background: linear-gradient(135deg, var(--brand-yellow-dark) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(255, 222, 159, 0.4);
            transform: translateY(-2px);
        }

        /* Content Section */
        .orders-content-section {
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
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown);
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
            color: var(--brand-brown);
            line-height: 1;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Orders List */
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .order-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .order-header {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.1) 100%);
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 222, 159, 0.3);
        }

        .order-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .order-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown);
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
        }

        .order-details {
            display: flex;
            flex-direction: column;
        }

        .order-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin: 0 0 0.25rem 0;
        }

        .order-date {
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

        .status-confirmed {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-processing {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-shipped {
            background: linear-gradient(135deg, #cce5ff 0%, #b3d9ff 100%);
            color: #004085;
            border: 1px solid #b3d9ff;
        }

        .status-delivered {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Order Content */
        .order-content {
            padding: 2rem;
        }

        .section-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        /* Items List */
        .items-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .item-card {
            background: linear-gradient(135deg, rgba(255, 222, 159, 0.1) 0%, rgba(255, 222, 159, 0.05) 100%);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(255, 222, 159, 0.3);
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.3) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown);
            font-size: 1.5rem;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: var(--brand-brown);
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
        }

        .item-quantity, .item-price {
            color: #6c757d;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Order Summary */
        .order-summary {
            background: linear-gradient(135deg, rgba(255, 222, 159, 0.1) 0%, rgba(255, 222, 159, 0.05) 100%);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 222, 159, 0.3);
            margin-bottom: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--brand-brown);
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 222, 159, 0.3);
        }

        /* Order Actions */
        .order-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-primary, .btn-danger {
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
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
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
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.3) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: var(--brand-brown);
            font-size: 3rem;
        }

        .empty-state-title {
            color: var(--brand-brown);
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
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
            transition: all 0.3s ease;
        }

        .empty-state-action:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .orders-title {
                font-size: 2rem;
            }

            .orders-subtitle {
                font-size: 1rem;
            }

            .header-actions {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }

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

            .order-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .order-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn-primary, .btn-danger {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .orders-header {
                padding: 2rem 0;
            }

            .orders-content-section {
                padding: 2rem 0;
            }

            .orders-title {
                font-size: 1.75rem;
            }

            .breadcrumb-list {
                flex-direction: column;
                align-items: flex-start;
            }

            .breadcrumb-item:not(:last-child)::after {
                display: none;
            }

            .order-content {
                padding: 1.5rem;
            }

            .item-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection
