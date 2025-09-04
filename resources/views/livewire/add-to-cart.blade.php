<div>
    <!-- Add to Cart Button -->
    <button wire:click="openModal"
        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
        <i class="fas fa-shopping-cart mr-2"></i>
        {{ trans('cart.add_to_cart') }}
    </button>


    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mt-2 p-2 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-2 p-2 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ trans('cart.add_to_cart') }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Error Messages -->
                        @if (session()->has('error'))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="text-red-800">{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Product Info -->
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                            <p class="text-green-600 font-semibold">
                                {{ number_format($product->base_price, 2) }} {{ trans('products.currency') }}
                            </p>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ trans('cart.quantity') }}
                            </label>
                            <input type="number" wire:model="quantity" min="1" max="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('quantity')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Product Options -->
                        @if ($product->options->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ trans('cart.options') }}
                                </label>
                                <div class="space-y-3">
                                    @foreach ($product->options as $option)
                                        <div
                                            class="border border-gray-200 rounded-lg p-3 {{ $errors->has('selectedOptions.' . $option->id) ? 'border-red-300 bg-red-50' : '' }}">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="font-medium text-gray-700">{{ $option->name }}</span>
                                                @if ($option->is_required)
                                                    <span
                                                        class="text-red-500 text-xs font-medium">{{ trans('products.required') }}</span>
                                                @endif
                                            </div>

                                            @if ($option->values->count() > 0)
                                                <div class="space-y-2">
                                                    @foreach ($option->values as $value)
                                                        <label class="flex items-center">
                                                            <input type="radio"
                                                                wire:model="selectedOptions.{{ $option->id }}"
                                                                value="{{ $value->id }}"
                                                                name="option_{{ $option->id }}" class="mr-2">
                                                            <span class="text-sm text-gray-700">
                                                                {{ $value->value }}
                                                                @if ($value->price_adjustment != 0)
                                                                    <span
                                                                        class="text-sm {{ $value->price_adjustment > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                        ({{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 2) }}
                                                                        {{ trans('products.currency') }})
                                                                    </span>
                                                                @endif
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                    @error('selectedOptions.' . $option->id)
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Order Summary -->
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h4 class="font-medium text-gray-900 mb-2">
                                {{ trans('cart.order_summary') }}</h4>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ trans('cart.base_price') }}:</span>
                                    <span class="font-medium">${{ number_format($product->base_price, 2) }}</span>
                                </div>
                                @php
                                    $totalPrice = $product->base_price;
                                    $optionsPrice = 0;
                                    foreach ($selectedOptions as $optionId => $valueId) {
                                        if ($valueId) {
                                            $optionValue = App\Models\OptionValue::find($valueId);
                                            if ($optionValue) {
                                                $optionsPrice += $optionValue->price_adjustment;
                                            }
                                        }
                                    }
                                    $totalPrice += $optionsPrice;
                                    $finalPrice = $totalPrice * $quantity;
                                @endphp
                                @if ($optionsPrice > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ trans('cart.options_price') }}:</span>
                                        <span
                                            class="font-medium text-green-600">+${{ number_format($optionsPrice, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ trans('cart.quantity') }}:</span>
                                    <span class="font-medium">{{ $quantity }}</span>
                                </div>
                                <div class="border-t pt-1 mt-1">
                                    <div class="flex justify-between font-semibold text-lg">
                                        <span class="text-gray-900">{{ trans('cart.total') }}:</span>
                                        <span class="text-blue-600">${{ number_format($finalPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ trans('cart.notes') }}
                                ({{ trans('cart.optional') }})
                            </label>
                            <textarea wire:model="notes" rows="3" placeholder="{{ trans('cart.notes_placeholder') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('notes')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-3 mt-6">
                        <button type="button" wire:click="addToCart" wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="addToCart">
                                {{ trans('cart.add_to_cart') }}
                            </span>
                            <span wire:loading wire:target="addToCart" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                {{ trans('cart.adding') }}
                            </span>
                        </button>
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50 transition-colors">
                            {{ trans('cart.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- JavaScript for cart integration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('AddToCart JavaScript loaded');

            // Function to handle adding to cart
            function addToCartHandler(cartItem) {
                console.log('🎉 AddToCart event received:', cartItem);

                let cartData = JSON.parse(localStorage.getItem('shopping_cart') ||
                    '{"items": [], "total": 0, "itemCount": 0}');

                console.log('Current cart data:', cartData);

                // Check if product already exists in cart
                const existingItemIndex = cartData.items.findIndex(item =>
                    item.product_id === cartItem.product_id &&
                    JSON.stringify(item.selected_options) === JSON.stringify(cartItem.selected_options)
                );

                if (existingItemIndex !== -1) {
                    // Update existing item quantity
                    cartData.items[existingItemIndex].quantity += cartItem.quantity;
                    cartData.items[existingItemIndex].total_price = cartData.items[existingItemIndex]
                        .unit_price * cartData.items[existingItemIndex].quantity;
                    console.log('Updated existing item');
                } else {
                    // Add new item
                    cartData.items.push(cartItem);
                    console.log('Added new item');
                }

                // Update totals
                cartData.itemCount = cartData.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
                cartData.total = cartData.items.reduce((sum, item) => sum + (item.total_price || 0), 0);

                console.log('Updated cart data:', cartData);

                // Save to localStorage
                localStorage.setItem('shopping_cart', JSON.stringify(cartData));

                // Update cart count immediately
                if (window.updateCartCount) {
                    window.updateCartCount();
                }

                // Dispatch cart updated event
                document.dispatchEvent(new CustomEvent('cartUpdated'));

                // Show success message
                Swal.fire({
                    title: '{{ app()->getLocale() === 'ar' ? 'تمت الإضافة!' : 'Added to Cart!' }}',
                    text: '{{ app()->getLocale() === 'ar' ? 'تم إضافة المنتج إلى السلة بنجاح' : 'Product added to cart successfully!' }}',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Note: Cart addition is now handled directly in the Livewire component

            // Note: Removed browser event listener to prevent duplicate additions

            // Test function
            window.testCart = function() {
                console.log('Testing cart...');
                const testItem = {
                    product_id: 999,
                    product_name: 'Test Product',
                    product_image: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2NjY2NjYyIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjEyIiBmaWxsPSIjNjY2NjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+VGVzdDwvdGV4dD48L3N2Zz4=',
                    base_price: 10.00,
                    unit_price: 10.00,
                    quantity: 1,
                    total_price: 10.00,
                    selected_options: {},
                    notes: 'Test item'
                };
                addToCartHandler(testItem);
            };
        });
    </script>
</div>
