@extends('admin.layouts.app')

@section('pageTitle', __('admin.page_details'))
@section('title', __('admin.page_details'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ $page->localized_title }}</h1>
                <p class="text-gray-600">{{ __('admin.page_details') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    <span>{{ __('admin.edit_page') }}</span>
                </a>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.back_to_pages') }}</span>
                </a>
            </div>
        </div>

        <!-- Page Information -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('admin.page_information') }}</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.page_title') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.page_title_ar') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->title_ar ?: __('admin.not_provided') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.page_slug') }}</label>
                        <p class="mt-1 text-sm text-gray-900"><code class="bg-gray-100 px-2 py-1 rounded">{{ $page->slug }}</code></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.page_type') }}</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($page->type === 'page') bg-blue-100 text-blue-800
                            @elseif($page->type === 'section') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            @if($page->type === 'page') {{ __('admin.page_type_page') }}
                            @elseif($page->type === 'section') {{ __('admin.page_type_section') }}
                            @else {{ __('admin.page_type_modal') }} @endif
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.access_level') }}</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($page->access_level === 'public') bg-green-100 text-green-800
                            @elseif($page->access_level === 'authenticated') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($page->access_level === 'public') {{ __('admin.access_level_public') }}
                            @elseif($page->access_level === 'authenticated') {{ __('admin.access_level_authenticated') }}
                            @else {{ __('admin.access_level_admin') }} @endif
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.status') }}</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $page->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $page->is_active ? __('admin.page_status_active') : __('admin.page_status_inactive') }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.sort_order') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->sort_order }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.page_created_at') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.page_updated_at') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('admin.page_content') }}</h3>
            </div>
            <div class="px-6 py-4 space-y-6">
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-2">{{ __('admin.page_content') }}</h4>
                    <div class="prose max-w-none">
                        {!! $page->content !!}
                    </div>
                </div>

                @if($page->content_ar)
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-2">{{ __('admin.page_content_ar') }}</h4>
                        <div class="prose max-w-none" dir="rtl">
                            {!! $page->content_ar !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- SEO Information -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">{{ __('admin.page_seo_information') }}</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.meta_title') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->meta_title ?: __('admin.not_provided') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.meta_title_ar') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->meta_title_ar ?: __('admin.not_provided') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.meta_description') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->meta_description ?: __('admin.not_provided') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.meta_description_ar') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->meta_description_ar ?: __('admin.not_provided') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.meta_keywords') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->meta_keywords ?: __('admin.not_provided') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.meta_keywords_ar') }}</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $page->meta_keywords_ar ?: __('admin.not_provided') }}</p>
                    </div>
                </div>

                @if($page->og_image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('admin.og_image') }}</label>
                        <div class="mt-2">
                            <img src="{{ Storage::url($page->og_image) }}" alt="OG Image" class="h-32 w-32 object-cover rounded">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Images -->
        @if($page->images && count($page->images) > 0)
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('admin.page_images_section') }}</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($page->images as $image)
                            <div class="relative">
                                <img src="{{ Storage::url($image) }}" alt="Page Image" class="h-24 w-24 object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
