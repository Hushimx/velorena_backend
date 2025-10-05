@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Create Product')
@section('title', trans('products.create_product'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200 gap-3">
                <i class="fas fa-arrow-left"></i>
                {{ trans('products.back_to_products') }}
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4 mx-3">
                        <h1 class="text-2xl font-bold text-gray-900">{{ trans('products.create_product') }}</h1>
                        <p class="text-gray-600 mt-1">{{ trans('products.add_new_product_to_system') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf

                    <!-- Basic Information Section -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ trans('products.basic_information') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('products.name') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="{{ trans('products.enter_product_name') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Product Name Arabic -->
                            <div class="space-y-2">
                                <label for="name_ar" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('products.name_ar') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}"
                                        placeholder="{{ trans('products.enter_product_name_ar') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-language text-gray-400"></i>
                                    </div>
                                </div>
                                @error('name_ar')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="space-y-2">
                                <label for="category_id" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('products.category') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="category_id" id="category_id" required
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm appearance-none">
                                        <option value="">{{ trans('products.select_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-folder text-gray-400"></i>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Base Price -->
                            <div class="space-y-2">
                                <label for="base_price" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('products.base_price') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="base_price" id="base_price"
                                        value="{{ old('base_price', 0) }}" step="0.01" min="0" required
                                        placeholder="0.00"
                                        class="w-full px-4 py-3 pl-12 pr-16 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-dollar-sign text-gray-400"></i>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span
                                            class="text-gray-500 text-sm font-medium">{{ trans('products.currency') }}</span>
                                    </div>
                                </div>
                                @error('base_price')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descriptions -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('products.description') }}
                                </label>
                                <div class="relative">
                                    <textarea name="description" id="description" rows="4"
                                        placeholder="{{ trans('products.enter_product_description') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('description') }}</textarea>
                                    <div class="absolute top-3 left-4 pointer-events-none">
                                        <i class="fas fa-align-left text-gray-400"></i>
                                    </div>
                                </div>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="description_ar" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('products.description_ar') }}
                                </label>
                                <div class="relative">
                                    <textarea name="description_ar" id="description_ar" rows="4"
                                        placeholder="{{ trans('products.enter_product_description_ar') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('description_ar') }}</textarea>
                                    <div class="absolute top-3 left-4 pointer-events-none">
                                        <i class="fas fa-align-right text-gray-400"></i>
                                    </div>
                                </div>
                                @error('description_ar')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Main Product Image Section -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200"
                        style="background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%); border-color: var(--brand-yellow-dark);">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3"
                                style="background: var(--brand-brown);">
                                <i class="fas fa-image text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold" style="color: var(--brand-brown);">
                                {{ trans('products.main_image') }}</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="border-2 border-dashed rounded-xl p-8 text-center hover:border-opacity-60 transition-all duration-200"
                                style="border-color: var(--brand-brown); background: rgba(255, 255, 255, 0.5);">
                                <input type="file" name="main_image" id="main_image" accept="image/*"
                                    class="hidden">
                                <label for="main_image" class="cursor-pointer">
                                    <div class="mb-3">
                                        <i class="fas fa-cloud-upload-alt text-3xl"
                                            style="color: var(--brand-brown);"></i>
                                    </div>
                                    <p class="text-sm font-medium mb-1" style="color: var(--brand-brown);">
                                        {{ trans('products.click_to_upload_main_image') }}
                                    </p>
                                    <p class="text-xs" style="color: var(--brand-brown-light);">
                                        {{ trans('products.main_image_requirements') }}
                                    </p>
                                </label>
                            </div>
                            <div id="main-image-preview" class="mt-4" style="display: none;">
                                <img id="main-image-preview-img" src="" alt="Preview"
                                    class="w-32 h-32 object-cover rounded-lg shadow-md"
                                    style="border: 2px solid var(--brand-brown);">
                                <p class="text-xs mt-2" style="color: var(--brand-brown-light);">
                                    {{ trans('products.main_image_preview') }}</p>
                            </div>
                            @error('main_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Images Section -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200"
                        style="background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%); border-color: var(--brand-yellow-dark);">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3"
                                style="background: var(--brand-brown);">
                                <i class="fas fa-images text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold" style="color: var(--brand-brown);">
                                {{ trans('products.additional_images') }}</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="border-2 border-dashed rounded-xl p-8 text-center hover:border-opacity-60 transition-all duration-200"
                                style="border-color: var(--brand-brown); background: rgba(255, 255, 255, 0.5);">
                                <input type="file" name="images[]" id="images" accept="image/*" multiple
                                    class="hidden">
                                <label for="images" class="cursor-pointer">
                                    <div class="mb-3">
                                        <i class="fas fa-cloud-upload-alt text-3xl"
                                            style="color: var(--brand-brown);"></i>
                                    </div>
                                    <p class="text-sm font-medium mb-1" style="color: var(--brand-brown);">
                                        {{ trans('products.click_to_upload_images') }}
                                    </p>
                                    <p class="text-xs" style="color: var(--brand-brown-light);">
                                        {{ trans('products.images_requirements') }}
                                    </p>
                                </label>
                            </div>
                            <div id="image-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                                style="display: none;">
                                <!-- Image previews will be added here -->
                            </div>
                            <input type="hidden" name="primary_image" id="primary_image" value="0">
                            @error('images')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- SEO Settings Section -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-search text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ trans('products.seo_settings') }}</h2>
                        </div>

                        <div class="space-y-6">
                            <!-- Meta Tags -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="meta_title" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_meta_title') }}
                                    </label>
                                    <input type="text" name="meta_title" id="meta_title"
                                        value="{{ old('meta_title') }}"
                                        placeholder="{{ trans('products.seo_meta_title_placeholder') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_meta_title_help') }}</p>
                                    @error('meta_title')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="meta_title_ar" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_meta_title_ar') }}
                                    </label>
                                    <input type="text" name="meta_title_ar" id="meta_title_ar"
                                        value="{{ old('meta_title_ar') }}"
                                        placeholder="{{ trans('products.seo_meta_title_ar_placeholder') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_meta_title_help') }}</p>
                                    @error('meta_title_ar')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="meta_description" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_meta_description') }}
                                    </label>
                                    <textarea name="meta_description" id="meta_description" rows="3"
                                        placeholder="{{ trans('products.seo_meta_description_placeholder') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('meta_description') }}</textarea>
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_meta_description_help') }}</p>
                                    @error('meta_description')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="meta_description_ar" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_meta_description_ar') }}
                                    </label>
                                    <textarea name="meta_description_ar" id="meta_description_ar" rows="3"
                                        placeholder="{{ trans('products.seo_meta_description_ar_placeholder') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('meta_description_ar') }}</textarea>
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_meta_description_help') }}</p>
                                    @error('meta_description_ar')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_meta_keywords') }}
                                    </label>
                                    <input type="text" name="meta_keywords" id="meta_keywords"
                                        value="{{ old('meta_keywords') }}"
                                        placeholder="{{ trans('products.seo_meta_keywords_placeholder') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_meta_keywords_help') }}</p>
                                    @error('meta_keywords')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="meta_keywords_ar" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_meta_keywords_ar') }}
                                    </label>
                                    <input type="text" name="meta_keywords_ar" id="meta_keywords_ar"
                                        value="{{ old('meta_keywords_ar') }}"
                                        placeholder="{{ trans('products.seo_meta_keywords_ar_placeholder') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_meta_keywords_help') }}</p>
                                    @error('meta_keywords_ar')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Open Graph -->
                            <div class="border-t border-green-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Open Graph (Social Media)</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="og_title" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('products.seo_og_title') }}
                                        </label>
                                        <input type="text" name="og_title" id="og_title"
                                            value="{{ old('og_title') }}"
                                            placeholder="{{ trans('products.seo_og_title_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                        <p class="text-xs text-gray-500">{{ trans('products.seo_og_title_help') }}</p>
                                        @error('og_title')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label for="og_title_ar" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('products.seo_og_title_ar') }}
                                        </label>
                                        <input type="text" name="og_title_ar" id="og_title_ar"
                                            value="{{ old('og_title_ar') }}"
                                            placeholder="{{ trans('products.seo_og_title_ar_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                        <p class="text-xs text-gray-500">{{ trans('products.seo_og_title_help') }}</p>
                                        @error('og_title_ar')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
                                    <div class="space-y-2">
                                        <label for="og_description" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('products.seo_og_description') }}
                                        </label>
                                        <textarea name="og_description" id="og_description" rows="3"
                                            placeholder="{{ trans('products.seo_og_description_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('og_description') }}</textarea>
                                        <p class="text-xs text-gray-500">{{ trans('products.seo_og_description_help') }}
                                        </p>
                                        @error('og_description')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label for="og_description_ar" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('products.seo_og_description_ar') }}
                                        </label>
                                        <textarea name="og_description_ar" id="og_description_ar" rows="3"
                                            placeholder="{{ trans('products.seo_og_description_ar_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('og_description_ar') }}</textarea>
                                        <p class="text-xs text-gray-500">{{ trans('products.seo_og_description_help') }}
                                        </p>
                                        @error('og_description_ar')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="og_image" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_og_image') }}
                                    </label>
                                    <input type="file" name="og_image" id="og_image" accept="image/*"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_og_image_help') }}</p>
                                    @error('og_image')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional SEO Settings -->
                            <div class="border-t border-green-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    {{ trans('products.additional_seo_settings') }}</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="canonical_url" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('products.seo_canonical_url') }}
                                        </label>
                                        <input type="url" name="canonical_url" id="canonical_url"
                                            value="{{ old('canonical_url') }}"
                                            placeholder="{{ trans('products.seo_canonical_url_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                        <p class="text-xs text-gray-500">{{ trans('products.seo_canonical_url_help') }}
                                        </p>
                                        @error('canonical_url')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label for="robots" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('products.seo_robots') }}
                                        </label>
                                        <select name="robots" id="robots"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <option value="index,follow"
                                                {{ old('robots') == 'index,follow' ? 'selected' : '' }}>Index, Follow
                                            </option>
                                            <option value="index,nofollow"
                                                {{ old('robots') == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow
                                            </option>
                                            <option value="noindex,follow"
                                                {{ old('robots') == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow
                                            </option>
                                            <option value="noindex,nofollow"
                                                {{ old('robots') == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No
                                                Follow</option>
                                        </select>
                                        <p class="text-xs text-gray-500">{{ trans('products.seo_robots_help') }}</p>
                                        @error('robots')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="structured_data" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('products.seo_structured_data') }}
                                    </label>
                                    <textarea name="structured_data" id="structured_data" rows="6"
                                        placeholder="{{ trans('products.seo_structured_data_placeholder') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm font-mono text-sm text-gray-900">{{ old('structured_data') }}</textarea>
                                    <p class="text-xs text-gray-500">{{ trans('products.seo_structured_data_help') }}</p>
                                    @error('structured_data')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Options Section -->
                    <div class="bg-white rounded-xl p-6 border border-indigo-100">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">{{ trans('products.product_options') }}</h2>
                        </div>

                        <div class="bg-white rounded-lg p-6 border border-indigo-200 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ trans('products.add_product_options_title') }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ trans('products.add_product_options_description') }}</p>
                                </div>
                                <button type="button" id="add-option-btn"
                                    class="gap-3 inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg font-medium text-sm text-white hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-plus"></i>
                                    {{ trans('products.add_option') }}
                                </button>
                            </div>

                            <div id="product-options-container" class="space-y-4">
                                <!-- Options will be added here dynamically -->
                            </div>

                            <div class="mt-6 p-4 bg-white border border-indigo-200 rounded-lg">
                                <div>
                                    <p class="text-sm text-indigo-800 font-medium">
                                        {{ trans('products.option_management') }}</p>
                                    <p class="text-sm text-indigo-600">
                                        {{ trans('products.option_management_description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 gap-3">
                        <a href="{{ route('admin.products.index') }}"
                            class="inline-flex items-center px-6 py-3 gap-3 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                            <i class="fas fa-times"></i>
                            {{ trans('products.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 gap-3 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg font-medium text-white hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 shadow-lg">
                            <i class="fas fa-save"></i>
                            {{ trans('products.create_product_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Pass translations to JavaScript
        window.translations = {
            product_option: '{{ trans('products.product_option') }}',
            option_name_required: '{{ trans('products.option_name_required') }}',
            option_name_arabic: '{{ trans('products.option_name_arabic') }}',
            option_type: '{{ trans('products.option_type') }}',
            option_values: '{{ trans('products.option_values') }}',
            add_value: '{{ trans('products.add_value') }}',
            value_placeholder: '{{ trans('products.value_placeholder') }}',
            value_arabic_placeholder: '{{ trans('products.value_arabic_placeholder') }}',
            price_adjustment_placeholder: '{{ trans('products.price_adjustment_placeholder') }}',
            required_label: '{{ trans('products.required_label') }}'
        };

        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('image-preview');
            const primaryImageInput = document.getElementById('primary_image');

            // Main image upload preview
            const mainImageInput = document.getElementById('main_image');
            const mainImagePreview = document.getElementById('main-image-preview');
            const mainImagePreviewImg = document.getElementById('main-image-preview-img');

            if (mainImageInput && mainImagePreview && mainImagePreviewImg) {
                mainImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            mainImagePreviewImg.src = e.target.result;
                            mainImagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            const addOptionBtn = document.getElementById('add-option-btn');
            const optionsContainer = document.getElementById('product-options-container');
            let optionCounter = 0;

            // Image upload functionality
            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                imagePreview.innerHTML = '';
                imagePreview.style.display = 'grid';

                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imageContainer = document.createElement('div');
                            imageContainer.className = 'relative group';
                            imageContainer.innerHTML = `
                                <img src="${e.target.result}" alt="Preview ${index + 1}"
                                     class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-purple-400 transition-all duration-200">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <button type="button" class="set-primary-btn opacity-0 group-hover:opacity-100 bg-purple-600 text-white px-3 py-1 rounded text-sm transition-all duration-200" data-index="${index}">
                                        Set as Primary
                                    </button>
                                </div>
                                <div class="absolute top-2 right-2">
                                    <span class="bg-white text-gray-800 text-xs px-2 py-1 rounded-full shadow-sm">${index + 1}</span>
                                </div>
                            `;
                            imagePreview.appendChild(imageContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

            // Handle primary image selection
            imagePreview.addEventListener('click', function(e) {
                if (e.target.classList.contains('set-primary-btn')) {
                    const index = parseInt(e.target.dataset.index);
                    primaryImageInput.value = index;

                    // Update visual indicators
                    document.querySelectorAll('.set-primary-btn').forEach(btn => {
                        btn.textContent = 'Set as Primary';
                        btn.classList.remove('bg-purple-800');
                        btn.classList.add('bg-purple-600');
                    });

                    e.target.textContent = 'Primary';
                    e.target.classList.remove('bg-purple-600');
                    e.target.classList.add('bg-purple-800');
                }
            });

            // Set first image as primary by default
            imageInput.addEventListener('change', function(e) {
                setTimeout(() => {
                    if (imagePreview.children.length > 0 && primaryImageInput.value === '0') {
                        primaryImageInput.value = '0';
                        const firstBtn = imagePreview.querySelector('.set-primary-btn');
                        if (firstBtn) {
                            firstBtn.click();
                        }
                    }
                }, 100);
            });

            // Product Options functionality
            addOptionBtn.addEventListener('click', function() {
                const optionId = `option_${optionCounter++}`;
                const optionHtml = `
                    <div class="bg-white border border-indigo-200 rounded-lg p-6 shadow-sm" data-option-id="${optionId}">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">${window.translations.product_option}</h4>
                            <button type="button" class="remove-option-btn text-red-600 hover:text-red-800 transition duration-200" data-option-id="${optionId}">
                                ×
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">${window.translations.option_name_required}</label>
                                <input type="text" name="options[${optionId}][name]" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                                       placeholder="e.g., Size, Color, Material">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">${window.translations.option_name_arabic}</label>
                                <input type="text" name="options[${optionId}][name_ar]"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                                       placeholder="اسم الخيار">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">${window.translations.option_type}</label>
                                <select name="options[${optionId}][type]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                                    <option value="select">Select</option>
                                    <option value="radio">Radio</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                </select>
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox" name="options[${optionId}][is_required]" class="mr-2" style="accent-color: #6366f1;">
                                    <span class="text-sm text-gray-700">${window.translations.required_label}</span>
                                </label>
                            </div>
                        </div>

                        <div class="option-values-container" data-option-id="${optionId}">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-semibold text-gray-700">${window.translations.option_values}</label>
                                <button type="button" class="add-value-btn text-sm text-green-600 hover:text-green-800 transition duration-200" data-option-id="${optionId}">
                                    <i class="fas fa-plus mr-1"></i>${window.translations.add_value}
                                </button>
                            </div>
                            <div class="values-list space-y-2">
                                <!-- Values will be added here -->
                            </div>
                        </div>
                    </div>
                `;

                optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
            });

            // Remove option functionality
            optionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-option-btn')) {
                    const optionId = e.target.closest('.remove-option-btn').dataset.optionId;
                    const optionElement = document.querySelector(`[data-option-id="${optionId}"]`);
                    if (optionElement) {
                        optionElement.remove();
                    }
                }
            });

            // Add value functionality
            optionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.add-value-btn')) {
                    const optionId = e.target.closest('.add-value-btn').dataset.optionId;
                    const valuesList = document.querySelector(
                        `[data-option-id="${optionId}"] .values-list`);
                    const valueIndex = valuesList.children.length;

                    const valueHtml = `
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 p-3 bg-gray-50 rounded border">
                            <input type="text" name="options[${optionId}][values][${valueIndex}][value]"
                                   placeholder="${window.translations.value_placeholder}"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <input type="text" name="options[${optionId}][values][${valueIndex}][value_ar]"
                                   placeholder="${window.translations.value_arabic_placeholder}"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <input type="number" name="options[${optionId}][values][${valueIndex}][price_adjustment]"
                                   placeholder="${window.translations.price_adjustment_placeholder}" step="0.01"
                                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200">
                            <div class="flex items-center justify-end">
                                <button type="button" class="remove-value-btn text-red-600 hover:text-red-800 transition duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;

                    valuesList.insertAdjacentHTML('beforeend', valueHtml);
                }
            });

            // Remove value functionality
            optionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-value-btn')) {
                    e.target.closest('.grid').remove();
                }
            });
        });
    </script>
@endsection
