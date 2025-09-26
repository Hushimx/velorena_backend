<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle', trans('Print Your Design Now with the Highest Quality'))</title>

    <!-- SEO Meta Tags -->
    @if (isset($seoData) && $seoData)
        <meta name="description" content="{{ $seoData['description'] ?? '' }}">
        <meta name="keywords" content="{{ $seoData['keywords'] ?? '' }}">
        <meta name="robots" content="{{ $seoData['robots'] ?? 'index, follow' }}">

        <!-- Canonical URL -->
        @if (isset($seoData['canonical']))
            <link rel="canonical" href="{{ $seoData['canonical'] }}">
        @endif

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ $seoData['og_title'] ?? ($seoData['title'] ?? '') }}">
        <meta property="og:description" content="{{ $seoData['og_description'] ?? ($seoData['description'] ?? '') }}">
        <meta property="og:type" content="{{ $seoData['og_type'] ?? 'website' }}">
        <meta property="og:url" content="{{ $seoData['og_url'] ?? ($seoData['canonical'] ?? request()->url()) }}">
        @if (isset($seoData['og_image']))
            <meta property="og:image" content="{{ $seoData['og_image'] }}">
        @endif

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="{{ $seoData['twitter_card'] ?? 'summary' }}">
        <meta name="twitter:title" content="{{ $seoData['twitter_title'] ?? ($seoData['title'] ?? '') }}">
        <meta name="twitter:description"
            content="{{ $seoData['twitter_description'] ?? ($seoData['description'] ?? '') }}">
        @if (isset($seoData['twitter_image']))
            <meta name="twitter:image" content="{{ $seoData['twitter_image'] }}">
        @endif
    @endif
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Cairo:wght@300;400;600;700;900&display=swap"
        rel="stylesheet">
    {{-- Bootstrap CSS is loaded via Vite in app.css --}}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/home-page.css', 'resources/js/app.js'])
    @livewireStyles

    @yield('additionalHead')
</head>

<body>
