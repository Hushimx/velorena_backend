@props(['latestProducts' => [], 'bestSellingProducts' => []])

{{-- Product Slider Section --}}
<div class="product-slider-section py-5 bg-light">
    <div class="container">
        <!-- Latest Products Section -->
        <div class="mb-5">
            <h2 class="text-center fb-bold mb-4">{{ trans('Latest Products') }}</h2>
            <!-- Swiper -->
            <div class="swiper latestProductsSwiper">
                <div class="swiper-wrapper">
                    @if(isset($latestProducts) && count($latestProducts) > 0)
                        @foreach($latestProducts as $product)
                        <div class="swiper-slide">
                            <a href="{{ $product['url'] }}" class="product-link">
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="img-fluid" 
                                             onload="this.classList.add('loaded')"
                                             onerror="this.src='https://placehold.co/300x200/f8f9fa/6c757d?text=No+Image'; this.classList.add('loaded')"
                                             loading="lazy">
                                    </div>
                                    <div class="product-info p-3">
                                        <h5 class="product-name mb-2">{{ $product['name'] }}</h5>
                                        <p class="product-price fw-bold mb-3">{{ $product['base_price'] }} ر.س</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    @else
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product-info p-3 text-center">
                                    <h5 class="product-name mb-2">{{ trans('No products available') }}</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- Best Selling Products Section -->
        <div>
            <h2 class="text-center fb-bold mb-4">{{ trans('Best Selling Products') }}</h2>
            <!-- Swiper -->
            <div class="swiper bestSellingProductsSwiper">
                <div class="swiper-wrapper">
                    @if(isset($bestSellingProducts) && count($bestSellingProducts) > 0)
                        @foreach($bestSellingProducts as $product)
                        <div class="swiper-slide">
                            <a href="{{ $product['url'] }}" class="product-link">
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="img-fluid" 
                                             onload="this.classList.add('loaded')"
                                             onerror="this.src='https://placehold.co/300x200/f8f9fa/6c757d?text=No+Image'; this.classList.add('loaded')"
                                             loading="lazy">

                                    </div>
                                    <div class="product-info p-3">
                                        <h5 class="product-name mb-2">{{ $product['name'] }}</h5>
                                        <p class="product-price fw-bold mb-3">{{ $product['base_price'] }} ر.س</p>

                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    @else
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product-info p-3 text-center">
                                    <h5 class="product-name mb-2">{{ trans('No products available') }}</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</div>

<style>
.product-slider-section {
    background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%);
    padding: 4rem 0;
}

.product-link {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.product-link:hover {
    text-decoration: none;
    color: inherit;
}

.product-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    border: 2px solid transparent;
    will-change: transform;
    backface-visibility: hidden;
    transform: translateZ(0);
    width: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--brand-yellow-dark);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    padding: 0;
    margin: 0;
    width: 100%;
}

.product-image img {
    width: 100% !important;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease, opacity 0.3s ease;
    display: block;
    border-radius: 0;
    background-color: #f8f9fa;
    opacity: 0;
    animation: fadeInImage 0.5s ease forwards;
    max-width: none;
    min-width: 100%;
}

.product-image img.loaded {
    opacity: 1;
}

@keyframes fadeInImage {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.bestseller-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

.bestseller-badge .badge {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
    color: white;
    font-weight: 600;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.product-info {
    text-align: center;
    padding: 1.5rem;
}

.product-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--brand-brown);
    min-height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Cairo', sans-serif;
}

.product-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--brand-brown);
    margin: 1rem 0;
}

.product-slider-section .btn {
    background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
    color: var(--brand-brown);
    border: 2px solid transparent;
    border-radius: 25px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
    font-family: 'Cairo', sans-serif;
}

.product-slider-section .btn:hover {
    background: linear-gradient(135deg, var(--brand-yellow-dark) 0%, var(--brand-yellow) 100%);
    color: var(--brand-brown);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 222, 159, 0.4);
    border-color: var(--brand-yellow-dark);
}

/* Section Titles */
.product-slider-section h2 {
    font-family: 'Cairo', cursive;
    color: var(--brand-brown);
    font-weight: 700;
    margin-bottom: 2rem;
    position: relative;
}

.product-slider-section h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
    border-radius: 2px;
}

/* Swiper customization */
.latestProductsSwiper,
.bestSellingProductsSwiper {
    padding: 20px 0 50px 0;
}

.latestProductsSwiper .swiper-button-next,
.latestProductsSwiper .swiper-button-prev,
.bestSellingProductsSwiper .swiper-button-next,
.bestSellingProductsSwiper .swiper-button-prev {
    color: var(--brand-brown);
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 2px solid var(--brand-yellow);
}

.latestProductsSwiper .swiper-button-next:hover,
.latestProductsSwiper .swiper-button-prev:hover,
.bestSellingProductsSwiper .swiper-button-next:hover,
.bestSellingProductsSwiper .swiper-button-prev:hover {
    background: var(--brand-yellow);
    color: var(--brand-brown);
    transform: scale(1.1);
}

.latestProductsSwiper .swiper-button-next:after,
.latestProductsSwiper .swiper-button-prev:after,
.bestSellingProductsSwiper .swiper-button-next:after,
.bestSellingProductsSwiper .swiper-button-prev:after {
    font-size: 20px;
    font-weight: bold;
}

.latestProductsSwiper .swiper-pagination-bullet,
.bestSellingProductsSwiper .swiper-pagination-bullet {
    background: #ddd;
    opacity: 1;
    width: 12px;
    height: 12px;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.latestProductsSwiper .swiper-pagination-bullet-active,
.bestSellingProductsSwiper .swiper-pagination-bullet-active {
    background: var(--brand-yellow-dark);
    transform: scale(1.2);
}

/* Force full width for images */
.product-image img {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
}

/* Ensure container takes full width */
.product-image {
    width: 100% !important;
    max-width: 100% !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .product-image {
        height: 150px;
    }
    
    .product-name {
        font-size: 1rem;
    }
    
    .product-price {
        font-size: 1.1rem;
    }
    
    .product-slider-section h2 {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Latest Products Swiper
    const latestProductsSwiper = new Swiper('.latestProductsSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        speed: 300,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.latestProductsSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.latestProductsSwiper .swiper-button-next',
            prevEl: '.latestProductsSwiper .swiper-button-prev',
        },
        preloadImages: false,
        lazy: {
            loadPrevNext: true,
            loadOnTransitionStart: true,
        },
        watchSlidesProgress: true,
        watchSlidesVisibility: true,
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 30,
            },
        },
    });

    // Initialize Best Selling Products Swiper
    const bestSellingProductsSwiper = new Swiper('.bestSellingProductsSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        speed: 300,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.bestSellingProductsSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.bestSellingProductsSwiper .swiper-button-next',
            prevEl: '.bestSellingProductsSwiper .swiper-button-prev',
        },
        preloadImages: false,
        lazy: {
            loadPrevNext: true,
            loadOnTransitionStart: true,
        },
        watchSlidesProgress: true,
        watchSlidesVisibility: true,
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 30,
            },
        },
    });

    // Function to add product to cart
    function addToCart(productId) {
        // You can implement cart functionality here
        // For now, we'll just show an alert
        alert('{{ trans("Product added to cart!") }}');
        
        // Example: You can make an AJAX call to add to cart
        /*
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ trans("Product added to cart!") }}');
            } else {
                alert('{{ trans("Error adding product to cart") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ trans("Error adding product to cart") }}');
        });
        */
    }
});
</script>