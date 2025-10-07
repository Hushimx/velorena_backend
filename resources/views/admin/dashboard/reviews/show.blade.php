@extends('admin.layouts.app')

@section('title', 'تفاصيل التقييم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star mr-2"></i>
                        تفاصيل التقييم
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right mr-1"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Review Details -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">معلومات التقييم</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>رقم التقييم:</strong> #{{ $review->id }}<br>
                                            <strong>التقييم:</strong> 
                                            <div class="rating-display d-inline-block">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="ml-2">{{ $review->rating }}/5</span>
                                            </div><br>
                                            <strong>الحالة:</strong> 
                                            @if($review->is_approved)
                                                <span class="badge badge-success">مقبول</span>
                                            @else
                                                <span class="badge badge-warning">في الانتظار</span>
                                            @endif<br>
                                            <strong>نوع التقييم:</strong> 
                                            @if($review->is_verified_purchase)
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-shield-alt"></i> مؤكد
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">غير مؤكد</span>
                                            @endif<br>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>تاريخ الإنشاء:</strong> {{ $review->created_at->format('Y-m-d H:i') }}<br>
                                            <strong>تاريخ التحديث:</strong> {{ $review->updated_at->format('Y-m-d H:i') }}<br>
                                        </div>
                                    </div>

                                    @if($review->comment_ar || $review->comment)
                                        <hr>
                                        <h6>التعليقات:</h6>
                                        @if($review->comment_ar)
                                            <div class="mb-3">
                                                <strong>التعليق العربي:</strong>
                                                <div class="border p-3 bg-light rounded">
                                                    {{ $review->comment_ar }}
                                                </div>
                                            </div>
                                        @endif
                                        @if($review->comment)
                                            <div class="mb-3">
                                                <strong>التعليق الإنجليزي:</strong>
                                                <div class="border p-3 bg-light rounded">
                                                    {{ $review->comment }}
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    @if($review->metadata)
                                        <hr>
                                        <h6>البيانات الإضافية:</h6>
                                        <div class="border p-3 bg-light rounded">
                                            <pre>{{ json_encode($review->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User & Product Info -->
                        <div class="col-md-4">
                            <!-- User Information -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="card-title">معلومات العميل</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>الاسم:</strong> {{ $review->user->name ?? 'غير متوفر' }}</p>
                                    <p><strong>رقم العميل:</strong> {{ $review->user_id }}</p>
                                    <p><strong>البريد الإلكتروني:</strong> {{ $review->user->email ?? 'غير متوفر' }}</p>
                                    <p><strong>الهاتف:</strong> {{ $review->user->phone ?? 'غير متوفر' }}</p>
                                </div>
                            </div>

                            <!-- Product Information -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="card-title">معلومات المنتج</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>اسم المنتج:</strong> {{ $review->product->name_ar ?: $review->product->name }}</p>
                                    <p><strong>رقم المنتج:</strong> {{ $review->product_id }}</p>
                                    <p><strong>السعر:</strong> {{ $review->product->base_price }} ر.س</p>
                                    <a href="{{ route('admin.products.show', $review->product) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> عرض المنتج
                                    </a>
                                </div>
                            </div>

                            <!-- Order Information -->
                            @if($review->order)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="card-title">معلومات الطلب</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>رقم الطلب:</strong> #{{ $review->order->order_number }}</p>
                                        <p><strong>حالة الطلب:</strong> 
                                            <span class="badge badge-{{ $review->order->status === 'completed' ? 'success' : 'warning' }}">
                                                {{ $review->order->status }}
                                            </span>
                                        </p>
                                        <p><strong>تاريخ الطلب:</strong> {{ $review->order->created_at->format('Y-m-d') }}</p>
                                        <a href="{{ route('admin.orders.show', $review->order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> عرض الطلب
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">إجراءات التقييم</h6>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group" role="group">
                                        @if(!$review->is_approved)
                                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> قبول التقييم
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($review->is_approved)
                                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="fas fa-times"></i> رفض التقييم
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> حذف التقييم
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-display {
    display: flex;
    align-items: center;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.badge {
    font-size: 0.8em;
}
</style>
@endsection
