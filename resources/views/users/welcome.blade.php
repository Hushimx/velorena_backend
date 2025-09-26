@extends('components.layout')
@section('content')
    <x-navbar />

    <x-main-content />

    <x-categories-slider :categories="$categories" />
    <x-product-slider :latest-products="$latestProducts" :best-selling-products="$bestSellingProducts" />

    <x-why-choose-us />

    <x-reviews-section />

    <x-footer />
@endsection
