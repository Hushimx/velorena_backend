<div>
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
                                                        @if ($item['notes'])
                                                            <br><small class="text-muted">{{ $item['notes'] }}</small>
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
                                                        wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                        value="{{ $item['quantity'] }}" min="1">
                                                </td>
                                                <td>${{ number_format($item['unit_price'], 2) }}</td>
                                                <td>
                                                    <strong class="text-success">
                                                        ${{ number_format($item['total_price'], 2) }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        wire:click="removeFromCart({{ $index }})"
                                                        onclick="return confirm('Remove this item from the order?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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

                <!-- Add Products Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-plus me-2"></i>
                                Add Products
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
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $product->name }}</h6>
                                                <p class="card-text text-muted small">
                                                    {{ Str::limit($product->description, 100) }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-success">
                                                        ${{ number_format($product->base_price, 2) }}
                                                    </span>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        wire:click="addToCart({{ $product->id }})">
                                                        <i class="fas fa-plus me-1"></i>Add
                                                    </button>
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
                            <div class="mt-4">
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
</div>
