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
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <div class="order-details">
                                            <h5 class="order-title">{{ trans('طلب رقم') }} #{{ $order->order_number }}</h5>
                                            <p class="order-date">{{ $order->created_at->format('d/m/Y - H:i') }}</p>
                                            <p class="order-total">{{ number_format($order->total, 2) }} {{ trans('ريال') }}</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="order-content">
                                    <!-- Order Actions -->
                                    <div class="order-actions">
                                        <a href="{{ route('user.orders.show', $order->id) }}" class="btn-primary">
                                            <i class="fas fa-eye me-2"></i>{{ trans('عرض التفاصيل') }}
                                        </a>
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
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 222, 159, 0.2);
            overflow: hidden;
            backdrop-filter: blur(20px);
            padding: 2.5rem;
            display: flex;
            align-items: center;
            gap: 1.75rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--brand-yellow) 0%, var(--brand-brown) 50%, var(--brand-yellow) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.08);
            border-color: rgba(255, 222, 159, 0.4);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown);
            font-size: 1.6rem;
            box-shadow: 0 6px 20px rgba(255, 222, 159, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 2.25rem;
            font-weight: 900;
            color: var(--brand-brown);
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        /* Orders List */
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .order-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 222, 159, 0.2);
            overflow: hidden;
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .order-card::before {
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

        .order-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
            border-color: rgba(255, 222, 159, 0.4);
        }

        .order-card:hover::before {
            opacity: 1;
        }

        .order-header {
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, rgba(255, 222, 159, 0.05) 0%, rgba(255, 222, 159, 0.02) 100%);
            border-bottom: 1px solid rgba(255, 222, 159, 0.1);
        }

        .order-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .order-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-yellow);
            font-size: 1.6rem;
            box-shadow: 0 8px 25px rgba(42, 30, 30, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.4);
            position: relative;
            transition: all 0.3s ease;
        }

        .order-icon::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-brown) 100%);
            border-radius: 50%;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .order-card:hover .order-icon::before {
            opacity: 0.3;
        }

        .order-details {
            display: flex;
            flex-direction: column;
        }

        .order-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--brand-brown);
            margin: 0 0 0.75rem 0;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .order-date {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0 0 1rem 0;
            font-weight: 500;
        }

        .order-total {
            color: var(--brand-brown);
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.01em;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.875rem 1.75rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .status-badge:hover::before {
            left: 100%;
        }

        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border-color: #f59e0b;
        }

        .status-confirmed {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border-color: #3b82f6;
        }

        .status-processing {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border-color: #22c55e;
        }

        .status-shipped {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #3730a3;
            border-color: #6366f1;
        }

        .status-delivered {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-color: #10b981;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-color: #ef4444;
        }

        /* Order Content */
        .order-content {
            padding: 2rem 2rem 2.5rem 2rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 222, 159, 0.1);
        }




        /* Order Actions */
        .order-actions {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .btn-primary, .btn-danger {
            padding: 1rem 2.25rem;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(42, 30, 30, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(42, 30, 30, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .btn-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn-danger:hover::before {
            left: 100%;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(239, 68, 68, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
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
                gap: 1.5rem;
                align-items: flex-start;
                padding: 1.5rem;
            }

            .order-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-primary, .btn-danger {
                width: 100%;
                justify-content: center;
                padding: 1rem 1.5rem;
                font-size: 0.9rem;
            }

            .order-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .order-title {
                font-size: 1.2rem;
            }

            .order-total {
                font-size: 1.1rem;
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
                padding: 1rem 1.5rem 1.5rem 1.5rem;
            }

            .order-header {
                padding: 1.25rem;
            }

            .order-title {
                font-size: 1.1rem;
            }

            .order-total {
                font-size: 1rem;
            }

            .status-badge {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
        }
    </style>
@endsection
