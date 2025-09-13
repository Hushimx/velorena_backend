@extends('admin.layouts.app')

@section('pageTitle', __('admin.add_new_banner'))
@section('title', __('admin.add_new_banner'))

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-6 lg:mb-0">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="fas fa-plus text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                                    {{ __('admin.add_new_banner') }}
                                </h1>
                                <p class="text-lg text-gray-600 mt-2">{{ __('admin.create_new_banner_description') }}</p>
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

            <!-- Create Form -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-magic mr-3"></i>
                        {{ __('admin.banner_creation_wizard') }}
                    </h2>
                    <p class="text-blue-100 mt-2">{{ __('admin.fill_form_to_create_banner') }}</p>
                </div>
                
                <form action="{{ route('admin.home-banners.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- English Title -->
                        <div class="group">
                            <label for="title" class="block text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors duration-200">
                                    <i class="fas fa-heading text-blue-600 text-sm"></i>
                                </div>
                                {{ __('admin.title') }} <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}"
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 bg-gray-50 hover:bg-white @error('title') border-red-400 focus:ring-red-100 focus:border-red-500 @enderror"
                                       placeholder="{{ __('admin.enter_banner_title') }}"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="title-check"></i>
                                </div>
                            </div>
                            @error('title')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-sm text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Arabic Title -->
                        <div class="group">
                            <label for="title_ar" class="block text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors duration-200">
                                    <i class="fas fa-language text-purple-600 text-sm"></i>
                                </div>
                                {{ __('admin.title_ar') }} <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="title_ar" 
                                       name="title_ar" 
                                       value="{{ old('title_ar') }}"
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all duration-300 bg-gray-50 hover:bg-white @error('title_ar') border-red-400 focus:ring-red-100 focus:border-red-500 @enderror"
                                       placeholder="{{ __('admin.enter_banner_title_ar') }}"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-green-500 opacity-0 transition-opacity duration-200" id="title-ar-check"></i>
                                </div>
                            </div>
                            @error('title_ar')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-sm text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>



                        <!-- English Description -->
                        <div class="lg:col-span-2 group">
                            <label for="description" class="block text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors duration-200">
                                    <i class="fas fa-align-left text-green-600 text-sm"></i>
                                </div>
                                {{ __('admin.description') }}
                            </label>
                            <div class="relative">
                                <textarea id="description" 
                                          name="description" 
                                          rows="4"
                                          class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300 bg-gray-50 hover:bg-white resize-none @error('description') border-red-400 focus:ring-red-100 focus:border-red-500 @enderror"
                                          placeholder="{{ __('admin.enter_banner_description') }}">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-sm text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Arabic Description -->
                        <div class="lg:col-span-2 group">
                            <label for="description_ar" class="block text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 transition-colors duration-200">
                                    <i class="fas fa-align-right text-orange-600 text-sm"></i>
                                </div>
                                {{ __('admin.description_ar') }}
                            </label>
                            <div class="relative">
                                <textarea id="description_ar" 
                                          name="description_ar" 
                                          rows="4"
                                          class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all duration-300 bg-gray-50 hover:bg-white resize-none @error('description_ar') border-red-400 focus:ring-red-100 focus:border-red-500 @enderror"
                                          placeholder="{{ __('admin.enter_banner_description_ar') }}">{{ old('description_ar') }}</textarea>
                            </div>
                            @error('description_ar')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-sm text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="lg:col-span-2 group">
                            <label for="image" class="block text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-pink-200 transition-colors duration-200">
                                    <i class="fas fa-image text-pink-600 text-sm"></i>
                                </div>
                                {{ __('admin.banner_image') }} <span class="text-red-500 ml-1">*</span>
                            </label>
                            
                            <!-- Stunning file upload area -->
                            <div class="relative">
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*" 
                                       class="hidden"
                                       required>
                                <label for="image" class="cursor-pointer block">
                                    <div class="border-3 border-dashed border-gray-300 rounded-2xl p-12 text-center hover:border-pink-400 hover:bg-pink-50 transition-all duration-300 group-hover:shadow-lg">
                                        <div class="space-y-4">
                                            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-pink-100 to-purple-100 rounded-full flex items-center justify-center group-hover:from-pink-200 group-hover:to-purple-200 transition-all duration-300">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-pink-500 group-hover:text-pink-600 transition-colors duration-300"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-semibold text-gray-700 mb-2">{{ __('admin.click_to_upload') }}</p>
                                                <p class="text-sm text-gray-500 mb-3">{{ __('admin.supported_formats') }}: PNG, JPG, GIF</p>
                                                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg text-sm font-medium hover:from-pink-600 hover:to-purple-700 transition-all duration-300">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    {{ __('admin.choose_file') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                <!-- File preview area (hidden initially) -->
                                <div id="image-preview" class="hidden mt-4">
                                    <div class="relative inline-block">
                                        <img id="preview-img" class="w-32 h-20 object-cover rounded-lg shadow-md" alt="Preview">
                                        <button type="button" id="remove-image" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors duration-200">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            @error('image')
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-sm text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="group">
                            <label for="sort_order" class="block text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-200 transition-colors duration-200">
                                    <i class="fas fa-sort-numeric-up text-indigo-600 text-sm"></i>
                                </div>
                                {{ __('admin.sort_order') }} <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <select id="sort_order" 
                                        name="sort_order" 
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 bg-gray-50 hover:bg-white appearance-none cursor-pointer @error('sort_order') border-red-400 focus:ring-red-100 focus:border-red-500 @enderror"
                                        required>
                                    <option value="1" {{ old('sort_order', 1) == 1 ? 'selected' : '' }}>1 - {{ __('admin.first_position') }}</option>
                                    <option value="2" {{ old('sort_order') == 2 ? 'selected' : '' }}>2 - {{ __('admin.second_position') }}</option>
                                    <option value="3" {{ old('sort_order') == 3 ? 'selected' : '' }}>3 - {{ __('admin.third_position') }}</option>
                                    <option value="4" {{ old('sort_order') == 4 ? 'selected' : '' }}>4 - {{ __('admin.fourth_position') }}</option>
                                    <option value="5" {{ old('sort_order') == 5 ? 'selected' : '' }}>5 - {{ __('admin.fifth_position') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('sort_order')
                                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="text-sm text-red-700">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-emerald-200 transition-colors duration-200">
                                    <i class="fas fa-toggle-on text-emerald-600 text-sm"></i>
                                </div>
                                {{ __('admin.status') }}
                            </label>
                            <div class="flex items-center p-6 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border-2 border-emerald-200 hover:border-emerald-300 transition-all duration-300">
                                <input type="hidden" name="is_active" value="0">
                                <div class="relative">
                                    <input type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="sr-only">
                                    <label for="is_active" class="flex items-center cursor-pointer">
                                        <div class="relative">
                                            <div class="w-14 h-7 bg-gray-300 rounded-full shadow-inner transition-colors duration-300" id="toggle-bg"></div>
                                            <div class="absolute top-0.5 left-0.5 w-6 h-6 bg-white rounded-full shadow transition-transform duration-300" id="toggle-dot"></div>
                                        </div>
                                        <span class="ml-4 text-sm font-semibold text-gray-700">
                                            {{ __('admin.is_active') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-6">
                            <a href="{{ route('admin.home-banners.index') }}" 
                               class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 shadow-sm hover:shadow-md font-semibold">
                                <i class="fas fa-times mr-3"></i>
                                {{ __('admin.cancel') }}
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold transform hover:scale-105">
                                <i class="fas fa-magic mr-3"></i>
                                {{ __('admin.create_banner') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for enhanced interactions -->
    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image functionality
        document.getElementById('remove-image').addEventListener('click', function() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
        });

        // Toggle switch functionality
        document.getElementById('is_active').addEventListener('change', function() {
            const bg = document.getElementById('toggle-bg');
            const dot = document.getElementById('toggle-dot');
            if (this.checked) {
                bg.classList.remove('bg-gray-300');
                bg.classList.add('bg-emerald-500');
                dot.classList.add('translate-x-7');
            } else {
                bg.classList.remove('bg-emerald-500');
                bg.classList.add('bg-gray-300');
                dot.classList.remove('translate-x-7');
            }
        });

        // Initialize toggle state
        if (document.getElementById('is_active').checked) {
            document.getElementById('toggle-bg').classList.add('bg-emerald-500');
            document.getElementById('toggle-dot').classList.add('translate-x-7');
        }
    </script>

@endsection
