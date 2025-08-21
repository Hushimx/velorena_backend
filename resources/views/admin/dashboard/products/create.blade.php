@extends('admin.layouts.app')

@section('title', trans('products.create_product'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-right mx-2"></i>
                {{ trans('products.back_to_products') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('products.create_product') }}</h1>
                <p class="text-gray-600 mt-1">{{ trans('products.add_new_product_to_system') }}</p>
            </div>

            <!-- Form -->
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ trans('products.basic_information') }}</h3>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="name"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.name') }}
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    placeholder="{{ trans('products.enter_product_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="name_ar"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.name_ar') }}</label>
                                <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}"
                                    placeholder="{{ trans('products.enter_product_name_ar') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white">
                                @error('name_ar')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="description"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.description') }}</label>
                                <textarea name="description" id="description" rows="4"
                                    placeholder="{{ trans('products.enter_product_description') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none">{{ old('description') }}</textarea>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none">{{ old('description_ar') }}</textarea>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white">
                                    <option value="">{{ trans('products.select_category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ trans('products.additional_information') }}
                            </h3>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="base_price"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.base_price') }}
                                    <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="base_price" id="base_price"
                                        value="{{ old('base_price', 0) }}" step="0.01" min="0" required
                                        placeholder="0.00"
                                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 text-sm">{{ trans('products.currency') }}</span>
                                    </div>
                                </div>
                                @error('base_price')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="sort_order"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.sort_order') }}</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                                    min="0" placeholder="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white">
                                @error('sort_order')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="image"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.image') }}</label>
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition duration-200">
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="hidden">
                                    <label for="image" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-sm text-gray-600">{{ trans('products.click_to_upload_image') }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ trans('products.image_requirements') }}
                                        </p>
                                    </label>
                                </div>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.is_active') }}</label>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }} class="toggle-switch">
                                    <label for="is_active"
                                        class="mx-2 text-sm text-gray-700">{{ trans('products.is_active') }}</label>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="specifications"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.specifications') }}</label>
                                <textarea name="specifications" id="specifications" rows="6"
                                    placeholder="{{ trans('products.specifications_placeholder') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none font-mono text-sm">{{ old('specifications') }}</textarea>
                                <p class="mt-2 text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>{{ trans('products.specifications_help') }}
                                </p>
                                @error('specifications')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3 gap-3">
                        <a href="{{ route('admin.products.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ trans('products.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-save mx-2"></i>
                            {{ trans('products.create_product_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
