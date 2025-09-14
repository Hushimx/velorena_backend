@extends('layouts.app')

@section('title', trans('Appointment Details'))

@section('content')
<div class="appointment-details-page">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <a href="{{ route('client.appointments') }}" class="back-btn">
                <i class="fas fa-arrow-right"></i>
                {{ trans('العودة للمواعيد') }}
            </a>
            <h1>{{ trans('تفاصيل الموعد') }} #{{ $appointment->id }}</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Appointment Information -->
                <div class="appointment-info-section">
                    <h2>{{ trans('معلومات الموعد') }}</h2>
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>{{ trans('تاريخ الموعد') }}</h3>
                                <p>{{ $appointment->appointment_date->format('d-m-Y') }}</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>{{ trans('وقت الموعد') }}</h3>
                                <p>{{ $appointment->appointment_time->format('H:i') }}</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="info-content">
                                <h3>{{ trans('المصمم') }}</h3>
                                <p>{{ $appointment->designer->name ?? trans('غير محدد') }}</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="info-content">
                                <h3>{{ trans('حالة الموعد') }}</h3>
                                <p class="status status-{{ $appointment->status }}">
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
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($appointment->notes)
                    <div class="notes-section">
                        <h3>{{ trans('ملاحظاتك') }}</h3>
                        <div class="notes-content">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                    @endif

                    @if($appointment->designer_notes)
                    <div class="notes-section">
                        <h3>{{ trans('ملاحظات المصمم') }}</h3>
                        <div class="notes-content designer-notes">
                            {{ $appointment->designer_notes }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Meeting Information -->
                @if($appointment->zoom_meeting_url || $appointment->google_meet_link)
                <div class="meeting-section">
                    <h2>{{ trans('معلومات الاجتماع') }}</h2>
                    <div class="meeting-info">
                        @if($appointment->zoom_meeting_url)
                            <div class="meeting-item">
                                <div class="meeting-icon">
                                    <i class="fas fa-video"></i>
                                </div>
                                <div class="meeting-content">
                                    <h3>{{ trans('رابط اجتماع الزووم') }}</h3>
                                    <p>{{ $appointment->zoom_meeting_url }}</p>
                                    <a href="{{ $appointment->zoom_meeting_url }}" target="_blank" class="btn-join">
                                        {{ trans('انضم للاجتماع') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($appointment->google_meet_link)
                            <div class="meeting-item">
                                <div class="meeting-icon">
                                    <i class="fab fa-google"></i>
                                </div>
                                <div class="meeting-content">
                                    <h3>{{ trans('رابط اجتماع جوجل') }}</h3>
                                    <p>{{ $appointment->google_meet_link }}</p>
                                    <a href="{{ $appointment->google_meet_link }}" target="_blank" class="btn-join">
                                        {{ trans('انضم للاجتماع') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Order Information -->
                @if($appointment->order)
                <div class="order-info-section">
                    <h3>{{ trans('معلومات الطلب المرتبط') }}</h3>
                    
                    <div class="order-summary">
                        <div class="summary-item">
                            <span>{{ trans('رقم الطلب') }}</span>
                            <span>#{{ $appointment->order->order_number }}</span>
                        </div>
                        
                        <div class="summary-item">
                            <span>{{ trans('تاريخ الطلب') }}</span>
                            <span>{{ $appointment->order->created_at->format('d-m-Y') }}</span>
                        </div>
                        
                        <div class="summary-item">
                            <span>{{ trans('حالة الطلب') }}</span>
                            <span class="status status-{{ $appointment->order->status }}">
                                @switch($appointment->order->status)
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
                        
                        <div class="summary-item total">
                            <span>{{ trans('المجموع الكلي') }}</span>
                            <span>{{ $appointment->order->total }} {{ trans('ريال') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('client.order.details', $appointment->order->id) }}" class="btn-view-order">
                        {{ trans('عرض تفاصيل الطلب') }}
                    </a>
                </div>
                @endif

                <!-- Actions -->
                <div class="actions-section">
                    <h3>{{ trans('الإجراءات') }}</h3>
                    <div class="action-buttons">
                        @if($appointment->status === 'pending')
                            <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-cancel" onclick="return confirm('هل أنت متأكد من إلغاء الموعد؟')">
                                    {{ trans('إلغاء الموعد') }}
                                </button>
                            </form>
                        @endif

                        @if($appointment->zoom_meeting_url || $appointment->google_meet_link)
                            <a href="{{ $appointment->zoom_meeting_url ?? $appointment->google_meet_link }}" target="_blank" class="btn-join-meeting">
                                {{ trans('انضم للاجتماع الآن') }}
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="timeline-section">
                    <h3>{{ trans('سجل الموعد') }}</h3>
                    <div class="timeline">
                        <div class="timeline-item {{ $appointment->created_at ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>{{ trans('تم إنشاء الموعد') }}</h4>
                                <p>{{ $appointment->created_at ? $appointment->created_at->format('d-m-Y H:i') : '' }}</p>
                            </div>
                        </div>

                        @if($appointment->accepted_at)
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>{{ trans('تم قبول الموعد') }}</h4>
                                <p>{{ $appointment->accepted_at->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($appointment->rejected_at)
                        <div class="timeline-item rejected">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>{{ trans('تم رفض الموعد') }}</h4>
                                <p>{{ $appointment->rejected_at->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($appointment->completed_at)
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>{{ trans('تم إكمال الموعد') }}</h4>
                                <p>{{ $appointment->completed_at->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($appointment->cancelled_at)
                        <div class="timeline-item cancelled">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h4>{{ trans('تم إلغاء الموعد') }}</h4>
                                <p>{{ $appointment->cancelled_at->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.appointment-details-page {
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

.appointment-info-section, .meeting-section, .order-info-section, .actions-section, .timeline-section {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.appointment-info-section h2, .meeting-section h2, .order-info-section h3, .actions-section h3, .timeline-section h3 {
    color: #8B4513;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: #8B4513;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.info-content h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    color: #333;
}

.info-content p {
    margin: 0;
    color: #666;
    font-weight: 500;
}

.notes-section {
    margin-top: 1.5rem;
}

.notes-section h3 {
    color: #8B4513;
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
}

.notes-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #8B4513;
    color: #666;
    line-height: 1.6;
}

.designer-notes {
    border-left-color: #28a745;
}

.meeting-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.meeting-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
}

.meeting-icon {
    width: 50px;
    height: 50px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.meeting-content h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
}

.meeting-content p {
    margin: 0 0 1rem 0;
    color: #666;
    word-break: break-all;
}

.btn-join, .btn-join-meeting {
    background: #28a745;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease;
}

.btn-join:hover, .btn-join-meeting:hover {
    background: #218838;
    color: white;
}

.order-summary {
    margin-bottom: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.summary-item.total {
    border-top: 2px solid #8B4513;
    border-bottom: none;
    font-weight: 700;
    color: #8B4513;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
}

.btn-view-order {
    background: #8B4513;
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    display: block;
    text-align: center;
    transition: background 0.3s ease;
}

.btn-view-order:hover {
    background: #A0522D;
    color: white;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.btn-cancel {
    background: #dc3545;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-cancel:hover {
    background: #c82333;
}

.status {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-accepted { background: #d4edda; color: #155724; }
.status-rejected { background: #f8d7da; color: #721c24; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e0e0e0;
    border: 3px solid white;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
}

.timeline-item.rejected .timeline-marker,
.timeline-item.cancelled .timeline-marker {
    background: #dc3545;
}

.timeline-content h4 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1rem;
}

.timeline-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .meeting-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endpush
