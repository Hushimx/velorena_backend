@extends('admin.layouts.app')

@section('pageTitle', __('admin.create_page'))
@section('title', __('admin.create_page'))

@push('styles')
<style>
    .tox-tinymce {
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .tox .tox-toolbar {
        background-color: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    
    .tox .tox-edit-area__iframe {
        border-radius: 0 0 8px 8px !important;
    }
    
    /* RTL Support for Arabic editor */
    #content_ar {
        direction: rtl;
    }
    
    .tox .tox-edit-area__iframe[data-direction="rtl"] {
        direction: rtl;
    }
</style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.create_page') }}</h1>
                <p class="text-gray-600">{{ __('admin.create_new_page_description') }}</p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>{{ __('admin.back_to_pages') }}</span>
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white shadow sm:rounded-lg">
            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
                @csrf

                <!-- Basic Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Title (English) -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">{{ __('admin.page_title') }} *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            placeholder="{{ __('admin.enter_page_title') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('title') border-red-300 @enderror"
                            required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title (Arabic) -->
                    <div>
                        <label for="title_ar" class="block text-sm font-medium text-gray-700">{{ __('admin.page_title_ar') }}</label>
                        <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar') }}"
                            placeholder="{{ __('admin.enter_page_title_ar') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('title_ar') border-red-300 @enderror">
                        @error('title_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Type and Access Level -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">{{ __('admin.page_type') }} *</label>
                        <select name="type" id="type"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('type') border-red-300 @enderror"
                            required>
                            <option value="page" {{ old('type') === 'page' ? 'selected' : '' }}>{{ __('admin.page_type_page') }}</option>
                            <option value="section" {{ old('type') === 'section' ? 'selected' : '' }}>{{ __('admin.page_type_section') }}</option>
                            <option value="modal" {{ old('type') === 'modal' ? 'selected' : '' }}>{{ __('admin.page_type_modal') }}</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="access_level" class="block text-sm font-medium text-gray-700">{{ __('admin.access_level') }} *</label>
                        <select name="access_level" id="access_level"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('access_level') border-red-300 @enderror"
                            required>
                            <option value="public" {{ old('access_level') === 'public' ? 'selected' : '' }}>{{ __('admin.access_level_public') }}</option>
                            <option value="authenticated" {{ old('access_level') === 'authenticated' ? 'selected' : '' }}>{{ __('admin.access_level_authenticated') }}</option>
                            <option value="admin" {{ old('access_level') === 'admin' ? 'selected' : '' }}>{{ __('admin.access_level_admin') }}</option>
                        </select>
                        @error('access_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-4">
                    <div>
                        <label for="content" class="form-label">{{ __('admin.page_content') }}</label>
                        <textarea id="content" name="content" class="form-control" rows="15" required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content_ar" class="form-label">{{ __('admin.page_content_ar') }}</label>
                        <textarea id="content_ar" name="content_ar" class="form-control" rows="15">{{ old('content_ar') }}</textarea>
                        @error('content_ar')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.seo_settings') }}</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Meta Title (English) -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">{{ __('admin.meta_title') }}</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                placeholder="{{ __('admin.enter_meta_title') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('meta_title') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">{{ __('admin.meta_title_help') }}</p>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Title (Arabic) -->
                        <div>
                            <label for="meta_title_ar" class="block text-sm font-medium text-gray-700">{{ __('admin.meta_title_ar') }}</label>
                            <input type="text" name="meta_title_ar" id="meta_title_ar" value="{{ old('meta_title_ar') }}"
                                placeholder="{{ __('admin.enter_meta_title_ar') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('meta_title_ar') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">{{ __('admin.meta_title_help') }}</p>
                            @error('meta_title_ar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
                        <!-- Meta Description (English) -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">{{ __('admin.meta_description') }}</label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('meta_description') border-red-300 @enderror"
                                placeholder="{{ __('admin.enter_meta_description') }}">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">{{ __('admin.meta_description_help') }}</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description (Arabic) -->
                        <div>
                            <label for="meta_description_ar" class="block text-sm font-medium text-gray-700">{{ __('admin.meta_description_ar') }}</label>
                            <textarea name="meta_description_ar" id="meta_description_ar" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('meta_description_ar') border-red-300 @enderror"
                                placeholder="{{ __('admin.enter_meta_description_ar') }}">{{ old('meta_description_ar') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">{{ __('admin.meta_description_help') }}</p>
                            @error('meta_description_ar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
                        <!-- Meta Keywords (English) -->
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords (English)</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('meta_keywords') border-red-300 @enderror"
                                placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Keywords (Arabic) -->
                        <div>
                            <label for="meta_keywords_ar" class="block text-sm font-medium text-gray-700">Meta Keywords (Arabic)</label>
                            <input type="text" name="meta_keywords_ar" id="meta_keywords_ar" value="{{ old('meta_keywords_ar') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('meta_keywords_ar') border-red-300 @enderror"
                                placeholder="كلمة مفتاحية 1، كلمة مفتاحية 2، كلمة مفتاحية 3">
                            @error('meta_keywords_ar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- OG Image -->
                    <div class="mt-4">
                        <label for="og_image" class="block text-sm font-medium text-gray-700">Open Graph Image</label>
                        <input type="file" name="og_image" id="og_image" accept="image/*"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('og_image') border-red-300 @enderror">
                        @error('og_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin.additional_settings') }}</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700">{{ __('admin.sort_order') }}</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                                placeholder="{{ __('admin.enter_sort_order') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('sort_order') border-red-300 @enderror"
                                min="0">
                            <p class="mt-1 text-xs text-gray-500">{{ __('admin.enter_sort_order') }}</p>
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                {{ __('admin.is_active') }}
                            </label>
                        </div>
                    </div>

                    <!-- Page Images -->
                    <div class="mt-4">
                        <label for="images" class="block text-sm font-medium text-gray-700">{{ __('admin.page_images') }}</label>
                        <input type="file" name="images[]" id="images" accept="image/*" multiple
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('images') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.supported_formats') }} - {{ __('admin.max_file_size') }}</p>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        <span>{{ __('admin.cancel') }}</span>
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <span>{{ __('admin.create_page') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/lus14gjppahyq76u1oy228l79g3p3v669i5z1kgo5gdqxz8i/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE for English content
    tinymce.init({
        selector: '#content',
        height: 400,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        branding: false,
        promotion: false,
        placeholder: 'Enter page content in English...',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // Initialize TinyMCE for Arabic content with RTL support
    tinymce.init({
        selector: '#content_ar',
        height: 400,
        menubar: false,
        directionality: 'rtl',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; direction: rtl; text-align: right; }',
        branding: false,
        promotion: false,
        placeholder: 'أدخل محتوى الصفحة باللغة العربية...',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
});
</script>
@endpush
@endsection
