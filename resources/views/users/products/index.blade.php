@extends('layouts.app')

@section('pageTitle', trans('products.products'))
@section('title', trans('products.products'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ trans('products.products') }}</h1>
                <p class="text-gray-600 mt-2">{{ trans('products.browse_our_products') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('home') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ trans('dashboard.back_to_dashboard') }}</span>
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span class="text-green-800 font-medium">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Products Livewire Component -->
        @livewire('user-products-table')
    </div>
@endsection
