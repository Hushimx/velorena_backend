@extends('layouts.app')

@section('title', $page->localized_meta_title ?: $page->localized_title)

@section('meta')
    @if($page->localized_meta_description)
        <meta name="description" content="{{ $page->localized_meta_description }}">
    @endif
    
    @if($page->localized_meta_keywords)
        <meta name="keywords" content="{{ $page->localized_meta_keywords }}">
    @endif
    
    @if($page->og_image)
        <meta property="og:title" content="{{ $page->localized_meta_title ?: $page->localized_title }}">
        <meta property="og:description" content="{{ $page->localized_meta_description ?: Str::limit(strip_tags($page->localized_content), 160) }}">
        <meta property="og:image" content="{{ Storage::url($page->og_image) }}">
        <meta property="og:type" content="article">
    @endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $page->localized_title }}</h1>
                    @if($page->title_ar && $page->title)
                        <p class="text-sm text-gray-600 mt-1">
                            @if(app()->getLocale() === 'ar')
                                {{ $page->title }}
                            @else
                                {{ $page->title_ar }}
                            @endif
                        </p>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    @if($page->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($page->type) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Images Section -->
            @if($page->images && count($page->images) > 0)
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Images</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($page->images as $image)
                            <div class="relative">
                                <img src="{{ Storage::url($image) }}" alt="Page Image" 
                                     class="w-full h-48 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="px-6 py-8">
                <div class="prose max-w-none">
                    @if($page->content_ar && $page->content)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                @if(app()->getLocale() === 'ar')
                                    المحتوى الإنجليزي
                                @else
                                    English Content
                                @endif
                            </h2>
                            <div class="text-gray-700 leading-relaxed" dir="ltr">
                                {!! nl2br(e($page->content)) !!}
                            </div>
                        </div>

                        <div class="border-t pt-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                @if(app()->getLocale() === 'ar')
                                    المحتوى العربي
                                @else
                                    Arabic Content
                                @endif
                            </h2>
                            <div class="text-gray-700 leading-relaxed" dir="rtl">
                                {!! nl2br(e($page->content_ar)) !!}
                            </div>
                        </div>
                    @else
                        <div class="text-gray-700 leading-relaxed" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                            {!! nl2br(e($page->localized_content)) !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Info -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div>
                        <span>Last updated: {{ $page->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span>Access Level: {{ ucfirst($page->access_level) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

