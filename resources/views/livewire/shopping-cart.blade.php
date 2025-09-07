<div>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-shopping-cart text-blue-600 mr-3"></i>
                        {{ trans('cart.shopping_cart') }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <span>{{ $itemCount }}</span>
                        {{ trans('cart.items_in_cart') }}
                    </p>
                </div>
                @if ($itemCount > 0)
                    <button wire:click="clearCart"
                        wire:confirm="{{ trans('cart.confirm_clear_cart', ['default' => 'Are you sure you want to clear all items from your cart? This action cannot be undone.']) }}"
                        class="text-red-600 hover:text-red-800 font-medium flex items-center">
                        <i class="fas fa-trash mr-2"></i>
                        {{ trans('cart.clear_cart') }}
                    </button>
                @endif
            </div>
        </div>

        @if ($itemCount == 0)
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-shopping-cart text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    {{ trans('cart.empty_cart') }}
                </h3>
                <p class="text-gray-600 mb-6">
                    {{ trans('cart.empty_cart_message') }}
                </p>
                <a href="{{ route('user.products.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    {{ trans('cart.continue_shopping') }}
                </a>
            </div>
        @else
            <!-- Cart Items -->
            <div class="space-y-4">
                @foreach ($cartItems as $index => $rawItem)
                    @php
                        // Handle mixed structure - some items might be wrapped in arrays
                        $item =
                            is_array($rawItem) && isset($rawItem[0]) && is_array($rawItem[0]) ? $rawItem[0] : $rawItem;
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                        <div class="flex items-start space-x-4">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <img src="{{ $item['product_image'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2NjY2NjYyIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjEyIiBmaWxsPSIjNjY2NjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UHJvZHVjdDwvdGV4dD48L3N2Zz4=' }}"
                                    alt="{{ $item['product_name'] ?? 'Product' }}"
                                    class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    {{ $item['product_name'] ?? 'Unknown Product' }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-2">
                                    {{ trans('cart.base_price') }}:
                                    <span class="font-medium">${{ number_format($item['base_price'] ?? 0, 2) }}</span>
                                </p>

                                <!-- Selected Options -->
                                @if (!empty($item['selected_options']))
                                    <div class="mb-3">
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">
                                            {{ trans('cart.selected_options') }}:
                                        </h4>
                                        <div class="space-y-1">
                                            @foreach ($item['selected_options'] as $optionName => $optionValue)
                                                <div class="text-sm text-gray-600">
                                                    <span class="font-medium">{{ $optionName }}:</span>
                                                    {{ $optionValue['value'] }}
                                                    @if ($optionValue['price_adjustment'] != 0)
                                                        <span
                                                            class="text-sm {{ $optionValue['price_adjustment'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            ({{ $optionValue['price_adjustment'] > 0 ? '+' : '' }}${{ number_format($optionValue['price_adjustment'], 2) }})
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Notes -->
                                @if (!empty($item['notes']))
                                    <div class="mb-3">
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">
                                            {{ trans('cart.notes') }}:
                                        </h4>
                                        <p class="text-sm text-gray-600 italic">{{ $item['notes'] }}</p>
                                    </div>
                                @endif

                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-3">
                                    <label class="text-sm font-medium text-gray-700">
                                        {{ trans('cart.quantity') }}:
                                    </label>
                                    <div class="flex items-center border border-gray-300 rounded-lg"
                                        wire:key="quantity-controls-{{ $item['product_id'] ?? 0 }}-{{ $item['quantity'] ?? 1 }}">
                                        <button
                                            wire:click="updateQuantity({{ $item['id'] ?? 0 }}, {{ max(1, ($item['quantity'] ?? 1) - 1) }})"
                                            class="px-3 py-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-l-lg {{ ($item['quantity'] ?? 1) <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ ($item['quantity'] ?? 1) <= 1 ? 'disabled' : '' }}>
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="px-4 py-1 text-center min-w-[3rem] font-medium">
                                            {{ $item['quantity'] ?? 1 }}
                                        </span>
                                        <button
                                            wire:click="updateQuantity({{ $item['id'] ?? 0 }}, {{ ($item['quantity'] ?? 1) + 1 }})"
                                            class="px-3 py-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-r-lg">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Selected Designs Display -->
                                @php
                                    // Force fresh query each time to avoid stale data after deletions
                                    $selectedDesigns = \App\Models\ProductDesign::where('user_id', auth()->id())
                                        ->where('product_id', $item['product_id'] ?? 0)
                                        ->with('design')
                                        ->orderBy('priority')
                                        ->get();
                                @endphp

                                @if ($selectedDesigns->count() > 0)
                                    <div class="mt-3">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                            <i class="fas fa-palette text-purple-600 mr-1"></i>
                                            {{ trans('cart.selected_designs', ['default' => 'Selected Designs']) }}
                                            ({{ $selectedDesigns->count() }})
                                        </h4>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            @foreach ($selectedDesigns as $productDesign)
                                                @if ($productDesign->design)
                                                    <div wire:key="design-{{ $productDesign->id }}-{{ $productDesign->product_id }}-{{ $productDesign->design_id }}"
                                                        class="flex items-center bg-purple-50 border border-purple-200 rounded-lg p-2 group hover:bg-purple-100 transition-colors">
                                                        <img src="{{ $productDesign->design->thumbnail_url ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjMwIiBoZWlnaHQ9IjMwIiBmaWxsPSIjY2NjY2NjIi8+PHRleHQgeD0iMTUiIHk9IjE1IiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iOCIgZmlsbD0iIzY2NjY2NiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkQ8L3RleHQ+PC9zdmc+' }}"
                                                            alt="{{ $productDesign->design->title }}"
                                                            class="w-8 h-8 object-cover rounded mr-2">
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs font-medium text-gray-900 truncate"
                                                                title="{{ $productDesign->design->title }}">
                                                                {{ $productDesign->design->title }}
                                                            </p>
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="text-xs text-purple-600 font-medium">{{ trans('cart.priority', ['default' => 'Priority']) }}
                                                                    {{ $productDesign->priority }}</span>
                                                                @if ($productDesign->notes)
                                                                    <span class="text-xs text-gray-500 ml-2">•
                                                                        {{ Str::limit($productDesign->notes, 20) }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <button
                                                            wire:click="removeDesignFromProduct({{ $productDesign->product_id }}, {{ $productDesign->design_id }})"
                                                            wire:confirm="{{ trans('cart.confirm_remove_design', ['default' => 'Are you sure you want to remove this design from :product?', 'product' => $item['product_name'] ?? trans('cart.this_product', ['default' => 'this product'])]) }}"
                                                            class="ml-2 p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors opacity-0 group-hover:opacity-100"
                                                            title="{{ trans('cart.remove_this_design', ['default' => 'Remove this design']) }}">
                                                            <i class="fas fa-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <a href="{{ route('user.product.designs', ['product' => $item['product_id'] ?? 0]) }}"
                                            class="inline-flex items-center text-xs text-purple-600 hover:text-purple-800 font-medium">
                                            <i class="fas fa-edit mr-1"></i>
                                            {{ trans('cart.edit_designs', ['default' => 'Edit Designs']) }}
                                        </a>
                                    </div>
                                @else
                                    <!-- Design Selection Button -->
                                    <div class="mt-3">
                                        <a href="{{ route('user.product.designs', ['product' => $item['product_id'] ?? 0]) }}"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-palette mr-2"></i>
                                            {{ trans('cart.select_designs', ['default' => 'Select Designs']) }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ trans('cart.select_designs_help', ['default' => 'Choose designs to inspire your product']) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Price and Actions -->
                            <div class="flex-shrink-0 text-right">
                                <div class="text-lg font-bold text-gray-900 mb-2">
                                    ${{ number_format($item['total_price'] ?? 0, 2) }}
                                </div>
                                <button wire:click="removeItem({{ $item['id'] ?? 0 }})"
                                    wire:confirm="{{ trans('cart.confirm_remove_item', ['default' => 'Are you sure you want to remove this item from your cart?', 'product' => $item['product_name'] ?? trans('cart.this_item', ['default' => 'this item'])]) }}"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                                    <i class="fas fa-trash mr-1"></i>
                                    {{ trans('cart.remove') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ trans('cart.order_summary') }}
                    </h3>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-gray-600">
                        <span>{{ trans('cart.subtotal') }}:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>{{ trans('cart.tax') }}:</span>
                        <span>$0.00</span>
                    </div>
                    <div class="border-t pt-2">
                        <div class="flex justify-between text-lg font-bold text-gray-900">
                            <span>{{ trans('cart.total') }}:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="checkout"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        {{ trans('cart.checkout') }}
                    </button>

                    <button wire:click="bookAppointment"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        {{ trans('cart.book_appointment') }}
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('user.products.index') }}"
                        class="text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>
                        {{ trans('cart.continue_shopping') }}
                    </a>
                </div>
            </div>

            <!-- Checkout Form Modal -->
            @if ($showCheckoutForm)
                <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                    wire:click="hideCheckout">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ trans('cart.checkout') }}
                            </h3>

                            <form wire:submit.prevent="createOrder">
                                <div class="space-y-4">
                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ trans('cart.phone') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" wire:model="checkoutData.phone"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="{{ trans('cart.phone_placeholder') }}">
                                        @error('checkoutData.phone')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Shipping Address -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ trans('cart.shipping_address') }}
                                        </label>
                                        <textarea wire:model="checkoutData.shipping_address" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="{{ trans('cart.shipping_address_placeholder') }}"></textarea>
                                        @error('checkoutData.shipping_address')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Billing Address -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ trans('cart.billing_address') }}
                                        </label>
                                        <textarea wire:model="checkoutData.billing_address" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="{{ trans('cart.billing_address_placeholder') }}"></textarea>
                                        @error('checkoutData.billing_address')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ trans('cart.notes') }}
                                        </label>
                                        <textarea wire:model="checkoutData.notes" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="{{ trans('cart.order_notes_placeholder') }}"></textarea>
                                        @error('checkoutData.notes')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-6">
                                    <button type="button" wire:click="hideCheckout"
                                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                                        {{ trans('cart.cancel') }}
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        {{ trans('cart.create_order') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- JavaScript for localStorage integration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('ShoppingCart JavaScript loaded');

            // Flag to prevent multiple cart loads
            let cartLoaded = false;

            // Load cart from localStorage
            // Function to normalize cart data structure
            window.normalizeCartData = function(cartData) {
                if (!cartData.items) cartData.items = [];

                // Normalize items - ensure all items are direct objects, not wrapped in arrays
                cartData.items = cartData.items.map(item => {
                    // If item is wrapped in an array, extract the first element
                    if (Array.isArray(item) && item.length > 0 && typeof item[0] === 'object') {
                        return item[0];
                    }
                    return item;
                });

                // Recalculate totals
                cartData.itemCount = cartData.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
                cartData.total = cartData.items.reduce((sum, item) => sum + (item.total_price || 0), 0);

                return cartData;
            };

            window.loadCartFromStorage = function() {
                // Prevent multiple loads
                if (cartLoaded) {
                    console.log('Cart already loaded, skipping...');
                    return;
                }

                let cartData = JSON.parse(localStorage.getItem('shopping_cart') ||
                    '{"items": [], "total": 0, "itemCount": 0}');

                // Fix null values
                if (cartData.total === null) cartData.total = 0;
                if (cartData.itemCount === null) cartData.itemCount = 0;
                if (!cartData.items) cartData.items = [];

                // Normalize the cart data structure
                cartData = window.normalizeCartData(cartData);

                console.log('Loading cart from storage (normalized):', cartData);

                // Try multiple methods to update the cart
                try {
                    // Method 1: Direct Livewire call
                    @this.updateCart(cartData);
                    cartLoaded = true; // Mark as loaded
                } catch (e) {
                    console.log('Method 1 failed:', e);
                    try {
                        // Method 2: Using $wire
                        $wire.updateCart(cartData);
                        cartLoaded = true; // Mark as loaded
                    } catch (e2) {
                        console.log('Method 2 failed:', e2);
                        // Method 3: Using Livewire.find
                        const component = document.querySelector('[wire\\:id]');
                        if (component) {
                            const id = component.getAttribute('wire:id');
                            Livewire.find(id).updateCart(cartData);
                            cartLoaded = true; // Mark as loaded
                        }
                    }
                }
            };

            // Remove item from cart
            window.removeFromCart = function(productId) {
                let cartData = JSON.parse(localStorage.getItem('shopping_cart') ||
                    '{"items": [], "total": 0, "itemCount": 0}');

                // Normalize cart data first
                cartData = window.normalizeCartData(cartData);

                cartData.items = cartData.items.filter(item => item.product_id != productId);
                cartData.itemCount = cartData.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
                cartData.total = cartData.items.reduce((sum, item) => sum + (item.total_price || 0), 0);
                localStorage.setItem('shopping_cart', JSON.stringify(cartData));
                @this.updateCart(cartData);
                // Dispatch event to update header cart count
                document.dispatchEvent(new CustomEvent('cartUpdated'));
                // Reset flag to allow reloading
                cartLoaded = false;
            };

            // Update quantity in cart
            window.updateCartQuantity = function(productId, quantity) {
                let cartData = JSON.parse(localStorage.getItem('shopping_cart') ||
                    '{"items": [], "total": 0, "itemCount": 0}');

                // Normalize cart data first
                cartData = window.normalizeCartData(cartData);

                const itemIndex = cartData.items.findIndex(item => item.product_id == productId);

                if (itemIndex !== -1) {
                    if (quantity <= 0) {
                        cartData.items.splice(itemIndex, 1);
                    } else {
                        cartData.items[itemIndex].quantity = quantity;
                        cartData.items[itemIndex].total_price = cartData.items[itemIndex].unit_price * quantity;
                    }
                }

                cartData.itemCount = cartData.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
                cartData.total = cartData.items.reduce((sum, item) => sum + (item.total_price || 0), 0);
                localStorage.setItem('shopping_cart', JSON.stringify(cartData));
                @this.updateCart(cartData);
                // Dispatch event to update header cart count
                document.dispatchEvent(new CustomEvent('cartUpdated'));
                // Reset flag to allow reloading
                cartLoaded = false;
            };

            // Clear cart
            window.clearCart = function() {
                localStorage.removeItem('shopping_cart');
                @this.updateCart({
                    "items": [],
                    "total": 0,
                    "itemCount": 0
                });
                // Dispatch event to update header cart count
                document.dispatchEvent(new CustomEvent('cartUpdated'));
                // Reset flag to allow reloading
                cartLoaded = false;
            };

            // Note: Removed cartUpdated listener to prevent duplicate updates
            // The cart is only updated when user is on the cart page

            // Listen for clear cart event
            Livewire.on('clearCart', () => {
                window.clearCart();
            });

            // Listen for remove item event
            Livewire.on('removeFromCart', (event) => {
                const [productId] = event;
                window.removeFromCart(productId);
            });

            // Listen for update quantity event
            Livewire.on('updateCartQuantity', (event) => {
                const [productId, quantity] = event;
                window.updateCartQuantity(productId, quantity);

                // Force a small delay to ensure localStorage is updated before refresh
                setTimeout(() => {
                    @this.$refresh();
                }, 100);
            });

            // Use event delegation for remove buttons to handle dynamically added elements
            document.addEventListener('click', function(e) {
                if (e.target.closest('button[wire\\:click*="removeItem"]')) {
                    const button = e.target.closest('button[wire\\:click*="removeItem"]');
                    const wireClick = button.getAttribute('wire:click');
                    const productId = wireClick.match(/removeItem\((\d+)\)/)?.[1];
                    if (productId) {
                        window.removeFromCart(productId);
                    }
                }
            });

            // Clean up existing corrupted cart data
            window.cleanupCartData = function() {
                let cartData = JSON.parse(localStorage.getItem('shopping_cart') ||
                    '{"items": [], "total": 0, "itemCount": 0}');
                const normalizedData = window.normalizeCartData(cartData);
                localStorage.setItem('shopping_cart', JSON.stringify(normalizedData));
                console.log('Cart data cleaned up:', normalizedData);
                return normalizedData;
            };

            // Initial load with delay to ensure component is ready
            setTimeout(() => {
                console.log('Initial cart load...');
                // Clean up any corrupted data first
                window.cleanupCartData();
                loadCartFromStorage();
            }, 100);

            // Also try loading when Livewire is ready
            document.addEventListener('livewire:initialized', () => {
                console.log('Livewire initialized, loading cart...');
                loadCartFromStorage();
            });

            // Test function for manual testing
            window.testLoadCart = function() {
                console.log('Manual test load cart...');
                loadCartFromStorage();
            };

            // Test function to manually set cart data
            window.testSetCart = function() {
                console.log('Testing manual cart set...');
                const testData = {
                    items: [{
                        product_id: 1,
                        product_name: 'Test Product',
                        product_image: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y3ZjdmNyIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UHJvZHVjdDwvdGV4dD48L3N2Zz4=',
                        base_price: 10,
                        unit_price: 10,
                        quantity: 1,
                        total_price: 10,
                        selected_options: {},
                        notes: 'Test'
                    }],
                    total: 10,
                    itemCount: 1
                };

                try {
                    @this.updateCart(testData);
                    console.log('Manual cart set successful');
                } catch (e) {
                    console.log('Manual cart set failed:', e);
                }
            };

            // Listen for design removal events (optional - the $refresh above should be enough)
            document.addEventListener('livewire:init', () => {
                Livewire.on('design-removed', (event) => {
                    // The $refresh dispatch above should handle the refresh
                });
            });
        });
    </script>

    <!-- Database Cart JavaScript (New Implementation) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Database Cart JavaScript loaded');

            // Dispatch event to update header cart count
            window.updateCartCount = function() {
                document.dispatchEvent(new CustomEvent('cartUpdated'));
            };

            // Listen for cart updates
            document.addEventListener('cartUpdated', function() {
                console.log('Cart updated event received - Database Cart Mode');
            });
        });
    </script>
</div>
