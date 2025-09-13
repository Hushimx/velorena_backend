@extends('admin.layouts.app')

@section('pageTitle', __('admin.edit_banner'))
@section('title', __('admin.edit_banner'))

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-6 lg:mb-0">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl shadow-lg">
                                <i class="fas fa-edit text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-purple-600 bg-clip-text text-transparent">
                                    {{ __('admin.edit_banner') }}
                                </h1>
                                <p class="text-lg text-gray-600 mt-2">{{ __('admin.edit_banner_description') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.home-banners.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300 shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-left mr-2"></i>
                            <span class="font-medium">{{ __('admin.back_to_banners') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Banner Preview -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        {{ __('admin.current_banner') }}
                    </h3>
                    <p class="text-purple-100 mt-2">{{ __('admin.current_banner_preview') }}</p>
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

            <!-- Edit Form -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-magic mr-3"></i>
                        {{ __('admin.banner_edit_wizard') }}
                    </h2>
                    <p class="text-purple-100 mt-2">{{ __('admin.update_banner_information') }}</p>
                </div>
                
                <form action="{{ route('admin.home-banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- English Title -->
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-semibold text-gray-700">
                            {{ __('admin.title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $banner->title) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('title') border-red-500 @enderror"
                               placeholder="{{ __('admin.enter_banner_title') }}"
                               required>
                        @error('title')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Arabic Title -->
                    <div class="space-y-2">
                        <label for="title_ar" class="block text-sm font-semibold text-gray-700">
                            {{ __('admin.title_ar') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title_ar" 
                               name="title_ar" 
                               value="{{ old('title_ar', $banner->title_ar) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('title_ar') border-red-500 @enderror"
                               placeholder="{{ __('admin.enter_banner_title_ar') }}"
                               required>
                        @error('title_ar')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    <!-- English Description -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700">
                            {{ __('admin.description') }}
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('description') border-red-500 @enderror"
                                  placeholder="{{ __('admin.enter_banner_description') }}">{{ old('description', $banner->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Arabic Description -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="description_ar" class="block text-sm font-semibold text-gray-700">
                            {{ __('admin.description_ar') }}
                        </label>
                        <textarea id="description_ar" 
                                  name="description_ar" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('description_ar') border-red-500 @enderror"
                                  placeholder="{{ __('admin.enter_banner_description_ar') }}">{{ old('description_ar', $banner->description_ar) }}</textarea>
                        @error('description_ar')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="image" class="block text-sm font-semibold text-gray-700">
                            {{ __('admin.banner_image') }} ({{ __('admin.optional') }})
                        </label>
                        
                        <!-- Enhanced file input -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200">
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="hidden">
                            <label for="image" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 mb-1">{{ __('admin.click_to_upload') }}</p>
                                <p class="text-xs text-gray-500">{{ __('admin.supported_formats') }}: PNG, JPG, GIF ({{ __('admin.max_size') }}: 10MB)</p>
                            </label>
                        </div>
                        
                        @error('image')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div class="space-y-2">
                        <label for="sort_order" class="block text-sm font-semibold text-gray-700">
                            {{ __('admin.sort_order') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="sort_order" 
                                name="sort_order" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('sort_order') border-red-500 @enderror"
                                required>
                            <option value="1" {{ old('sort_order', $banner->sort_order) == 1 ? 'selected' : '' }}>1 - {{ __('admin.first_position') }}</option>
                            <option value="2" {{ old('sort_order', $banner->sort_order) == 2 ? 'selected' : '' }}>2 - {{ __('admin.second_position') }}</option>
                            <option value="3" {{ old('sort_order', $banner->sort_order) == 3 ? 'selected' : '' }}>3 - {{ __('admin.third_position') }}</option>
                            <option value="4" {{ old('sort_order', $banner->sort_order) == 4 ? 'selected' : '' }}>4 - {{ __('admin.fourth_position') }}</option>
                            <option value="5" {{ old('sort_order', $banner->sort_order) == 5 ? 'selected' : '' }}>5 - {{ __('admin.fifth_position') }}</option>
                        </select>
                        @error('sort_order')
                            <p class="text-sm text-red-600 flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">{{ __('admin.status') }}</label>
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-200">
                            <label for="is_active" class="ml-3 block text-sm font-medium text-gray-700">
                                {{ __('admin.is_active') }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.home-banners.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center shadow-sm">
                        <i class="fas fa-save mr-2"></i>
                        {{ __('admin.update_banner') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
