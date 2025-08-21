@extends('admin.layouts.app')

@section('title', trans('products.show_product'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.products.index') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                            <i class="fas fa-box mr-2"></i>
                            {{ trans('products.products_list') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-6 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            @if ($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                    class="w-20 h-20 rounded-xl object-cover shadow-md">
                            @else
                                <div
                                    class="w-20 h-20 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-md">
                                    <i class="fas fa-box text-3xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Product Title -->
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                            <p class="text-gray-600 mt-1">{{ $product->name_ar ?? trans('products.not_provided') }}</p>
                            <div class="flex items-center mt-2 space-x-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-circle mr-2 text-xs"></i>
                                    {{ $product->is_active ? trans('products.active') : trans('products.inactive') }}
                                </span>
                                <span class="text-sm text-gray-500">#{{ $product->id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3 mt-4 lg:mt-0">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            {{ trans('products.edit_product') }}
                        </a>
                        <a href="{{ route('admin.products.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ trans('products.back_to_products') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left Column - Basic Information -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            {{ trans('products.basic_information') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ trans('products.name') }}</label>
                                <p class="text-gray-900 font-medium">{{ $product->name }}</p>
                            </div>

                            <!-- Arabic Name -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ trans('products.name_ar') }}</label>
                                <p class="text-gray-900 font-medium">
                                    {{ $product->name_ar ?? trans('products.not_provided') }}</p>
                            </div>

                            <!-- Category -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ trans('products.category') }}</label>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->name ?? trans('products.no_category') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Base Price -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ trans('products.base_price') }}</label>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($product->base_price, 2) }}
                                    {{ trans('products.currency') }}</p>
                            </div>

                            <!-- Sort Order -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ trans('products.sort_order') }}</label>
                                <p class="text-gray-900 font-medium">{{ $product->sort_order }}</p>
                            </div>

                            <!-- Status -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2">{{ trans('products.status') }}</label>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-circle mr-2 text-xs"></i>
                                    {{ $product->is_active ? trans('products.active') : trans('products.inactive') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descriptions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-align-left mr-2 text-purple-500"></i>
                            {{ trans('products.description') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- English Description -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-3">{{ trans('products.description') }}</label>
                                <div class="bg-gray-50 rounded-lg p-4 min-h-[120px]">
                                    <p class="text-gray-900 leading-relaxed">
                                        {{ $product->description ?? trans('products.not_provided') }}</p>
                                </div>
                            </div>

                            <!-- Arabic Description -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-3">{{ trans('products.description_ar') }}</label>
                                <div class="bg-gray-50 rounded-lg p-4 min-h-[120px]">
                                    <p class="text-gray-900 leading-relaxed" dir="rtl">
                                        {{ $product->description_ar ?? trans('products.not_provided') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specifications Card -->
                @if ($product->specifications)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-cogs mr-2 text-orange-500"></i>
                                {{ trans('products.specifications') }}
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ json_encode($product->specifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Product Options Card -->
                @if ($product->options && $product->options->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list-ul mr-2 text-indigo-500"></i>
                                {{ trans('products.product_options') }}
                                <span
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $product->options->count() }}
                                </span>
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($product->options as $option)
                                    <div
                                        class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 text-lg">{{ $option->name }}</h4>
                                                @if ($option->name_ar)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $option->name_ar }}</p>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $option->is_required ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                                    <i
                                                        class="fas fa-{{ $option->is_required ? 'exclamation' : 'info' }}-circle mr-1"></i>
                                                    {{ $option->is_required ? trans('products.required') : trans('products.optional') }}
                                                </span>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $option->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    {{ $option->is_active ? trans('products.active') : trans('products.inactive') }}
                                                </span>
                                            </div>
                                        </div>

                                        @if ($option->values && $option->values->count() > 0)
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach ($option->values as $value)
                                                    <div
                                                        class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex-1">
                                                                <p class="font-medium text-gray-900">{{ $value->value }}
                                                                </p>
                                                                @if ($value->value_ar)
                                                                    <p class="text-sm text-gray-600 mt-1" dir="rtl">
                                                                        {{ $value->value_ar }}</p>
                                                                @endif
                                                            </div>
                                                            <div class="text-right ml-3">
                                                                <p class="text-lg font-bold text-green-600">
                                                                    {{ number_format($value->price_adjustment, 2) }}
                                                                </p>
                                                                <p class="text-xs text-gray-500">
                                                                    {{ trans('products.currency') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Additional Information -->
            <div class="space-y-6">
                <!-- Product Image Card -->
                @if ($product->image)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-image mr-2 text-pink-500"></i>
                                {{ trans('products.image') }}
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="relative group">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                    class="w-full h-64 object-cover rounded-lg shadow-md group-hover:shadow-lg transition duration-200">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-200 rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timestamps Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-clock mr-2 text-gray-500"></i>
                            {{ trans('products.timestamps') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-700">{{ trans('products.created_at') }}</span>
                                <span
                                    class="text-sm text-gray-900">{{ $product->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span
                                    class="text-sm font-medium text-gray-700">{{ trans('products.last_updated') }}</span>
                                <span
                                    class="text-sm text-gray-900">{{ $product->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-teal-500"></i>
                            {{ trans('products.quick_stats') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ trans('products.total_options') }}</span>
                                <span class="text-lg font-bold text-gray-900">{{ $product->options->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ trans('products.total_values') }}</span>
                                <span
                                    class="text-lg font-bold text-gray-900">{{ $product->options->sum(function ($option) {return $option->values->count();}) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ trans('products.required_options') }}</span>
                                <span
                                    class="text-lg font-bold text-red-600">{{ $product->options->where('is_required', true)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
