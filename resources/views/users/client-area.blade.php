@extends('components.layout')

@section('pageTitle', trans('حسابي'))
@section('title', trans('حسابي'))


@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="client-area-page">
        <div class="container">
            <!-- Header -->
            <div class="client-header">
                <div class="user-info">
                    <div class="user-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details">
                        <h1>{{ trans('حسابي') }}</h1>
                        <p>{{ Auth::user()->full_name ?? Auth::user()->company_name }}</p>
                    </div>
                </div>
                <div class="logout-btn">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ trans('تسجيل الخروج') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Navigation Cards -->
            <div class="client-nav-grid">
                <a href="{{ route('client.orders') }}" class="nav-card {{ request()->routeIs('client.orders') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <span>{{ trans('طلباتي') }}</span>
                </a>
                
                <a href="{{ route('client.appointments') }}" class="nav-card {{ request()->routeIs('client.appointments') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span>{{ trans('مواعيدي') }}</span>
                </a>
                
                <a href="{{ route('user.products.index') }}" class="nav-card">
                    <div class="nav-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <span>{{ trans('المنتجات') }}</span>
                </a>
                
                <a href="{{ route('appointments.create') }}" class="nav-card">
                    <div class="nav-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <span>{{ trans('حجز موعد') }}</span>
                </a>
            </div>

            <!-- Content Section -->
            <div class="content-section">
            @if(request()->routeIs('client.orders'))
                <h2 class="section-title">{{ trans('سجل الطلبات') }}</h2>
                <div class="orders-list">
                    @forelse($orders as $order)
                        <div class="order-card">
                            <div class="order-info">
                                <div class="order-date">{{ $order->created_at->format('d-m-Y') }}</div>
                                <div class="order-number">#{{ $order->order_number }}</div>
                                <div class="order-location">{{ trans('ارسال الى') }}: {{ $order->shipping_address ?? 'غير محدد' }}</div>
                                <div class="order-status status-{{ $order->status }}">
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
                                </div>
                                <div class="order-total">{{ $order->total }} {{ trans('ريال') }}</div>
                            </div>
                            <div class="order-actions">
                                <a href="{{ route('client.order.details', $order->id) }}" class="btn-view">
                                    {{ trans('عرض التفاصيل') }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>{{ trans('لا توجد طلبات حتى الآن') }}</p>
                        </div>
                    @endforelse
                </div>
                
                {{ $orders->links() }}
                
            @elseif(request()->routeIs('client.appointments'))
                <h2 class="section-title">{{ trans('مواعيدي') }}</h2>
                <div class="appointments-list">
                    @forelse($appointments as $appointment)
                        <div class="appointment-card">
                            <div class="appointment-info">
                                <div class="appointment-date">{{ $appointment->appointment_date->format('d-m-Y') }}</div>
                                <div class="appointment-time">{{ $appointment->appointment_time->format('H:i') }}</div>
                                <div class="appointment-designer">
                                    {{ trans('المصمم') }}: {{ $appointment->designer->name ?? 'غير محدد' }}
                                </div>
                                <div class="appointment-status status-{{ $appointment->status }}">
                                    @switch($appointment->status)
                                        @case('pending')
                                            {{ trans('في الانتظار') }}
                                            @break
                                        @case('accepted')
                                            {{ trans('مقبول') }}
                                            @break
                                        @case('rejected')
                                            {{ trans('مرفوض') }}
                                            @break
                                        @case('completed')
                                            {{ trans('مكتمل') }}
                                            @break
                                        @case('cancelled')
                                            {{ trans('ملغي') }}
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            <div class="appointment-actions">
                                @if($appointment->zoom_meeting_url)
                                    <a href="{{ $appointment->zoom_meeting_url }}" target="_blank" class="btn-join">
                                        {{ trans('انضم للاجتماع') }}
                                    </a>
                                @endif
                                <a href="{{ route('client.appointment.details', $appointment->id) }}" class="btn-view">
                                    {{ trans('عرض التفاصيل') }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <p>{{ trans('لا توجد مواعيد حتى الآن') }}</p>
                        </div>
                    @endforelse
                </div>
                
                {{ $appointments->links() }}
                
            @else
                <!-- Dashboard Overview -->
                <div class="dashboard-overview">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">{{ $orders->total() }}</div>
                                <div class="stat-label">{{ trans('إجمالي الطلبات') }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">{{ $appointments->total() }}</div>
                                <div class="stat-label">{{ trans('إجمالي المواعيد') }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-label">{{ $orders->where('status', 'delivered')->count() }}</div>
                                <div class="stat-label">{{ trans('طلبات مكتملة') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Orders -->
                    <div class="recent-section">
                        <h3>{{ trans('الطلبات الأخيرة') }}</h3>
                        <div class="recent-orders">
                            @foreach($orders->take(3) as $order)
                                <div class="recent-order">
                                    <a href="{{ route('user.orders.show', $order->id) }}">
                                        <span class="order-num">#{{ $order->order_number }}</span>
                                    </a>
                                    <span class="order-status status-{{ $order->status }}">
                                        @switch($order->status)
                                            @case('delivered')
                                                {{ trans('تم التسليم') }}
                                                @break
                                            @case('pending')
                                                {{ trans('في الانتظار') }}
                                                @break
                                            @default
                                                {{ trans('قيد المعالجة') }}
                                        @endswitch
                                    </span>
                                    <span class="order-total">{{ $order->total }} {{ trans('ريال') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />
@endsection
