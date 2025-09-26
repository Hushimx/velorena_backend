@props(['reviews' => []])

{{-- Reviews Section --}}
<div class="reviews-section py-5">
    <div class="container">
        <h2 class="text-center fb-bold mb-4">Our Reviews</h2>
        
        <!-- Swiper -->
        <div class="swiper reviewsSwiper">
            <div class="swiper-wrapper">
                @if(isset($reviews) && count($reviews) > 0)
                    @foreach($reviews as $review)
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <img src="{{ $review['avatar'] ?? 'https://placehold.co/60x60/007bff/ffffff?text=' . substr($review['name'], 0, 1) }}" alt="{{ $review['name'] }}" class="img-fluid">
                                </div>
                                <div class="reviewer-info">
                                    <h5 class="reviewer-name">{{ $review['name'] }}</h5>
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review['rating'] ? 'active' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="review-text">"{{ $review['comment'] }}"</p>
                            </div>
                            <div class="review-date">
                                <small>{{ $review['date'] }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    {{-- Default fake reviews --}}
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <img src="https://placehold.co/60x60/007bff/ffffff?text=A" alt="Ahmed" class="img-fluid">
                                </div>
                                <div class="reviewer-info">
                                    <h5 class="reviewer-name">أحمد محمد</h5>
                                    <div class="review-rating">
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="review-text">"خدمة ممتازة وجودة عالية في الطباعة. أنصح الجميع بالتعامل معهم."</p>
                            </div>
                            <div class="review-date">
                                <small>منذ أسبوع</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <img src="https://placehold.co/60x60/28a745/ffffff?text=F" alt="Fatima" class="img-fluid">
                                </div>
                                <div class="reviewer-info">
                                    <h5 class="reviewer-name">فاطمة علي</h5>
                                    <div class="review-rating">
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="review-text">"التصميم كان رائعاً والتنفيذ سريع. شكراً لكم على الجودة العالية."</p>
                            </div>
                            <div class="review-date">
                                <small>منذ 3 أيام</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <img src="https://placehold.co/60x60/dc3545/ffffff?text=M" alt="Mohammed" class="img-fluid">
                                </div>
                                <div class="reviewer-info">
                                    <h5 class="reviewer-name">محمد السعد</h5>
                                    <div class="review-rating">
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="review-text">"أفضل مكان للطباعة والتصميم. الأسعار مناسبة والخدمة سريعة."</p>
                            </div>
                            <div class="review-date">
                                <small>منذ أسبوعين</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <img src="https://placehold.co/60x60/ffc107/ffffff?text=S" alt="Sara" class="img-fluid">
                                </div>
                                <div class="reviewer-info">
                                    <h5 class="reviewer-name">سارة أحمد</h5>
                                    <div class="review-rating">
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="review-text">"التعامل معهم سهل ومريح. النتائج دائماً تتجاوز التوقعات."</p>
                            </div>
                            <div class="review-date">
                                <small>منذ 5 أيام</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-avatar">
                                    <img src="https://placehold.co/60x60/6f42c1/ffffff?text=K" alt="Khalid" class="img-fluid">
                                </div>
                                <div class="reviewer-info">
                                    <h5 class="reviewer-name">خالد العتيبي</h5>
                                    <div class="review-rating">
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p class="review-text">"خدمة احترافية ومهنية. أنصح جميع الأصدقاء بالتعامل معهم."</p>
                            </div>
                            <div class="review-date">
                                <small>منذ شهر</small>
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
.reviews-section {
    background: transparent;
    padding: 4rem 0;
}

.review-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    padding: 25px;
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 2px solid transparent;
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #c4a700;
}

.review-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.reviewer-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    margin-left: 15px;
    flex-shrink: 0;
}

.reviewer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.reviewer-info {
    flex: 1;
}

.reviewer-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
    font-family: 'Cairo', sans-serif;
}

.review-rating {
    display: flex;
    gap: 2px;
}

.review-rating .fas.fa-star {
    color: #ddd;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.review-rating .fas.fa-star.active {
    color: #ffc107;
}

.review-content {
    margin-bottom: 15px;
}

.review-text {
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
    margin: 0;
    font-style: italic;
    font-family: 'Cairo', sans-serif;
}

.review-date {
    text-align: left;
}

.review-date small {
    color: #999;
    font-size: 0.85rem;
}

/* Section Title */
.reviews-section h2 {
    font-family: 'Cairo', cursive;
    color: #333;
    font-weight: 700;
    margin-bottom: 2rem;
    position: relative;
}

.reviews-section h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #c4a700 0%, #a08500 100%);
    border-radius: 2px;
}

/* Swiper customization */
.reviewsSwiper {
    padding: 20px 0 50px 0;
}

/* Pagination positioning */
.reviewsSwiper .swiper-pagination {
    bottom: 10px;
    position: relative;
}

.reviewsSwiper .swiper-button-next,
.reviewsSwiper .swiper-button-prev {
    color: #333;
    background: white;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: 2px solid #c4a700;
}

.reviewsSwiper .swiper-button-next:hover,
.reviewsSwiper .swiper-button-prev:hover {
    background: #c4a700;
    color: white;
    transform: scale(1.1);
}

.reviewsSwiper .swiper-button-next:after,
.reviewsSwiper .swiper-button-prev:after {
    font-size: 18px;
    font-weight: bold;
}

.reviewsSwiper .swiper-pagination-bullet {
    background: #ddd;
    opacity: 1;
    width: 12px;
    height: 12px;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.reviewsSwiper .swiper-pagination-bullet-active {
    background: #c4a700;
    transform: scale(1.2);
}

/* Responsive design */
@media (max-width: 768px) {
    .review-card {
        padding: 20px;
    }
    
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        margin-left: 12px;
    }
    
    .reviewer-name {
        font-size: 1rem;
    }
    
    .review-text {
        font-size: 0.9rem;
    }
    
    .reviews-section h2 {
        font-size: 1.8rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Reviews Swiper
    const reviewsSwiper = new Swiper('.reviewsSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        speed: 300,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.reviewsSwiper .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.reviewsSwiper .swiper-button-next',
            prevEl: '.reviewsSwiper .swiper-button-prev',
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
                slidesPerView: 1,
                spaceBetween: 15,
            },
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 25,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });
});
</script>
