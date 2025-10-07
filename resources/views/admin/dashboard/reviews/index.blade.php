@extends('admin.layouts.app')

@section('pageTitle', trans('admin.reviews'))
@section('title', trans('admin.reviews'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ trans('admin.reviews') }}</h1>
                <p class="text-gray-600">{{ trans('admin.manage_reviews_platform') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="btn btn-warning">
                    <i class="fas fa-clock"></i>
                    <span>{{ trans('admin.pending_reviews') }}</span>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-star text-blue-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('admin.total_reviews') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $reviews->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('admin.pending_reviews') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $reviews->where('is_approved', null)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('admin.approved_reviews') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $reviews->where('is_approved', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div class="mx-4">
                        <p class="text-sm font-medium text-gray-600">{{ trans('admin.rejected_reviews') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $reviews->where('is_approved', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('admin.review_product') }}</label>
                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="filterByProduct(this.value)">
                        <option value="">جميع المنتجات</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name_ar ?: $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('admin.review_rating') }}</label>
                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="filterByRating(this.value)">
                        <option value="">جميع التقييمات</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 نجوم</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 نجوم</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 نجوم</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 نجوم</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 نجمة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ trans('admin.review_status') }}</label>
                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="filterByStatus(this.value)">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمد</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">{{ trans('admin.all_reviews') }}</h3>
                </div>
            </div>

            @if ($reviews->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('admin.review_product') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('admin.reviewer_name') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('admin.review_rating') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('admin.review_comment') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('admin.review_status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('admin.review_date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ trans('admin.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($reviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if ($review->product->image_url)
                                                <img class="h-10 w-10 rounded-lg object-cover"
                                                    src="{{ asset($review->product->image_url) }}"
                                                    alt="{{ $review->product->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($review->product->name_ar ?: $review->product->name, 30) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    #{{ $review->product->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $review->user->name ?? 'مجهول' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $review->user->email ?? 'لا يوجد' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $review->rating }})</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            @if($review->comment_ar || $review->comment)
                                                {{ Str::limit($review->comment_ar ?: $review->comment, 60) }}
                                            @else
                                                <span class="text-gray-400">لا يوجد تعليق</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->is_approved === null)
                                            <span class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock"></i>
                                                {{ trans('admin.pending_reviews') }}
                                            </span>
                                        @elseif($review->is_approved)
                                            <span class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle"></i>
                                                {{ trans('admin.approved_reviews') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle"></i>
                                                {{ trans('admin.rejected_reviews') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $review->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.reviews.show', $review) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                title="{{ trans('admin.view_review') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($review->is_approved === null)
                                                <button onclick="approveReview({{ $review->id }})"
                                                    class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                    title="{{ trans('admin.approve_review') }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button onclick="rejectReview({{ $review->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                    title="{{ trans('admin.reject_review') }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($review->is_approved)
                                                <button onclick="rejectReview({{ $review->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                    title="{{ trans('admin.reject_review') }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @else
                                                <button onclick="approveReview({{ $review->id }})"
                                                    class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                    title="{{ trans('admin.approve_review') }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button onclick="deleteReview({{ $review->id }})"
                                                class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                title="{{ trans('admin.delete_review') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <i class="fas fa-star text-4xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ trans('admin.no_reviews_found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ trans('admin.no_reviews_description') }}</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Filter functions
        function filterByProduct(productId) {
            const url = new URL(window.location);
            if (productId) {
                url.searchParams.set('product_id', productId);
            } else {
                url.searchParams.delete('product_id');
            }
            window.location.href = url.toString();
        }

        function filterByRating(rating) {
            const url = new URL(window.location);
            if (rating) {
                url.searchParams.set('rating', rating);
            } else {
                url.searchParams.delete('rating');
            }
            window.location.href = url.toString();
        }

        function filterByStatus(status) {
            const url = new URL(window.location);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location.href = url.toString();
        }



        // Individual actions
        function approveReview(reviewId) {
            if (confirm('هل أنت متأكد من اعتماد هذا التقييم؟')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/reviews/${reviewId}/approve`;
                
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PATCH';
                form.appendChild(method);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectReview(reviewId) {
            if (confirm('هل أنت متأكد من رفض هذا التقييم؟')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/reviews/${reviewId}/reject`;
                
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PATCH';
                form.appendChild(method);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function deleteReview(reviewId) {
            if (confirm('هل أنت متأكد من حذف هذا التقييم؟')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/reviews/${reviewId}`;
                
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection