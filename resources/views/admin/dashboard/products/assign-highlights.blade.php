@extends('admin.layouts.app')

@section('pageTitle', __('admin.assign_highlights'))
@section('title', __('admin.assign_highlights'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('admin.assign_highlights') }}</h1>
                <p class="text-gray-600">{{ __('admin.assign_highlights_to_product') }}:
                    <strong>{{ $product->name }}</strong>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('products.back_to_products') }}</span>
                </a>
            </div>
        </div>

        <!-- Product Info -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center gap-4">
                @if ($product->image && file_exists(public_path($product->image)))
                    <img class="h-20 w-20 rounded-lg object-cover" src="{{ asset($product->image) }}"
                        alt="{{ $product->name }}">
                @else
                    <div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-box text-gray-400"></i>
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $product->name_ar }}</p>
                    <p class="text-sm text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>
                </div>
            </div>
        </div>

        <!-- Assign Highlights Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.products.store-highlights', $product->id) }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.select_highlights') }}</h3>

                    @if ($highlights->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($highlights as $highlight)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <label class="flex items-start space-x-3 cursor-pointer">
                                        <input type="checkbox" name="highlights[]" value="{{ $highlight->id }}"
                                            {{ in_array($highlight->id, $productHighlights) ? 'checked' : '' }}
                                            class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">

                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white"
                                                    style="background-color: {{ $highlight->color }}">
                                                    {{ $highlight->name }}
                                                </span>
                                            </div>

                                            <div class="text-sm text-gray-900 font-medium">{{ $highlight->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $highlight->name_ar }}</div>

                                            @if ($highlight->description)
                                                <div class="text-xs text-gray-500 mt-1">{{ $highlight->description }}</div>
                                            @endif

                                            <!-- Sort Order Input -->
                                            <div class="mt-2">
                                                <label
                                                    class="block text-xs text-gray-600 mb-1">{{ __('admin.sort_order') }}</label>
                                                <input type="number" name="sort_orders[{{ $highlight->id }}]"
                                                    value="{{ $highlight->pivot->sort_order ?? 0 }}" min="0"
                                                    class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-purple-500">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-star text-4xl mb-4"></i>
                            <p>{{ __('admin.no_highlights_available') }}</p>
                            <a href="{{ route('admin.highlights.create') }}"
                                class="text-purple-600 hover:text-purple-800 mt-2 inline-block">
                                {{ __('admin.create_first_highlight') }}
                            </a>
                        </div>
                    @endif
                </div>

                @if ($highlights->count() > 0)
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            {{ __('admin.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>{{ __('admin.save_assignments') }}</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        // Auto-update sort order inputs when checkboxes are toggled
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="highlights[]"]');
            const sortInputs = document.querySelectorAll('input[name^="sort_orders"]');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const highlightId = this.value;
                    const sortInput = document.querySelector(
                        `input[name="sort_orders[${highlightId}]"]`);

                    if (sortInput) {
                        sortInput.disabled = !this.checked;
                        if (!this.checked) {
                            sortInput.value = '0';
                        }
                    }
                });

                // Trigger change event on page load
                checkbox.dispatchEvent(new Event('change'));
            });
        });
    </script>
@endsection
