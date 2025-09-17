@extends('components.layout')

@section('pageTitle', trans('dashboard.book_new_appointment'))
@section('title', trans('dashboard.book_new_appointment'))

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="appointment-booking-page">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h1 class="hero-title">حجز موعد جديد</h1>
                    <p class="hero-subtitle">احجز موعدك مع مصممينا الخبراء لتحويل رؤيتك إلى واقع</p>
                    
                    <!-- Breadcrumb -->
                    <nav class="breadcrumb">
                        <a href="{{ route('home') }}" class="breadcrumb-link">
                            <i class="fas fa-home"></i>
                            الرئيسية
                        </a>
                        <span class="breadcrumb-separator">›</span>
                        <a href="{{ route('appointments.index') }}" class="breadcrumb-link">
                            مواعيدي
                        </a>
                        <span class="breadcrumb-separator">›</span>
                        <span class="breadcrumb-current">حجز موعد جديد</span>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                @livewire('book-appointment')
            </div>
        </div>
    </div>

    <!-- Footer from Welcome Page -->
    <x-footer />

    <style>
        /* Brand Colors */
        :root {
            --brand-yellow: #ffde9f;
            --brand-yellow-dark: #f5d182;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #3a2e2e;
            --brand-yellow-light: #fff4e6;
            --brand-yellow-hover: #f0d4a0;
            --brand-brown-dark: #1a1414;
            --brand-brown-hover: #4a3e3e;
        }

        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, #ffffff 100%);
            direction: rtl;
            line-height: 1.6;
        }

        /* Page Container */
        .appointment-booking-page {
            min-height: 100vh;
            position: relative;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
            animation: heroSlideIn 1s ease-out;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            pointer-events: none;
            animation: backgroundFloat 20s ease-in-out infinite;
        }

        @keyframes heroSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes backgroundFloat {
            0%, 100% {
                transform: translateX(0) translateY(0);
            }
            25% {
                transform: translateX(-10px) translateY(-5px);
            }
            50% {
                transform: translateX(5px) translateY(-10px);
            }
            75% {
                transform: translateX(-5px) translateY(5px);
            }
        }

        .hero-content {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .hero-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            animation: iconBounce 2s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        .hero-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: iconShine 3s ease-in-out infinite;
        }

        .hero-icon i {
            font-size: 3rem;
            color: var(--brand-brown);
            position: relative;
            z-index: 1;
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconBounce {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-10px) scale(1.05);
            }
        }

        @keyframes iconShine {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes iconPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .hero-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(42, 30, 30, 0.1);
            animation: titleSlideIn 1s ease-out 0.3s both;
            background: linear-gradient(45deg, var(--brand-brown), var(--brand-brown-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes titleSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--brand-brown-light);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 500;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .breadcrumb-link {
            color: var(--brand-brown);
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-link:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            text-decoration: none;
            color: var(--brand-brown-dark);
        }

        .breadcrumb-separator {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 1.2rem;
        }

        .breadcrumb-current {
            color: var(--brand-brown-dark);
            font-weight: 700;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 25px;
        }

        /* Main Content */
        .main-content {
            padding: 3rem 0;
            position: relative;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 3rem 0;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-icon {
                width: 80px;
                height: 80px;
            }

            .hero-icon i {
                font-size: 2.5rem;
            }

            .breadcrumb {
                gap: 0.5rem;
            }

            .breadcrumb-link {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 2rem 0;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-icon {
                width: 70px;
                height: 70px;
            }

            .hero-icon i {
                font-size: 2rem;
            }

            .main-content {
                padding: 2rem 0;
            }
        }
    </style>
@endsection