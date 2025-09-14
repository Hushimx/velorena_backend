@extends('layouts.app')

@section('title', trans('Order Details'))

@section('content')
<div class="order-details-page">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <a href="{{ route('client.orders') }}" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                {{ trans('العودة للطلبات') }}
            </a>
            <h1>{{ trans('تفاصيل الطلب') }} #{{ $order->order_number }}</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="order-items-section">
                    <h2>{{ trans('عناصر الطلب') }}</h2>
                    <div class="items-list">
                        @foreach($order->items as $item)
                            <div class="item-card">
                                <div class="item-info">
                                    <h3>{{ $item->product->name_ar ?? $item->product->name }}</h3>
                                    <p class="item-description">{{ $item->product->description_ar ?? $item->product->description }}</p>
                                    <div class="item-specs">
                                        <span class="quantity">{{ trans('الكمية') }}: {{ $item->quantity }}</span>
                                        @if($item->selected_options)
                                            @foreach(json_decode($item->selected_options, true) as $optionId => $valueId)
                                                @php
                                                    $option = $item->product->options->find($optionId);
                                                    $value = $option ? $option->values->find($valueId) : null;
                                                @endphp
                                                @if($option && $value)
                                                    <span class="option">{{ $option->name_ar ?? $option->name }}: {{ $value->value_ar ?? $value->value }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="item-price">
                                    <span class="price">{{ $item->total_price }} {{ trans('ريال') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>{{ trans('ملخص الطلب') }}</h3>
                    
                    <div class="summary-item">
                        <span>{{ trans('رقم الطلب') }}</span>
                        <span>#{{ $order->order_number }}</span>
                    </div>
                    
                    <div class="summary-item">
                        <span>{{ trans('تاريخ الطلب') }}</span>
                        <span>{{ $order->created_at->format('d-m-Y H:i') }}</span>
                    </div>
                    
                    <div class="summary-item">
                        <span>{{ trans('حالة الطلب') }}</span>
                        <span class="status status-{{ $order->status }}">
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
                    
                    <div class="summary-item">
                        <span>{{ trans('المجموع الفرعي') }}</span>
                        <span>{{ $order->subtotal }} {{ trans('ريال') }}</span>
                    </div>
                    
                    @if($order->tax > 0)
                    <div class="summary-item">
                        <span>{{ trans('الضريبة') }}</span>
                        <span>{{ $order->tax }} {{ trans('ريال') }}</span>
                    </div>
                    @endif
                    
                    <div class="summary-item total">
                        <span>{{ trans('المجموع الكلي') }}</span>
                        <span>{{ $order->total }} {{ trans('ريال') }}</span>
                    </div>
                </div>

                <!-- Shipping Information -->
                @if($order->shipping_address)
                <div class="shipping-info">
                    <h3>{{ trans('معلومات الشحن') }}</h3>
                    <div class="address-info">
                        <p><strong>{{ trans('عنوان الشحن') }}:</strong></p>
                        <p>{{ $order->shipping_address }}</p>
                        @if($order->phone)
                            <p><strong>{{ trans('رقم الهاتف') }}:</strong> {{ $order->phone }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Appointment Information -->
                @if($order->appointment)
                <div class="appointment-info">
                    <h3>{{ trans('معلومات الموعد') }}</h3>
                    <div class="appointment-details">
                        <p><strong>{{ trans('تاريخ الموعد') }}:</strong> {{ $order->appointment->appointment_date->format('d-m-Y') }}</p>
                        <p><strong>{{ trans('وقت الموعد') }}:</strong> {{ $order->appointment->appointment_time->format('H:i') }}</p>
                        @if($order->appointment->designer)
                            <p><strong>{{ trans('المصمم') }}:</strong> {{ $order->appointment->designer->name }}</p>
                        @endif
                        <p><strong>{{ trans('حالة الموعد') }}:</strong> 
                            <span class="status status-{{ $order->appointment->status }}">
                                @switch($order->appointment->status)
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
                            </span>
                        </p>
                        @if($order->appointment->zoom_meeting_url)
                            <a href="{{ $order->appointment->zoom_meeting_url }}" target="_blank" class="btn-join-meeting">
                                {{ trans('انضم للاجتماع') }}
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.order-details-page {
    padding: 2rem 0;
    background: #f5f5f5;
    min-height: 100vh;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.back-btn {
    background: #8B4513;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.back-btn:hover {
    background: #A0522D;
    color: white;
}

.page-header h1 {
    color: #8B4513;
    margin: 0;
    font-size: 2rem;
}

.order-items-section, .order-summary, .shipping-info, .appointment-info {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.order-items-section h2, .order-summary h3, .shipping-info h3, .appointment-info h3 {
    color: #8B4513;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.item-card {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.item-info h3 {
    color: #333;
    margin-bottom: 0.5rem;
}

.item-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.item-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.9rem;
}

.item-specs span {
    background: #f8f9fa;
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
    color: #666;
}

.item-price .price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #8B4513;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.summary-item.total {
    border-top: 2px solid #8B4513;
    border-bottom: none;
    font-weight: 700;
    font-size: 1.1rem;
    color: #8B4513;
    margin-top: 1rem;
    padding-top: 1rem;
}

.status {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d1ecf1; color: #0c5460; }
.status-processing { background: #d4edda; color: #155724; }
.status-shipped { background: #cce5ff; color: #004085; }
.status-delivered { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.status-accepted { background: #d4edda; color: #155724; }
.status-rejected { background: #f8d7da; color: #721c24; }
.status-completed { background: #d4edda; color: #155724; }

.btn-join-meeting {
    background: #28a745;
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    margin-top: 1rem;
    transition: background 0.3s ease;
}

.btn-join-meeting:hover {
    background: #218838;
    color: white;
}

.address-info p {
    margin-bottom: 0.5rem;
    color: #666;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
    }
    
    .item-card {
        flex-direction: column;
        gap: 1rem;
    }
    
    .item-specs {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
@endpush
