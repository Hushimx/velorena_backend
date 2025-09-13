<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-100 text-green-700 rounded-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="px-2">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('products.search') }}</label>
                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                    placeholder="{{ trans('products.products_search_placeholder') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            {{ trans('products.name') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('products.category') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('products.base_price') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('products.highlights') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('products.status') }}
                        </th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            {{ trans('products.created_at') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('products.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50" wire:key="product-row-{{ $product->id }}">
                            <td class="p-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($product->image && file_exists(public_path($product->image)))
                                        <a href="{{ asset($product->image) }}" class="glightbox"
                                            data-gallery="product">
                                            <img class="h-20 w-20 rounded-lg object-cover mx-3"
                                                src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                        </a>
                                    @else
                                        <div
                                            class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center mx-3">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($product->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 whitespace-nowrap text-center text-sm text-gray-900">
                                {{ $product->category->name ?? trans('products.no_category') }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-center text-sm text-gray-900">
                                {{ number_format($product->base_price, 2) }} {{ trans('products.currency') }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-center">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    @forelse($product->highlights as $highlight)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $highlight->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs">{{ trans('products.no_highlights') }}</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-3 whitespace-nowrap text-center">
                                @if ($product->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ trans('products.active') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ trans('products.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $product->created_at->format('Y-m-d') }}
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-4">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.products.show', $product) }}"
                                        class="inline-flex items-center gap-2 px-3 mx-2 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-eye"></i>
                                        <span>{{ trans('products.show') }}</span>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="inline-flex items-center gap-2 px-3 mx-2 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-pen"></i>
                                        <span>{{ trans('products.edit') }}</span>
                                    </a>

                                    <!-- Assign Highlights Button -->
                                    <a href="{{ route('admin.products.assign-highlights', $product) }}"
                                        class="inline-flex items-center gap-2 px-3 mx-2 py-1.5 border border-transparent text-xs font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-star"></i>
                                        <span>{{ trans('products.assign_highlights') }}</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <button wire:click="confirmDelete({{ $product->id }})"
                                        wire:key="delete-{{ $product->id }}"
                                        class="inline-flex items-center gap-2 px-3 mx-2 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-trash"></i>
                                        <span>{{ trans('products.delete') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                {{ trans('products.no_products_exist') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $products->links() }}
        </div>

    </div>

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="delete-modal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">
                        {{ trans('products.confirm_delete_title') }}</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            {{ trans('products.confirm_delete_product') }}
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="deleteProduct" wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50">
                            <span wire:loading.remove wire:target="deleteProduct">{{ trans('products.delete') }}</span>
                            <span wire:loading wire:target="deleteProduct" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <button wire:click="cancelDelete" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50">
                            {{ trans('products.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('modal-closed', () => {
                // Ensure modal is properly closed
                console.log('Modal closed event received');
            });
        });
    </script>
</div>
