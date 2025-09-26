@extends('admin.layouts.app')

@section('pageTitle', __('admin.edit_page'))
@section('title', __('admin.edit_page'))

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
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.edit_page') }}</h1>
                <p class="text-gray-600">{{ __('admin.edit_page_description') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-secondary">
                    <i class="fas fa-external-link-alt"></i>
                    <span>{{ __('admin.view_live_page') }}</span>
                </a>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    <span>{{ __('admin.back_to_pages') }}</span>
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title (English) -->
                        <div>
                            <label for="title" class="form-label">{{ __('admin.page_title') }}</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}"
                                class="form-control @error('title') border-red-500 @enderror"
                                required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title (Arabic) -->
                        <div>
                            <label for="title_ar" class="form-label">{{ __('admin.page_title_ar') }}</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $page->title_ar) }}"
                                class="form-control @error('title_ar') border-red-500 @enderror">
                            @error('title_ar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Page Type -->
                        <div>
                            <label for="type" class="form-label">{{ __('admin.page_type') }}</label>
                            <select id="type" name="type"
                                class="form-control @error('type') border-red-500 @enderror"
                                required>
                                <option value="page" {{ old('type', $page->type) === 'page' ? 'selected' : '' }}>
                                    {{ __('admin.page_type_page') }}
                                </option>
                                <option value="section" {{ old('type', $page->type) === 'section' ? 'selected' : '' }}>
                                    {{ __('admin.page_type_section') }}
                                </option>
                                <option value="modal" {{ old('type', $page->type) === 'modal' ? 'selected' : '' }}>
                                    {{ __('admin.page_type_modal') }}
                                </option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Access Level -->
                        <div>
                            <label for="access_level" class="form-label">{{ __('admin.access_level') }}</label>
                            <select id="access_level" name="access_level"
                                class="form-control @error('access_level') border-red-500 @enderror"
                                required>
                                <option value="public" {{ old('access_level', $page->access_level) === 'public' ? 'selected' : '' }}>
                                    {{ __('admin.access_level_public') }}
                                </option>
                                <option value="authenticated" {{ old('access_level', $page->access_level) === 'authenticated' ? 'selected' : '' }}>
                                    {{ __('admin.access_level_authenticated') }}
                                </option>
                                <option value="admin" {{ old('access_level', $page->access_level) === 'admin' ? 'selected' : '' }}>
                                    {{ __('admin.access_level_admin') }}
                                </option>
                            </select>
                            @error('access_level')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="form-label">{{ __('admin.sort_order') }}</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}"
                                min="0"
                                class="form-control @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300" style="color: var(--brand-brown);">
                                <span class="mr-2 text-sm text-gray-700">{{ __('admin.is_active') }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mt-6">
                        <label for="content" class="form-label">{{ __('admin.page_content') }}</label>
                        <textarea id="content" name="content" class="form-control" rows="15">{{ old('content', $page->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label for="content_ar" class="form-label">{{ __('admin.page_content_ar') }}</label>
                        <textarea id="content_ar" name="content_ar" class="form-control" rows="15">{{ old('content_ar', $page->content_ar) }}</textarea>
                        @error('content_ar')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SEO Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="form-label">{{ __('admin.meta_title') }}</label>
                            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                                class="form-control @error('meta_title') border-red-500 @enderror">
                            @error('meta_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Title (Arabic) -->
                        <div>
                            <label for="meta_title_ar" class="form-label">{{ __('admin.meta_title_ar') }}</label>
                            <input type="text" id="meta_title_ar" name="meta_title_ar" value="{{ old('meta_title_ar', $page->meta_title_ar) }}"
                                class="form-control @error('meta_title_ar') border-red-500 @enderror">
                            @error('meta_title_ar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="form-label">{{ __('admin.meta_description') }}</label>
                            <textarea id="meta_description" name="meta_description" rows="3"
                                class="form-control @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $page->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description (Arabic) -->
                        <div>
                            <label for="meta_description_ar" class="form-label">{{ __('admin.meta_description_ar') }}</label>
                            <textarea id="meta_description_ar" name="meta_description_ar" rows="3"
                                class="form-control @error('meta_description_ar') border-red-500 @enderror">{{ old('meta_description_ar', $page->meta_description_ar) }}</textarea>
                            @error('meta_description_ar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="meta_keywords" class="form-label">{{ __('admin.meta_keywords') }}</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}"
                                class="form-control @error('meta_keywords') border-red-500 @enderror">
                            @error('meta_keywords')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Keywords (Arabic) -->
                        <div>
                            <label for="meta_keywords_ar" class="form-label">{{ __('admin.meta_keywords_ar') }}</label>
                            <input type="text" id="meta_keywords_ar" name="meta_keywords_ar" value="{{ old('meta_keywords_ar', $page->meta_keywords_ar) }}"
                                class="form-control @error('meta_keywords_ar') border-red-500 @enderror">
                            @error('meta_keywords_ar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- File Uploads -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- OG Image -->
                        <div>
                            <label for="og_image" class="form-label">{{ __('admin.og_image') }}</label>
                            <input type="file" id="og_image" name="og_image" accept="image/*"
                                class="form-control @error('og_image') border-red-500 @enderror">
                            @if($page->og_image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($page->og_image) }}" alt="Current OG Image" class="h-16 w-16 object-cover rounded">
                                    <p class="text-xs text-gray-500 mt-1">{{ __('admin.current_image') }}</p>
                                </div>
                            @endif
                            @error('og_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Page Images -->
                        <div>
                            <label for="images" class="form-label">{{ __('admin.page_images') }}</label>
                            <input type="file" id="images" name="images[]" accept="image/*" multiple
                                class="form-control @error('images') border-red-500 @enderror">
                            @if($page->images && count($page->images) > 0)
                                <div class="mt-2">
                                    <div class="flex space-x-2">
                                        @foreach($page->images as $image)
                                            <img src="{{ Storage::url($image) }}" alt="Page Image" class="h-12 w-12 object-cover rounded">
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ __('admin.current_images') }}</p>
                                </div>
                            @endif
                            @error('images')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                            {{ __('admin.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>{{ __('admin.update') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

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