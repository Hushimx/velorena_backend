@extends('components.layout')

@section('pageTitle', trans('cart.shopping_cart'))
@section('title', trans('cart.shopping_cart'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="cart-index-page">
        <!-- Header Section -->
        <div class="cart-index-header">
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
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('cart.shopping_cart') }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="cart-index-title">{{ trans('cart.shopping_cart') }}</h1>
                        <p class="cart-index-subtitle">{{ trans('cart.manage_your_cart_items') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <a href="{{ route('user.products.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ trans('cart.continue_shopping') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Content -->
        <div class="cart-content-section">
            <div class="container">
                <!-- Shopping Cart Component -->
                @livewire('shopping-cart')
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Cart Index Page Styles - Based on Product Show Page Design */
        .cart-index-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Section */
        .cart-index-header {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 50%, var(--brand-yellow-light) 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        .cart-index-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Breadcrumb Navigation */
        .breadcrumb-nav {
            margin-bottom: 1.5rem;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.5rem;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        .breadcrumb-item.active {
            color: var(--brand-brown);
            font-weight: 600;
        }

        .breadcrumb-link {
            color: var(--brand-brown);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .breadcrumb-link:hover {
            background: rgba(42, 30, 30, 0.1);
            color: var(--brand-brown);
        }

        .breadcrumb-link i {
            font-size: 0.875rem;
        }

        /* Title Section */
        .cart-index-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--brand-brown);
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(42, 30, 30, 0.1);
        }

        .cart-index-subtitle {
            font-size: 1.25rem;
            color: var(--brand-brown);
            opacity: 0.8;
            margin-bottom: 0;
        }

        /* Back Button */
        .back-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        .back-btn i {
            font-size: 1.1rem;
        }

        /* Cart Content Section */
        .cart-content-section {
            padding: 3rem 0;
            position: relative;
            z-index: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-index-header {
                padding: 2rem 0;
            }

            .cart-index-title {
                font-size: 2rem;
            }

            .cart-index-subtitle {
                font-size: 1rem;
            }

            .back-btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }

            .cart-content-section {
                padding: 2rem 0;
            }
        }

        @media (max-width: 576px) {
            .cart-index-header {
                padding: 1.5rem 0;
            }

            .cart-index-title {
                font-size: 1.75rem;
            }

            .breadcrumb-list {
                flex-wrap: wrap;
            }

            .back-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection
