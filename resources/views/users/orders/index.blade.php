@extends('components.layout')

@section('pageTitle', trans('orders.my_orders'))
@section('title', trans('orders.my_orders'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="orders-index-page">
        <!-- Header Section -->
        <div class="orders-index-header">
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
                                    {{ trans('orders.my_orders') }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="orders-index-title">{{ trans('orders.my_orders') }}</h1>
                        <p class="orders-index-subtitle">{{ trans('orders.view_your_orders') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <a href="{{ route('home') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ trans('dashboard.back_to_dashboard') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Content -->
        <div class="orders-content-section">
            <div class="container">
                <!-- Orders Table -->
                @livewire('user-orders-table')
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Orders Index Page Styles - Based on Product Show Page Design */
        .orders-index-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, #FFEBC6 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Styles */
        .orders-index-header {
            background: linear-gradient(135deg, #2C2C2C 0%, #404040 100%);
            color: #FFEBC6;
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .orders-index-header::before {
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

        .orders-index-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: #FFEBC6;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease forwards;
            position: relative;
            z-index: 1;
        }

        .orders-index-subtitle {
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

        /* Orders Content Section */
        .orders-content-section {
            padding: 2rem 0;
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
            .orders-index-title {
                font-size: 2.5rem;
            }

            .orders-index-subtitle {
                font-size: 1rem;
            }

            .back-btn {
                padding: 0.6rem 1.2rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .orders-index-header {
                padding: 2rem 0;
            }

            .orders-index-title {
                font-size: 2rem;
            }

            .orders-index-subtitle {
                font-size: 0.9rem;
            }

            .breadcrumb-list {
                flex-direction: column;
                align-items: flex-start;
            }

            .breadcrumb-item:not(:last-child)::after {
                display: none;
            }
        }
    </style>
@endsection
