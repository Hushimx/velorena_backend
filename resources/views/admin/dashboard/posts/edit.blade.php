@extends('admin.layouts.app')

@section('pageTitle', trans('posts.edit_post'))
@section('title', trans('posts.edit_post'))

@section('styles')
    <style>
        /* Simple textarea styling */
        .content-textarea {
            min-height: 300px;
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
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
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="mx-3">
                            <h1 class="text-2xl font-bold text-gray-900">{{ trans('posts.edit_post') }}</h1>
                            <p class="text-gray-600 mt-1">{{ $post->title }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="toggle-advanced"
                            class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-cog"></i>
                            <span id="advanced-text">{{ trans('posts.show_advanced_options') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data"
                    class="space-y-8">
                    @csrf
                    @method('PUT')

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
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $post->title) }}" required
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
                                        <option value="draft"
                                            {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                            {{ trans('posts.draft') }}</option>
                                        <option value="published"
                                            {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>
                                            {{ trans('posts.published') }}</option>
                                        <option value="archived"
                                            {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>
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

                    <!-- Content Section -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                        <div class="flex items-center mb-6 gap-3">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-edit text-white text-sm"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ trans('posts.content') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Excerpt -->
                            <div class="space-y-2">
                                <label for="excerpt" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.excerpt') }}
                                </label>
                                <div class="relative">
                                    <textarea name="excerpt" id="excerpt" rows="4" placeholder="{{ trans('posts.enter_post_excerpt') }}"
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('excerpt', $post->excerpt) }}</textarea>
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
                                        class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('excerpt_ar', $post->excerpt_ar) }}</textarea>
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

                        <!-- Content -->
                        <div class="mt-6 space-y-4">
                            <div class="space-y-2">
                                <label for="content" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.content') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="content" id="content" rows="12" required
                                    placeholder="{{ trans('posts.enter_post_content') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900 content-textarea ckeditor">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="content_ar" class="block text-sm font-semibold text-gray-700">
                                    {{ trans('posts.content_ar') }}
                                </label>
                                <textarea name="content_ar" id="content_ar" rows="12" placeholder="{{ trans('posts.enter_post_content_ar') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900 content-textarea ckeditor">{{ old('content_ar', $post->content_ar) }}</textarea>
                                @error('content_ar')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image Section -->
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
                            @if ($post->featured_image)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Current Featured Image:</p>
                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                        alt="Current featured image" class="w-32 h-32 object-cover rounded-lg shadow-md"
                                        style="border: 2px solid var(--brand-brown);">
                                </div>
                            @endif

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
                                        {{ $post->featured_image ? 'Change Featured Image' : 'Upload Featured Image' }}
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
                                            value="{{ old('title_ar', $post->title_ar) }}"
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
                                            {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}
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
                                            value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
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

                                <!-- Meta Tags -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="meta_title" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('posts.seo_meta_title') }}
                                        </label>
                                        <input type="text" name="meta_title" id="meta_title"
                                            value="{{ old('meta_title', $post->meta_title) }}"
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
                                            value="{{ old('meta_title_ar', $post->meta_title_ar) }}"
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
                                        <label for="meta_description" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('posts.seo_meta_description') }}
                                        </label>
                                        <textarea name="meta_description" id="meta_description" rows="3"
                                            placeholder="{{ trans('posts.seo_meta_description_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('meta_description', $post->meta_description) }}</textarea>
                                        <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_description_help') }}
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
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('meta_description_ar', $post->meta_description_ar) }}</textarea>
                                        <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_description_help') }}
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
                                            value="{{ old('meta_keywords', $post->meta_keywords) }}"
                                            placeholder="{{ trans('posts.seo_meta_keywords_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                        <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_keywords_help') }}</p>
                                        @error('meta_keywords')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label for="meta_keywords_ar" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('posts.seo_meta_keywords_ar') }}
                                        </label>
                                        <input type="text" name="meta_keywords_ar" id="meta_keywords_ar"
                                            value="{{ old('meta_keywords_ar', $post->meta_keywords_ar) }}"
                                            placeholder="{{ trans('posts.seo_meta_keywords_ar_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                        <p class="text-xs text-gray-500">{{ trans('posts.seo_meta_keywords_help') }}</p>
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
                                                value="{{ old('og_title', $post->og_title) }}"
                                                placeholder="{{ trans('posts.seo_og_title_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_og_title_help') }}</p>
                                            @error('og_title')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="og_title_ar" class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_og_title_ar') }}
                                            </label>
                                            <input type="text" name="og_title_ar" id="og_title_ar"
                                                value="{{ old('og_title_ar', $post->og_title_ar) }}"
                                                placeholder="{{ trans('posts.seo_og_title_ar_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_og_title_help') }}</p>
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
                                                {{ trans('posts.seo_og_description') }}
                                            </label>
                                            <textarea name="og_description" id="og_description" rows="3"
                                                placeholder="{{ trans('posts.seo_og_description_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('og_description', $post->og_description) }}</textarea>
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_og_description_help') }}
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
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm text-gray-900">{{ old('og_description_ar', $post->og_description_ar) }}</textarea>
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_og_description_help') }}
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
                                        @if ($post->og_image)
                                            <div class="mb-2">
                                                <p class="text-sm font-medium text-gray-700 mb-2">Current OG Image:</p>
                                                <img src="{{ asset('storage/' . $post->og_image) }}"
                                                    alt="Current OG image"
                                                    class="w-32 h-32 object-cover rounded-lg shadow-md"
                                                    style="border: 2px solid var(--brand-brown);">
                                            </div>
                                        @endif
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
                                            <label for="canonical_url" class="block text-sm font-semibold text-gray-700">
                                                {{ trans('posts.seo_canonical_url') }}
                                            </label>
                                            <input type="url" name="canonical_url" id="canonical_url"
                                                value="{{ old('canonical_url', $post->canonical_url) }}"
                                                placeholder="{{ trans('posts.seo_canonical_url_placeholder') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white shadow-sm text-gray-900">
                                            <p class="text-xs text-gray-500">{{ trans('posts.seo_canonical_url_help') }}
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
                                                    {{ old('robots', $post->robots) == 'index,follow' ? 'selected' : '' }}>
                                                    Index, Follow</option>
                                                <option value="index,nofollow"
                                                    {{ old('robots', $post->robots) == 'index,nofollow' ? 'selected' : '' }}>
                                                    Index, No Follow</option>
                                                <option value="noindex,follow"
                                                    {{ old('robots', $post->robots) == 'noindex,follow' ? 'selected' : '' }}>
                                                    No Index, Follow</option>
                                                <option value="noindex,nofollow"
                                                    {{ old('robots', $post->robots) == 'noindex,nofollow' ? 'selected' : '' }}>
                                                    No Index, No Follow</option>
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
                                        <label for="structured_data" class="block text-sm font-semibold text-gray-700">
                                            {{ trans('posts.seo_structured_data') }}
                                        </label>
                                        <textarea name="structured_data" id="structured_data" rows="6"
                                            placeholder="{{ trans('posts.seo_structured_data_placeholder') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white hover:bg-gray-50 focus:bg-white resize-none shadow-sm font-mono text-sm text-gray-900">{{ old('structured_data', is_array($post->structured_data) ? json_encode($post->structured_data, JSON_PRETTY_PRINT) : $post->structured_data) }}</textarea>
                                        <p class="text-xs text-gray-500">{{ trans('posts.seo_structured_data_help') }}
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
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 gap-3 mb-3">
                <a href="{{ route('admin.posts.index') }}"
                    class="inline-flex items-center px-6 py-3 gap-3 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-sm">
                    <i class="fas fa-times"></i>
                    {{ trans('posts.cancel') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 gap-3 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg font-medium text-white hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 shadow-lg">
                    <i class="fas fa-save"></i>
                    {{ trans('posts.update_post_button') }}
                </button>
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

            if (toggleButton && advancedOptions) {
                toggleButton.addEventListener('click', function() {
                    if (advancedOptions.classList.contains('hidden')) {
                        advancedOptions.classList.remove('hidden');
                        toggleButton.innerHTML =
                            '<i class="fas fa-cog"></i><span id="advanced-text">{{ trans('posts.hide_advanced_options') }}</span>';
                    } else {
                        advancedOptions.classList.add('hidden');
                        toggleButton.innerHTML =
                            '<i class="fas fa-cog"></i><span id="advanced-text">{{ trans('posts.show_advanced_options') }}</span>';
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

        // Initialize CKEditor for content textareas
        async function initializeEditors() {
            try {
                // Initialize main content editor
                contentEditor = await ClassicEditor.create(document.querySelector('#content'), {
                    language: 'en',
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                    direction: 'ltr' // English content
                });

                // Initialize Arabic content editor
                contentArEditor = await ClassicEditor.create(document.querySelector('#content_ar'), {
                    language: 'ar',
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                    direction: 'rtl' // Arabic content
                });

                // Make editors available globally for form submission
                window.contentEditor = contentEditor;
                window.contentArEditor = contentArEditor;

            } catch (error) {
                console.error('Error initializing CKEditor:', error);
            }
        }

        // Initialize editors when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeEditors();
        });

        // Handle form submission to sync CKEditor content
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (contentEditor) {
                        contentEditor.updateSourceElement();
                    }
                    if (contentArEditor) {
                        contentArEditor.updateSourceElement();
                    }
                });
            }
        });
    </script>
@endpush
