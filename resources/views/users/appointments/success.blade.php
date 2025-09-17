@extends('components.layout')

@section('pageTitle', 'تم حجز الموعد بنجاح')
@section('title', 'تم حجز الموعد بنجاح')

@section('content')
    <!-- Navbar from Welcome Page -->
    <x-navbar />

    <div class="success-page">
        <!-- Success Hero Section -->
        <div class="success-hero">
            <div class="container">
                <div class="success-content">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="success-title">تم حجز الموعد بنجاح!</h1>
                    <p class="success-subtitle">تم تأكيد حجز موعدك وسيتم التواصل معك قريباً</p>
                    
                    <!-- Success Details -->
                    <div class="success-details">
                        <div class="detail-card">
                            <div class="detail-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="detail-content">
                                <h3>موعدك محجوز</h3>
                                <p>تم تأكيد حجز موعدك بنجاح</p>
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-content">
                                <h3>تأكيد بالبريد الإلكتروني</h3>
                                <p>سيتم إرسال تفاصيل الموعد إلى بريدك الإلكتروني</p>
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="detail-content">
                                <h3>متابعة هاتفية</h3>
                                <p>سنتواصل معك قبل الموعد للتأكيد</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="success-actions">
                        <a href="{{ route('appointments.index') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-alt"></i>
                            عرض مواعيدي
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i>
                            العودة للرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Section -->
        <div class="info-section">
            <div class="container">
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>تذكير بالموعد</h4>
                        <p>سوف نتذكرك بالموعد قبل 24 ساعة من الموعد المحدد</p>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4>تعديل الموعد</h4>
                        <p>يمكنك تعديل أو إلغاء الموعد من صفحة مواعيدي</p>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>الدعم الفني</h4>
                        <p>تواصل معنا في أي وقت للحصول على المساعدة</p>
                    </div>
                </div>
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

        /* Success Page Styles */
        .success-page {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, #ffffff 100%);
            direction: rtl;
        }

        /* Success Hero Section */
        .success-hero {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .success-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            pointer-events: none;
        }

        .success-content {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            border: 4px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            animation: successPulse 2s ease-in-out infinite, successBounce 1s ease-out 0.5s both;
            position: relative;
            overflow: hidden;
        }

        .success-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: successShine 3s ease-in-out infinite;
        }

        @keyframes successBounce {
            0% {
                opacity: 0;
                transform: scale(0.3) rotate(-180deg);
            }
            50% {
                transform: scale(1.1) rotate(0deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        @keyframes successShine {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .success-icon i {
            font-size: 4rem;
            color: var(--brand-brown);
            position: relative;
            z-index: 1;
            animation: iconCheckmark 0.8s ease-out 1s both;
        }

        @keyframes iconCheckmark {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes successPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
            }
        }

        .success-title {
            font-family: 'Cairo', cursive;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(42, 30, 30, 0.1);
            animation: titleSlideUp 1s ease-out 0.8s both;
            background: linear-gradient(45deg, var(--brand-brown), var(--brand-brown-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes titleSlideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-subtitle {
            font-size: 1.3rem;
            color: var(--brand-brown-light);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 500;
            animation: subtitleFadeIn 1s ease-out 1.2s both;
        }

        @keyframes subtitleFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success Details */
        .success-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .detail-card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            animation: detailCardSlideIn 0.8s ease-out both;
            position: relative;
            overflow: hidden;
        }

        .detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .detail-card:hover::before {
            left: 100%;
        }

        .detail-card:hover {
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 12px 35px rgba(42, 30, 30, 0.2);
        }

        .detail-card:nth-child(1) { animation-delay: 1.4s; }
        .detail-card:nth-child(2) { animation-delay: 1.6s; }
        .detail-card:nth-child(3) { animation-delay: 1.8s; }

        @keyframes detailCardSlideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .detail-icon {
            width: 60px;
            height: 60px;
            background: var(--brand-brown);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .detail-icon i {
            font-size: 1.5rem;
            color: var(--brand-yellow);
        }

        .detail-content h3 {
            font-family: 'Cairo', cursive;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin-bottom: 0.5rem;
        }

        .detail-content p {
            color: var(--brand-brown-light);
            font-size: 1rem;
            margin: 0;
            line-height: 1.5;
        }

        /* Success Actions */
        .success-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-dark) 100%);
            color: var(--brand-yellow);
            border-color: var(--brand-brown);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--brand-brown-dark) 0%, var(--brand-brown) 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(42, 30, 30, 0.4);
            color: var(--brand-yellow);
            text-decoration: none;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: var(--brand-brown);
            border-color: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(42, 30, 30, 0.2);
            color: var(--brand-brown);
            text-decoration: none;
        }

        /* Info Section */
        .info-section {
            padding: 4rem 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .info-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.08);
            border: 1px solid rgba(255, 222, 159, 0.2);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 48px rgba(42, 30, 30, 0.12);
        }

        .info-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 3px solid var(--brand-brown);
        }

        .info-icon i {
            font-size: 2rem;
            color: var(--brand-brown);
        }

        .info-card h4 {
            font-family: 'Cairo', cursive;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--brand-brown);
            margin-bottom: 1rem;
        }

        .info-card p {
            color: var(--brand-brown-light);
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .success-hero {
                padding: 3rem 0;
            }

            .success-title {
                font-size: 2.5rem;
            }

            .success-subtitle {
                font-size: 1.1rem;
            }

            .success-icon {
                width: 100px;
                height: 100px;
            }

            .success-icon i {
                font-size: 3rem;
            }

            .success-details {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .detail-card {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }

            .success-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .success-hero {
                padding: 2rem 0;
            }

            .success-title {
                font-size: 2rem;
            }

            .success-subtitle {
                font-size: 1rem;
            }

            .success-icon {
                width: 80px;
                height: 80px;
            }

            .success-icon i {
                font-size: 2.5rem;
            }

            .info-section {
                padding: 3rem 0;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>
@endsection
