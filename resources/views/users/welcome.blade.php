@extends('components.layout')
@section('content')
    <x-navbar />

    <x-main-content />

    <x-services />
    <x-product-slider :latest-products="$latestProducts" :best-selling-products="$bestSellingProducts" />

    <x-why-choose-us />


    <x-latest-products />


    <x-footer />
@endsection
