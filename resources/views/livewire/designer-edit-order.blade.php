<div class="designer-edit-order-wrapper">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        <i class="fas fa-edit me-2" style="color: #ffde9f;"></i>
                        Edit Order
                    </h1>
                    <p class="mt-1" style="color: #ffde9f;">
                        Editing order for appointment #{{ $appointment->id }} - {{ $appointment->user->full_name }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('designer.appointments.show', $appointment) }}"
                        class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        <span class="hidden sm:block">Back to Appointment</span>
                    </a>
                    <div class="hidden md:block">
                        <i class="fas fa-shopping-cart text-4xl" style="color: #ffde9f;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                <div class="p-2 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
                <button type="button" class="text-green-600 hover:text-green-800"
                    onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Debug Info -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <p class="text-yellow-800">Debug: showEditOptionsModal = {{ $showEditOptionsModal ? 'true' : 'false' }}</p>
            <p class="text-yellow-800">Debug: editingItemIndex = {{ $editingItemIndex ?? 'null' }}</p>
            <p class="text-yellow-800">Debug: isSavingOptions = {{ $isSavingOptions ? 'true' : 'false' }}</p>
        </div>

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
                <div class="p-2 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
                <button type="button" class="text-red-600 hover:text-red-800"
                    onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Toast Notification -->
        <div id="toast" class="fixed top-4 right-4 z-50 hidden">
            <div
                class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in">
                <div class="p-1 rounded-full bg-green-400">
                    <i class="fas fa-check text-sm"></i>
                </div>
                <div>
                    <p class="font-medium" id="toast-message">Product added successfully!</p>
                </div>
                <button type="button" class="text-green-200 hover:text-white ml-4" onclick="hideToast()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Current Order Items -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                                <i class="fas fa-shopping-cart text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                                <p class="text-sm text-gray-500">{{ $this->cartItemsCount }} items in order</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium"
                            style="background-color: #ffde9f; color: #2a1e1e;">
                            {{ $this->cartItemsCount }} items
                        </span>
                    </div>
                    @if (count($cartItems) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Product</th>
                                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-32">Quantity</th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700 w-24">Unit Price
                                        </th>
                                        <th class="text-right py-3 px-4 font-semibold text-gray-700 w-24">Total</th>
                                        <th class="text-center py-3 px-4 font-semibold text-gray-700 w-24">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $index => $item)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                            <td class="py-4 px-4">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <strong
                                                            class="text-gray-900">{{ $item['product_name'] }}</strong>
                                                        @if ($item['is_existing'])
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Existing</span>
                                                        @else
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">New</span>
                                                        @endif
                                                    </div>

                                                    @if (!empty($item['option_details']))
                                                        <div class="mt-2">
                                                            <p class="text-xs font-medium text-gray-600 mb-1">Selected
                                                                Options:</p>
                                                            <ul class="space-y-1">
                                                                @foreach ($item['option_details'] as $option)
                                                                    <li
                                                                        class="text-sm text-gray-600 flex items-center gap-1">
                                                                        <i
                                                                            class="fas fa-check text-green-500 text-xs"></i>
                                                                        {{ $option['option_name'] }}:
                                                                        {{ $option['value'] }}
                                                                        @if ($option['price_adjustment'] != 0)
                                                                            <span class="text-green-600 font-medium">
                                                                                ({{ $option['price_adjustment'] > 0 ? '+' : '' }}${{ number_format($option['price_adjustment'], 2) }})
                                                                            </span>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    @if ($item['notes'])
                                                        <div class="mt-2">
                                                            <p class="text-xs font-medium text-gray-600 mb-1">Notes:</p>
                                                            <p class="text-sm text-gray-600">{{ $item['notes'] }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                <input type="number"
                                                    class="w-20 px-2 py-1 border border-gray-300 rounded-md text-center text-gray-900 font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    wire:model.live="cartItems.{{ $index }}.quantity"
                                                    wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                    min="1">
                                            </td>
                                            <td class="py-4 px-4 text-right font-medium text-gray-900">
                                                ${{ number_format($item['unit_price'], 2) }}
                                            </td>
                                            <td class="py-4 px-4 text-right">
                                                <span class="font-semibold text-green-600">
                                                    ${{ number_format($item['total_price'], 2) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <button type="button"
                                                        class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors"
                                                        wire:click="editItemOptions({{ $index }})"
                                                        title="Edit Options">
                                                        <i class="fas fa-cog text-sm"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors"
                                                        wire:click="removeFromCart({{ $index }})"
                                                        onclick="return confirm('Remove this item from the order?')"
                                                        title="Remove Item">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Order Notes -->
                        <div class="mt-6">
                            <label for="orderNotes" class="block text-sm font-medium text-gray-700 mb-2">Order
                                Notes</label>
                            <textarea
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                id="orderNotes" wire:model="orderNotes" rows="3" placeholder="Add any notes about this order..."></textarea>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="p-4 rounded-full bg-gray-100 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-2xl text-gray-400"></i>
                            </div>
                            <h5 class="text-lg font-medium text-gray-900 mb-2">No items in order</h5>
                            <p class="text-gray-500">Add products to this order using the "Add Products" section below.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Popular Products Quick Add -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Popular Products - Quick Add</h3>
                            <p class="text-sm text-gray-500">Quickly add popular products to the order</p>
                        </div>
                    </div>

                    @php
                        $popularProducts = \App\Models\Product::where('is_active', true)
                            ->whereIn('id', [1, 2, 3, 4, 5]) // You can adjust this to get actual popular products
                            ->take(4)
                            ->get();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($popularProducts as $product)
                            <div
                                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h6 class="font-medium text-gray-900 mb-1">{{ $product->name }}</h6>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ Str::limit($product->description, 80) }}</p>
                                        <span
                                            class="font-semibold text-green-600">${{ number_format($product->base_price, 2) }}</span>
                                    </div>
                                    <button type="button"
                                        class="ml-3 p-2 rounded-md text-white hover:opacity-90 transition-opacity"
                                        style="background-color: #2a1e1e;"
                                        wire:click="quickAddProduct({{ $product->id }})" title="Quick Add">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Add Products Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-3 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                                    <i class="fas fa-plus text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Browse All Products</h3>
                                    <p class="text-sm text-gray-500">Search and add products to the order</p>
                                </div>
                            </div>
                            <button type="button"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                wire:click="toggleAddProducts">
                                @if ($showAddProducts)
                                    <i class="fas fa-eye-slash mr-2"></i>Hide
                                @else
                                    <i class="fas fa-eye mr-2"></i>Show
                                @endif
                            </button>
                        </div>
                    </div>
                    @if ($showAddProducts)
                        <div class="p-6">
                            <!-- Search and Filter -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
                                <div class="md:col-span-5">
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        wire:model.live="search" placeholder="Search products...">
                                </div>
                                <div class="md:col-span-4">
                                    <select
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        wire:model.live="category_filter">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <button type="button"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                        wire:click="$set('search', '')">
                                        <i class="fas fa-times mr-2"></i>Clear
                                    </button>
                                </div>
                            </div>

                            <!-- Products Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @forelse ($products as $product)
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <!-- Product Image -->
                                        <div class="text-center p-4">
                                            <img src="{{ $product->image ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgZmlsbD0iI2NjY2NjYyIvPjx0ZXh0IHg9IjYwIiB5PSI2MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE0IiBmaWxsPSIjNjY2NjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+' }}"
                                                alt="{{ $product->name }}" class="mx-auto rounded-lg"
                                                style="max-height: 120px; width: auto; object-fit: cover;">
                                        </div>
                                        <div class="p-4">
                                            <h6 class="font-medium text-gray-900 mb-2">{{ $product->name }}</h6>
                                            <p class="text-sm text-gray-600 mb-3">
                                                {{ Str::limit($product->description, 100) }}
                                            </p>
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-green-600">
                                                    ${{ number_format($product->base_price, 2) }}
                                                </span>
                                                <div class="flex gap-2">
                                                    <button type="button"
                                                        class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors"
                                                        wire:click="quickAddProduct({{ $product->id }})"
                                                        title="Quick Add with Options">
                                                        <i class="fas fa-bolt text-sm"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="p-2 text-white hover:opacity-90 rounded-md transition-opacity"
                                                        style="background-color: #2a1e1e;"
                                                        wire:click="addToCart({{ $product->id }})"
                                                        title="Add with Default Options">
                                                        <i class="fas fa-plus text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full">
                                        <div class="text-center py-12">
                                            <div
                                                class="p-4 rounded-full bg-gray-100 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                                <i class="fas fa-search text-2xl text-gray-400"></i>
                                            </div>
                                            <h6 class="text-lg font-medium text-gray-900 mb-2">No products found</h6>
                                            <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6 flex justify-center">
                                {{ $products->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                            <i class="fas fa-calculator text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
                            <p class="text-sm text-gray-500">Order totals and breakdown</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Items:</span>
                            <span class="font-medium text-gray-900">{{ $this->cartItemsCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium text-gray-900">${{ number_format($this->cartTotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tax (15%):</span>
                            <span
                                class="font-medium text-gray-900">${{ number_format($this->cartTotal * 0.15, 2) }}</span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span
                                class="text-lg font-bold text-green-600">${{ number_format($this->cartTotal * 1.15, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                            <i class="fas fa-cogs text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                            <p class="text-sm text-gray-500">Save or cancel changes</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button type="button"
                            class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center justify-center gap-2"
                            wire:click="saveOrder" @if (count($cartItems) == 0) disabled @endif>
                            <i class="fas fa-save"></i>
                            Save Order Changes
                        </button>

                        <a href="{{ route('designer.appointments.show', $appointment) }}"
                            class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>

                    @if (count($cartItems) == 0)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                <p class="text-sm text-yellow-800">Add at least one product to save the order.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Client Info -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                            <i class="fas fa-user text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Client Information</h3>
                            <p class="text-sm text-gray-500">Appointment client details</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Name</p>
                            <p class="text-gray-900">{{ $appointment->user->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Email</p>
                            <a href="mailto:{{ $appointment->user->email }}"
                                class="text-blue-600 hover:text-blue-800 transition-colors">
                                {{ $appointment->user->email }}
                            </a>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Phone</p>
                            <a href="tel:{{ $appointment->user->phone }}"
                                class="text-blue-600 hover:text-blue-800 transition-colors">
                                {{ $appointment->user->phone }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Options Modal -->
    @if ($showEditOptionsModal)
        <div x-data="{ show: true }" x-show="show" x-cloak x-init="$watch('show', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        });"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @if (!$isSavingOptions) wire:click="closeEditOptionsModal" @endif
                @if ($isSavingOptions) style="cursor: not-allowed;" @endif>
            </div>

            <!-- Modal -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                    <!-- Header -->
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Edit Product Options
                                </h3>
                            </div>
                            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                style="{{ $isSavingOptions ? 'opacity: 0.5; cursor: not-allowed;' : '' }}"
                                wire:click="closeEditOptionsModal" @if ($isSavingOptions) disabled @endif>
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="bg-white px-6 py-6">
                        @if ($editingItemIndex !== null && isset($cartItems[$editingItemIndex]))
                            @php $item = $cartItems[$editingItemIndex]; @endphp

                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <h6 class="font-semibold text-gray-900 mb-1">{{ $item['product_name'] }}</h6>
                                <p class="text-sm text-gray-600">Current Unit Price: <span
                                        class="font-medium text-green-600">${{ number_format($item['unit_price'], 2) }}</span>
                                </p>
                            </div>

                            @php
                                $product = \App\Models\Product::with(['options.values'])->find($item['product_id']);
                            @endphp

                            @if ($product && $product->options->count() > 0)
                                <div class="option-grid">
                                    @foreach ($product->options as $option)
                                        <div class="option-item">
                                            <label class="block text-sm font-semibold text-gray-800 mb-3">
                                                {{ $option->name }}
                                                @if ($option->is_required)
                                                    <span class="text-red-500 ml-1">*</span>
                                                @endif
                                            </label>

                                            <div class="space-y-3">
                                                @foreach ($option->values as $value)
                                                    <div
                                                        class="flex items-center p-2 rounded-md hover:bg-gray-50 transition-colors">
                                                        <input
                                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 mr-3"
                                                            type="radio" name="option_{{ $option->id }}"
                                                            value="{{ $value->id }}"
                                                            id="option_{{ $option->id }}_{{ $value->id }}"
                                                            wire:model="editingOptions.{{ $option->id }}"
                                                            @if (in_array($value->id, $item['options'] ?? [])) checked @endif>
                                                        <label class="flex-1 text-sm text-gray-700 cursor-pointer"
                                                            for="option_{{ $option->id }}_{{ $value->id }}">
                                                            <span class="font-medium">{{ $value->value }}</span>
                                                            @if ($value->price_adjustment != 0)
                                                                <span class="text-green-600 font-semibold ml-2">
                                                                    ({{ $value->price_adjustment > 0 ? '+' : '' }}${{ number_format($value->price_adjustment, 2) }})
                                                                </span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                        <p class="text-blue-800">This product has no customizable options.</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-end gap-3">
                            <button type="button"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors"
                                style="{{ $isSavingOptions ? 'opacity: 0.5; cursor: not-allowed;' : '' }}"
                                wire:click="closeEditOptionsModal" @if ($isSavingOptions) disabled @endif>
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="button"
                                class="px-4 py-2 text-white rounded-lg font-medium transition-opacity flex items-center justify-center gap-2"
                                style="background-color: {{ $isSavingOptions ? '#6b7280' : '#2a1e1e' }}; {{ $isSavingOptions ? 'cursor: not-allowed;' : '' }}"
                                wire:click="saveItemOptions" @if ($isSavingOptions) disabled @endif>
                                @if ($isSavingOptions)
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Saving...
                                @else
                                    <i class="fas fa-save"></i>
                                    Save Options
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    @endif

    <!-- Quick Add Product Modal -->
    @if ($showQuickAddModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeQuickAdd"></div>

            <!-- Modal -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                    <!-- Header -->
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Quick Add Product
                                </h3>
                            </div>
                            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                                wire:click="closeQuickAdd">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="bg-white px-6 py-6">
                        @if ($quickAddProductId)
                            @php $quickProduct = \App\Models\Product::with(['options.values'])->find($quickAddProductId); @endphp

                            @if ($quickProduct)
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                    <h6 class="font-semibold text-gray-900 mb-2">{{ $quickProduct->name }}</h6>
                                    <p class="text-sm text-gray-600 mb-3">{{ $quickProduct->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-lg font-bold text-green-600">${{ number_format($quickProduct->base_price, 2) }}</span>
                                        <div class="flex items-center gap-2">
                                            <label for="quickQuantity"
                                                class="text-sm font-medium text-gray-700">Quantity:</label>
                                            <input type="number" id="quickQuantity"
                                                class="w-20 px-2 py-1 border border-gray-300 rounded-md text-center text-gray-900 font-medium focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                wire:model="quickAddQuantity" min="1" max="50">
                                        </div>
                                    </div>
                                </div>

                                @if ($quickProduct->options->count() > 0)
                                    <h6 class="text-lg font-semibold text-gray-900 mb-4">Product Options</h6>
                                    <div class="option-grid">
                                        @foreach ($quickProduct->options as $option)
                                            <div class="option-item">
                                                <label class="block text-sm font-semibold text-gray-800 mb-3">
                                                    {{ $option->name }}
                                                    @if ($option->is_required)
                                                        <span class="text-red-500 ml-1">*</span>
                                                    @endif
                                                </label>

                                                <div class="space-y-3">
                                                    @foreach ($option->values as $value)
                                                        <div
                                                            class="flex items-center p-2 rounded-md hover:bg-gray-50 transition-colors">
                                                            <input
                                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 mr-3"
                                                                type="radio"
                                                                name="quick_option_{{ $option->id }}"
                                                                value="{{ $value->id }}"
                                                                id="quick_option_{{ $option->id }}_{{ $value->id }}"
                                                                wire:model="quickAddOptions.{{ $option->id }}">
                                                            <label class="flex-1 text-sm text-gray-700 cursor-pointer"
                                                                for="quick_option_{{ $option->id }}_{{ $value->id }}">
                                                                <span class="font-medium">{{ $value->value }}</span>
                                                                @if ($value->price_adjustment != 0)
                                                                    <span class="text-green-600 font-semibold ml-2">
                                                                        ({{ $value->price_adjustment > 0 ? '+' : '' }}${{ number_format($value->price_adjustment, 2) }})
                                                                    </span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-end gap-3">
                            <button type="button"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors"
                                wire:click="closeQuickAdd">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="button"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors"
                                wire:click="confirmQuickAdd">
                                <i class="fas fa-plus mr-2"></i>Add to Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Inline Styles -->
    <style>
        .hover-shadow:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.2s ease;
        }

        /* Fix hover text visibility */
        .btn:hover {
            color: white !important;
        }

        .btn-outline-primary:hover {
            background-color: #2a1e1e !important;
            border-color: #2a1e1e !important;
            color: white !important;
        }

        .btn-outline-secondary:hover {
            background-color: #6b7280 !important;
            border-color: #6b7280 !important;
            color: white !important;
        }

        .btn-outline-danger:hover {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            color: white !important;
        }

        /* Enhanced button hover effects */
        button[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #1f2937;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 4px;
        }

        button[title] {
            position: relative;
        }

        /* Custom focus states */
        .focus\:ring-blue-500:focus {
            --tw-ring-color: #2a1e1e !important;
        }

        .focus\:border-transparent:focus {
            border-color: #2a1e1e !important;
        }

        /* Table row hover effects */
        .table-hover tbody tr:hover {
            background-color: #f9fafb !important;
        }

        /* Card hover effects */
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            transition: box-shadow 0.2s ease;
        }

        /* Alpine.js modal improvements */
        [x-cloak] {
            display: none !important;
        }

        /* Radio button styling improvements */
        input[type="radio"] {
            accent-color: #2a1e1e !important;
        }

        input[type="radio"]:checked {
            background-color: #2a1e1e !important;
            border-color: #2a1e1e !important;
        }

        /* Modal body spacing */
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Option grid improvements */
        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .option-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .option-item:hover {
            background-color: #e9ecef;
            border-color: #2a1e1e;
        }

        /* Toast Animation */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
    </style>

    <!-- Inline Scripts -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Listen for Livewire success messages and show toast
            Livewire.on('product-added', (message) => {
                showToast(message);
            });
        });

        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');

            toastMessage.textContent = message;
            toast.classList.remove('hidden');

            // Auto hide after 4 seconds
            setTimeout(() => {
                hideToast();
            }, 4000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.add('hidden');
        }

        // Show toast when success flash message appears
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.bg-green-50');
            if (successMessage && successMessage.textContent.includes('Added') && successMessage.textContent
                .includes('to order successfully')) {
                showToast(successMessage.textContent.trim());
            }
        });
    </script>
</div>
