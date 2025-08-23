<div>
    <!-- Search and Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ trans('products.search') }}
                </label>
                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                    placeholder="{{ trans('products.search_products_placeholder') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="categoryFilter" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ trans('products.category') }}
                </label>
                <select wire:model.live="categoryFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ trans('products.all_categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if ($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                @foreach ($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300"
                        wire:key="product-card-{{ $product->id }}">
                        <!-- Product Image -->
                        <a href="{{ route('user.products.show', $product) }}"
                            class="block aspect-w-1 aspect-h-1 w-full">
                            @if ($product->image && file_exists(public_path($product->image)))
                                <img class="w-full h-48 object-cover" src="{{ asset($product->image) }}"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="mb-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $product->category->name ?? trans('products.no_category') }}
                                </span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('user.products.show', $product) }}"
                                    class="hover:text-blue-600 transition-colors">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                {{ Str::limit($product->description, 100) }}
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-green-600">
                                    {{ number_format($product->base_price, 2) }} {{ trans('products.currency') }}
                                </span>

                                <a href="{{ route('user.products.show', $product) }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    {{ trans('products.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    {{ trans('products.no_products_found') }}
                </h3>
                <p class="text-gray-600">
                    {{ trans('products.no_products_found_description') }}
                </p>
            </div>
        @endif
    </div>
</div>
