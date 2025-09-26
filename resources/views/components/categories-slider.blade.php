@props(['categories' => []])

{{-- Categories Slider Section --}}
<div class="categories-slider-section py-5">
    <div class="container">
        <!-- Swiper -->
        <div class="swiper categoriesSwiper">
            <div class="swiper-wrapper">
                @if(isset($categories) && count($categories) > 0)
                    @foreach($categories as $category)
                    <div class="swiper-slide">
                        <div class="category-card" data-url="{{ $category['url'] }}">
                            <div class="category-image">
                                <img src="{{ $category['image_url'] }}" alt="{{ $category['name'] }}" class="img-fluid" 
                                     onload="this.classList.add('loaded')"
                                     onerror="this.src='{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}'; this.classList.add('loaded')"
                                     loading="lazy">
                                @if(isset($category['badge']))
                                <div class="category-badge">{{ $category['badge'] }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    {{-- Default fake categories matching the image --}}
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="صندوق عطور" class="img-fluid" loading="lazy">
                                <div class="category-badge">مجاني</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="الصحة والتغذية" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="الملابس التقليدية" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="متجر السفر" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="شنط السفر" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="الجمال" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="المقاضي" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="الجو الحلو" class="img-fluid" loading="lazy">
                                <div class="category-badge">عرض</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="الأحذية" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="متجر عالمي" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="اليوم الوطني 95" class="img-fluid" loading="lazy">
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('assets/imgs/تنظيم المـواتمرات (2).png') }}" alt="عروض" class="img-fluid" loading="lazy">
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

<style>
.categories-slider-section {
    background: transparent;
    padding: 2rem 0;
}

.category-card {
    background: transparent;
    border-radius: 0;
    box-shadow: none;
    overflow: hidden;
    transition: transform 0.3s ease;
    height: 241px; /* 482px / 2 for proper scaling */
    width: 164px; /* 328px / 2 for proper scaling */
    border: none;
    cursor: pointer;
    text-align: center;
    margin: 0 auto;
    position: relative;
}

.category-card:hover {
    transform: translateY(-3px);
}

.category-image {
    position: relative;
    height: 241px; /* 482px / 2 for proper scaling */
    width: 164px; /* 328px / 2 for proper scaling */
    overflow: hidden;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Show full image without cropping */
    transition: transform 0.3s ease;
    border-radius: 0;
}

.category-card:hover .category-image img {
    transform: scale(1.05);
}

.category-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: #dc3545;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 4px;
    z-index: 2;
    font-family: 'Cairo', sans-serif;
}


/* Swiper customization */
.categoriesSwiper {
    padding: 20px 0 50px 0;
}

/* Pagination positioning */
.categoriesSwiper .swiper-pagination {
    bottom: 10px;
    position: relative;
}

.categoriesSwiper .swiper-button-next,
.categoriesSwiper .swiper-button-prev {
    color: #333;
    background: white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
}

.categoriesSwiper .swiper-button-next:hover,
.categoriesSwiper .swiper-button-prev:hover {
    background: #f8f9fa;
    color: #333;
    transform: scale(1.05);
}

.categoriesSwiper .swiper-button-next:after,
.categoriesSwiper .swiper-button-prev:after {
    font-size: 16px;
    font-weight: bold;
}

.categoriesSwiper .swiper-pagination-bullet {
    background: #999;
    opacity: 1;
    width: 8px;
    height: 8px;
    margin: 0 4px;
    transition: all 0.3s ease;
}

.categoriesSwiper .swiper-pagination-bullet-active {
    background: #333;
    transform: scale(1.3);
}

/* Responsive design */
@media (max-width: 768px) {
    .category-card {
        height: 180px; /* Smaller for mobile */
        width: 123px; /* Smaller for mobile */
    }
    
    .category-image {
        height: 180px; /* Smaller for mobile */
        width: 123px; /* Smaller for mobile */
    }
    
    
    .category-badge {
        font-size: 0.6rem;
        padding: 1px 4px;
        bottom: 6px;
        left: 6px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Categories Swiper
    const categoriesSwiper = new Swiper('.categoriesSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        speed: 300,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.categoriesSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.categoriesSwiper .swiper-button-next',
            prevEl: '.categoriesSwiper .swiper-button-prev',
        },
        preloadImages: false,
        lazy: {
            loadPrevNext: true,
            loadOnTransitionStart: true,
        },
        watchSlidesProgress: true,
        watchSlidesVisibility: true,
        breakpoints: {
            320: {
                slidesPerView: 2.5,
                spaceBetween: 25,
            },
            768: {
                slidesPerView: 3.5,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 6,
                spaceBetween: 30,
            },
        },
    });

    // Add click functionality to category cards
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const categoryUrl = this.getAttribute('data-url');
            if (categoryUrl) {
                window.location.href = categoryUrl;
            }
        });
    });
});
</script>