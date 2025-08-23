@extends('layouts.app')

@section('pageTitle', $product->name)
@section('title', $product->name)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 gap-3">
                                <i class="fas fa-home"></i>
                                {{ trans('dashboard.dashboard') }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('user.products.index') }}"
                                    class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                    {{ trans('products.products') }}
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">{{ $product->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $product->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $product->category->name ?? trans('products.no_category') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('user.products.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ trans('products.back_to_products') }}</span>
                </a>
            </div>
        </div>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @if ($product->image && file_exists(public_path($product->image)))
                    <img class="w-full h-96 object-cover rounded-lg" src="{{ asset($product->image) }}"
                        alt="{{ $product->name }}">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-gray-400 text-6xl"></i>
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="space-y-6">
                    <!-- Category Badge -->
                    <div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $product->category->name ?? trans('products.no_category') }}
                        </span>
                    </div>

                    <!-- Price -->
                    <div>
                        <h2 class="text-3xl font-bold text-green-600">
                            {{ number_format($product->base_price, 2) }} {{ trans('products.currency') }}
                        </h2>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ trans('products.description') }}</h3>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $product->description ?: trans('products.not_provided') }}
                        </p>
                    </div>

                    <!-- Specifications -->
                    @if ($product->specifications && is_array($product->specifications))
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ trans('products.specifications') }}
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @foreach ($product->specifications as $key => $value)
                                    <div class="flex justify-between py-2 border-b border-gray-200 last:border-b-0">
                                        <span class="font-medium text-gray-700">{{ $key }}:</span>
                                        <span class="text-gray-600">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Product Options -->
                    @if ($product->options->count() > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ trans('products.product_options') }}
                            </h3>
                            <div class="space-y-3">
                                @foreach ($product->options as $option)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-medium text-gray-700">{{ $option->name }}</span>
                                            @if ($option->is_required)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ trans('products.required') }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ trans('products.optional') }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($option->values->count() > 0)
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                @foreach ($option->values as $value)
                                                    <div
                                                        class="flex justify-between items-center p-2 bg-white rounded border">
                                                        <span class="text-sm text-gray-700">{{ $value->name }}</span>
                                                        @if ($value->price_adjustment != 0)
                                                            <span
                                                                class="text-sm font-medium {{ $value->price_adjustment > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 2) }}
                                                                {{ trans('products.currency') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex space-x-4 gap-3">
                            @livewire('add-to-order', ['product' => $product])
                            <button
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                <i class="fas fa-heart"></i>
                                {{ trans('products.add_to_favorites') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @php
            $relatedProducts = \App\Models\Product::where('is_active', true)
                ->where('id', '!=', $product->id)
                ->where('category_id', $product->category_id)
                ->take(4)
                ->get();
        @endphp

        @if ($relatedProducts->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ trans('products.related_products') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        <a href="{{ route('user.products.show', $relatedProduct) }}"
                            class="block bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            @if ($relatedProduct->image && file_exists(public_path($relatedProduct->image)))
                                <img class="w-full h-32 object-cover" src="{{ asset($relatedProduct->image) }}"
                                    alt="{{ $relatedProduct->name }}">
                            @else
                                <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="p-3">
                                <h4 class="font-medium text-gray-900 text-sm line-clamp-2">{{ $relatedProduct->name }}</h4>
                                <p class="text-green-600 font-semibold text-sm mt-1">
                                    {{ number_format($relatedProduct->base_price, 2) }} {{ trans('products.currency') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
