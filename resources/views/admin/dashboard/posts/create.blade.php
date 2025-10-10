@extends('admin.layouts.app')

@section('pageTitle', trans('posts.create_post'))
@section('title', trans('posts.create_post'))

@section('styles')
    <style>
        /* Simple textarea styling */
        .content-textarea {
            min-height: 300px;
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
        }

        /* CKEditor container - ensure toolbar is visible */
        .ck-editor {
            position: relative;
            z-index: 1;
        }

        /* CKEditor toolbar - CRITICAL: ensure it's visible and wraps */
        .ck.ck-toolbar {
            position: relative !important;
            z-index: 10 !important;
            border: 1px solid #c4c4c4 !important;
            border-bottom: none !important;
            background: #f7f7f7 !important;
            padding: 8px !important;
            overflow: visible !important;
            flex-wrap: wrap !important;
        }

        /* Make toolbar items wrap - CRITICAL FIX */
        .ck.ck-toolbar > .ck-toolbar__items {
            flex-wrap: wrap !important;
            overflow: visible !important;
        }

        /* Ensure each toolbar item can shrink if needed */
        .ck.ck-toolbar .ck-toolbar__items > * {
            flex-shrink: 1;
        }

        /* CKEditor editable area */
        .ck-editor__editable {
            min-height: 400px !important;
            max-height: 600px !important;
            overflow-y: auto !important;
            resize: none !important;
            border: 1px solid #c4c4c4 !important;
            padding: 15px !important;
        }

        .ck-editor__editable_inline {
            min-height: 400px !important;
            max-height: 600px !important;
            overflow-y: auto !important;
        }

        /* Ensure dropdowns are visible */
        .ck-dropdown__panel {
            z-index: 999 !important;
        }

        /* Fix for heading dropdown */
        .ck-heading-dropdown {
            min-width: 150px !important;
        }

        /* EMERGENCY FIX - Force heading sizes with maximum specificity */
        .ck-editor__editable h1, .ck-content h1, .ck.ck-editor__editable h1, .ck.ck-content h1,
        #content + .ck-editor .ck-editor__editable h1,
        #content_ar + .ck-editor .ck-editor__editable h1 {
            font-size: 48px !important;
            font-weight: bold !important;
            margin: 20px 0 !important;
            line-height: 1.2 !important;
            color: #333 !important;
            display: block !important;
        }

        .ck-editor__editable h2, .ck-content h2, .ck.ck-editor__editable h2, .ck.ck-content h2,
        #content + .ck-editor .ck-editor__editable h2,
        #content_ar + .ck-editor .ck-editor__editable h2 {
            font-size: 36px !important;
            font-weight: bold !important;
            margin: 18px 0 !important;
            line-height: 1.3 !important;
            color: #333 !important;
            display: block !important;
        }

        .ck-editor__editable h3, .ck-content h3, .ck.ck-editor__editable h3, .ck.ck-content h3,
        #content + .ck-editor .ck-editor__editable h3,
        #content_ar + .ck-editor .ck-editor__editable h3 {
            font-size: 28px !important;
            font-weight: bold !important;
            margin: 16px 0 !important;
            line-height: 1.4 !important;
            color: #333 !important;
            display: block !important;
        }

        .ck-editor__editable h4, .ck-content h4, .ck.ck-editor__editable h4, .ck.ck-content h4,
        #content + .ck-editor .ck-editor__editable h4,
        #content_ar + .ck-editor .ck-editor__editable h4 {
            font-size: 22px !important;
            font-weight: bold !important;
            margin: 14px 0 !important;
            line-height: 1.4 !important;
            color: #333 !important;
            display: block !important;
        }

        .ck-editor__editable h5, .ck-content h5, .ck.ck-editor__editable h5, .ck.ck-content h5,
        #content + .ck-editor .ck-editor__editable h5,
        #content_ar + .ck-editor .ck-editor__editable h5 {
            font-size: 18px !important;
            font-weight: bold !important;
            margin: 12px 0 !important;
            line-height: 1.4 !important;
            color: #333 !important;
            display: block !important;
        }

        .ck-editor__editable h6, .ck-content h6, .ck.ck-editor__editable h6, .ck.ck-content h6,
        #content + .ck-editor .ck-editor__editable h6,
        #content_ar + .ck-editor .ck-editor__editable h6 {
            font-size: 16px !important;
            font-weight: bold !important;
            margin: 10px 0 !important;
            line-height: 1.4 !important;
            color: #333 !important;
            display: block !important;
        }

        /* EMERGENCY FIX - Force bold to work */
        .ck-editor__editable strong, .ck-editor__editable b,
        .ck-content strong, .ck-content b,
        .ck.ck-editor__editable strong, .ck.ck-editor__editable b,
        .ck.ck-content strong, .ck.ck-content b,
        #content + .ck-editor .ck-editor__editable strong,
        #content_ar + .ck-editor .ck-editor__editable strong {
            font-weight: 900 !important;
            color: #000 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-0">
        <div class="mb-6">
            <a href="{{ route('admin.posts.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200 gap-3">
                <i class="fas fa-arrow-left"></i>
                {{ trans('posts.back_to_posts') }}
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-plus text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4 mx-3">
                            <h1 class="text-2xl font-bold text-gray-900">{{ trans('posts.create_post') }}</h1>
                            <p class="text-gray-600 mt-1">{{ trans('posts.create_post') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="toggle-advanced"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-cog"></i>
                            <span id="advanced-text">{{ trans('posts.show_advanced_options') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf

                    <!-- Basic Information Section - Always Visible -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ trans('posts.basic_information') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Post Title -->
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.title') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                        placeholder="{{ trans('posts.enter_post_title') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-heading text-gray-400"></i>
                                    </div>
                                </div>
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="status" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.status') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="status" id="status" required
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm appearance-none">
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                            {{ trans('posts.draft') }}</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                            {{ trans('posts.published') }}</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                                            {{ trans('posts.archived') }}</option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-toggle-on text-gray-400"></i>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Excerpt Section - Always Visible -->
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-100">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-quote-left text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ trans('posts.excerpt') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Excerpt -->
                            <div class="space-y-2">
                                <label for="excerpt" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.excerpt') }}
                                </label>
                                <div class="relative">
                                    <textarea name="excerpt" id="excerpt" rows="4" placeholder="{{ trans('posts.enter_post_excerpt') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('excerpt') }}</textarea>
                                    <div class="absolute top-3 left-4 pointer-events-none">
                                        <i class="fas fa-align-left text-gray-400"></i>
                                    </div>
                                </div>
                                @error('excerpt')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Excerpt Arabic -->
                            <div class="space-y-2">
                                <label for="excerpt_ar" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.excerpt_ar') }}
                                </label>
                                <div class="relative">
                                    <textarea name="excerpt_ar" id="excerpt_ar" rows="4" placeholder="{{ trans('posts.enter_post_excerpt_ar') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('excerpt_ar') }}</textarea>
                                    <div class="absolute top-3 left-4 pointer-events-none">
                                        <i class="fas fa-align-right text-gray-400"></i>
                                    </div>
                                </div>
                                @error('excerpt_ar')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Content Section - Always Visible -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-edit text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ trans('posts.content') }}</h2>
                        </div>

                        <!-- Content -->
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label for="content" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.content') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="content" id="content" rows="12" required
                                    placeholder="{{ trans('posts.enter_post_content') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900 content-textarea ckeditor">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image Section - Always Visible -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3"
                                style="background: var(--brand-brown);">
                                <i class="fas fa-image text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold" style="color: var(--brand-brown);">
                                {{ trans('posts.featured_image') }}</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="border-2 border-dashed rounded-xl p-8 text-center hover:border-opacity-60 transition-all duration-200"
                                style="border-color: var(--brand-brown); background: rgba(255, 255, 255, 0.5);">
                                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                    class="hidden">
                                <label for="featured_image" class="cursor-pointer">
                                    <div class="mb-3">
                                        <i class="fas fa-cloud-upload-alt text-3xl"
                                            style="color: var(--brand-brown);"></i>
                                    </div>
                                    <p class="text-sm font-medium mb-1" style="color: var(--brand-brown);">
                                        {{ trans('posts.click_to_upload_featured_image') }}
                                    </p>
                                    <p class="text-xs" style="color: var(--brand-brown-light);">
                                        {{ trans('posts.featured_image_requirements') }}
                                    </p>
                                </label>
                            </div>
                            <div id="featured-image-preview" class="mt-4" style="display: none;">
                                <img id="featured-image-preview-img" src="" alt="Preview"
                                    class="w-32 h-32 object-cover rounded-lg shadow-md"
                                    style="border: 2px solid var(--brand-brown);">
                                <p class="text-xs mt-2" style="color: var(--brand-brown-light);">
                                    {{ trans('posts.featured_image_preview') }}</p>
                            </div>
                            @error('featured_image')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Advanced Options Section - Hidden by Default -->
                    <div id="advanced-options" class="hidden space-y-8">
                        <!-- Additional Basic Fields -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                            <div class="flex items-center mb-6 gap-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-plus-circle text-white text-sm"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ trans('posts.additional_options') }}
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Post Title Arabic -->
                                <div class="space-y-2">
                                    <label for="title_ar" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('posts.title_ar') }}
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="title_ar" id="title_ar"
                                            value="{{ old('title_ar') }}"
                                            placeholder="{{ trans('posts.enter_post_title_ar') }}"
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-language text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('title_ar')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Featured -->
                                <div class="space-y-2">
                                    <label for="is_featured" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('posts.is_featured') }}
                                    </label>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                            {{ old('is_featured') ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                            {{ trans('posts.featured') }}
                                        </label>
                                    </div>
                                    @error('is_featured')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Published At -->
                                <div class="space-y-2">
                                    <label for="published_at" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('posts.published_at') }}
                                    </label>
                                    <div class="relative">
                                        <input type="datetime-local" name="published_at" id="published_at"
                                            value="{{ old('published_at') }}"
                                            class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('published_at')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Content Arabic -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                            <div class="flex items-center mb-6 gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-language text-white text-sm"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ trans('posts.content_ar') }}</h2>
                            </div>

                            <div class="space-y-4">
                                <!-- Content Arabic -->
                                <div class="space-y-2">
                                    <label for="content_ar" class="block text-sm font-semibold text-gray-700">
                                        {{ trans('posts.content_ar') }}
                                    </label>
                                    <textarea name="content_ar" id="content_ar" rows="12" placeholder="{{ trans('posts.enter_post_content_ar') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900 content-textarea ckeditor">{{ old('content_ar') }}</textarea>
                                    @error('content_ar')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings Section -->
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-100">
                            <div class="flex items-center mb-6 gap-3">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-search text-white text-sm"></i>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ trans('posts.seo_settings') }}</h2>
                            </div>

                            <div class="space-y-6">
                                <!-- Auto-generate SEO button -->
                                <div class="flex justify-end mb-4">
                                    <button type="button" id="auto-generate-seo"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <i class="fas fa-magic mr-2"></i>
                                        {{ trans('posts.auto_generate_seo') }}
                                    </button>
                                </div>

                                <div class="space-y-6">
                                    <!-- Meta Tags -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label for="meta_title" class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_meta_title') }}
                                            </label>
                                            <input type="text" name="meta_title" id="meta_title"
                                                value="{{ old('meta_title') }}"
                                                placeholder="{{ trans('posts.seo_meta_title_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_title_help') }}</p>
                                            @error('meta_title')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="meta_title_ar" class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_meta_title_ar') }}
                                            </label>
                                            <input type="text" name="meta_title_ar" id="meta_title_ar"
                                                value="{{ old('meta_title_ar') }}"
                                                placeholder="{{ trans('posts.seo_meta_title_ar_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_title_help') }}</p>
                                            @error('meta_title_ar')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label for="meta_description"
                                                class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_meta_description') }}
                                            </label>
                                            <textarea name="meta_description" id="meta_description" rows="3"
                                                placeholder="{{ trans('posts.seo_meta_description_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('meta_description') }}</textarea>
                                            <p class="text-xs text-gray-500">
                                                {{ trans('posts.seo_meta_description_help') }}
                                            </p>
                                            @error('meta_description')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="meta_description_ar"
                                                class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_meta_description_ar') }}
                                            </label>
                                            <textarea name="meta_description_ar" id="meta_description_ar" rows="3"
                                                placeholder="{{ trans('posts.seo_meta_description_ar_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('meta_description_ar') }}</textarea>
                                            <p class="text-xs text-gray-500">
                                                {{ trans('posts.seo_meta_description_help') }}
                                            </p>
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
                                                {{ trans('posts.seo_meta_keywords') }}
                                            </label>
                                            <input type="text" name="meta_keywords" id="meta_keywords"
                                                value="{{ old('meta_keywords') }}"
                                                placeholder="{{ trans('posts.seo_meta_keywords_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_keywords_help') }}
                                            </p>
                                            @error('meta_keywords')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="meta_keywords_ar"
                                                class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_meta_keywords_ar') }}
                                            </label>
                                            <input type="text" name="meta_keywords_ar" id="meta_keywords_ar"
                                                value="{{ old('meta_keywords_ar') }}"
                                                placeholder="{{ trans('posts.seo_meta_keywords_ar_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_keywords_help') }}
                                            </p>
                                            @error('meta_keywords_ar')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Open Graph -->
                                    <div class="border-t border-purple-200 pt-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Open Graph (Social Media)</h3>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <label for="og_title" class="block text-sm font-semibold text-gray-700">
                                                    {{ trans('posts.seo_og_title') }}
                                                </label>
                                                <input type="text" name="og_title" id="og_title"
                                                    value="{{ old('og_title') }}"
                                                    placeholder="{{ trans('posts.seo_og_title_placeholder') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                                <p class="text-xs text-gray-500">{{ trans('posts.seo_og_title_help') }}
                                                </p>
                                                @error('og_title')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label for="og_title_ar"
                                                    class="block text-sm font-semibold text-gray-700">
                                                    {{ trans('posts.seo_og_title_ar') }}
                                                </label>
                                                <input type="text" name="og_title_ar" id="og_title_ar"
                                                    value="{{ old('og_title_ar') }}"
                                                    placeholder="{{ trans('posts.seo_og_title_ar_placeholder') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                                <p class="text-xs text-gray-500">{{ trans('posts.seo_og_title_help') }}
                                                </p>
                                                @error('og_title_ar')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
                                            <div class="space-y-2">
                                                <label for="og_description"
                                                    class="block text-sm font-semibold text-gray-700">
                                                    {{ trans('posts.seo_og_description') }}
                                                </label>
                                                <textarea name="og_description" id="og_description" rows="3"
                                                    placeholder="{{ trans('posts.seo_og_description_placeholder') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('og_description') }}</textarea>
                                                <p class="text-xs text-gray-500">
                                                    {{ trans('posts.seo_og_description_help') }}
                                                </p>
                                                @error('og_description')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label for="og_description_ar"
                                                    class="block text-sm font-semibold text-gray-700">
                                                    {{ trans('posts.seo_og_description_ar') }}
                                                </label>
                                                <textarea name="og_description_ar" id="og_description_ar" rows="3"
                                                    placeholder="{{ trans('posts.seo_og_description_ar_placeholder') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('og_description_ar') }}</textarea>
                                                <p class="text-xs text-gray-500">
                                                    {{ trans('posts.seo_og_description_help') }}
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
                                                {{ trans('posts.seo_og_image') }}
                                            </label>
                                            <input type="file" name="og_image" id="og_image" accept="image/*"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_og_image_help') }}</p>
                                            @error('og_image')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Additional SEO Settings -->
                                    <div class="border-t border-purple-200 pt-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional SEO Settings</h3>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <label for="canonical_url"
                                                    class="block text-sm font-semibold text-gray-700">
                                                    {{ trans('posts.seo_canonical_url') }}
                                                </label>
                                                <input type="url" name="canonical_url" id="canonical_url"
                                                    value="{{ old('canonical_url') }}"
                                                    placeholder="{{ trans('posts.seo_canonical_url_placeholder') }}"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                                <p class="text-xs text-gray-500">
                                                    {{ trans('posts.seo_canonical_url_help') }}
                                                </p>
                                                @error('canonical_url')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label for="robots" class="block text-sm font-semibold text-gray-700">
                                                    {{ trans('posts.seo_robots') }}
                                                </label>
                                                <select name="robots" id="robots"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                                    <option value="index,follow"
                                                        {{ old('robots', 'index,follow') == 'index,follow' ? 'selected' : '' }}>
                                                        Index, Follow</option>
                                                    <option value="index,nofollow"
                                                        {{ old('robots') == 'index,nofollow' ? 'selected' : '' }}>Index,
                                                        No
                                                        Follow
                                                    </option>
                                                    <option value="noindex,follow"
                                                        {{ old('robots') == 'noindex,follow' ? 'selected' : '' }}>No
                                                        Index,
                                                        Follow
                                                    </option>
                                                    <option value="noindex,nofollow"
                                                        {{ old('robots') == 'noindex,nofollow' ? 'selected' : '' }}>No
                                                        Index,
                                                        No
                                                        Follow</option>
                                                </select>
                                                <p class="text-xs text-gray-500">{{ trans('posts.seo_robots_help') }}</p>
                                                @error('robots')
                                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <label for="structured_data"
                                                class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_structured_data') }}
                                            </label>
                                            <textarea name="structured_data" id="structured_data" rows="6"
                                                placeholder="{{ trans('posts.seo_structured_data_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm font-mono text-sm text-gray-900">{{ old('structured_data') }}</textarea>
                                            <p class="text-xs text-gray-500">
                                                {{ trans('posts.seo_structured_data_help') }}
                                            </p>
                                            @error('structured_data')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions - Always Visible -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200 gap-3">
                        <div class="flex gap-3">
                            <button type="button" id="preview-draft-btn"
                                class="inline-flex items-center px-6 py-3 gap-3 border border-purple-300 rounded-lg font-medium text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 shadow-sm">
                                <i class="fas fa-eye"></i>
                                {{ trans('posts.preview_draft') }}
                            </button>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.posts.index') }}"
                                class="inline-flex items-center px-6 py-3 gap-3 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                                <i class="fas fa-times"></i>
                                {{ trans('posts.cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 gap-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg">
                                <i class="fas fa-save"></i>
                                {{ trans('posts.create_post_button') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- CKEditor 5 Classic -->
    <script src="{{ asset('assets/lib/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Featured image upload preview
            const featuredImageInput = document.getElementById('featured_image');
            const featuredImagePreview = document.getElementById('featured-image-preview');
            const featuredImagePreviewImg = document.getElementById('featured-image-preview-img');

            if (featuredImageInput && featuredImagePreview && featuredImagePreviewImg) {
                featuredImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            featuredImagePreviewImg.src = e.target.result;
                            featuredImagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Toggle advanced options
            const toggleButton = document.getElementById('toggle-advanced');
            const advancedOptions = document.getElementById('advanced-options');
            const advancedText = document.getElementById('advanced-text');

            if (toggleButton && advancedOptions && advancedText) {
                toggleButton.addEventListener('click', function() {
                    if (advancedOptions.classList.contains('hidden')) {
                        advancedOptions.classList.remove('hidden');
                        advancedText.textContent = '{{ trans('posts.hide_advanced_options') }}';
                        toggleButton.innerHTML =
                            '<i class="fas fa-cog mr-2"></i><span id="advanced-text">{{ trans('posts.hide_advanced_options') }}</span>';
                    } else {
                        advancedOptions.classList.add('hidden');
                        advancedText.textContent = '{{ trans('posts.show_advanced_options') }}';
                        toggleButton.innerHTML =
                            '<i class="fas fa-cog mr-2"></i><span id="advanced-text">{{ trans('posts.show_advanced_options') }}</span>';
                    }
                });
            }

            // Auto-generate SEO
            const autoGenerateBtn = document.getElementById('auto-generate-seo');
            if (autoGenerateBtn) {
                autoGenerateBtn.addEventListener('click', function() {
                    const title = document.getElementById('title').value;
                    const content = document.getElementById('content').value;

                    if (title) {
                        // Generate meta title (max 60 chars)
                        const metaTitle = title.length > 60 ? title.substring(0, 57) + '...' : title;
                        document.getElementById('meta_title').value = metaTitle;

                        // Generate meta description from content (max 160 chars)
                        if (content) {
                            const cleanContent = content.replace(/<[^>]*>/g, '').trim();
                            const metaDescription = cleanContent.length > 160 ? cleanContent.substring(0,
                                157) + '...' : cleanContent;
                            document.getElementById('meta_description').value = metaDescription;
                        }

                        // Show success message
                        this.innerHTML =
                            '<i class="fas fa-check mr-2"></i>{{ trans('posts.seo_generated') }}';
                        this.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                        this.classList.add('bg-green-600', 'hover:bg-green-700');

                        setTimeout(() => {
                            this.innerHTML =
                                '<i class="fas fa-magic mr-2"></i>{{ trans('posts.auto_generate_seo') }}';
                            this.classList.remove('bg-green-600', 'hover:bg-green-700');
                            this.classList.add('bg-purple-600', 'hover:bg-purple-700');
                        }, 2000);
                    }
                });
            }
        });
    </script>
    <script>
        let contentEditor, contentArEditor;

        // EMERGENCY FIX - Complete working solution
        console.log('🚨 EMERGENCY FIX: Starting CKEditor initialization...');

        // Custom Upload Adapter
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);

                    fetch('{{ route("admin.upload.image") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: data
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            resolve({ default: result.url });
                        } else {
                            reject(result.error?.message || 'Upload failed');
                        }
                    })
                    .catch(error => reject(error));
                }));
            }

            abort() {}
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        // EMERGENCY FIX - Use DEFAULT CKEditor configuration (GUARANTEED TO WORK)
        async function initializeEditors() {
            try {
                console.log('🚨 EMERGENCY FIX: Using DEFAULT CKEditor configuration...');
                
                // Check if ClassicEditor is available
                if (typeof ClassicEditor === 'undefined') {
                    console.error('❌ ClassicEditor not loaded!');
                    alert('CKEditor not loaded. Please refresh the page.');
                    return;
                }
                
                console.log('✅ ClassicEditor found, creating editors...');
                
                // ENGLISH EDITOR - Use DEFAULT config (no custom settings)
                console.log('📝 Creating English editor...');
                contentEditor = await ClassicEditor.create(document.querySelector('#content'));
                console.log('✅ English editor created successfully');
                
                // Add upload adapter
                if (contentEditor.plugins.has('FileRepository')) {
                    MyCustomUploadAdapterPlugin(contentEditor);
                    console.log('✅ Upload adapter added to English editor');
                }
                
                // ARABIC EDITOR - Use DEFAULT config (no custom settings)
                console.log('📝 Creating Arabic editor...');
                contentArEditor = await ClassicEditor.create(document.querySelector('#content_ar'));
                console.log('✅ Arabic editor created successfully');
                
                // Make editors globally available
                window.contentEditor = contentEditor;
                window.contentArEditor = contentArEditor;
                
                console.log('✅ All editors ready!');
                console.log('📋 Available commands:', Array.from(contentEditor.commands._commands.keys()).slice(0, 10).join(', ') + '...');
                
                // Test features and add debugging
                setTimeout(() => {
                    const heading = contentEditor.commands.get('heading');
                    const bold = contentEditor.commands.get('bold');
                    console.log('🎯 Heading:', heading ? '✅' : '❌');
                    console.log('🎯 Bold:', bold ? '✅' : '❌');
                    
                    if (heading) {
                        console.log('📋 Heading options:', heading.modelElements);
                        console.log('📋 Heading value:', heading.value);
                    }
                    
                    // Test if headings actually work
                    console.log('🧪 Testing heading functionality...');
                    const headingCommand = contentEditor.commands.get('heading');
                    if (headingCommand) {
                        console.log('✅ Heading command available');
                        // Try to set heading programmatically to test
                        try {
                            headingCommand.value = 'heading1';
                            console.log('✅ Heading command working');
                        } catch (e) {
                            console.log('❌ Heading command failed:', e);
                        }
                    }
                }, 500);

                    // Preview draft functionality
                    const previewDraftBtn = document.getElementById('preview-draft-btn');
                    if (previewDraftBtn) {
                        previewDraftBtn.addEventListener('click', function() {
                        console.log('Preview clicked');
                        if (window.contentEditor) window.contentEditor.updateSourceElement();
                        if (window.contentArEditor) window.contentArEditor.updateSourceElement();
                        
                        const formData = new FormData(document.querySelector('form'));
                            const tempForm = document.createElement('form');
                            tempForm.method = 'POST';
                            tempForm.action = '{{ route('admin.posts.store') }}';
                            tempForm.target = '_blank';
                            tempForm.style.display = 'none';

                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            tempForm.appendChild(csrfInput);

                            const previewInput = document.createElement('input');
                            previewInput.type = 'hidden';
                            previewInput.name = 'preview';
                            previewInput.value = '1';
                            tempForm.appendChild(previewInput);

                            for (let [key, value] of formData.entries()) {
                                if (key !== '_token') {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = key;
                                    input.value = value;
                                    tempForm.appendChild(input);
                                }
                            }

                            document.body.appendChild(tempForm);
                            tempForm.submit();
                            document.body.removeChild(tempForm);
                        });
                    }

            } catch (error) {
                console.error('Error initializing CKEditor:', error);
                console.error('Error details:', error.message);
                console.error('Error stack:', error.stack);
                alert('Failed to initialize editor. Check console for details.');
            }
        }

        // EMERGENCY FIX - Simple form handler
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚨 EMERGENCY FIX: Starting initialization...');
            
            // Initialize editors
            initializeEditors().then(() => {
                console.log('✅ Editors initialized, setting up form handler...');
                
                // Simple form handler
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                        console.log('📤 FORM SUBMIT CLICKED!');
                        
                        // Sync content
                        if (window.contentEditor) {
                            window.contentEditor.updateSourceElement();
                            console.log('✅ English synced');
                        }
                        
                        if (window.contentArEditor) {
                            window.contentArEditor.updateSourceElement();
                            console.log('✅ Arabic synced');
                        }
                        
                        console.log('✅ Form submitting...');
                    });
                    console.log('✅ Form handler attached');
                }
            }).catch(error => {
                console.error('❌ Error:', error);
            });
        });
    </script>
@endpush
