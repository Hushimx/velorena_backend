@extends('admin.layouts.app')

@section('pageTitle', __('admin.products_management'))
@section('title', __('admin.products_management'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.products_management') }}</h1>
                <p class="text-gray-600">{{ __('admin.manage_products_platform') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('admin.categories') }}</span>
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>{{ __('admin.add_new_product') }}</span>
                </a>
            </div>
        </div>

        <!-- Products Table -->
        @livewire('products-table')
    </div>
@endsection
