@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Edit Product')
@section('title', trans('products.edit_product'))

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
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('products.edit_product') }}</h1>
                <p class="text-gray-600 mt-1">{{ trans('products.update_product_information') }}</p>
            </div>

            <!-- Form -->
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ trans('products.basic_information') }}</h3>

                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="name"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.name') }}
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $product->name) }}" required
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
                                <input type="text" name="name_ar" id="name_ar"
                                    value="{{ old('name_ar', $product->name_ar) }}"
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none">{{ old('description', $product->description) }}</textarea>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none">{{ old('description_ar', $product->description_ar) }}</textarea>
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
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                        value="{{ old('base_price', $product->base_price) }}" step="0.01" min="0"
                                        required placeholder="0.00"
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
                                <label for="images"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.images') }}</label>
                                
                                <!-- Existing Images -->
                                @if($product->images && $product->images->count() > 0)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-3">{{ trans('products.current_images') }}:</p>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="existing-images">
                                            @foreach($product->images as $image)
                                                <div class="relative group existing-image" data-image-id="{{ $image->id }}">
                                                    <img src="{{ asset($image->image_path) }}" alt="{{ $image->alt_text }}"
                                                        class="w-full h-32 object-cover rounded-lg border-2 {{ $image->is_primary ? 'border-green-500' : 'border-gray-200' }}">
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                        <button type="button" class="remove-image-btn opacity-0 group-hover:opacity-100 bg-red-600 text-white px-3 py-1 rounded text-sm transition-all duration-200">
                                                            Remove
                                                        </button>
                                                    </div>
                                                    @if($image->is_primary)
                                                        <div class="absolute top-2 left-2">
                                                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow-sm">Primary</span>
                                                        </div>
                                                    @endif
                                                    <div class="absolute top-2 right-2">
                                                        <button type="button" class="set-primary-existing-btn bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow-sm {{ $image->is_primary ? 'hidden' : '' }}" data-image-id="{{ $image->id }}">
                                                            Set Primary
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="existing_images[]" value="{{ $image->id }}" class="existing-image-input">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- New Images Upload -->
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition duration-200">
                                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                                        class="hidden">
                                    <label for="images" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-sm text-gray-600">{{ trans('products.click_to_upload_images') }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ trans('products.images_requirements') }}</p>
                                    </label>
                                </div>
                                <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden">
                                    <!-- New image previews will be added here -->
                                </div>
                                <input type="hidden" name="primary_image" id="primary_image" value="">
                                @error('images')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>


                            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <label for="specifications"
                                    class="block text-sm font-semibold text-gray-700 mb-2">{{ trans('products.specifications') }}</label>
                                <textarea name="specifications" id="specifications" rows="4"
                                    placeholder="{{ trans('products.specifications_placeholder') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 bg-gray-50 hover:bg-white focus:bg-white resize-none font-mono text-sm">{{ old('specifications', $product->specifications ? json_encode($product->specifications, JSON_PRETTY_PRINT) : '') }}</textarea>
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

                    <!-- Product Options Management -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Options</h3>
                        <p class="text-sm text-gray-600 mb-6">Manage product options like size, color, material, etc.</p>
                        @livewire('product-options-manager', ['product' => $product])
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3 gap-3">
                        <a href="{{ route('admin.products.show', $product) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ trans('products.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150"
                            style="background: var(--brand-brown);"
                            onmouseover="this.style.background='var(--brand-brown-hover)'"
                            onmouseout="this.style.background='var(--brand-brown)'">
                            <i class="fas fa-save mx-2"></i>
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
                imagePreview.classList.remove('hidden');

                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imageContainer = document.createElement('div');
                            imageContainer.className = 'relative group';
                            imageContainer.innerHTML = `
                                <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                     class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <button type="button" class="set-primary-new-btn opacity-0 group-hover:opacity-100 bg-green-600 text-white px-3 py-1 rounded text-sm transition-all duration-200" data-index="${index}">
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
                        const btn = container.querySelector('.set-primary-existing-btn');
                        const badge = container.querySelector('.bg-green-600');
                        
                        img.classList.remove('border-green-500');
                        img.classList.add('border-gray-200');
                        btn.classList.remove('hidden');
                        if (badge) badge.remove();
                    });
                    
                    const currentContainer = e.target.closest('.existing-image');
                    const currentImg = currentContainer.querySelector('img');
                    currentImg.classList.remove('border-gray-200');
                    currentImg.classList.add('border-green-500');
                    e.target.classList.add('hidden');
                    
                    // Add primary badge
                    const badge = document.createElement('div');
                    badge.className = 'absolute top-2 left-2';
                    badge.innerHTML = '<span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow-sm">Primary</span>';
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
                        btn.classList.remove('bg-green-800');
                        btn.classList.add('bg-green-600');
                    });
                    
                    e.target.textContent = 'Primary';
                    e.target.classList.remove('bg-green-600');
                    e.target.classList.add('bg-green-800');
                }
            });

            // Initialize primary image selection on page load
            document.addEventListener('DOMContentLoaded', function() {
                const existingPrimary = document.querySelector('.existing-image .bg-green-600');
                if (existingPrimary) {
                    const imageId = existingPrimary.closest('.existing-image').dataset.imageId;
                    primaryImageInput.value = imageId;
                }
            });
        });
    </script>
@endsection
