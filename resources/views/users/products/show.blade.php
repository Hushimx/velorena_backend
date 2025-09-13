@extends('components.layout')

@section('pageTitle', $product->name)
@section('title', $product->name)

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="product-show-page">
        <!-- Header Section -->
        <div class="product-show-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <!-- Breadcrumb -->
                        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                            <ol class="breadcrumb-list">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}" class="breadcrumb-link">
                                        <i class="fas fa-home"></i>
                                        {{ trans('dashboard.dashboard') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('user.products.index') }}" class="breadcrumb-link">
                                        {{ trans('products.products') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $product->name }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="product-show-title">{{ $product->name }}</h1>
                        <p class="product-show-subtitle">{{ $product->category->name ?? trans('products.no_category') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <a href="{{ route('user.products.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ trans('products.back_to_products') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="product-details-section">
            <div class="container">
                <div class="row">
                    <!-- Product Image -->
                    <div class="col-lg-6 mb-4">
                        <div class="product-image-card">
                            @if ($product->image && file_exists(public_path($product->image)))
                                <img class="product-main-image" src="{{ asset($product->image) }}"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="product-image-placeholder">
                                    <i class="fas fa-box"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="col-lg-6 mb-4">
                        <div class="product-info-card">
                            <div class="product-info-content">
                                <!-- Category Badge -->
                                <div class="product-category-badge">
                                    {{ $product->category->name ?? trans('products.no_category') }}
                                </div>

                                <!-- Price -->
                                <div class="product-price-section">
                                    <h2 class="product-price">
                                        {{ number_format($product->base_price, 2) }} {{ trans('products.currency') }}
                                    </h2>
                                </div>

                                <!-- Description -->
                                <div class="product-description-section">
                                    <h3 class="section-title">{{ trans('products.description') }}</h3>
                                    <p class="product-description-text">
                                        {{ $product->description ?: trans('products.not_provided') }}
                                    </p>
                                </div>

                                <!-- Specifications -->
                                @if ($product->specifications && is_array($product->specifications))
                                    <div class="product-specifications-section">
                                        <h3 class="section-title">{{ trans('products.specifications') }}</h3>
                                        <div class="specifications-container">
                                            @foreach ($product->specifications as $key => $value)
                                                <div class="specification-item">
                                                    <span class="specification-key">{{ $key }}:</span>
                                                    <span class="specification-value">{{ $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Product Options -->
                                @if ($product->options->count() > 0)
                                    <div class="product-options-section">
                                        <h3 class="section-title">{{ trans('products.product_options') }}</h3>
                                        <div class="options-container">
                                            @foreach ($product->options as $option)
                                                <div class="option-card">
                                                    <div class="option-header">
                                                        <span class="option-name">{{ $option->name }}</span>
                                                        @if ($option->is_required)
                                                            <span class="option-badge required">
                                                                {{ trans('products.required') }}
                                                            </span>
                                                        @else
                                                            <span class="option-badge optional">
                                                                {{ trans('products.optional') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if ($option->values->count() > 0)
                                                        <div class="option-values">
                                                            @foreach ($option->values as $value)
                                                                <div class="option-value-item">
                                                                    <span class="value-name">{{ $value->name }}</span>
                                                                    @if ($value->price_adjustment != 0)
                                                                        <span
                                                                            class="value-price {{ $value->price_adjustment > 0 ? 'positive' : 'negative' }}">
                                                                            {{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 2) }}
                                                                            {{ trans('products.currency') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="product-actions-section">
                                    <div class="actions-container">
                                        @livewire('add-to-cart', ['product' => $product])
                                        <button class="favorite-btn">
                                            <i class="fas fa-heart"></i>
                                            {{ trans('products.add_to_favorites') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @php
            $relatedProducts = \App\Models\Product::where('is_active', true)
                ->where('id', '!=', $product->id)
                ->where('category_id', $product->category_id)
                ->take(4)
                ->get();
        @endphp

        @if ($relatedProducts->count() > 0)
            <div class="related-products-section">
                <div class="container">
                    <div class="related-products-card">
                        <h3 class="related-products-title">{{ trans('products.related_products') }}</h3>
                        <div class="row">
                            @foreach ($relatedProducts as $relatedProduct)
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <a href="{{ route('user.products.show', $relatedProduct) }}"
                                        class="related-product-card">
                                        @if ($relatedProduct->image && file_exists(public_path($relatedProduct->image)))
                                            <img class="related-product-image" src="{{ asset($relatedProduct->image) }}"
                                                alt="{{ $relatedProduct->name }}">
                                        @else
                                            <div class="related-product-placeholder">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                        <div class="related-product-info">
                                            <h4 class="related-product-name">{{ $relatedProduct->name }}</h4>
                                            <p class="related-product-price">
                                                {{ number_format($relatedProduct->base_price, 2) }}
                                                {{ trans('products.currency') }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>


    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Product Show Page Styles - Based on Welcome Page Design */
        .product-show-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, #FFEBC6 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Styles */
        .product-show-header {
            background: linear-gradient(135deg, #2C2C2C 0%, #404040 100%);
            color: #FFEBC6;
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .product-show-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 235, 198, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Breadcrumb */
        .breadcrumb-nav {
            margin-bottom: 1rem;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '>';
            margin: 0 0.5rem;
            color: #FFEBC6;
            opacity: 0.7;
        }

        .breadcrumb-link {
            color: #FFEBC6;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .breadcrumb-link:hover {
            color: #FFD700;
        }

        .breadcrumb-item.active {
            color: #FFEBC6;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .product-show-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: #FFEBC6;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease forwards;
            position: relative;
            z-index: 1;
        }

        .product-show-subtitle {
            font-size: 1.2rem;
            color: #FFEBC6;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease 0.2s forwards;
            opacity: 0;
            position: relative;
            z-index: 1;
        }

        /* Back Button */
        .back-btn {
            background: #FFEBC6;
            color: #2C2C2C;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(255, 235, 198, 0.3);
            position: relative;
            z-index: 1;
        }

        .back-btn:hover {
            background: #FFD700;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 235, 198, 0.4);
            color: #2C2C2C;
        }

        /* Product Details Section */
        .product-details-section {
            padding: 2rem 0;
        }

        /* Product Image Card */
        .product-image-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            /* transition: all 0.3s ease; */
            overflow: hidden;
            padding: 1.5rem;
        }

        .product-image-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #c4a700;
        }

        .product-main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            /* transition: transform 0.3s ease; */
        }

        .product-image-card:hover .product-main-image {
            transform: scale(1.02);
        }

        .product-image-placeholder {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2C2C2C;
            font-size: 4rem;
            border-radius: 10px;
        }

        /* Product Info Card */
        .product-info-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 2rem;
            height: 100%;
        }

        .product-info-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Category Badge */
        .product-category-badge {
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            color: #2C2C2C;
            border: 1px solid #c4a700;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            display: inline-block;
            width: fit-content;
        }

        /* Price Section */
        .product-price-section {
            margin: 1rem 0;
        }

        .product-price {
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        /* Section Titles */
        .section-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2C2C2C;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Description */
        .product-description-text {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Specifications */
        .specifications-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .specification-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .specification-item:last-child {
            border-bottom: none;
        }

        .specification-key {
            font-weight: 600;
            color: #2C2C2C;
        }

        .specification-value {
            color: #666;
        }

        /* Options */
        .options-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .option-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .option-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .option-name {
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1.1rem;
        }

        .option-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .option-badge.required {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #c62828;
            border: 1px solid #ef5350;
        }

        .option-badge.optional {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            color: #2e7d32;
            border: 1px solid #4caf50;
        }

        .option-values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .option-value-item {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .value-name {
            color: #2C2C2C;
            font-weight: 500;
        }

        .value-price {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .value-price.positive {
            color: #2e7d32;
        }

        .value-price.negative {
            color: #c62828;
        }

        /* Actions */
        .product-actions-section {
            padding-top: 2rem;
            border-top: 2px solid #e5e7eb;
            margin-top: 2rem;
        }

        .actions-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .favorite-btn {
            background: #fff;
            color: #2C2C2C;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
        }

        /* Related Products */
        .related-products-section {
            padding: 3rem 0;
        }

        .related-products-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 2rem;
        }


        .related-products-title {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2C2C2C;
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .related-product-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            text-decoration: none;
            display: block;
            height: 100%;
        }


        .related-product-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .related-product-card:hover .related-product-image {
            transform: scale(1.05);
        }

        .related-product-placeholder {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2C2C2C;
            font-size: 2rem;
        }

        .related-product-info {
            padding: 1rem;
        }

        .related-product-name {
            font-family: 'Cairo', cursive;
            font-weight: 700;
            color: #2C2C2C;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .related-product-price {
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .product-show-title {
                font-size: 2.5rem;
            }

            .product-show-subtitle {
                font-size: 1rem;
            }

            .back-btn {
                padding: 0.6rem 1.2rem;
                font-size: 1rem;
            }

            .product-main-image,
            .product-image-placeholder {
                height: 300px;
            }

            .product-price {
                font-size: 2rem;
            }

            .actions-container {
                flex-direction: column;
            }

            .option-values {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .product-show-header {
                padding: 2rem 0;
            }

            .product-show-title {
                font-size: 2rem;
            }

            .product-show-subtitle {
                font-size: 0.9rem;
            }

            .breadcrumb-list {
                flex-direction: column;
                align-items: flex-start;
            }

            .breadcrumb-item:not(:last-child)::after {
                display: none;
            }

            .product-main-image,
            .product-image-placeholder {
                height: 250px;
            }
        }
    </style>
@endsection
