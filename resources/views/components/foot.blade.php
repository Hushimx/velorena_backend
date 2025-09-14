<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    // Initialize Swiper
    document.addEventListener('DOMContentLoaded', function() {
        // Product Slider Swiper
        const productSwiper = new Swiper('.product-slider', {
            slidesPerView: 1.2,
            spaceBetween: 20,
            centeredSlides: false,
            loop: false,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2.2,
                    spaceBetween: 20,
                    centeredSlides: false,
                },
                768: {
                    slidesPerView: 3.2,
                    spaceBetween: 25,
                    centeredSlides: false,
                },
                1024: {
                    slidesPerView: 4.2,
                    spaceBetween: 25,
                    centeredSlides: false,
                },
                1200: {
                    slidesPerView: 5.2,
                    spaceBetween: 25,
                    centeredSlides: false,
                },
            },
        });

        // Services Swiper
        const servicesSwiper = new Swiper('.servicesSwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    centeredSlides: false,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    centeredSlides: false,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 40,
                    centeredSlides: false,
                },
            },
        });

        // Add some interactive animations
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

<!-- Bootstrap JavaScript -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@livewireScripts
</body>

</html>
