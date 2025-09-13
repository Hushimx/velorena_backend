@extends('admin.layouts.app')

@section('pageTitle', __('admin.banner_details'))
@section('title', __('admin.banner_details'))

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-6 lg:mb-0">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                                <i class="fas fa-eye text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-emerald-600 bg-clip-text text-transparent">
                                    {{ __('admin.banner_details') }}
                                </h1>
                                <p class="text-lg text-gray-600 mt-2">{{ __('admin.view_banner_information') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.home-banners.edit', $banner) }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                            <i class="fas fa-edit mr-2"></i>
                            <span>{{ __('admin.edit_banner') }}</span>
                        </a>
                        <a href="{{ route('admin.home-banners.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300 shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="font-medium">{{ __('admin.back_to_banners') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Banner Preview -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-image mr-3"></i>
                        {{ __('admin.banner_preview') }}
                    </h3>
                    <p class="text-emerald-100 mt-2">{{ __('admin.full_banner_display') }}</p>
                </div>
                <div class="p-8">
                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl overflow-hidden shadow-inner">
                        @if($banner->image && file_exists(public_path($banner->image)))
                            <img src="{{ asset($banner->image) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-image text-gray-400 text-6xl mb-4"></i>
                                    <p class="text-gray-500">{{ __('admin.no_image') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        <!-- Banner Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('admin.basic_information') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.title') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->title }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.title_ar') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->title_ar }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.sort_order') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->sort_order }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.status') }}</label>
                        <div class="mt-1">
                            @if($banner->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ __('admin.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ __('admin.inactive') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descriptions -->
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('admin.descriptions') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.description') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->description ?: __('admin.not_provided') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.description_ar') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->description_ar ?: __('admin.not_provided') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('admin.timestamps') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('admin.created_at') }}</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $banner->created_at->format('M d, Y H:i') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('admin.updated_at') }}</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $banner->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <form action="{{ route('admin.home-banners.destroy', $banner) }}" method="POST" 
                  onsubmit="return confirm('{{ __('admin.confirm_delete') }}')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    <span>{{ __('admin.delete_banner') }}</span>
                </button>
            </form>
        </div>
    </div>
@endsection
