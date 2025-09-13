<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قاد - طباعة عالية الجودة</title>

        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/home-page.css', 'resources/js/app.js'])
    </head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <img src="{{ asset('storage/qaads-logo.png') }}" alt="قاد" class="logo-image">
            </div>
            
            <nav>
                <ul class="nav-links">
                    <li><a href="/" class="active">الصفحة الرئيسية</a></li>
                    <li><a href="{{ route('user.products.index') }}">المنتجات</a></li>
                    <li><a href="{{ route('user.orders.index') }}">الطلبات</a></li>
                    <li><a href="#">تصميم بالذكاء الصناعي</a></li>
                </ul>
            </nav>
            
            <a href="{{ route('login') }}" class="appointment-btn">
                <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                موعد مع مصمم
            </a>
        </div>
        </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Product Display -->
        <div class="product-display">
            <div class="product-stack">
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF69B4/FFFFFF?text=Love+in+Token" alt="Love in Token" class="product-image">
                    <div class="box-title">Love in Token</div>
                    <div class="box-subtitle">EVERY TOKEN HAS A STORY</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF1493/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders" class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF69B4/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders" class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF1493/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders" class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF69B4/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders" class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <div class="content-background">
                <img src="https://placehold.co/400x300/FFEBC6/2C2C2C?text=High+Quality+Printing" alt="Printing Services" class="background-image">
            </div>
            <h1 class="main-headline">أطبع تصميمك الآن بأعلى جودة ممكنة</h1>
            <p class="sub-headline">حول أفكارك إلى مطبوعات مميزة بجودة عالية وتصميم احترافي يلبي احتياجاتك ويعكس هويتك</p>
            <a href="{{ route('register') }}" class="print-btn">
                <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                أطبع الآن
            </a>
        </div>
    </main>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us">
        <div class="why-choose-container">
            <h2 class="section-title">لماذا تختارنا؟</h2>
            <div class="features-grid">
                <!-- Professional Designs -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="8" r="2"/>
                            <path d="M8 12h8"/>
                            <path d="M8 16h4"/>
                        </svg>
                        <div class="icon-people">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="feature-title">تصاميم احترافية</h3>
                    <p class="feature-description">معانا هتلاقي فريق من المصممين اللي بيساعدوك تترجم أفكارك لتصاميم مميزة، وكمان تقدر تستعين بالذكاء الاصطناعي لإبداع تصميمات مبتكرة.</p>
                </div>

                <!-- High Quality -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                            <path d="M4 22h16"/>
                            <path d="M10 14.66V17c0 .55.47.98.97 1.21l1.03.4c.15.06.3.1.46.1.16 0 .31-.04.46-.1l1.03-.4c.5-.23.97-.66.97-1.21v-2.34"/>
                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                            <path d="M12 9V2"/>
                            <path d="M8 2h8"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">جودة عالية</h3>
                    <p class="feature-description">نستخدم أفضل الخامات وأحدث تقنيات الطباعة لنقدم لك مطبوعات متينة بألوان زاهية وتفاصيل دقيقة. الجودة عندنا مش مجرد خيار.</p>
                </div>

                <!-- Fast Delivery -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                            <path d="M15 18H9"/>
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.28a1 1 0 0 0-.684-.948l-1.923-.641a1 1 0 0 1-.578-.502l-1.539-3.076A1 1 0 0 0 16.382 8H14"/>
                            <path d="M8 8h2"/>
                            <path d="M9 18h6"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">توصيل سريع</h3>
                    <p class="feature-description">بنقدملك خدمة توصيل سريعة وموثوقة لحد باب بيتك أو شركتك، علشان تضمن إن مطبوعاتك توصلك في الوقت اللي محتاجه بدون تأخير.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Logo and Description -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="{{ asset('storage/qaads-logo.png') }}" alt="قاد" class="footer-logo-image">
                    </div>
                    <p class="footer-description">منتجات مطبوعة تناسب<br>كل الأذواق و الاحتياجات</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                        <a href="#" class="social-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                    </div>
                </div>

                <!-- Contact Us -->
                <div class="footer-section">
                    <h3 class="footer-title">تواصل معنا</h3>
                    <div class="contact-item">
                        <svg class="contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <span>0123456789</span>
                    </div>
                    <div class="contact-item">
                        <svg class="contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span>السعودية</span>
                    </div>
                    <div class="contact-item">
                        <svg class="contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <span>Qaads@email.com</span>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h3 class="footer-title">روابط سريعة</h3>
                    <ul class="footer-links">
                        <li><a href="/">الرئيسية</a></li>
                        <li><a href="{{ route('user.products.index') }}">المنتجات</a></li>
                        <li><a href="{{ route('user.orders.index') }}">الطلبات</a></li>
                        <li><a href="#contact">تواصل معنا</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="footer-section">
                    <h3 class="footer-title">الدعم</h3>
                    <ul class="footer-links">
                        <li><a href="#help">مركز المساعدة</a></li>
                        <li><a href="#terms">شروط الخدمات</a></li>
                        <li><a href="#privacy">شروط الخصوصية</a></li>
                        <li><a href="#legal">قانوني</a></li>
                    </ul>
                </div>
        </div>

            <!-- Copyright -->
            <div class="footer-bottom">
                <p>جميع الحقوق محفوظة - 2025 Qaads</p>
                </div>
        </div>
    </footer>

    <script>
        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            const productBoxes = document.querySelectorAll('.product-box');
            
            productBoxes.forEach((box, index) => {
                box.style.animationDelay = `${index * 0.1}s`;
                box.style.animation = 'fadeInUp 0.6s ease forwards';
            });
            
            // Add hover effects to buttons
            const buttons = document.querySelectorAll('.appointment-btn, .print-btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
    </body>
</html>
