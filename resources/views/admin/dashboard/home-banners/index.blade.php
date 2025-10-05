@extends('admin.layouts.app')

@section('pageTitle', __('admin.home_banners_management'))
@section('title', __('admin.home_banners_management'))

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-6 lg:mb-0">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-xl">
                                <i class="fas fa-images text-white text-3xl"></i>
                            </div>
                            <div>
                                <h1
                                    class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-indigo-800 to-purple-800 bg-clip-text text-transparent">
                                    {{ __('admin.home_banners_management') }}
                                </h1>
                                <p class="text-xl text-gray-600 mt-3">{{ __('admin.manage_home_banners_platform') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.home-banners.create') }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                            <i class="fas fa-plus mr-3 text-lg"></i>
                            <span>{{ __('admin.add_new_banner') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Banners Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($banners as $banner)
                    <div
                        class="group bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Banner Image -->
                        <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                            @if ($banner->image && file_exists(public_path($banner->image)))
                                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-image text-gray-400 text-5xl mb-2"></i>
                                        <p class="text-gray-500 text-sm">{{ __('admin.no_image') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Gradient Overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                @if ($banner->is_active)
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-lg">
                                        <div class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div>
                                        {{ __('admin.active') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-red-400 to-pink-500 text-white shadow-lg">
                                        <div class="w-2 h-2 bg-white rounded-full mr-2"></div>
                                        {{ __('admin.inactive') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Sort Order Badge -->
                            <div class="absolute top-4 left-4">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-gray-700 shadow-lg">
                                    {{ $banner->sort_order }}
                                </span>
                            </div>
                        </div>

                        <!-- Banner Content -->
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-300">
                                        {{ $banner->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 font-medium">{{ $banner->title_ar }}</p>
                                </div>

                                @if ($banner->description)
                                    <p class="text-sm text-gray-500 leading-relaxed">
                                        {{ Str::limit($banner->description, 120) }}</p>
                                @endif

                                <div class="flex items-center justify-between pt-2">
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-sort-numeric-up mr-1"></i>
                                            {{ __('admin.position') }} {{ $banner->sort_order }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $banner->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.home-banners.show', $banner) }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-all duration-200">
                                        <i class="fas fa-eye mr-2"></i>
                                        {{ __('admin.view') }}
                                    </a>
                                    <a href="{{ route('admin.home-banners.edit', $banner) }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-all duration-200">
                                        <i class="fas fa-edit mr-2"></i>
                                        {{ __('admin.edit') }}
                                    </a>
                                </div>

                                <form action="{{ route('admin.home-banners.destroy', $banner) }}" method="POST"
                                    onsubmit="return confirm('{{ __('admin.confirm_delete') }}')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-all duration-200">
                                        <i class="fas fa-trash mr-2"></i>
                                        {{ __('admin.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-20">
                            <div
                                class="mx-auto w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mb-8">
                                <i class="fas fa-images text-6xl text-indigo-500"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('admin.no_banners_found') }}</h3>
                            <p class="text-xl text-gray-500 mb-8 max-w-md mx-auto">
                                {{ __('admin.create_your_first_banner') }}</p>
                            <a href="{{ route('admin.home-banners.create') }}"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                                <i class="fas fa-plus mr-3 text-lg"></i>
                                <span>{{ __('admin.add_new_banner') }}</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
