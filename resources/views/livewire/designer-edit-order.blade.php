<div class="designer-edit-order-wrapper">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-edit text-primary me-2"></i>
                            Edit Order
                        </h2>
                        <p class="text-muted mb-0">
                            Editing order for appointment #{{ $appointment->id }} - {{ $appointment->user->full_name }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('designer.appointments.show', $appointment) }}"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Order Items Section -->
            <div class="col-lg-8">
                <!-- Current Order Items -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Order Items
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $this->cartItemsCount }} items
                        </span>
                    </div>
                    <div class="card-body">
                        @if (count($cartItems) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th width="120">Quantity</th>
                                            <th width="100">Unit Price</th>
                                            <th width="100">Total</th>
                                            <th width="100">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $index => $item)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong>{{ $item['product_name'] }}</strong>

                                                        @if (!empty($item['option_details']))
                                                            <div class="mt-2">
                                                                <small class="text-muted fw-bold">Selected
                                                                    Options:</small>
                                                                <ul class="list-unstyled mb-0 mt-1">
                                                                    @foreach ($item['option_details'] as $option)
                                                                        <li class="small text-muted">
                                                                            <i
                                                                                class="fas fa-check text-success me-1"></i>
                                                                            {{ $option['option_name'] }}:
                                                                            {{ $option['value'] }}
                                                                            @if ($option['price_adjustment'] != 0)
                                                                                <span class="text-success fw-bold">
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
                                                                <small class="text-muted fw-bold">Notes:</small>
                                                                <br><small
                                                                    class="text-muted">{{ $item['notes'] }}</small>
                                                            </div>
                                                        @endif

                                                        @if ($item['is_existing'])
                                                            <span class="badge bg-info ms-2">Existing</span>
                                                        @else
                                                            <span class="badge bg-success ms-2">New</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm"
                                                        wire:model.live="cartItems.{{ $index }}.quantity"
                                                        wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                        min="1">
                                                </td>
                                                <td>${{ number_format($item['unit_price'], 2) }}</td>
                                                <td>
                                                    <strong class="text-success">
                                                        ${{ number_format($item['total_price'], 2) }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            wire:click="editItemOptions({{ $index }})"
                                                            title="Edit Options">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            wire:click="removeFromCart({{ $index }})"
                                                            onclick="return confirm('Remove this item from the order?')"
                                                            title="Remove Item">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Order Notes -->
                            <div class="mt-4">
                                <label for="orderNotes" class="form-label fw-bold">Order Notes</label>
                                <textarea class="form-control" id="orderNotes" wire:model="orderNotes" rows="3"
                                    placeholder="Add any notes about this order..."></textarea>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No items in order</h5>
                                <p class="text-muted">Add products to this order using the "Add Products" section below.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Popular Products Quick Add -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-star me-2"></i>
                            Popular Products - Quick Add
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $popularProducts = \App\Models\Product::where('is_active', true)
                                ->whereIn('id', [1, 2, 3, 4, 5]) // You can adjust this to get actual popular products
                                ->take(4)
                                ->get();
                        @endphp
                        
                        <div class="row g-3">
                            @foreach($popularProducts as $product)
                                <div class="col-md-6">
                                    <div class="border rounded p-3 hover-shadow">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $product->name }}</h6>
                                                <p class="text-muted small mb-1">{{ Str::limit($product->description, 80) }}</p>
                                                <span class="fw-bold text-success">${{ number_format($product->base_price, 2) }}</span>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm ms-2" 
                                                    wire:click="quickAddProduct({{ $product->id }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Add Products Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-plus me-2"></i>
                                Browse All Products
                            </h5>
                            <button type="button" class="btn btn-light btn-sm" wire:click="toggleAddProducts">
                                @if ($showAddProducts)
                                    <i class="fas fa-eye-slash me-1"></i>Hide
                                @else
                                    <i class="fas fa-eye me-1"></i>Show
                                @endif
                            </button>
                        </div>
                    </div>
                    @if ($showAddProducts)
                        <div class="card-body">
                            <!-- Search and Filter -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" wire:model.live="search"
                                        placeholder="Search products...">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" wire:model.live="category_filter">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-secondary w-100"
                                        wire:click="$set('search', '')">
                                        Clear
                                    </button>
                                </div>
                            </div>

                            <!-- Products Grid -->
                            <div class="row g-3">
                                @forelse ($products as $product)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <!-- Product Image -->
                                            <div class="text-center p-3">
                                                <img src="{{ $product->image ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgZmlsbD0iI2NjY2NjYyIvPjx0ZXh0IHg9IjYwIiB5PSI2MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE0IiBmaWxsPSIjNjY2NjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+' }}"
                                                    alt="{{ $product->name }}" class="img-fluid rounded"
                                                    style="max-height: 120px; width: auto; object-fit: cover;">
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $product->name }}</h6>
                                                <p class="card-text text-muted small">
                                                    {{ Str::limit($product->description, 100) }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-success">
                                                        ${{ number_format($product->base_price, 2) }}
                                                    </span>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            wire:click="quickAddProduct({{ $product->id }})"
                                                            title="Quick Add with Options">
                                                            <i class="fas fa-bolt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            wire:click="addToCart({{ $product->id }})"
                                                            title="Add with Default Options">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-4">
                                            <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                            <h6 class="text-muted">No products found</h6>
                                            <p class="text-muted">Try adjusting your search or filter criteria.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $products->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items:</span>
                            <span>{{ $this->cartItemsCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($this->cartTotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (15%):</span>
                            <span>${{ number_format($this->cartTotal * 0.15, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-success">${{ number_format($this->cartTotal * 1.15, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" wire:click="saveOrder"
                                @if (count($cartItems) == 0) disabled @endif>
                                <i class="fas fa-save me-2"></i>Save Order Changes
                            </button>

                            <a href="{{ route('designer.appointments.show', $appointment) }}"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>

                        @if (count($cartItems) == 0)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small>Add at least one product to save the order.</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Client Info -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>
                            Client Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Name:</strong><br>
                            {{ $appointment->user->full_name }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $appointment->user->email }}" class="text-decoration-none">
                                {{ $appointment->user->email }}
                            </a>
                        </div>
                        <div class="mb-0">
                            <strong>Phone:</strong><br>
                            <a href="tel:{{ $appointment->user->phone }}" class="text-decoration-none">
                                {{ $appointment->user->phone }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Options Modal -->
    <div class="modal fade" id="editOptionsModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-cog me-2"></i>
                        Edit Product Options
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($editingItemIndex !== null && isset($cartItems[$editingItemIndex]))
                        @php $item = $cartItems[$editingItemIndex]; @endphp

                        <div class="mb-3">
                            <h6 class="fw-bold">{{ $item['product_name'] }}</h6>
                            <p class="text-muted mb-0">Current Unit Price:
                                ${{ number_format($item['unit_price'], 2) }}</p>
                        </div>

                        @php
                            $product = \App\Models\Product::with(['options.values'])->find($item['product_id']);
                        @endphp

                        @if ($product && $product->options->count() > 0)
                            <div class="row">
                                @foreach ($product->options as $option)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">{{ $option->name }}</label>
                                        @if ($option->is_required)
                                            <span class="text-danger">*</span>
                                        @endif

                                        <div class="mt-2">
                                            @foreach ($option->values as $value)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="option_{{ $option->id }}"
                                                        value="{{ $value->id }}"
                                                        id="option_{{ $option->id }}_{{ $value->id }}"
                                                        wire:model="editingOptions.{{ $option->id }}"
                                                        @if (in_array($value->id, $item['options'] ?? [])) checked @endif>
                                                    <label class="form-check-label"
                                                        for="option_{{ $option->id }}_{{ $value->id }}">
                                                        {{ $value->value }}
                                                        @if ($value->price_adjustment != 0)
                                                            <span class="text-success fw-bold">
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
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                This product has no customizable options.
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="saveItemOptions">
                        <i class="fas fa-save me-2"></i>Save Options
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Product Modal -->
    <div class="modal fade" id="quickAddModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        Quick Add Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeQuickAdd"></button>
                </div>
                <div class="modal-body">
                    @if($quickAddProductId)
                        @php $quickProduct = \App\Models\Product::with(['options.values'])->find($quickAddProductId); @endphp
                        
                        @if($quickProduct)
                            <div class="mb-4">
                                <h6 class="fw-bold">{{ $quickProduct->name }}</h6>
                                <p class="text-muted">{{ $quickProduct->description }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="fw-bold text-success fs-5">${{ number_format($quickProduct->base_price, 2) }}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <label for="quickQuantity" class="form-label mb-0">Quantity:</label>
                                        <input type="number" id="quickQuantity" class="form-control form-control-sm" 
                                               style="width: 80px;" wire:model="quickAddQuantity" min="1" max="50">
                                    </div>
                                </div>
                            </div>

                            @if($quickProduct->options->count() > 0)
                                <h6 class="fw-bold mb-3">Product Options</h6>
                                <div class="row">
                                    @foreach($quickProduct->options as $option)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                {{ $option->name }}
                                                @if($option->is_required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            
                                            <div class="mt-2">
                                                @foreach($option->values as $value)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" 
                                                               name="quick_option_{{ $option->id }}"
                                                               value="{{ $value->id }}"
                                                               id="quick_option_{{ $option->id }}_{{ $value->id }}"
                                                               wire:model="quickAddOptions.{{ $option->id }}">
                                                        <label class="form-check-label" 
                                                               for="quick_option_{{ $option->id }}_{{ $value->id }}">
                                                            {{ $value->value }}
                                                            @if($value->price_adjustment != 0)
                                                                <span class="text-success fw-bold">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeQuickAdd">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="confirmQuickAdd">
                        <i class="fas fa-plus me-2"></i>Add to Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Styles -->
    <style>
    .hover-shadow:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: box-shadow 0.2s ease;
    }
    </style>

    <!-- Inline Scripts -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showModal', (modalId) => {
                const modal = new bootstrap.Modal(document.getElementById(modalId[0]));
                modal.show();
            });

            Livewire.on('hideModal', (modalId) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId[0]));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>
