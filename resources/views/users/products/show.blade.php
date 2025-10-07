@extends('components.layout')

@section('pageTitle', app()->getLocale() === 'ar' ? ($product->name_ar ?? $product->name) : $product->name)
@section('title', app()->getLocale() === 'ar' ? ($product->name_ar ?? $product->name) : $product->name)

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $product->seo_description }}">
    @if ($product->seo_keywords)
        <meta name="keywords" content="{{ $product->seo_keywords }}">
    @endif
    <meta name="robots" content="{{ $product->robots }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $product->canonical_url }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->open_graph_title }}">
    <meta property="og:description" content="{{ $product->open_graph_description }}">
    <meta property="og:url" content="{{ $product->canonical_url }}">
    @if ($product->seo_image)
        <meta property="og:image" content="{{ $product->seo_image }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="{{ $product->seo_title }}">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->twitter_card_title }}">
    <meta name="twitter:description" content="{{ $product->twitter_card_description }}">
    @if ($product->seo_image)
        <meta name="twitter:image" content="{{ $product->seo_image }}">
        <meta name="twitter:image:alt" content="{{ $product->seo_title }}">
    @endif

    <!-- Product Specific Meta Tags -->
    <meta property="product:price:amount" content="{{ $product->base_price }}">
    <meta property="product:price:currency" content="SAR">
    <meta property="product:availability" content="{{ $product->is_active ? 'in stock' : 'out of stock' }}">
    @if ($product->category)
        <meta property="product:category"
            content="{{ app()->getLocale() === 'ar' ? $product->category->name_ar ?? $product->category->name : $product->category->name }}">
    @endif

    <!-- Structured Data -->
    <script type="application/ld+json">
    {!! json_encode($product->structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <!-- Navbar -->
    <x-navbar />

    <div class="product-page" dir="rtl">
        <div class="container-fluid">
            <div class="row">
                <!-- Product Image Section (Left Side) -->
                <div class="col-lg-6 col-md-12 product-image-section">
                    <div class="image-container">
                        @php
                            // Get all product images (optimized)
                            $productImages = [];

                            // Add main image_url first if exists
                            if ($product->image_url) {
                                $productImages[] = $product->image_url;
                            }

                            // Add additional images from product_images table
                            if ($product->relationLoaded('images') && $product->images->count() > 0) {
                                foreach ($product->images as $image) {
                                    if ($image->image_path && $image->image_path !== $product->image_url) {
                                        $productImages[] = $image->image_path;
                                    }
                                }
                            }

                            // Add legacy main product image if exists and no main image_url
                            if (count($productImages) === 0 && $product->image) {
                                $productImages[] = $product->image;
                            }
                        @endphp

                        @if (count($productImages) > 0)
                            <!-- Product Images Carousel -->
                            <div class="product-images-carousel">
                                <div class="main-image-container">
                                    <img src="{{ asset($productImages[0]) }}"
                                        alt="{{ app()->getLocale() === 'ar' ? $product->name_ar ?? $product->name : $product->name }}"
                                        class="product-image active" id="main-product-image">
                                </div>

                                @if (count($productImages) > 1)
                                    <!-- Thumbnail Images -->
                                    <div class="thumbnail-images">
                                        @foreach ($productImages as $index => $image)
                                            <img src="{{ asset($image) }}"
                                                alt="{{ app()->getLocale() === 'ar' ? $product->name_ar ?? $product->name : $product->name }}"
                                                class="thumbnail-image {{ $index === 0 ? 'active' : '' }}"
                                                data-index="{{ $index }}"
                                                onclick="changeMainImage('{{ asset($image) }}', {{ $index }})">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Simple product placeholder -->
                            <div class="product-placeholder">
                                <div class="placeholder-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <p class="placeholder-text">{{ trans('products.image') }}</p>
                            </div>
                        @endif

                        <!-- Image Navigation Dots -->
                        @if (count($productImages) > 1)
                            <div class="image-navigation">
                                @foreach ($productImages as $index => $image)
                                    <span class="nav-dot {{ $index === 0 ? 'active' : '' }}"
                                        data-index="{{ $index }}"
                                        onclick="changeMainImage('{{ asset($image) }}', {{ $index }})"></span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Options Section (Right Side) -->
                <div class="col-lg-6 col-md-12 product-options-section" style="margin-top: 2rem;">
                    @livewire('add-to-cart', ['product' => $product])
                </div>
            </div>
            
            <!-- Review Modal (Hidden for now) -->
            <div id="reviewModal" class="review-modal" style="display: none !important;">
                <div class="review-modal-content">
                    <div class="review-modal-header">
                        <h3>تقييم المنتج</h3>
                        <button class="close-modal" onclick="closeReviewModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="reviewForm" onsubmit="submitReview(event)">
                        <div class="review-modal-body">
                            <div class="product-info">
                                <img src="{{ asset($product->image_url ?: ($product->images->first()->image_path ?? 'uploads/default-product.png')) }}" alt="{{ $product->name_ar ?: $product->name }}" class="product-image-small">
                                <div class="product-details">
                                    <h4>{{ $product->name_ar ?: $product->name }}</h4>
                                    <p>{{ $product->base_price }} ر.س</p>
                                </div>
                            </div>
                            
                            <div class="rating-input-section">
                                <label>تقييمك للمنتج:</label>
                                <div class="star-rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star-input" data-rating="{{ $i }}" onclick="setRating({{ $i }})"></i>
                                    @endfor
                                </div>
                                <span class="rating-text">اضغط على النجوم للتقييم</span>
                            </div>
                            
                            <div class="comment-input-section">
                                <label for="reviewComment">تعليقك (اختياري):</label>
                                <textarea id="reviewComment" name="comment" placeholder="شاركنا رأيك في هذا المنتج..." rows="4"></textarea>
                            </div>
                        </div>
                        
                        <div class="review-modal-footer">
                            <button type="button" class="btn-cancel" onclick="closeReviewModal()">إلغاء</button>
                            <button type="submit" class="btn-submit">إرسال التقييم</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <x-footer />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');

        /* Brand Colors */
        :root {
            /* Brand Colors */
            --brand-yellow: #ffde9f;
            --brand-yellow-dark: #f5d182;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #3a2e2e;

            /* Extended Brand Palette */
            --brand-yellow-light: #fff4e6;
            --brand-yellow-hover: #f0d4a0;
            --brand-brown-dark: #1a1414;
            --brand-brown-hover: #4a3e3e;

            /* Status Colors */
            --status-pending: #fbbf24;
            --status-processing: #3b82f6;
            --status-completed: #10b981;
            --status-cancelled: #ef4444;
            --status-active: #10b981;
            --status-inactive: #6b7280;
            --status-suspended: #f59e0b;
        }

        .product-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            min-height: 100vh;
            direction: rtl;
        }

        /* Sticky behavior for product options on PC */


        .options-container {
            width: 100%;
            max-width: 500px;
        }

        .product-header {
            margin-bottom: 2rem;
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: #2C2C2C;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .product-description {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .option-group {
            margin-bottom: 2rem;
        }

        .option-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2C2C2C;
            margin-bottom: 1rem;
        }

        .option-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .option-btn {
            position: relative;
            cursor: pointer;
            margin: 0;
        }

        .option-btn input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .option-btn span {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #fff;
            border: 2px solid #E0E0E0;
            border-radius: 25px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .option-btn input:checked+span {
            background: #2C2C2C;
            color: #fff;
            border-color: #2C2C2C;
        }

        .option-btn:hover span {
            border-color: #2C2C2C;
        }

        .shape-selector select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #E0E0E0;
            border-radius: 25px;
            background: #fff;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .shape-selector select:focus {
            border-color: #2C2C2C;
        }

        .price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0;
            padding: 1.5rem;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .price-display {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .price {
            font-size: 2.5rem;
            font-weight: 900;
            color: #2C2C2C;
        }

        .currency {
            font-size: 1.2rem;
            font-weight: 600;
            color: #666;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            background: #F5F5F5;
            border-radius: 25px;
            overflow: hidden;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--brand-brown);
            color: var(--brand-yellow-light);
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .qty-btn:hover {
            background: var(--brand-brown-hover);
        }

        .qty-input {
            width: 60px;
            height: 40px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--brand-brown);
            outline: none;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-add-cart,
        .btn-buy-now {
            flex: 1;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-cart {
            background: var(--brand-yellow-light);
            color: var(--brand-brown);
            border: 2px solid var(--brand-brown);
        }

        .btn-add-cart:hover {
            background: var(--brand-brown);
            color: var(--brand-yellow-light);
        }

        .btn-buy-now {
            background: var(--brand-brown);
            color: var(--brand-yellow-light);
        }

        .btn-buy-now:hover {
            background: var(--brand-brown-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        /* Button Loading States */
        .btn-add-cart.loading .btn-text,
        .btn-buy-now.loading .btn-text {
            display: none;
        }

        .btn-add-cart.loading .btn-loading,
        .btn-buy-now.loading .btn-loading {
            display: inline-flex !important;
            align-items: center;
            gap: 0.5rem;
        }

        /* Sticky Cart Container */
        .sticky-cart-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            border-top: 3px solid var(--brand-yellow-dark);
            box-shadow: 0 -8px 25px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .sticky-cart-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            gap: 2rem;
        }

        .sticky-cart-info {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .sticky-cart-price {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .sticky-price {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--brand-yellow);
            font-family: 'Cairo', cursive;
        }

        .sticky-currency {
            font-size: 1rem;
            font-weight: 600;
            color: var(--brand-yellow-light);
        }

        .sticky-cart-quantity {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sticky-qty-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sticky-qty-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .sticky-qty-input {
            width: 50px;
            height: 35px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            outline: none;
        }

        .sticky-cart-actions {
            display: flex;
            gap: 1rem;
        }

        .sticky-btn-add-cart,
        .sticky-btn-buy-now {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .sticky-btn-add-cart {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            border: 2px solid var(--brand-yellow-dark);
        }

        .sticky-btn-add-cart:hover {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-hover) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }

        .sticky-btn-buy-now {
            background: linear-gradient(135deg, var(--brand-yellow-dark) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
        }

        .sticky-btn-buy-now:hover {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-hover) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }

        /* Sticky Button Loading States */
        .sticky-btn-add-cart.loading .sticky-btn-text,
        .sticky-btn-buy-now.loading .sticky-btn-text {
            display: none;
        }

        .sticky-btn-add-cart.loading .sticky-btn-loading,
        .sticky-btn-buy-now.loading .sticky-btn-loading {
            display: inline-flex !important;
            align-items: center;
            gap: 0.5rem;
        }


        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 1rem 1.5rem;
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid var(--brand-yellow-dark);
            max-width: 350px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            border-left-color: #4CAF50;
        }

        .notification-info {
            border-left-color: #2196F3;
        }

        .notification-error {
            border-left-color: #f44336;
        }

        .notification-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-family: 'Cairo', sans-serif;
        }

        .notification-content i {
            font-size: 1.2rem;
        }

        .notification-success .notification-content i {
            color: #4CAF50;
        }

        .notification-info .notification-content i {
            color: #2196F3;
        }

        .notification-error .notification-content i {
            color: #f44336;
        }

        .notification-content span {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Product Image Section */
        .product-image-section {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            height: auto;
            min-height: 400px;
        }

        .image-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
        }

        .product-image {
            max-width: 100%;
            max-height: 100%;
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 15px;
            transition: opacity 0.3s ease;
        }

        .product-images-carousel {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .main-image-container {
            width: 100%;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 15px;
        }

        .thumbnail-images {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .thumbnail-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail-image:hover,
        .thumbnail-image.active {
            border-color: #2C2C2C;
            transform: scale(1.1);
        }

        /* Simple Product Placeholder */
        .product-placeholder {
            width: 100%;
            height: 350px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px dashed rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
        }

        .placeholder-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .placeholder-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }

        .image-navigation {
            display: none;
        }

        .nav-dot {
            display: none;
        }


        /* RTL Layout - Image on right, options on left */

        .product-options-section {
            order: 1;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .product-options-section {
                order: 2;
            }

            .product-image-section {
                order: 1;
                min-height: 350px;
            }

            .product-title {
                font-size: 2rem;
            }

            .price {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {

            .product-options-section,
            .product-image-section {
                padding: 1rem;
            }

            .product-title {
                font-size: 1.8rem;
            }

            .option-buttons {
                flex-direction: column;
            }

            .option-btn span {
                display: block;
                text-align: center;
            }

            .price-section {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .product-placeholder {
                height: 250px;
            }

            /* Sticky cart responsive */
            .sticky-cart-content {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .sticky-cart-info {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }

            .sticky-cart-actions {
                width: 100%;
                justify-content: center;
            }

            .sticky-btn-add-cart,
            .sticky-btn-buy-now {
                flex: 1;
                justify-content: center;
            }
        }

        /* Reviews Styles - Enhanced (Hidden for now) */
        /* .reviews-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 3rem 0;
            border: 1px solid rgba(255, 222, 159, 0.3);
        }

        .reviews-header {
            background: linear-gradient(135deg, #fff9e6 0%, #fff4d6 100%);
            padding: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
            border-bottom: 3px solid #ffc107;
        }

        .rating-overview {
            flex: 1;
        }

        .average-rating {
            text-align: center;
        }

        .rating-number-wrapper {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
        }

        .rating-number {
            font-size: 4rem;
            font-weight: 900;
            color: var(--brand-brown);
            line-height: 1;
            text-shadow: 0 2px 4px rgba(42, 30, 30, 0.1);
        }

        .rating-max {
            font-size: 2rem;
            font-weight: 700;
            color: #999;
            line-height: 1;
        }

        .rating-stars-display {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-bottom: 0.75rem;
        }

        .rating-stars-display i {
            font-size: 2rem;
            color: #e0e0e0;
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 3px rgba(0,0,0,0.1));
        }

        .rating-stars-display i.filled {
            color: #ffc107;
            animation: starPulse 0.5s ease;
        }

        @keyframes starPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .rating-count {
            color: #666;
            font-weight: 600;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.7);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: inline-block;
        }

        .add-review-section {
            flex-shrink: 0;
        }

        .btn-add-review {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: #ffc107;
            border: 2px solid #ffc107;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-add-review::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 193, 7, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-add-review:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-add-review:hover {
            background: linear-gradient(135deg, var(--brand-brown-hover) 0%, var(--brand-brown) 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
            border-color: #ffd700;
        }

        .btn-add-review i {
            font-size: 1.2rem;
        }

        .btn-add-review.disabled {
            background: linear-gradient(135deg, #999 0%, #888 100%);
            border-color: #777;
            cursor: not-allowed;
            opacity: 0.7;
            color: #ddd;
        }

        .btn-add-review.disabled:hover {
            background: linear-gradient(135deg, #999 0%, #888 100%);
            transform: none;
            box-shadow: none;
        }

        .btn-add-review.disabled::before {
            display: none;
        }

        .rating-distribution {
            padding: 2rem 2.5rem;
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            border-bottom: 1px solid #e0e0e0;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .rating-bar:hover {
            background: rgba(255, 193, 7, 0.05);
            transform: translateX(-5px);
        }

        .rating-label {
            font-weight: 700;
            color: var(--brand-brown);
            width: 25px;
            text-align: center;
            font-size: 1.1rem;
        }

        .rating-star-icon {
            color: #ffc107;
            font-size: 1.1rem;
            filter: drop-shadow(0 1px 2px rgba(255, 193, 7, 0.3));
        }

        .rating-progress {
            flex: 1;
            height: 12px;
            background: #e9ecef;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }

        .rating-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ffd700 0%, #ffc107 50%, #ffb300 100%);
            border-radius: 20px;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
            position: relative;
            overflow: hidden;
        }

        .rating-progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            to {
                left: 100%;
            }
        }

        .rating-count-text {
            font-weight: 700;
            color: #666;
            min-width: 40px;
            text-align: right;
            background: rgba(255, 193, 7, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.95rem;
        }

        .reviews-list {
            padding: 2.5rem;
            background: #fff;
        }

        .reviews-title {
            color: var(--brand-brown);
            font-weight: 800;
            margin-bottom: 2rem;
            font-size: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .reviews-title i {
            color: #ffc107;
            font-size: 1.5rem;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .review-card {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 2px solid #f0f0f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .review-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #ffc107 0%, #ffd700 100%);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .review-card:hover::before {
            transform: scaleX(1);
        }

        .review-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.15);
            border-color: #ffc107;
        }

        .review-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .reviewer-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #fff4d6 0%, #ffe9a3 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-brown);
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .reviewer-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .reviewer-name {
            font-weight: 700;
            color: var(--brand-brown);
            font-size: 1rem;
        }

        .verified-badge-inline {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            color: #28a745;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .verified-badge-inline i {
            font-size: 0.85rem;
        }

        .review-date {
            color: #999;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .review-rating-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 1rem;
        }

        .review-rating-stars i {
            font-size: 1.1rem;
            color: #e0e0e0;
        }

        .review-rating-stars i.filled {
            color: #ffc107;
            text-shadow: 0 1px 2px rgba(255, 193, 7, 0.3);
        }

        .review-comment {
            color: #555;
            line-height: 1.7;
            font-size: 0.95rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f1f2 100%);
            padding: 1.25rem;
            border-radius: 12px;
            border-right: 4px solid #ffc107;
            position: relative;
        }

        .quote-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 2rem;
            color: rgba(255, 193, 7, 0.15);
        }

        .load-more-reviews {
            text-align: center;
            margin-top: 2rem;
        }

        .btn-load-more {
            background: linear-gradient(135deg, #fff 0%, #fafafa 100%);
            color: var(--brand-brown);
            border: 2px solid #ffc107;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 10px rgba(255, 193, 7, 0.2);
        }

        .btn-load-more:hover {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: #ffc107;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .btn-load-more i {
            transition: transform 0.3s ease;
        }

        .btn-load-more:hover i {
            transform: translateY(3px);
        }

        /* No Reviews State */
        .no-reviews {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            border-radius: 20px;
            border: 2px dashed #e0e0e0;
        }

        .no-reviews-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #fff4d6 0%, #ffe9a3 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 4px 20px rgba(255, 193, 7, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }

        .no-reviews-icon i {
            font-size: 3rem;
            color: #ffc107;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .no-reviews h4 {
            color: var(--brand-brown);
            font-weight: 800;
            margin-bottom: 0.75rem;
            font-size: 1.75rem;
        }

        .no-reviews p {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .btn-first-review {
            background: linear-gradient(135deg, #ffc107 0%, #ffd700 100%);
            color: var(--brand-brown);
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .btn-first-review:hover {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: #ffc107;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
        }

        .btn-first-review.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(50%);
        }

        .btn-first-review.disabled:hover {
            transform: none;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        /* Review Modal */
        .review-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            backdrop-filter: blur(5px);
        }

        .review-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .review-modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .review-modal-header {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: var(--brand-yellow-light);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px 16px 0 0;
        }

        .review-modal-header h3 {
            margin: 0;
            font-weight: 700;
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--brand-yellow-light);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            transition: background 0.2s ease;
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .review-modal-body {
            padding: 2rem;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .product-image-small {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-details h4 {
            margin: 0 0 0.25rem 0;
            color: var(--brand-brown);
            font-weight: 600;
        }

        .product-details p {
            margin: 0;
            color: #666;
            font-weight: 600;
        }

        .rating-input-section {
            margin-bottom: 2rem;
        }

        .rating-input-section label {
            display: block;
            margin-bottom: 1rem;
            color: var(--brand-brown);
            font-weight: 600;
        }

        .star-rating-input {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .star-input {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-input:hover,
        .star-input.active {
            color: #ffc107;
            transform: scale(1.1);
        }

        .rating-text {
            color: #666;
            font-size: 0.875rem;
        }

        .comment-input-section label {
            display: block;
            margin-bottom: 0.75rem;
            color: var(--brand-brown);
            font-weight: 600;
        }

        .comment-input-section textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 1rem;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.3s ease;
        }

        .comment-input-section textarea:focus {
            outline: none;
            border-color: var(--brand-brown);
        }

        .review-modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-cancel,
        .btn-submit {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #dee2e6;
        }

        .btn-cancel:hover {
            background: #e9ecef;
        }

        .btn-submit {
            background: var(--brand-brown);
            color: var(--brand-yellow-light);
            border: none;
        }

        .btn-submit:hover {
            background: var(--brand-brown-hover);
        } */

        /* Responsive Design */
        @media (max-width: 768px) {
            /* .reviews-header {
                flex-direction: column;
                text-align: center;
            }

            .rating-number {
                font-size: 3rem;
            }

            .rating-stars-large i {
                font-size: 1.5rem;
            }

            .rating-distribution {
                padding: 1rem;
            }

            .reviews-list {
                padding: 1rem;
            }

            .review-header {
                flex-direction: column;
                gap: 1rem;
            }

            .review-meta {
                align-items: flex-start;
            }

            .review-modal-content {
                width: 95%;
                margin: 1rem;
            }

            .review-modal-body {
                padding: 1rem;
            }

            .product-info {
                flex-direction: column;
                text-align: center;
            }

            .review-modal-footer {
                flex-direction: column;
            }

            .reviews-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .review-card {
                padding: 1.25rem;
            }

            .reviews-list {
                padding: 1.5rem;
            }

            .reviews-title {
                font-size: 1.5rem;
            } */
        }
    </style>

    <script>
        // Image switching functionality
        function changeMainImage(imageSrc, index) {
            // Update main image
            const mainImage = document.getElementById('main-product-image');
            if (mainImage) {
                mainImage.src = imageSrc;
            }

            // Update thumbnail active state
            document.querySelectorAll('.thumbnail-image').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });

            // Update navigation dots
            document.querySelectorAll('.nav-dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        // Review Modal Functionality (Hidden for now)
        /* let selectedRating = 0;
        const canReview = false;
        const canReviewMessage = '';

        function showReviewMessage() {
            showNotification(canReviewMessage, 'info');
        }

        function openReviewModal() {
            if (!canReview) {
                showNotification(canReviewMessage, 'info');
                return;
            }
            
            document.getElementById('reviewModal').classList.add('show');
            selectedRating = 0;
            updateStarDisplay();
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.remove('show');
            selectedRating = 0;
            updateStarDisplay();
            document.getElementById('reviewComment').value = '';
        }

        function setRating(rating) {
            selectedRating = rating;
            updateStarDisplay();
        }

        function updateStarDisplay() {
            const stars = document.querySelectorAll('.star-input');
            stars.forEach((star, index) => {
                if (index < selectedRating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function submitReview(event) {
            event.preventDefault();
            
            if (selectedRating === 0) {
                showNotification('يرجى اختيار تقييم من 1 إلى 5 نجوم', 'error');
                return;
            }

            const comment = document.getElementById('reviewComment').value;
            const submitButton = event.target.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Show loading state
            submitButton.textContent = 'جاري الإرسال...';
            submitButton.disabled = true;

            // Prepare form data
            const formData = new FormData();
            formData.append('product_id', '{{ $product->id }}');
            formData.append('rating', selectedRating);
            formData.append('comment', comment);
            formData.append('_token', '{{ csrf_token() }}');

            // Submit review via AJAX
            fetch('/api/reviews', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    @auth
                    'Authorization': 'Bearer {{ auth()->user()->api_token ?? "" }}'
                    @endauth
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إرسال تقييمك بنجاح! سيتم مراجعته قبل النشر.', 'success');
                    closeReviewModal();
                    // Refresh the page to show updated reviews
                    setTimeout(() => {
                    window.location.reload();
                    }, 2000);
                } else {
                    showNotification('حدث خطأ في إرسال التقييم: ' + (data.message || 'خطأ غير معروف'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ في إرسال التقييم. يرجى المحاولة مرة أخرى.', 'error');
            })
            .finally(() => {
                // Reset button state
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        }

        function loadMoreReviews() {
            // This function can be implemented to load more reviews via AJAX
            showNotification('سيتم إضافة هذه الميزة قريباً', 'info');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('reviewModal');
            if (event.target === modal) {
                closeReviewModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeReviewModal();
            }
        }); */
    </script>
@endsection
