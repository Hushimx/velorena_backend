@extends('admin.layouts.app')

@section('title', 'تقييمات المنتج')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star mr-2"></i>
                        تقييمات المنتج: {{ $product->name_ar ?: $product->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info mr-2">
                            <i class="fas fa-eye mr-1"></i>
                            عرض المنتج
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right mr-1"></i>
                            جميع التقييمات
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Product Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي التقييمات</span>
                                    <span class="info-box-number">{{ $product->reviews()->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">مقبولة</span>
                                    <span class="info-box-number">{{ $product->approvedReviews()->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">في الانتظار</span>
                                    <span class="info-box-number">{{ $product->reviews()->where('is_approved', false)->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">متوسط التقييم</span>
                                    <span class="info-box-number">{{ number_format($product->average_rating, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Distribution -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">توزيع التقييمات</h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $distribution = $product->rating_distribution;
                                        $total = array_sum($distribution);
                                    @endphp
                                    @for($i = 5; $i >= 1; $i--)
                                        @php
                                            $count = $distribution[$i] ?? 0;
                                            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                                        @endphp
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>{{ $i }} ⭐</span>
                                                <span>{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">معلومات المنتج</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>اسم المنتج:</strong> {{ $product->name_ar ?: $product->name }}</p>
                                    <p><strong>السعر:</strong> {{ $product->base_price }} ر.س</p>
                                    <p><strong>الفئة:</strong> {{ $product->category->name_ar ?? $product->category->name ?? 'غير محدد' }}</p>
                                    <p><strong>الحالة:</strong> 
                                        @if($product->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>العميل</th>
                                    <th>التقييم</th>
                                    <th>التعليق</th>
                                    <th>الحالة</th>
                                    <th>نوع التقييم</th>
                                    <th>الطلب</th>
                                    <th>التاريخ</th>
                                    <th width="150">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $review->user->name ?? 'مجهول' }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $review->user_id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rating-display">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="ml-2">{{ $review->rating }}/5</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($review->comment_ar || $review->comment)
                                                <div class="review-comment">
                                                    @if($review->comment_ar)
                                                        <strong>عربي:</strong> {{ Str::limit($review->comment_ar, 50) }}<br>
                                                    @endif
                                                    @if($review->comment)
                                                        <strong>إنجليزي:</strong> {{ Str::limit($review->comment, 50) }}
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">لا يوجد تعليق</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->is_approved)
                                                <span class="badge badge-success">مقبول</span>
                                            @else
                                                <span class="badge badge-warning">في الانتظار</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->is_verified_purchase)
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-shield-alt"></i> مؤكد
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">غير مؤكد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($review->order)
                                                <a href="{{ route('admin.orders.show', $review->order) }}" class="btn btn-sm btn-info">
                                                    #{{ $review->order->order_number }}
                                                </a>
                                            @else
                                                <span class="text-muted">غير مرتبط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                {{ $review->created_at->format('Y-m-d') }}
                                                <br>
                                                <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.reviews.show', $review) }}" 
                                                   class="btn btn-sm btn-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if(!$review->is_approved)
                                                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="قبول">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if($review->is_approved)
                                                    <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" title="رفض">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" 
                                                      style="display: inline;" 
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد تقييمات لهذا المنتج</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $reviews->links() }}
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

.review-comment {
    max-width: 200px;
    word-wrap: break-word;
    font-size: 0.9em;
}

.info-box {
    margin-bottom: 0;
}

.info-box .info-box-icon {
    height: 60px;
    line-height: 60px;
}

.info-box .info-box-content {
    padding: 5px 10px;
}

.table td {
    vertical-align: middle;
}

.progress {
    border-radius: 4px;
}
</style>
@endsection
