@extends('components.layout')

@section('pageTitle', trans('dashboard.book_new_appointment'))
@section('title', trans('dashboard.book_new_appointment'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="appointment-create-page">
        <!-- Header Section -->
        <div class="appointment-create-header">
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
                                    <a href="{{ route('appointments.index') }}" class="breadcrumb-link">
                                        {{ trans('dashboard.my_appointments') }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('dashboard.book_new_appointment') }}
                                </li>
                            </ol>
                        </nav>

                        <h1 class="appointment-create-title">{{ trans('dashboard.book_new_appointment') }}</h1>
                        <p class="appointment-create-subtitle">{{ trans('dashboard.book_appointment_description') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex justify-content-end">
                        <a href="{{ route('appointments.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ trans('dashboard.back_to_appointments') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Content -->
        <div class="appointment-content-section">
            <div class="container">
                <!-- Livewire Component -->
                @livewire('book-appointment-with-orders', ['orderId' => $orderId ?? null])
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Appointment Create Page Styles - Based on Product Show Page Design */
        .appointment-create-page {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(180deg, #FFEBC6 0%, #FFFFFF 100%);
            min-height: calc(100vh - 96px);
            direction: rtl;
            padding-top: 0;
        }

        /* Header Section */
        .appointment-create-header {
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }

        .appointment-create-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateX(0px) translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateX(30px) translateY(-30px) rotate(120deg);
            }

            66% {
                transform: translateX(-20px) translateY(20px) rotate(240deg);
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
            padding: 0;
            margin: 0;
            gap: 0.5rem;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: '›';
            margin: 0 0.5rem;
            color: rgba(139, 69, 19, 0.6);
            font-weight: 700;
        }

        .breadcrumb-link {
            color: rgba(139, 69, 19, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: #8B4513;
            background: rgba(255, 255, 255, 0.2);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #8B4513;
            font-weight: 700;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        /* Title and Subtitle */
        .appointment-create-title {
            font-size: 3rem;
            font-weight: 900;
            color: #8B4513;
            margin: 0 0 1rem 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .appointment-create-subtitle {
            font-size: 1.2rem;
            color: rgba(139, 69, 19, 0.8);
            margin: 0;
            font-weight: 500;
        }

        /* Back Button */
        .back-btn {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
            color: white;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
        }

        /* Content Section */
        .appointment-content-section {
            padding: 3rem 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .appointment-create-title {
                font-size: 2rem;
            }

            .appointment-create-subtitle {
                font-size: 1rem;
            }

            .breadcrumb-list {
                flex-wrap: wrap;
            }

            .back-btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .appointment-create-header {
                padding: 2rem 0;
            }

            .appointment-content-section {
                padding: 2rem 0;
            }

            .appointment-create-title {
                font-size: 1.75rem;
            }

            .breadcrumb-nav {
                margin-bottom: 1rem;
            }
        }
    </style>
@endsection
