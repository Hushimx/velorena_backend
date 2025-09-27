@extends('admin.layouts.app')

@section('pageTitle', __('admin.highlight_details'))
@section('title', __('admin.highlight_details'))

@section('content')
    <style>
        .form-label {
            color: #1f2937 !important;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $highlight->name }}</h1>
                <p class="text-gray-600">
                    {{ __('admin.highlight_details') }}: #{{ $highlight->id }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.highlights.edit', $highlight) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    <span>{{ __('admin.edit_highlight') }}</span>
                </a>
                <a href="{{ route('admin.highlights.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('admin.back') }}</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Highlight Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="card bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">{{ __('admin.highlight_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('admin.name') }}</label>
                                <p class="text-gray-900">{{ $highlight->name }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.name_ar') }}</label>
                                <p class="text-gray-900">{{ $highlight->name_ar }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.slug') }}</label>
                                <p class="text-gray-900">{{ $highlight->slug }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.status') }}</label>
                                <div>
                                    <span class="badge badge-{{ $highlight->is_active ? 'success' : 'secondary' }}">
                                        {{ $highlight->is_active ? __('admin.active') : __('admin.inactive') }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.sort_order') }}</label>
                                <p class="text-gray-900">{{ $highlight->sort_order ?? __('admin.not_set') }}</p>
                            </div>
                            <div>
                                <label class="form-label">{{ __('admin.created_at') }}</label>
                                <p class="text-gray-900">{{ $highlight->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                @if ($highlight->description || $highlight->description_ar)
                    <div class="card bg-white">
                        <div class="card-header bg-white">
                            <h3 class="text-lg font-semibold text-white">{{ __('admin.descriptions') }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($highlight->description)
                                <div class="mb-4">
                                    <label class="form-label">{{ __('admin.description') }}</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $highlight->description }}</p>
                                </div>
                            @endif
                            @if ($highlight->description_ar)
                                <div>
                                    <label class="form-label">{{ __('admin.description_ar') }}</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg" dir="rtl">
                                        {{ $highlight->description_ar }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Products Information -->
            <div class="lg:col-span-1">
                <div class="card bg-white">
                    <div class="card-header bg-white">
                        <h3 class="text-lg font-semibold text-white">{{ __('admin.associated_products') }}
                            ({{ $highlight->products->count() }})</h3>
                    </div>
                    <div class="card-body">
                        @if ($highlight->products->count() > 0)
                            <div class="space-y-3">
                                @foreach ($highlight->products as $product)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center gap-3">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded-lg">
                                            @else
                                                <div
                                                    class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ number_format($product->price, 2) }}
                                                    ر.س</p>
                                            </div>
                                            <a href="{{ route('admin.products.show', $product) }}"
                                                class="text-blue-600 hover:text-blue-800 transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-box-open text-4xl mb-4 text-gray-400"></i>
                                <p class="text-gray-500">{{ __('admin.no_products_associated') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
