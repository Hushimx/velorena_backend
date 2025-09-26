@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Edit Product')
@section('title', trans('products.edit_product'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ trans('products.back_to_products') }}
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">{{ trans('products.edit_product') }}</h1>
                        <p class="text-gray-600 mt-1">{{ trans('products.update_product_information') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                        <div class="flex items-center mb-6">
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
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $product->name) }}" required
                                        placeholder="{{ trans('products.enter_product_name') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm">
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
                                    <input type="text" name="name_ar" id="name_ar"
                                        value="{{ old('name_ar', $product->name_ar) }}"
                                        placeholder="{{ trans('products.enter_product_name_ar') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm">
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
                        </div>

                        <!-- Product Description -->
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700">
                                {{ trans('products.description') }}
                            </label>
                            <div class="relative">
                                <textarea name="description" id="description" rows="4"
                                    placeholder="{{ trans('products.enter_product_description') }}"
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm">{{ old('description', $product->description) }}</textarea>
                                <div class="absolute top-3 left-0 pl-4 flex items-start pointer-events-none">
                                    <i class="fas fa-align-left text-gray-400"></i>
                                </div>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <label for="description_ar"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.description_ar') }}</label>
                            <textarea name="description_ar" id="description_ar" rows="4"
                                placeholder="{{ trans('products.enter_product_description_ar') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none text-gray-900">{{ old('description_ar', $product->description_ar) }}</textarea>
                            @error('description_ar')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <label for="category_id"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.category') }}
                                <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white text-gray-900">
                                <option value="">{{ trans('products.select_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            =======
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
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                >>>>>>> cd613328a098bc6978e37fae62c144a0f03d661f
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
                                        value="{{ old('base_price', $product->base_price) }}" step="0.01"
                                        min="0" required placeholder="0.00"
                                        class="w-full px-4 py-3 pl-12 pr-16 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm">
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

                        <<<<<<< HEAD <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <label for="images"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.images') }}</label>

                            <!-- Existing Images -->
                            @if ($product->images && $product->images->count() > 0)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-3">{{ trans('products.current_images') }}:</p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="existing-images">
                                        @foreach ($product->images as $image)
                                            <div class="relative group existing-image"
                                                data-image-id="{{ $image->id }}">
                                                <img src="{{ asset($image->image_path) }}" alt="{{ $image->alt_text }}"
                                                    class="w-full h-32 object-cover rounded-lg border-2 {{ $image->is_primary ? 'border-green-500' : 'border-gray-200' }}">
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                    <button type="button"
                                                        class="remove-image-btn opacity-0 group-hover:opacity-100 bg-red-600 text-white px-3 py-1 rounded text-sm transition-all duration-200">
                                                        Remove
                                                    </button>
                                                </div>
                                                @if ($image->is_primary)
                                                    <div class="absolute top-2 left-2">
                                                        <span
                                                            class="bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow-sm">Primary</span>
                                                    </div>
                                                @endif
                                                <div class="absolute top-2 right-2">
                                                    <button type="button"
                                                        class="set-primary-existing-btn bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow-sm {{ $image->is_primary ? 'hidden' : '' }}"
                                                        data-image-id="{{ $image->id }}">
                                                        Set Primary
                                                    </button>
                                                </div>
                                                <input type="hidden" name="existing_images[]"
                                                    value="{{ $image->id }}" class="existing-image-input">
                                            </div>
                                        @endforeach
                                    </div>
                                    =======
                                    <!-- Descriptions -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                        <div class="space-y-2">
                                            <label for="description" class="block text-sm font-semibold text-gray-700">
                                                {{ trans('products.description') }}
                                            </label>
                                            <div class="relative">
                                                <textarea name="description" id="description" rows="4"
                                                    placeholder="{{ trans('products.enter_product_description') }}"
                                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm">{{ old('description', $product->description) }}</textarea>
                                                <div class="absolute top-3 left-4 pointer-events-none">
                                                    <i class="fas fa-align-left text-gray-400"></i>
                                                    >>>>>>> cd613328a098bc6978e37fae62c144a0f03d661f
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
                                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm">{{ old('description_ar', $product->description_ar) }}</textarea>
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

                                <!-- Images Section -->
                                <div
                                    class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                                    <div class="flex items-center mb-6">
                                        <div
                                            class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-images text-white text-sm"></i>
                                        </div>
                                        <h2 class="text-xl font-semibold text-gray-900">{{ trans('products.images') }}
                                        </h2>
                                    </div>

                                    <div class="space-y-6">
                                        <!-- Existing Images -->
                                        @if ($product->images && $product->images->count() > 0)
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                                    {{ trans('products.current_images') }}</h3>
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                                                    id="existing-images">
                                                    @foreach ($product->images as $image)
                                                        <div class="relative group existing-image"
                                                            data-image-id="{{ $image->id }}">
                                                            <img src="{{ asset($image->image_path) }}"
                                                                alt="{{ $image->alt_text }}"
                                                                class="w-full h-32 object-cover rounded-lg border-2 {{ $image->is_primary ? 'border-purple-500' : 'border-gray-200' }} hover:border-purple-400 transition-all duration-200">
                                                            <div
                                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                                <button type="button"
                                                                    class="remove-image-btn opacity-0 group-hover:opacity-100 bg-red-600 text-white px-3 py-1 rounded text-sm transition-all duration-200">
                                                                    {{ trans('products.remove') }}
                                                                </button>
                                                            </div>
                                                            @if ($image->is_primary)
                                                                <div class="absolute top-2 left-2">
                                                                    <span
                                                                        class="bg-purple-600 text-white text-xs px-2 py-1 rounded-full shadow-sm">{{ trans('products.primary') }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="absolute top-2 right-2">
                                                                <button type="button"
                                                                    class="set-primary-existing-btn bg-purple-600 text-white text-xs px-2 py-1 rounded-full shadow-sm {{ $image->is_primary ? 'hidden' : '' }}"
                                                                    data-image-id="{{ $image->id }}">
                                                                    {{ trans('products.set_primary') }}
                                                                </button>
                                                            </div>
                                                            <input type="hidden" name="existing_images[]"
                                                                value="{{ $image->id }}" class="existing-image-input">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- New Images Upload -->
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                                {{ trans('products.add_new_images') }}</h3>
                                            <div
                                                class="border-2 border-dashed border-purple-300 rounded-xl p-8 text-center hover:border-purple-400 transition-all duration-200 bg-white">
                                                <input type="file" name="images[]" id="images" accept="image/*"
                                                    multiple class="hidden">
                                                <label for="images" class="cursor-pointer">
                                                    <<<<<<< HEAD <i
                                                        class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                                        <p class="text-sm text-gray-600">
                                                            {{ trans('products.click_to_upload_images') }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ trans('products.images_requirements') }}
                                                        </p>
                                                        =======
                                                        <p class="text-lg font-medium text-gray-700 mb-2">
                                                            {{ trans('products.click_to_upload_images') }}</p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ trans('products.images_requirements') }}</p>
                                                        >>>>>>> cd613328a098bc6978e37fae62c144a0f03d661f
                                                </label>
                                            </div>
                                            <div id="image-preview"
                                                class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                                                style="display: none;">
                                                <!-- New image previews will be added here -->
                                            </div>
                                            <input type="hidden" name="primary_image" id="primary_image"
                                                value="">
                                            @error('images')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <<<<<<< HEAD <!-- SEO Information -->
                                    <div class="mt-8">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                                            {{ trans('products.seo_information') }}</h3>

                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                                <label for="slug"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.slug') }}</label>
                                                <input type="text" name="slug" id="slug"
                                                    value="{{ old('slug', $product->slug) }}"
                                                    placeholder="{{ trans('products.enter_product_slug') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white text-gray-900">
                                                <p class="mt-1 text-sm text-gray-500">{{ trans('products.slug_help') }}
                                                </p>
                                                @error('slug')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                                <label for="meta_title"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.meta_title') }}</label>
                                                <input type="text" name="meta_title" id="meta_title"
                                                    value="{{ old('meta_title', $product->meta_title) }}"
                                                    placeholder="{{ trans('products.enter_meta_title') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white text-gray-900">
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ trans('products.meta_title_help') }}</p>
                                                @error('meta_title')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                                <label for="meta_description"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.meta_description') }}</label>
                                                <textarea name="meta_description" id="meta_description" rows="3"
                                                    placeholder="{{ trans('products.enter_meta_description') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none text-gray-900">{{ old('meta_description', $product->meta_description) }}</textarea>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ trans('products.meta_description_help') }}</p>
                                                @error('meta_description')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                                <label for="meta_keywords"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.meta_keywords') }}</label>
                                                <input type="text" name="meta_keywords" id="meta_keywords"
                                                    value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                                    placeholder="{{ trans('products.enter_meta_keywords') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white text-gray-900">
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ trans('products.meta_keywords_help') }}</p>
                                                @error('meta_keywords')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                                <label for="og_title"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.og_title') }}</label>
                                                <input type="text" name="og_title" id="og_title"
                                                    value="{{ old('og_title', $product->og_title) }}"
                                                    placeholder="{{ trans('products.enter_og_title') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white text-gray-900">
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ trans('products.og_title_help') }}</p>
                                                @error('og_title')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                                <label for="og_description"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.og_description') }}</label>
                                                <textarea name="og_description" id="og_description" rows="3"
                                                    placeholder="{{ trans('products.enter_og_description') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none text-gray-900">{{ old('og_description', $product->og_description) }}</textarea>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ trans('products.og_description_help') }}</p>
                                                @error('og_description')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Options Management -->
                                    <div class="mt-8">
                                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Options</h3>
                                        <p class="text-sm text-gray-600 mb-6">Manage product options like size, color,
                                            material, etc.</p>
                                        @livewire('product-options-manager', ['product' => $product])
                                        =======
                                        <!-- Product Options Section -->
                                        <div class="bg-white rounded-xl p-6 border border-indigo-100">
                                            <div class="mb-6">
                                                <h2 class="text-xl font-semibold text-gray-900">
                                                    {{ trans('products.product_options') }}</h2>
                                            </div>

                                            <div class="bg-white rounded-lg p-6 border border-indigo-200 shadow-sm">
                                                @livewire('product-options-manager', ['product' => $product])
                                            </div>
                                            >>>>>>> cd613328a098bc6978e37fae62c144a0f03d661f
                                        </div>

                                        <!-- Form Actions -->
                                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                                class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                                                <i class="fas fa-times mr-2"></i>
                                                {{ trans('products.cancel') }}
                                            </a>
                                            <button type="submit"
                                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg">
                                                <i class="fas fa-save mr-2"></i>
                                                {{ trans('products.update_product_button') }}
                                            </button>
                                        </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('image-preview');
            const primaryImageInput = document.getElementById('primary_image');

            // Handle new image uploads
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
                                    <button type="button" class="set-primary-new-btn opacity-0 group-hover:opacity-100 bg-purple-600 text-white px-3 py-1 rounded text-sm transition-all duration-200" data-index="${index}">
                                        Set as Primary
                                    </button>
                                </div>
                                <div class="absolute top-2 right-2">
                                    <span class="bg-white text-gray-800 text-xs px-2 py-1 rounded-full shadow-sm">New ${index + 1}</span>
                                </div>
                            `;
                            imagePreview.appendChild(imageContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

            // Handle existing image removal
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-image-btn')) {
                    const imageContainer = e.target.closest('.existing-image');
                    const imageInput = imageContainer.querySelector('.existing-image-input');
                    imageInput.remove();
                    imageContainer.remove();
                }
            });

            // Handle primary image selection for existing images
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('set-primary-existing-btn')) {
                    const imageId = e.target.dataset.imageId;
                    primaryImageInput.value = imageId;

                    // Update visual indicators
                    document.querySelectorAll('.existing-image').forEach(container => {
                        const img = container.querySelector('img');
                        const btn = container.querySelector('.set-primary-existing-btn'); <<
                        <<
                        <<
                        < HEAD
                        const badge = container.querySelector('.bg-green-600');

                        img.classList.remove('border-green-500'); ===
                        ===
                        =
                        const badge = container.querySelector('.bg-purple-600');

                        img.classList.remove('border-purple-500'); >>>
                        >>>
                        >
                        cd613328a098bc6978e37fae62c144a0f03d661f
                        img.classList.add('border-gray-200');
                        btn.classList.remove('hidden');
                        if (badge) badge.remove();
                    });

                    const currentContainer = e.target.closest('.existing-image');
                    const currentImg = currentContainer.querySelector('img');
                    currentImg.classList.remove('border-gray-200');
                    currentImg.classList.add('border-purple-500');
                    e.target.classList.add('hidden');

                    // Add primary badge
                    const badge = document.createElement('div');
                    badge.className = 'absolute top-2 left-2'; <<
                    <<
                    <<
                    < HEAD
                    badge.innerHTML =
                        '<span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow-sm">Primary</span>'; ===
                    ===
                    =
                    badge.innerHTML =
                        '<span class="bg-purple-600 text-white text-xs px-2 py-1 rounded-full shadow-sm">Primary</span>'; >>>
                    >>>
                    >
                    cd613328a098bc6978e37fae62c144a0f03d661f
                    currentContainer.appendChild(badge);
                }
            });

            // Handle primary image selection for new images
            imagePreview.addEventListener('click', function(e) {
                if (e.target.classList.contains('set-primary-new-btn')) {
                    const index = parseInt(e.target.dataset.index);
                    primaryImageInput.value = 'new_' + index;

                    // Update visual indicators for new images
                    document.querySelectorAll('.set-primary-new-btn').forEach(btn => {
                        btn.textContent = 'Set as Primary';
                        btn.classList.remove('bg-purple-800');
                        btn.classList.add('bg-purple-600');
                    });

                    e.target.textContent = 'Primary';
                    e.target.classList.remove('bg-purple-600');
                    e.target.classList.add('bg-purple-800');
                }
            });

            // Initialize primary image selection on page load
            document.addEventListener('DOMContentLoaded', function() {
                const existingPrimary = document.querySelector('.existing-image .bg-purple-600');
                if (existingPrimary) {
                    const imageId = existingPrimary.closest('.existing-image').dataset.imageId;
                    primaryImageInput.value = imageId;
                }
            });
        });
    </script>
@endsection
