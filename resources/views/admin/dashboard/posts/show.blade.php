@extends('admin.layouts.app')

@section('pageTitle', $post->title)
@section('title', $post->title)

@section('content')
    <div class="container mx-auto px-4 py-8">
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
                                <i class="fas fa-newspaper text-white text-lg"></i>
                            </div>
                        </div>
                        <div class="mx-3">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
                            @if ($post->title_ar)
                                <p class="text-lg text-gray-600 mt-1">{{ $post->title_ar }}</p>
                            @endif
                            <div class="flex items-center gap-4 mt-2">
                                @if ($post->status === 'published')
                                    <span
                                        class="inline-flex items-center gap-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle"></i>
                                        {{ trans('posts.published') }}
                                    </span>
                                @elseif($post->status === 'draft')
                                    <span
                                        class="inline-flex items-center gap-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                        {{ trans('posts.draft') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-archive"></i>
                                        {{ trans('posts.archived') }}
                                    </span>
                                @endif

                                @if ($post->is_featured)
                                    <span
                                        class="inline-flex items-center gap-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-star"></i>
                                        {{ trans('posts.featured') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            <span>{{ trans('posts.edit_post') }}</span>
                        </a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline"
                            onsubmit="return confirm('{{ trans('posts.confirm_delete_post') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                                <span>{{ trans('posts.delete_post') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Featured Image -->
                        @if ($post->featured_image)
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('posts.featured_image') }}
                                </h3>
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                    class="w-full h-64 object-cover rounded-lg shadow-md">
                            </div>
                        @endif

                        <!-- Excerpt -->
                        @if ($post->excerpt || $post->excerpt_ar)
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('posts.excerpt') }}</h3>
                                @if ($post->excerpt)
                                    <div class="mb-4">
                                        <p class="text-gray-700 leading-relaxed">{{ $post->excerpt }}</p>
                                    </div>
                                @endif
                                @if ($post->excerpt_ar)
                                    <div class="mb-4">
                                        <p class="text-gray-700 leading-relaxed" dir="rtl">{{ $post->excerpt_ar }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('posts.content') }}</h3>
                            @if ($post->content)
                                <div class="mb-6">
                                    <div class="prose max-w-none text-gray-700">
                                        {!! nl2br(e($post->content)) !!}
                                    </div>
                                </div>
                            @endif
                            @if ($post->content_ar)
                                <div class="mb-6">
                                    <div class="prose max-w-none text-gray-700" dir="rtl">
                                        {!! nl2br(e($post->content_ar)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Post Details -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('posts.post_details') }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700">{{ trans('posts.author') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $post->admin->name ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700">{{ trans('posts.created_at') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $post->created_at->format('M d, Y \a\t H:i') }}</p>
                                </div>
                                @if ($post->published_at)
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700">{{ trans('posts.published_at') }}</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $post->published_at->format('M d, Y \a\t H:i') }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700">{{ trans('posts.updated_at') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $post->updated_at->format('M d, Y \a\t H:i') }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700">{{ trans('posts.slug') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-200 px-2 py-1 rounded">
                                        {{ $post->slug }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Information -->
                        @if ($post->meta_title || $post->meta_description || $post->meta_keywords)
                            <div
                                class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('posts.seo_settings') }}</h3>
                                <div class="space-y-4">
                                    @if ($post->meta_title)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_meta_title') }}</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $post->meta_title }}</p>
                                        </div>
                                    @endif
                                    @if ($post->meta_title_ar)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_meta_title_ar') }}</label>
                                            <p class="mt-1 text-sm text-gray-900" dir="rtl">{{ $post->meta_title_ar }}
                                            </p>
                                        </div>
                                    @endif
                                    @if ($post->meta_description)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_meta_description') }}</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $post->meta_description }}</p>
                                        </div>
                                    @endif
                                    @if ($post->meta_description_ar)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_meta_description_ar') }}</label>
                                            <p class="mt-1 text-sm text-gray-900" dir="rtl">
                                                {{ $post->meta_description_ar }}</p>
                                        </div>
                                    @endif
                                    @if ($post->meta_keywords)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_meta_keywords') }}</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $post->meta_keywords }}</p>
                                        </div>
                                    @endif
                                    @if ($post->meta_keywords_ar)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_meta_keywords_ar') }}</label>
                                            <p class="mt-1 text-sm text-gray-900" dir="rtl">
                                                {{ $post->meta_keywords_ar }}</p>
                                        </div>
                                    @endif
                                    @if ($post->canonical_url)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_canonical_url') }}</label>
                                            <a href="{{ $post->canonical_url }}" target="_blank"
                                                class="mt-1 text-sm text-blue-600 hover:text-blue-800 break-all">{{ $post->canonical_url }}</a>
                                        </div>
                                    @endif
                                    @if ($post->robots)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_robots') }}</label>
                                            <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-200 px-2 py-1 rounded">
                                                {{ $post->robots }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Open Graph Information -->
                        @if ($post->og_title || $post->og_description || $post->og_image)
                            <div
                                class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Open Graph</h3>
                                <div class="space-y-4">
                                    @if ($post->og_title)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_og_title') }}</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $post->og_title }}</p>
                                        </div>
                                    @endif
                                    @if ($post->og_title_ar)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_og_title_ar') }}</label>
                                            <p class="mt-1 text-sm text-gray-900" dir="rtl">{{ $post->og_title_ar }}
                                            </p>
                                        </div>
                                    @endif
                                    @if ($post->og_description)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_og_description') }}</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $post->og_description }}</p>
                                        </div>
                                    @endif
                                    @if ($post->og_description_ar)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700">{{ trans('posts.seo_og_description_ar') }}</label>
                                            <p class="mt-1 text-sm text-gray-900" dir="rtl">
                                                {{ $post->og_description_ar }}</p>
                                        </div>
                                    @endif
                                    @if ($post->og_image)
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">{{ trans('posts.seo_og_image') }}</label>
                                            <img src="{{ asset('storage/' . $post->og_image) }}" alt="OG Image"
                                                class="w-full h-32 object-cover rounded-lg shadow-sm">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Structured Data -->
                        @if ($post->structured_data)
                            <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-6 border border-green-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    {{ trans('posts.seo_structured_data') }}</h3>
                                <div class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
                                    <pre class="text-xs">{{ json_encode($post->structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
