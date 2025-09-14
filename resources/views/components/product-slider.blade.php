{{-- Product Slider Component --}}
@php
    $products = \App\Models\Product::where('is_active', true)
        ->with(['category', 'options.values'])
        ->orderBy('sort_order')
        ->take(10)
        ->get();
@endphp

<div class="product-slider-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="slider-title text-center mb-5">{{ trans('Our Premium Services') }}</h2>
                
                <div class="product-slider swiper">
                    <div class="swiper-wrapper">
                        @foreach($products as $product)
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="discount-banner">
                                    {{ trans('خصم لفترة محدودة') }}
                                </div>
                                <div class="card-header">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name_ar ?? $product->name }}" class="product-image">
                                    @else
                                        <div class="stats-graph">
                                            <div class="graph-line"></div>
                                            <div class="stats-text">
                                                <div class="stat-item">۲۷% | {{ trans('زيادة معدلات القراء') }}</div>
                                                <div class="stat-item">٤٢% | {{ trans('زيادة القرات') }}</div>
                                                <div class="stat-item">٣٤٦% | {{ trans('زيادة الوصول') }}</div>
                                                <div class="stat-number">۲۱۳</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h3 class="service-title">{{ $product->name_ar ?? $product->name }}</h3>
                                    <p class="package-name">{{ $product->category->name_ar ?? $product->category->name ?? 'خدمة متميزة' }}</p>
                                    <div class="divider">-</div>
                                    <div class="card-actions">
                                        <button class="favorite-btn">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        @livewire('add-to-cart', ['product' => $product], key($product->id))
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
