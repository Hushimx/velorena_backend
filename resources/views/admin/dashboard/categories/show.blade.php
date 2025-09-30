@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Category Details')
@section('title', trans('categories.category_details'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('categories.category_details') }}</h1>
                <p class="text-gray-600">{{ trans('categories.view_category_information') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.categories.edit', $category) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-edit pl-2"></i>
                    <span>{{ trans('categories.edit_category') }}</span>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-left pl-2"></i>
                    <span>{{ trans('categories.back_to_categories') }}</span>
                </a>
            </div>
        </div>

        <!-- Category Details -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header with Images -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8">
                <div class="flex items-center space-x-6">
                    <!-- Main Image (Mobile Categories) -->
                    @if ($category->main_image)
                        <img src="{{ asset($category->main_image) }}" alt="{{ $category->name }}"
                            class="h-24 w-24 rounded-lg object-cover border-4 border-white shadow-lg">
                    @elseif ($category->image)
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                            class="h-24 w-24 rounded-lg object-cover border-4 border-white shadow-lg">
                    @else
                        <div
                            class="h-24 w-24 rounded-lg bg-white bg-opacity-20 flex items-center justify-center border-4 border-white shadow-lg">
                            <i class="fas fa-image text-white text-3xl"></i>
                        </div>
                    @endif
                    <div class="text-white">
                        <h2 class="text-3xl font-bold">{{ $category->name }}</h2>
                        <p class="text-green-100 text-lg">{{ $category->name_ar }}</p>
                        <div class="flex items-center mt-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? trans('categories.active') : trans('categories.inactive') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            {{ trans('categories.basic_information') }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ trans('categories.name') }}
                                    ({{ trans('categories.english') }})</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $category->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ trans('categories.name') }}
                                    ({{ trans('categories.arabic') }})</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $category->name_ar }}</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('categories.description') }}
                                    ({{ trans('categories.english') }})</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $category->description ?: trans('categories.no_description') }}</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('categories.description') }}
                                    ({{ trans('categories.arabic') }})</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $category->description_ar ?: trans('categories.no_description') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            {{ trans('categories.additional_information') }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('categories.status') }}</label>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $category->is_active ? trans('categories.active') : trans('categories.inactive') }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('categories.sort_order') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $category->sort_order }}</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('categories.products_count') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $category->products()->count() }}
                                    {{ trans('categories.products') }}</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('categories.created_at') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $category->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('categories.updated_at') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $category->updated_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                @if ($category->products()->count() > 0)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ trans('categories.products_in_category') }} ({{ $category->products()->count() }})
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($category->products()->latest()->take(6)->get() as $product)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        @if ($product->image)
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                                class="h-12 w-12 rounded object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->price }}
                                                {{ trans('categories.currency') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($category->products()->count() > 6)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.products.index', ['category' => $category->id]) }}"
                                    class="text-green-600 hover:text-green-700 text-sm font-medium">
                                    {{ trans('categories.view_all_products') }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
