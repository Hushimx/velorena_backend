<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trans('Print Your Design Now with the Highest Quality') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Cairo:wght@300;400;600;700;900&display=swap"
        rel="stylesheet">
    {{-- bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- owl.carousel --}}
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/home-page.css', 'resources/js/app.js'])
</head>

<body>
    <x-navbar />

    <x-main-content />

    <div class="services">
        <h2 class="text-center fb-bold">{{ trans('Services') }}</h2>
        <div class="container">
            <div class="owl-carousel">
                <!-- Card 1 -->
                <div
                    class="item d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                    <i class="fa fa-print fa-3x mb-3 text-primary"></i>
                    <h3 class="mb-2">{{ trans('Printing') }}</h3>
                </div>
                <!-- Card 2 -->
                <div
                    class="item d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                    <i class="fa fa-paint-brush fa-3x mb-3 text-success"></i>
                    <h3 class="mb-2">{{ trans('Design') }}</h3>
                </div>
                <!-- Card 3 -->
                <div
                    class="item d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                    <i class="fa fa-shipping-fast fa-3x mb-3 text-warning"></i>
                    <h3 class="mb-2">{{ trans('Delivery') }}</h3>
                </div>
                <!-- Card 3 -->
                <div
                    class="item d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                    <i class="fa fa-shipping-fast fa-3x mb-3 text-warning"></i>
                    <h3 class="mb-2">{{ trans('Delivery') }}</h3>
                </div>
                <!-- Card 3 -->
                <div
                    class="item d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                    <i class="fa fa-shipping-fast fa-3x mb-3 text-warning"></i>
                    <h3 class="mb-2">{{ trans('Delivery') }}</h3>
                </div>
            </div>
        </div>
    </div>



    {{-- services --}}
    <div class="services">
        <h2 class="text-center fb-bold">{{ trans('Services') }}</h2>
        <div class="container">
            <div class="d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                <i class="fa fa-print fa-3x mb-3 text-primary"></i>
                <h3 class="mb-2">{{ trans('Printing') }}</h3>
            </div>
            <!-- Card 2 -->
            <div class="d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                <i class="fa fa-paint-brush fa-3x mb-3 text-success"></i>
                <h3 class="mb-2">{{ trans('Design') }}</h3>
            </div>
            <!-- Card 3 -->
            <div class="d-flex flex-column align-items-center justify-content-center service-box py-4 mx-2 flex-fill">
                <i class="fa fa-shipping-fast fa-3x mb-3 text-warning"></i>
                <h3 class="mb-2">{{ trans('Delivery') }}</h3>
            </div>
        </div>
    </div>

    {{-- our products --}}
    <div class="container">
        <h2 class="text-center fb-bold mb-3">{{ trans('Our Products') }}</h2>
        <div class="row">
            <div class="col-12">
                <p class="text-center">
                    {{ trans('We offer you a diverse range of prints and promotional products that suit all your needs. From catalogs and business cards to brochures, packaging, and gift boxes, every product we offer is executed with the highest quality and latest printing technologies to reflect the professionalism of your brand. Our products are not just a means of display, but a powerful tool for influence and leaving a lasting impression with your customers.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Product Display -->
        <div class="product-display">
            <div class="product-stack">
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF69B4/FFFFFF?text=Love+in+Token" alt="Love in Token"
                        class="product-image">
                    <div class="box-title">Love in Token</div>
                    <div class="box-subtitle">EVERY TOKEN HAS A STORY</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF1493/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders"
                        class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF69B4/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders"
                        class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF1493/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders"
                        class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
                <div class="product-box">
                    <img src="https://placehold.co/180x100/FF69B4/FFFFFF?text=LITTLE+WONDERS" alt="Little Wonders"
                        class="product-image">
                    <div class="box-title">LITTLE WONDERS</div>
                    <div class="box-subtitle">HANDMADE WITH LOVE</div>
                    <div class="box-side-text">MAGIC IN EVERY THREAD</div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <div class="content-background">
                <img src="https://placehold.co/400x300/FFEBC6/2C2C2C?text=High+Quality+Printing" alt="Printing Services"
                    class="background-image">
            </div>
            <h1 class="main-headline">{{ trans('Print Your Design Now with the Highest Quality') }}</h1>
            <p class="sub-headline">
                {{ trans('Transform your ideas into distinctive prints with high quality and professional design that meets your needs and reflects your identity') }}
            </p>
            <a href="{{ route('register') }}" class="print-btn">
                <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                {{ trans('Print Now') }}
            </a>
        </div>
    </main>


    <!-- Why Choose Us Section -->
    <x-why-choose-us />

    <!-- Footer -->
    <x-footer />

    {{-- bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
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
