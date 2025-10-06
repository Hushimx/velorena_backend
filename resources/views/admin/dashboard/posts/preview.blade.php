@extends('admin.layouts.app')

@section('pageTitle', trans('posts.preview_post'))
@section('title', trans('posts.preview_post'))

@section('styles')
    <style>
        /* RTL Support and Admin Theme Integration */
        .preview-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            border: 1px solid #e5e7eb;
        }

        .preview-header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: #ffffff;
            padding: 2rem;
            text-align: center;
            border-bottom: 3px solid #fbbf24;
        }

        .preview-content {
            padding: 2rem;
            background: #ffffff;
            min-height: 500px;
        }

        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            border-right: 4px solid #3b82f6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .post-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-published {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-draft {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-archived {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .featured-badge {
            background: #ff6b6b;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .post-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-align: right;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .post-excerpt {
            font-size: 1.125rem;
            color: #374151;
            line-height: 1.6;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            border-right: 4px solid #0ea5e9;
            text-align: right;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .featured-image {
            width: 100%;
            max-width: 800px;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            margin: 2rem auto;
            display: block;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .post-content {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #1f2937;
            text-align: right;
            background: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .post-content .prose {
            color: #1f2937;
            font-size: 1.125rem;
            line-height: 1.8;
            text-align: right;
        }

        .post-content .prose p {
            margin-bottom: 1.5rem;
            min-height: 1.5rem;
            color: #374151;
            font-weight: 400;
        }

        .post-content h1,
        .post-content h2,
        .post-content h3,
        .post-content h4,
        .post-content h5,
        .post-content h6 {
            color: #1f2937;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-align: right;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .post-content h1 {
            font-size: 2.25rem;
        }

        .post-content h2 {
            font-size: 1.875rem;
        }

        .post-content h3 {
            font-size: 1.5rem;
        }

        .post-content p {
            margin-bottom: 1.5rem;
            color: #374151;
            font-weight: 400;
        }

        .post-content ul,
        .post-content ol {
            margin-bottom: 1.5rem;
            padding-right: 2rem;
            text-align: right;
        }

        .post-content li {
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 400;
        }

        .post-content blockquote {
            border-right: 4px solid #3b82f6;
            padding-right: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #1f2937;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 1.5rem;
            border-radius: 12px 0 0 12px;
            text-align: right;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .post-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            direction: rtl;
        }

        .post-content th,
        .post-content td {
            border: 1px solid #d1d5db;
            padding: 0.75rem;
            text-align: right;
            color: #1f2937;
        }

        .post-content th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-weight: 700;
            color: #1f2937;
        }

        .preview-actions {
            background: #f8f9fa;
            padding: 1.5rem 2rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            direction: rtl;
        }

        .seo-preview {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            text-align: right;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .seo-preview h3 {
            color: #1f2937;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: right;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .seo-item {
            margin-bottom: 1.5rem;
            text-align: right;
            padding: 1rem;
            background: #ffffff;
            border-radius: 8px;
            border-right: 3px solid #10b981;
        }

        .seo-label {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .seo-value {
            color: #374151;
            word-break: break-word;
            font-weight: 400;
            line-height: 1.6;
        }

        .back-button {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            color: #ffffff;
            text-decoration: none;
        }

        .edit-button {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-button:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            direction: rtl;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .author-details {
            flex: 1;
            text-align: right;
        }

        .author-name {
            font-weight: 700;
            color: #1f2937;
            font-size: 1.1rem;
        }

        .post-date {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Debug and error messages styling */
        .bg-red-100 {
            background-color: #fef2f2 !important;
            border-color: #fecaca !important;
            color: #dc2626 !important;
            border-radius: 8px !important;
            padding: 1rem !important;
            font-weight: 500 !important;
        }

        .bg-yellow-100 {
            background-color: #fffbeb !important;
            border-color: #fed7aa !important;
            color: #d97706 !important;
            border-radius: 8px !important;
            padding: 1rem !important;
            font-weight: 500 !important;
        }

        .bg-blue-100 {
            background-color: #eff6ff !important;
            border-color: #bfdbfe !important;
            color: #2563eb !important;
            border-radius: 8px !important;
            padding: 1rem !important;
            font-weight: 500 !important;
        }

        .bg-gray-100 {
            background-color: #f9fafb !important;
            border-color: #d1d5db !important;
            color: #1f2937 !important;
            border-radius: 8px !important;
            padding: 1rem !important;
            font-weight: 500 !important;
        }

        /* Text alignment fixes */
        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        /* Arabic text support */
        .arabic-text {
            direction: rtl;
            text-align: right;
            font-family: 'Cairo', sans-serif;
        }

        @media (max-width: 768px) {
            .preview-container {
                margin: 1rem;
                border-radius: 8px;
            }

            .preview-header {
                padding: 1.5rem;
            }

            .preview-content {
                padding: 1.5rem;
            }

            .post-title {
                font-size: 2rem;
            }

            .post-meta {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .preview-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .featured-image {
                height: 250px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.posts.index') }}"
                class="inline-flex items-center text-sm text-gray-700 hover:text-gray-900 transition-colors duration-200 gap-3">
                <i class="fas fa-arrow-right"></i>
                {{ trans('posts.back_to_posts') }}
            </a>
        </div>

        <!-- Preview Container -->
        <div class="preview-container">
            <!-- Preview Header -->
            <div class="preview-header">
                <h1 class="text-2xl font-bold mb-2">
                    <i class="fas fa-eye mr-3"></i>
                    {{ trans('posts.post_preview') }}
                </h1>
                <p class="opacity-90">{{ trans('posts.preview_description') }}</p>
            </div>

            <!-- Preview Content -->
            <div class="preview-content">
                <!-- Error Message -->
                @if (isset($error))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>Error:</strong> {{ $error }}
                    </div>
                @endif

                <!-- Debug Information -->
                @if (config('app.debug'))
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <strong>Debug Info:</strong>
                        <br>Post Object: {{ $post ? 'Exists' : 'Null' }}
                        @if ($post)
                            <br>Post ID: {{ $post->id ?? 'No ID' }}
                            <br>Post Title: {{ $post->title ?? 'No Title' }}
                            <br>Post Status: {{ $post->status ?? 'No Status' }}
                            <br>Has Content: {{ !empty($post->content) ? 'Yes' : 'No' }}
                            <br>Content Length: {{ strlen($post->content ?? '') }}
                            <br>Has Admin: {{ $post->admin ? 'Yes' : 'No' }}
                            <br>Admin Name: {{ $post->admin->name ?? 'No Admin' }}
                            <br>Created At: {{ $post->created_at ?? 'No Date' }}
                            <br>All Attributes: {{ json_encode($post->getAttributes()) }}
                        @endif
                    </div>
                @endif

                <!-- Post Meta Information -->
                <div class="post-meta">
                    <div class="author-info">
                        <div class="author-avatar">
                            {{ strtoupper(substr($post->admin->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="author-details">
                            <div class="author-name">{{ $post->admin->name ?? 'Admin' }}</div>
                            <div class="post-date">
                                {{ trans('posts.created') }}: {{ $post->created_at->format('M d, Y \a\t H:i') }}
                                @if ($post->published_at)
                                    | {{ trans('posts.published') }}: {{ $post->published_at->format('M d, Y \a\t H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="post-status status-{{ $post->status }}">
                            {{ trans('posts.status_' . $post->status) }}
                        </span>
                        @if ($post->is_featured)
                            <span class="featured-badge">
                                <i class="fas fa-star mr-1"></i>
                                {{ trans('posts.featured') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Post Title -->
                <h1 class="post-title">
                    {{ $post->title ?? 'No Title Available' }}
                    @if ($post->title_ar)
                        <br><small class="text-gray-700 text-xl arabic-text">({{ $post->title_ar }})</small>
                    @endif
                </h1>

                <!-- Post Excerpt -->
                @if ($post->excerpt || $post->excerpt_ar)
                    <div class="post-excerpt">
                        @if ($post->excerpt)
                            <p class="mb-2"><strong>{{ trans('posts.excerpt') }}:</strong></p>
                            <p>{{ $post->excerpt }}</p>
                        @endif
                        @if ($post->excerpt_ar)
                            <p class="mb-2 mt-4"><strong>{{ trans('posts.excerpt_ar') }}:</strong></p>
                            <p class="arabic-text">{{ $post->excerpt_ar }}</p>
                        @endif
                    </div>
                @endif

                <!-- Featured Image -->
                @if ($post->featured_image)
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="featured-image">
                @endif

                <!-- Post Content -->
                <div class="post-content">
                    @if ($post->content)
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-3">{{ trans('posts.content') }}</h3>
                            @if (config('app.debug'))
                                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                                    <strong>Raw Content:</strong> "{{ $post->content }}"
                                    <br><strong>HTML Content:</strong> {!! $post->content !!}
                                </div>
                            @endif
                            <div class="prose max-w-none">
                                @if (trim($post->content))
                                    {!! $post->content !!}
                                @else
                                    <p class="text-gray-500 italic">Content appears to be empty or contains only whitespace.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-3">{{ trans('posts.content') }}</h3>
                            <div class="bg-gray-100 border border-gray-300 rounded-lg p-6 text-center text-gray-500">
                                <i class="fas fa-file-alt text-4xl mb-4"></i>
                                <p>{{ trans('posts.no_content_available') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($post->content_ar)
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-3">{{ trans('posts.content_ar') }}</h3>
                            <div class="arabic-text">{!! $post->content_ar !!}</div>
                        </div>
                    @endif
                </div>

                <!-- SEO Preview -->
                @if ($post->meta_title || $post->meta_description || $post->meta_keywords)
                    <div class="seo-preview">
                        <h3>{{ trans('posts.seo_preview') }}</h3>

                        @if ($post->meta_title)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_meta_title') }}:</div>
                                <div class="seo-value">{{ $post->meta_title }}</div>
                            </div>
                        @endif

                        @if ($post->meta_title_ar)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_meta_title_ar') }}:</div>
                                <div class="seo-value">{{ $post->meta_title_ar }}</div>
                            </div>
                        @endif

                        @if ($post->meta_description)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_meta_description') }}:</div>
                                <div class="seo-value">{{ $post->meta_description }}</div>
                            </div>
                        @endif

                        @if ($post->meta_description_ar)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_meta_description_ar') }}:</div>
                                <div class="seo-value">{{ $post->meta_description_ar }}</div>
                            </div>
                        @endif

                        @if ($post->meta_keywords)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_meta_keywords') }}:</div>
                                <div class="seo-value">{{ $post->meta_keywords }}</div>
                            </div>
                        @endif

                        @if ($post->meta_keywords_ar)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_meta_keywords_ar') }}:</div>
                                <div class="seo-value">{{ $post->meta_keywords_ar }}</div>
                            </div>
                        @endif

                        @if ($post->canonical_url)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_canonical_url') }}:</div>
                                <div class="seo-value">{{ $post->canonical_url }}</div>
                            </div>
                        @endif

                        @if ($post->robots)
                            <div class="seo-item">
                                <div class="seo-label">{{ trans('posts.seo_robots') }}:</div>
                                <div class="seo-value">{{ $post->robots }}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Preview Actions -->
            <div class="preview-actions">
                <a href="{{ route('admin.posts.index') }}" class="back-button">
                    <i class="fas fa-arrow-right"></i>
                    {{ trans('posts.back_to_posts') }}
                </a>

                <a href="{{ route('admin.posts.edit', $post) }}" class="edit-button">
                    <i class="fas fa-edit"></i>
                    {{ trans('posts.edit_post') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Add print functionality
        function printPreview() {
            window.print();
        }

        // Add keyboard shortcut for print (Ctrl+P)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                printPreview();
            }
        });
    </script>
@endpush
