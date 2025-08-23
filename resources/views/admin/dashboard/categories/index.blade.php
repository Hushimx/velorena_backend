@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Categories')
@section('title', trans('categories.categories_list'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('categories.categories_list') }}</h1>
                <p class="text-gray-600">{{ trans('categories.manage_categories') }}</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-plus pl-2"></i>
                <span>{{ trans('categories.add_new_category') }}</span>
            </a>
        </div>

        <!-- Categories Table -->
        @livewire('categories-table')
    </div>
@endsection
