    {{-- our products --}}
    <div class="our-products py-5">
        <h2 class="text-center fb-bold mb-3">{{ trans('Our Products') }}</h2>
        <div class="container">
            <!-- Swiper -->
            <div class="swiper servicesSwiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div
                            class="service-box d-flex flex-column align-items-center justify-content-center py-4 mx-auto">
                            <i class="fas fa-print fa-3x mb-3 text-primary"></i>
                            <h3 class="mb-4">{{ trans('Printing') }}</h3>
                            <button class="btn btn-dark">{{ trans('Order Now') }}</button>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div
                            class="service-box d-flex flex-column align-items-center justify-content-center py-4 mx-auto">
                            <i class="fas fa-paint-brush fa-3x mb-3 text-success"></i>
                            <h3 class="mb-4">{{ trans('Design') }}</h3>
                            <button class="btn btn-dark">{{ trans('Order Now') }}</button>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div
                            class="service-box d-flex flex-column align-items-center justify-content-center py-4 mx-auto">
                            <i class="fas fa-shipping-fast fa-3x mb-3 text-warning"></i>
                            <h3 class="mb-4">{{ trans('Delivery') }}</h3>
                            <button class="btn btn-dark">{{ trans('Order Now') }}</button>
                        </div>
                    </div>
                    <!-- Slide 4 -->
                    <div class="swiper-slide">
                        <div
                            class="service-box d-flex flex-column align-items-center justify-content-center py-4 mx-auto">
                            <i class="fas fa-cogs fa-3x mb-3 text-info"></i>
                            <h3 class="mb-4">Customization</h3>
                            <button class="btn btn-dark">{{ trans('Order Now') }}</button>
                        </div>
                    </div>
                    <!-- Slide 5 -->
                    <div class="swiper-slide">
                        <div
                            class="service-box d-flex flex-column align-items-center justify-content-center py-4 mx-auto">
                            <i class="fas fa-headset fa-3x mb-3 text-danger"></i>
                            <h3 class="mb-4">Support</h3>
                            <button class="btn btn-dark">{{ trans('Order Now') }}</button>
                        </div>
                    </div>
                </div>
                <!-- Navigation buttons -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
