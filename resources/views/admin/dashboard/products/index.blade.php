@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Products')
@section('title', trans('products.products_list'))

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ trans('products.products_list') }}</h1>
                <p class="text-gray-600 mt-2">{{ trans('products.manage_products') }}</p>
            </div>
            <div>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mx-2"></i>
                    {{ trans('products.add_new_product') }}
                </a>
            </div>
        </div>

        @livewire('products-table')
    </div>
@endsection
