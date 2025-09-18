@extends('components.layout')

@section('pageTitle', $product->name)
@section('title', $product->name)

@section('content')
    <!-- Navbar -->
    <x-navbar />

    <div class="product-page" dir="rtl">
        <div class="container-fluid">
            <div class="row min-vh-100">
                <!-- Product Image Section (Left Side) -->
                <div class="col-lg-6 col-md-12 product-image-section">
                    <div class="image-container">
                        @php
                            // Get all product images
                            $productImages = [];

                            // Add main product image if exists
                            if ($product->image && file_exists(public_path($product->image))) {
                                $productImages[] = $product->image;
                            }

                            // Add additional images from product_designs if they exist
                            if (method_exists($product, 'designs') && $product->designs) {
                                foreach ($product->designs as $design) {
                                    if ($design->image && file_exists(public_path($design->image))) {
                                        $productImages[] = $design->image;
                                    }
                                }
                            }

                            // Add images from product_images if they exist
                            if (method_exists($product, 'images') && $product->images) {
                                foreach ($product->images as $image) {
                                    if ($image->image_path && file_exists(public_path($image->image_path))) {
                                        $productImages[] = $image->image_path;
                                    }
                                }
                            }
                        @endphp

                        @if (count($productImages) > 0)
                            <!-- Product Images Carousel -->
                            <div class="product-images-carousel">
                                <div class="main-image-container">
                                    <img src="{{ asset($productImages[0]) }}" alt="{{ $product->name }}"
                                        class="product-image active" id="main-product-image">
                                </div>

                                @if (count($productImages) > 1)
                                    <!-- Thumbnail Images -->
                                    <div class="thumbnail-images">
                                        @foreach ($productImages as $index => $image)
                                            <img src="{{ asset($image) }}" alt="{{ $product->name }}"
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
                                <p class="placeholder-text">صورة المنتج</p>
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
                <div class="col-lg-6 col-md-12 product-options-section">
                    @livewire('add-to-cart', ['product' => $product])
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
            min-height: 500px;
        }

        /* Sticky behavior for PC */
        @media (min-width: 992px) {
            .product-image-section {
                position: sticky;
                top: 2rem;
                height: calc(100vh - 4rem);
                max-height: 800px;
            }
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

        /* Adjust height for sticky behavior on PC */
        @media (min-width: 992px) {
            .main-image-container {
                height: calc(100vh - 8rem);
                max-height: 600px;
            }
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
            height: 400px;
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
                min-height: 400px;
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
                height: 300px;
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
    </script>
@endsection
