@extends('components.layout')
@section('content')
    <x-navbar />

    <!-- About Us Section -->
    <section class="py-5" style="background: linear-gradient(180deg, var(--brand-yellow-light) 0%, #FFFFFF 100%); min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold mb-3" style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('About Us') }}</h1>
                        <p class="lead" style="color: var(--brand-brown-light); font-family: 'Cairo', sans-serif;">{{ trans('Learn more about our company and mission') }}</p>
                    </div>

                    <div class="row g-4">
                        <!-- Mission Section -->
                        <div class="col-md-6">
                            <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); height: 100%;">
                                <div class="mb-4">
                                    <i class="fas fa-bullseye fa-3x" style="color: var(--brand-yellow-dark);"></i>
                                </div>
                                <h3 class="mb-3" style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('Our Mission') }}</h3>
                                <p style="color: var(--brand-brown-light); font-family: 'Cairo', sans-serif; line-height: 1.6;">
                                    {{ trans('We are dedicated to providing exceptional design services and products that help our clients achieve their creative vision. Our mission is to deliver innovative solutions with the highest quality standards.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Vision Section -->
                        <div class="col-md-6">
                            <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); height: 100%;">
                                <div class="mb-4">
                                    <i class="fas fa-eye fa-3x" style="color: var(--brand-yellow-dark);"></i>
                                </div>
                                <h3 class="mb-3" style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('Our Vision') }}</h3>
                                <p style="color: var(--brand-brown-light); font-family: 'Cairo', sans-serif; line-height: 1.6;">
                                    {{ trans('To be the leading provider of design services and products, recognized for our creativity, quality, and customer satisfaction. We envision a future where every client can easily access professional design solutions.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Values Section -->
                        <div class="col-12">
                            <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <h3 class="mb-4" style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('Our Values') }}</h3>
                                <div class="row g-4">
                                    <div class="col-md-3 text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-heart fa-2x" style="color: var(--brand-yellow-dark);"></i>
                                        </div>
                                        <h5 style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('Quality') }}</h5>
                                        <p class="small" style="color: var(--brand-brown-light); font-family: 'Cairo', sans-serif;">{{ trans('We maintain the highest standards in all our products and services.') }}</p>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-lightbulb fa-2x" style="color: var(--brand-yellow-dark);"></i>
                                        </div>
                                        <h5 style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('Innovation') }}</h5>
                                        <p class="small" style="color: var(--brand-brown-light); font-family: 'Cairo', sans-serif;">{{ trans('We continuously seek new and creative solutions for our clients.') }}</p>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-handshake fa-2x" style="color: var(--brand-yellow-dark);"></i>
                                        </div>
                                        <h5 style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('Trust') }}</h5>
                                        <p class="small" style="color: var(--brand-brown-light); font-family: 'Cairo', sans-serif;">{{ trans('We build lasting relationships based on trust and reliability.') }}</p>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-users fa-2x" style="color: var(--brand-yellow-dark);"></i>
                                        </div>
                                        <h5 style="color: var(--brand-brown); font-family: 'Cairo', sans-serif;">{{ trans('Customer Focus') }}</h5>
                                        <p class="small" style="color: var(--brand-brown-light); font-family: 'Cairo', sans-serif;">{{ trans('Our customers are at the heart of everything we do.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact CTA -->
                    <div class="text-center mt-5">
                        <div class="p-5 rounded" style="background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%); color: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <h3 class="mb-3" style="font-family: 'Cairo', sans-serif;">{{ trans('Get in Touch') }}</h3>
                            <p class="mb-4" style="font-family: 'Cairo', sans-serif;">{{ trans('Ready to start your next project? Contact us today!') }}</p>
                            <a href="{{ route('home') }}" class="btn btn-lg" style="background: var(--brand-yellow); color: var(--brand-brown); border: none; border-radius: 25px; padding: 12px 30px; font-family: 'Cairo', sans-serif; font-weight: bold; transition: all 0.3s ease;">
                                <i class="fas fa-arrow-left me-2"></i>{{ trans('Back to Home') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-footer />
@endsection
