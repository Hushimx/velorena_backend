@extends('components.layout')

@php
    use App\Services\SeoService;
    $seoData = SeoService::generateProductsListingMetaTags();
@endphp

@section('pageTitle', $seoData['title'])
@section('title', $seoData['title'])

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="products-page">
        <!-- Header Section -->
        <div class="products-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="products-title">{{ trans('products.products') }}</h1>
                        <p class="products-subtitle">{{ trans('products.browse_our_products') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <div class="header-actions">
                            <a href="{{ route('home') }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                <span>{{ trans('dashboard.back_to_dashboard') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="container mt-4">
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Products Content -->
        <div class="products-content">
            <div class="container">
                @livewire('user-products-table')
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Products Page Styles - Professional Design */
        .products-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, #fff4e6 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Styles */
        .products-header {
            background: linear-gradient(135deg, #2a1e1e 0%, #3a2e2e 100%);
            color: #ffde9f;
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
        }

        .products-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: #ffde9f;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .products-subtitle {
            font-size: 1.2rem;
            color: #ffde9f;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .header-btn {
            background: #ffde9f;
            color: #2a1e1e;
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
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
            position: relative;
            z-index: 1;
        }

        .header-btn:hover {
            background: #f5d182;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 222, 159, 0.4);
            color: #2a1e1e;
        }


        /* Back Button */
        .back-btn {
            background: #ffde9f;
            color: #2a1e1e;
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
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
            position: relative;
            z-index: 1;
        }

        .back-btn:hover {
            background: #f5d182;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 222, 159, 0.4);
            color: #2a1e1e;
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
            border-radius: 15px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        }

        .success-message i {
            color: #28a745;
            font-size: 1.5rem;
        }

        .success-message span {
            color: #155724;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Content Area */
        .products-content {
            padding-bottom: 3rem;
        }

        /* Override Livewire Component Styles */
        .products-content .bg-white {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Search and Filter Section */
        .products-content .mb-6 {
            background: #fff !important;
            border-radius: 15px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border: 2px solid transparent !important;
            transition: all 0.3s ease !important;
            margin-bottom: 2rem !important;
        }

        .products-content .mb-6:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            border-color: #f5d182 !important;
        }

        .products-content input,
        .products-content select {
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            font-family: 'Cairo', sans-serif !important;
            transition: all 0.3s ease !important;
        }

        .products-content input:focus,
        .products-content select:focus {
            border-color: #f5d182 !important;
            box-shadow: 0 0 0 3px rgba(245, 209, 130, 0.1) !important;
            outline: none !important;
        }

        /* Products Grid */
        .products-content .grid {
            background: transparent !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }

        /* Product Cards */
        .products-content .grid>div {
            background: #fff !important;
            border: 2px solid transparent !important;
            border-radius: 15px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s ease !important;
            overflow: hidden !important;
            position: relative !important;
        }

        .products-content .grid>div:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            border-color: #f5d182 !important;
        }

        /* Product Images */
        .products-content img {
            transition: transform 0.3s ease !important;
        }

        .products-content .grid>div:hover img {
            transform: scale(1.05) !important;
        }

        /* Product Info */
        .products-content h3 {
            font-family: 'Cairo', cursive !important;
            font-weight: 700 !important;
            color: #2a1e1e !important;
        }

        .products-content h3 a {
            color: #2a1e1e !important;
            transition: color 0.3s ease !important;
        }

        .products-content h3 a:hover {
            color: #f5d182 !important;
        }

        /* Category Badge */
        .products-content .bg-blue-100 {
            background: linear-gradient(135deg, #ffde9f 0%, #f5d182 100%) !important;
            color: #2a1e1e !important;
            border: 1px solid #f5d182 !important;
            font-weight: 600 !important;
        }

        /* Price */
        .products-content .text-green-600 {
            color: #f5d182 !important;
            font-family: 'Cairo', cursive !important;
            font-size: 1.2rem !important;
            font-weight: 700 !important;
        }

        /* View Details Button */
        .products-content .bg-blue-600 {
            background: #2a1e1e !important;
            border: none !important;
            border-radius: 8px !important;
            font-family: 'Cairo', cursive !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3) !important;
        }

        .products-content .bg-blue-600:hover {
            background: #3a2e2e !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(42, 30, 30, 0.4) !important;
        }

        /* Pagination */
        .products-content .border-t {
            border-color: #e5e7eb !important;
            background: #fff !important;
            border-radius: 0 0 15px 15px !important;
        }

        /* No Products Found */
        .products-content .text-center {
            background: #fff !important;
            border-radius: 15px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            padding: 3rem !important;
        }

        .products-content .bg-gray-100 {
            background: linear-gradient(135deg, #ffde9f 0%, #f5d182 100%) !important;
            color: #2a1e1e !important;
        }

        .products-content .text-gray-400 {
            color: #f5d182 !important;
        }

        .products-content .text-gray-900 {
            color: #2a1e1e !important;
            font-family: 'Cairo', cursive !important;
            font-weight: 700 !important;
        }

        .products-content .text-gray-600 {
            color: #666 !important;
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            .products-title {
                font-size: 2.5rem;
            }

            .products-subtitle {
                font-size: 1rem;
            }

            .header-actions {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .header-btn,
            .back-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .header-btn span,
            .back-btn span {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .products-header {
                padding: 2rem 0;
            }

            .products-title {
                font-size: 2rem;
            }

            .products-subtitle {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection
