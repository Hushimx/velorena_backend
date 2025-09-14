<div class="shopping-cart-container">
    <!-- Cart Header -->
    <div class="cart-header-card">
        <div class="cart-header-content">
            <div class="cart-header-info">
                <div class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="cart-header-text">
                    <h2 class="cart-header-title">{{ trans('cart.shopping_cart') }}</h2>
                    <p class="cart-header-subtitle">
                        <span class="item-count">{{ $itemCount }}</span>
                        {{ trans('cart.items_in_cart') }}
                    </p>
                </div>
            </div>
            @if ($itemCount > 0)
                <button wire:click="clearCart"
                    wire:confirm="{{ trans('cart.confirm_clear_cart', ['default' => 'Are you sure you want to clear all items from your cart? This action cannot be undone.']) }}"
                    class="clear-cart-btn">
                    <i class="fas fa-trash"></i>
                    <span>{{ trans('cart.clear_cart') }}</span>
                </button>
            @endif
        </div>
    </div>

    @if ($itemCount == 0)
        <!-- Empty Cart -->
        <div class="empty-cart-card">
            <div class="empty-cart-content">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="empty-cart-title">{{ trans('cart.empty_cart') }}</h3>
                <p class="empty-cart-message">{{ trans('cart.empty_cart_message') }}</p>
                <a href="{{ route('user.products.index') }}" class="continue-shopping-btn">
                    <i class="fas fa-shopping-bag"></i>
                    <span>{{ trans('cart.continue_shopping') }}</span>
                </a>
            </div>
        </div>
    @else
        <!-- Cart Items -->
        <div class="cart-items-container">
            @foreach ($cartItems as $index => $rawItem)
                @php
                    // Handle mixed structure - some items might be wrapped in arrays
                    $item = is_array($rawItem) && isset($rawItem[0]) && is_array($rawItem[0]) ? $rawItem[0] : $rawItem;
                @endphp
                <div class="cart-item-card" wire:key="cart-item-{{ $item['id'] ?? $index }}">
                    <div class="cart-item-content">
                        <!-- Product Image -->
                        <div class="cart-item-image">
                            <img src="{{ $item['product_image'] ?? 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2NjY2NjYyIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjEyIiBmaWxsPSIjNjY2NjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UHJvZHVjdDwvdGV4dD48L3N2Zz4=' }}"
                                alt="{{ $item['product_name'] ?? 'Product' }}">
                        </div>

                        <!-- Product Details -->
                        <div class="cart-item-details">
                            <h3 class="cart-item-title">{{ $item['product_name'] ?? 'Unknown Product' }}</h3>
                            <p class="cart-item-base-price">
                                {{ trans('cart.base_price') }}:
                                <span class="price-value">{{ number_format($item['base_price'] ?? 0, 2) }} ريال</span>
                            </p>

                            <!-- Selected Options -->
                            @if (!empty($item['selected_options']))
                                <div class="cart-item-options">
                                    <h4 class="options-title">{{ trans('cart.selected_options') }}:</h4>
                                    <div class="options-list">
                                        @foreach ($item['selected_options'] as $optionName => $optionValue)
                                            <div class="option-item">
                                                <span class="option-name">{{ $optionName }}:</span>
                                                <span class="option-value">{{ $optionValue['value'] }}</span>
                                                @if ($optionValue['price_adjustment'] != 0)
                                                    <span
                                                        class="price-adjustment {{ $optionValue['price_adjustment'] > 0 ? 'positive' : 'negative' }}">
                                                        ({{ $optionValue['price_adjustment'] > 0 ? '+' : '' }}{{ number_format($optionValue['price_adjustment'], 2) }} ريال)
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Selected Designs -->
                            @if (!empty($item['designs']) && count($item['designs']) > 0)
                                <div class="cart-item-designs">
                                    <h4 class="designs-title">{{ trans('cart.selected_designs') }}:</h4>
                                    <div class="designs-list">
                                        @foreach ($item['designs'] as $design)
                                            <div class="design-item">
                                                <div class="design-thumbnail">
                                                    <img src="{{ $design['thumbnail_url'] ?? $design['image_url'] }}"
                                                        alt="{{ $design['title'] }}" class="design-thumb">
                                                </div>
                                                <div class="design-info">
                                                    <span class="design-title">{{ $design['title'] }}</span>
                                                    @if (!empty($design['notes']))
                                                        <span class="design-notes">({{ $design['notes'] }})</span>
                                                    @endif
                                                </div>
                                                <button
                                                    wire:click="removeDesignFromProduct({{ $item['product_id'] }}, {{ $design['id'] }})"
                                                    class="remove-design-btn"
                                                    wire:confirm="{{ trans('cart.confirm_remove_design', ['design' => $design['title'], 'product' => $item['product_name']]) }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if (!empty($item['notes']))
                                <div class="cart-item-notes">
                                    <h4 class="notes-title">{{ trans('cart.notes') }}:</h4>
                                    <p class="notes-content">{{ $item['notes'] }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Quantity Controls -->
                        <div class="cart-item-quantity">
                            <label class="quantity-label">{{ trans('cart.quantity') }}:</label>
                            <div class="quantity-controls"
                                wire:key="quantity-controls-{{ $item['product_id'] ?? 0 }}-{{ $item['quantity'] ?? 1 }}">
                                <button
                                    wire:click="updateQuantity({{ $item['id'] ?? 0 }}, {{ max(1, ($item['quantity'] ?? 1) - 1) }})"
                                    class="quantity-btn minus {{ ($item['quantity'] ?? 1) <= 1 ? 'disabled' : '' }}"
                                    {{ ($item['quantity'] ?? 1) <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="quantity-value">{{ $item['quantity'] ?? 1 }}</span>
                                <button
                                    wire:click="updateQuantity({{ $item['id'] ?? 0 }}, {{ ($item['quantity'] ?? 1) + 1 }})"
                                    class="quantity-btn plus">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="cart-item-actions">
                            <button wire:click="openDesignModal({{ $item['product_id'] ?? 0 }})"
                                class="add-design-btn">
                                <i class="fas fa-palette"></i>
                                <span>{{ trans('cart.select_designs') }}</span>
                            </button>
                            <button wire:click="removeItem({{ $item['id'] ?? 0 }})" class="remove-item-btn">
                                <i class="fas fa-trash"></i>
                                <span>{{ trans('cart.remove') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="order-summary-card">
            <div class="order-summary-content">
                <h3 class="summary-title">{{ trans('cart.order_summary') }}</h3>
                <div class="summary-details">
                    <div class="summary-item">
                        <span class="summary-label">{{ trans('cart.subtotal') }}:</span>
                        <span class="summary-value">{{ number_format($subtotal, 2) }} ريال</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ trans('cart.tax') }}:</span>
                        <span class="summary-value">{{ number_format($tax, 2) }} ريال</span>
                    </div>
                    <div class="summary-total">
                        <span class="summary-label">{{ trans('cart.total') }}:</span>
                        <span class="summary-total-value px-2">{{ number_format($total, 2) }} ريال</span>
                    </div>
                </div>
                <div class="summary-actions">
                    <a href="{{ route('user.products.index') }}" class="continue-shopping-btn">
                        <i class="fas fa-arrow-left"></i>
                        <span>{{ trans('cart.continue_shopping') }}</span>
                    </a>
                    <button wire:click="showCheckout" class="checkout-btn">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ trans('cart.checkout') }}</span>
                    </button>
                    <button wire:click="bookAppointment" class="appointment-btn">
                        <i class="fas fa-calendar-plus"></i>
                        <span>{{ trans('cart.make_appointment') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Design Selection Modal -->
    @if ($showDesignModal)
        <div class="design-modal-overlay" wire:click="closeDesignModal">
            <div class="design-modal" wire:click.stop>
                <div class="design-modal-header">
                    <h3 class="design-modal-title">{{ trans('cart.select_designs') }} -
                        {{ $currentProduct->name ?? '' }}</h3>
                    <button wire:click="closeDesignModal" class="design-modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="design-modal-content">
                    @livewire('design-selector', ['productId' => $currentProductId ?? null])
                </div>
                <div class="design-modal-footer">
                    <button wire:click="saveSelectedDesigns" class="save-designs-btn">
                        <i class="fas fa-save"></i>
                        <span>{{ trans('cart.save_designs') }}</span>
                    </button>
                    <button wire:click="closeDesignModal" class="cancel-btn">
                        <span>{{ trans('cart.cancel') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            document.addEventListener('cartUpdated', function() {
                console.log('Cart updated event received - Database Cart Mode');
            });
        });
    </script>

    <style>
        /* Shopping Cart Styles - Based on Product Show Page Design */
        .shopping-cart-container {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }

        /* Cart Header */
        .cart-header-card {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid rgba(42, 30, 30, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .cart-header-content {
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .cart-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(42, 30, 30, 0.2);
            box-shadow: 0 8px 24px rgba(42, 30, 30, 0.2);
        }

        .cart-icon i {
            font-size: 2rem;
            color: var(--brand-brown);
        }

        .cart-header-text {
            flex: 1;
        }

        .cart-header-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .cart-header-subtitle {
            color: var(--brand-brown);
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.8;
        }

        .item-count {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .clear-cart-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: 2px solid rgba(42, 30, 30, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .clear-cart-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        /* Empty Cart */
        .empty-cart-card {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid rgba(42, 30, 30, 0.1);
            padding: 4rem 2rem;
            text-align: center;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .empty-cart-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .empty-cart-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(42, 30, 30, 0.3);
        }

        .empty-cart-icon i {
            font-size: 3rem;
            color: white;
            opacity: 0.9;
        }

        .empty-cart-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
        }

        .empty-cart-message {
            color: var(--brand-brown);
            font-size: 1.1rem;
            margin: 0;
            max-width: 400px;
            opacity: 0.8;
        }

        .continue-shopping-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .continue-shopping-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        /* Cart Items Container */
        .cart-items-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Cart Item Card */
        .cart-item-card {
            background: linear-gradient(135deg, #ffffff 0%, var(--brand-yellow-light) 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid rgba(42, 30, 30, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .cart-item-card:hover {
            box-shadow: 0 12px 40px rgba(42, 30, 30, 0.15);
            border-color: rgba(42, 30, 30, 0.2);
        }

        .cart-item-content {
            padding: 2rem;
            display: grid;
            grid-template-columns: 120px 1fr auto auto;
            gap: 2rem;
            align-items: start;
        }

        /* Better responsive grid for cart items */
        @media (max-width: 1024px) {
            .cart-item-content {
                grid-template-columns: 100px 1fr auto auto;
                gap: 1.5rem;
                padding: 1.5rem;
            }
        }

        /* Tablet layout */
        @media (max-width: 992px) {
            .cart-item-content {
                grid-template-columns: 80px 1fr auto auto;
                gap: 1.25rem;
                padding: 1.25rem;
            }

            .cart-item-image img {
                width: 80px;
                height: 80px;
            }

            .cart-item-title {
                font-size: 1.1rem;
            }

            .quantity-controls {
                padding: 0.5rem;
            }

            .quantity-btn {
                width: 35px;
                height: 35px;
                padding: 0.5rem;
            }

            .quantity-value {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }

            .add-design-btn,
            .remove-item-btn {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .designs-list {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .design-item {
                max-width: 200px;
                flex: 1 1 200px;
            }

            .design-thumb {
                width: 45px;
                height: 45px;
            }
        }

        /* Cart Item Image */
        .cart-item-image {
            flex-shrink: 0;
        }

        .cart-item-image img {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255, 244, 230, 0.5);
        }

        .cart-item-image img:hover {
            border-color: var(--brand-yellow);
        }

        /* Cart Item Details */
        .cart-item-details {
            flex: 1;
        }

        .cart-item-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .cart-item-base-price {
            color: var(--brand-brown);
            font-size: 1rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .price-value {
            color: var(--brand-brown);
            font-weight: 700;
        }

        /* Cart Item Options */
        .cart-item-options {
            margin-bottom: 1rem;
        }

        .options-title {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .options-list {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .option-item {
            color: var(--brand-brown);
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .option-name {
            font-weight: 600;
        }

        .option-value {
            font-style: italic;
        }

        .price-adjustment {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .price-adjustment.positive {
            color: var(--brand-brown);
        }

        .price-adjustment.negative {
            color: var(--brand-brown-light);
        }

        /* Cart Item Notes */
        .cart-item-notes {
            margin-bottom: 1rem;
        }

        .notes-title {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .notes-content {
            color: var(--brand-brown);
            font-size: 0.9rem;
            background: rgba(255, 244, 230, 0.5);
            padding: 0.5rem;
            border-radius: 6px;
            font-style: italic;
            margin: 0;
            border: 1px solid rgba(42, 30, 30, 0.2);
        }

        /* Quantity Controls */
        .cart-item-quantity {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .quantity-label {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 12px;
            border: 2px solid rgba(42, 30, 30, 0.2);
            overflow: hidden;
        }

        .quantity-btn {
            background: transparent;
            border: none;
            padding: 0.75rem;
            cursor: pointer;
            color: var(--brand-brown);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .quantity-btn:hover:not(.disabled) {
            background: rgba(42, 30, 30, 0.1);
        }

        .quantity-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-value {
            padding: 0.75rem 1rem;
            font-weight: 700;
            color: var(--brand-brown);
            min-width: 50px;
            text-align: center;
            border-left: 1px solid rgba(42, 30, 30, 0.2);
            border-right: 1px solid rgba(42, 30, 30, 0.2);
        }

        /* Remove Button */
        .cart-item-actions {
            display: flex;
            align-items: center;
        }

        .remove-item-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: 2px solid rgba(42, 30, 30, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .remove-item-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        /* Add Design Button */
        .add-design-btn {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            border: 2px solid rgba(42, 30, 30, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .add-design-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-light) 100%);
            color: var(--brand-brown);
            box-shadow: 0 4px 12px rgba(42, 30, 30, 0.2);
        }

        /* Cart Item Actions */
        .cart-item-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        /* Selected Designs */
        .cart-item-designs {
            margin-bottom: 1rem;
        }

        .designs-title {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .designs-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .design-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 244, 230, 0.3);
            padding: 0.5rem;
            border-radius: 8px;
            border: 1px solid rgba(42, 30, 30, 0.1);
        }

        .design-thumbnail {
            flex-shrink: 0;
        }

        .design-thumb {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid rgba(42, 30, 30, 0.2);
        }

        .design-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .design-title {
            font-weight: 600;
            color: var(--brand-brown);
            font-size: 0.9rem;
        }

        .design-notes {
            font-style: italic;
            color: var(--brand-brown);
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .remove-design-btn {
            background: rgba(42, 30, 30, 0.1);
            color: var(--brand-brown);
            border: 1px solid rgba(42, 30, 30, 0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .remove-design-btn:hover {
            background: rgba(42, 30, 30, 0.2);
            color: var(--brand-brown);
        }

        /* Design Modal */
        .design-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .design-modal {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .design-modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
        }

        .design-modal-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }

        .design-modal-close {
            background: rgba(42, 30, 30, 0.1);
            color: var(--brand-brown);
            border: 1px solid rgba(42, 30, 30, 0.2);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .design-modal-close:hover {
            background: rgba(42, 30, 30, 0.2);
        }

        .design-modal-content {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            background: #f8f9fa;
        }

        /* Bootstrap overrides for design selector */
        .design-modal-content .design-selector {
            background: transparent;
            padding: 0;
        }

        .design-modal-content .card {
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .design-modal-content .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
        }

        .design-modal-content .form-control,
        .design-modal-content .form-select {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }

        .design-modal-content .form-control:focus,
        .design-modal-content .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .design-modal-content .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .design-modal-content .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        /* Override design selector styles for modal */
        .design-modal-content .design-selector {
            background: transparent;
            padding: 0;
        }

        .design-modal-content .mb-6 {
            margin-bottom: 1.5rem !important;
        }

        .design-modal-content .bg-white {
            background: white !important;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .design-modal-content .grid {
            display: grid !important;
        }

        .design-modal-content .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
        }

        .design-modal-content .sm\\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .design-modal-content .md\\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }

        .design-modal-content .lg\\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }

        .design-modal-content .xl\\:grid-cols-5 {
            grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
        }

        .design-modal-content .gap-4 {
            gap: 1rem !important;
        }

        .design-modal-content .p-4 {
            padding: 1rem !important;
        }

        .design-modal-content .p-3 {
            padding: 0.75rem !important;
        }

        .design-modal-content .rounded-lg {
            border-radius: 0.5rem !important;
        }

        .design-modal-content .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
        }

        .design-modal-content .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }

        .design-modal-content .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }

        .design-modal-content .hover\\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }

        .design-modal-content .transition-shadow {
            transition-property: box-shadow !important;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
            transition-duration: 150ms !important;
        }

        .design-modal-content .duration-200 {
            transition-duration: 200ms !important;
        }

        .design-modal-content .overflow-hidden {
            overflow: hidden !important;
        }

        .design-modal-content .aspect-square {
            aspect-ratio: 1 / 1 !important;
        }

        .design-modal-content .bg-gray-100 {
            background-color: #f3f4f6 !important;
        }

        .design-modal-content .w-full {
            width: 100% !important;
        }

        .design-modal-content .h-full {
            height: 100% !important;
        }

        .design-modal-content .object-cover {
            object-fit: cover !important;
        }

        .design-modal-content .cursor-pointer {
            cursor: pointer !important;
        }

        .design-modal-content .relative {
            position: relative !important;
        }

        .design-modal-content .absolute {
            position: absolute !important;
        }

        .design-modal-content .top-2 {
            top: 0.5rem !important;
        }

        .design-modal-content .right-2 {
            right: 0.5rem !important;
        }

        .design-modal-content .w-4 {
            width: 1rem !important;
        }

        .design-modal-content .h-4 {
            height: 1rem !important;
        }

        .design-modal-content .text-blue-600 {
            color: #2563eb !important;
        }

        .design-modal-content .bg-gray-100 {
            background-color: #f3f4f6 !important;
        }

        .design-modal-content .border-gray-300 {
            border-color: #d1d5db !important;
        }

        .design-modal-content .rounded {
            border-radius: 0.25rem !important;
        }

        .design-modal-content .focus\\:ring-blue-500:focus {
            --tw-ring-color: #3b82f6 !important;
        }

        .design-modal-content .focus\\:ring-2:focus {
            --tw-ring-offset-width: 2px !important;
            box-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color) !important;
        }

        .design-modal-content .font-medium {
            font-weight: 500 !important;
        }

        .design-modal-content .text-sm {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        .design-modal-content .text-gray-900 {
            color: #111827 !important;
        }

        .design-modal-content .truncate {
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        .design-modal-content .text-xs {
            font-size: 0.75rem !important;
            line-height: 1rem !important;
        }

        .design-modal-content .text-gray-500 {
            color: #6b7280 !important;
        }

        .design-modal-content .mt-1 {
            margin-top: 0.25rem !important;
        }

        .design-modal-content .mt-2 {
            margin-top: 0.5rem !important;
        }

        .design-modal-content .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .design-modal-content .px-2 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .design-modal-content .py-1 {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .design-modal-content .border {
            border-width: 1px !important;
        }

        .design-modal-content .focus\\:ring-1:focus {
            --tw-ring-offset-width: 1px !important;
            box-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color) !important;
        }

        .design-modal-content .focus\\:border-blue-500:focus {
            border-color: #3b82f6 !important;
        }

        .design-modal-content .rows-2 {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            resize: vertical !important;
        }

        .design-modal-content .text-center {
            text-align: center !important;
        }

        .design-modal-content .py-8 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }

        .design-modal-content .text-gray-500 {
            color: #6b7280 !important;
        }

        .design-modal-content .col-span-full {
            grid-column: 1 / -1 !important;
        }

        .design-modal-content .mx-auto {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .design-modal-content .h-12 {
            height: 3rem !important;
        }

        .design-modal-content .w-12 {
            width: 3rem !important;
        }

        .design-modal-content .text-gray-400 {
            color: #9ca3af !important;
        }

        .design-modal-content .mt-2 {
            margin-top: 0.5rem !important;
        }

        .design-modal-content .text-sm {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        .design-modal-content .font-medium {
            font-weight: 500 !important;
        }

        .design-modal-content .text-gray-900 {
            color: #111827 !important;
        }

        .design-modal-content .mt-1 {
            margin-top: 0.25rem !important;
        }

        /* Input and form styles */
        .design-modal-content input,
        .design-modal-content select,
        .design-modal-content textarea {
            font-family: 'Cairo', sans-serif !important;
            direction: rtl !important;
        }

        .design-modal-content .block {
            display: block !important;
        }

        .design-modal-content .text-sm {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        .design-modal-content .font-medium {
            font-weight: 500 !important;
        }

        .design-modal-content .text-gray-700 {
            color: #374151 !important;
        }

        .design-modal-content .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .design-modal-content .relative {
            position: relative !important;
        }

        .design-modal-content .w-full {
            width: 100% !important;
        }

        .design-modal-content .px-4 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .design-modal-content .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .design-modal-content .border {
            border-width: 1px !important;
        }

        .design-modal-content .border-gray-300 {
            border-color: #d1d5db !important;
        }

        .design-modal-content .rounded-md {
            border-radius: 0.375rem !important;
        }

        .design-modal-content .focus\\:ring-2:focus {
            --tw-ring-offset-width: 2px !important;
            box-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color) !important;
        }

        .design-modal-content .focus\\:ring-blue-500:focus {
            --tw-ring-color: #3b82f6 !important;
        }

        .design-modal-content .focus\\:border-blue-500:focus {
            border-color: #3b82f6 !important;
        }

        .design-modal-content .inset-y-0 {
            top: 0 !important;
            bottom: 0 !important;
        }

        .design-modal-content .right-0 {
            right: 0 !important;
        }

        .design-modal-content .pr-3 {
            padding-right: 0.75rem !important;
        }

        .design-modal-content .flex {
            display: flex !important;
        }

        .design-modal-content .items-center {
            align-items: center !important;
        }

        .design-modal-content .h-5 {
            height: 1.25rem !important;
        }

        .design-modal-content .w-5 {
            width: 1.25rem !important;
        }

        .design-modal-content .text-gray-400 {
            color: #9ca3af !important;
        }

        .design-modal-content .items-end {
            align-items: flex-end !important;
        }

        .design-modal-content .bg-blue-600 {
            background-color: #2563eb !important;
        }

        .design-modal-content .text-white {
            color: #ffffff !important;
        }

        .design-modal-content .hover\\:bg-blue-700:hover {
            background-color: #1d4ed8 !important;
        }

        .design-modal-content .focus\\:ring-offset-2:focus {
            --tw-ring-offset-width: 2px !important;
        }

        .design-modal-content .disabled\\:opacity-50:disabled {
            opacity: 0.5 !important;
        }

        .design-modal-content .inline-flex {
            display: inline-flex !important;
        }

        .design-modal-content .items-center {
            align-items: center !important;
        }

        .design-modal-content .font-semibold {
            font-weight: 600 !important;
        }

        .design-modal-content .leading-6 {
            line-height: 1.5rem !important;
        }

        .design-modal-content .text-sm {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        .design-modal-content .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
        }

        .design-modal-content .rounded {
            border-radius: 0.25rem !important;
        }

        .design-modal-content .text-white {
            color: #ffffff !important;
        }

        .design-modal-content .bg-blue-500 {
            background-color: #3b82f6 !important;
        }

        .design-modal-content .hover\\:bg-blue-400:hover {
            background-color: #60a5fa !important;
        }

        .design-modal-content .transition {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke !important;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
            transition-duration: 150ms !important;
        }

        .design-modal-content .ease-in-out {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .design-modal-content .duration-150 {
            transition-duration: 150ms !important;
        }

        .design-modal-content .cursor-not-allowed {
            cursor: not-allowed !important;
        }

        .design-modal-content .animate-spin {
            animation: spin 1s linear infinite !important;
        }

        .design-modal-content .-ml-1 {
            margin-left: -0.25rem !important;
        }

        .design-modal-content .mr-3 {
            margin-right: 0.75rem !important;
        }

        .design-modal-content .h-5 {
            height: 1.25rem !important;
        }

        .design-modal-content .w-5 {
            width: 1.25rem !important;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg) !important;
            }

            to {
                transform: rotate(360deg) !important;
            }
        }

        .design-modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: #f8f9fa;
        }

        .save-designs-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .save-designs-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        .cancel-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background: #5a6268;
        }

        /* Order Summary */
        .order-summary-card {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(42, 30, 30, 0.1);
            border: 2px solid rgba(42, 30, 30, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .order-summary-content {
            padding: 2rem;
        }

        .summary-title {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(42, 30, 30, 0.3);
        }

        .summary-details {
            margin-bottom: 2rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--brand-brown);
            font-weight: 600;
            font-size: 1rem;
        }

        .summary-value {
            color: var(--brand-brown);
            font-weight: 700;
            font-size: 1rem;
        }

        .summary-total {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1rem;
            border: 2px solid rgba(42, 30, 30, 0.3);
        }

        .summary-total-value {
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
        }

        .summary-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .checkout-btn {
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .checkout-btn:hover {
            background: linear-gradient(135deg, var(--brand-brown-light) 0%, var(--brand-brown) 100%);
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.4);
        }

        .appointment-btn {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-light) 100%);
            color: var(--brand-brown);
            border: 2px solid rgba(42, 30, 30, 0.3);
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.2);
        }

        .appointment-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            box-shadow: 0 6px 20px rgba(42, 30, 30, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-header-content {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .cart-item-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
                padding: 1.5rem;
            }

            .cart-item-image {
                display: flex;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .cart-item-image img {
                width: 120px;
                height: 120px;
            }

            .cart-item-details {
                text-align: center;
                margin-bottom: 1rem;
            }

            .cart-item-title {
                font-size: 1.1rem;
                margin-bottom: 0.5rem;
            }

            .cart-item-quantity {
                order: -1;
                margin-bottom: 1rem;
            }

            .cart-item-actions {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }

            .add-design-btn,
            .remove-item-btn {
                width: 100%;
                justify-content: center;
            }

            .summary-actions {
                flex-direction: column;
            }

            .continue-shopping-btn,
            .checkout-btn,
            .appointment-btn {
                width: 100%;
                justify-content: center;
            }

            /* Design items responsive */
            .designs-list {
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }

            .design-item {
                width: 100%;
                max-width: 300px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .design-thumbnail {
                flex-shrink: 0;
            }

            .design-thumb {
                width: 50px;
                height: 50px;
            }

            .design-info {
                flex: 1;
                text-align: left;
                margin: 0 1rem;
            }
        }

        @media (max-width: 576px) {

            .cart-header-content,
            .cart-item-content,
            .order-summary-content {
                padding: 1rem;
            }

            .cart-icon {
                width: 60px;
                height: 60px;
            }

            .cart-icon i {
                font-size: 1.5rem;
            }

            .cart-header-title {
                font-size: 1.5rem;
            }

            .cart-item-image img {
                width: 100px;
                height: 100px;
            }

            .cart-item-title {
                font-size: 1rem;
            }

            .cart-item-base-price {
                font-size: 0.9rem;
            }

            .options-title,
            .designs-title,
            .notes-title {
                font-size: 0.85rem;
            }

            .option-item,
            .design-title,
            .notes-content {
                font-size: 0.8rem;
            }

            .quantity-controls {
                padding: 0.5rem;
            }

            .quantity-btn {
                width: 35px;
                height: 35px;
                padding: 0.5rem;
            }

            .quantity-value {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }

            .add-design-btn,
            .remove-item-btn {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .design-thumb {
                width: 40px;
                height: 40px;
            }

            .design-info {
                margin: 0 0.5rem;
            }

            .design-title {
                font-size: 0.8rem;
            }

            .design-notes {
                font-size: 0.75rem;
            }

            .remove-design-btn {
                width: 30px;
                height: 30px;
                padding: 0.25rem;
            }

            .empty-cart-icon {
                width: 80px;
                height: 80px;
            }

            .empty-cart-icon i {
                font-size: 2rem;
            }

            .empty-cart-title {
                font-size: 1.25rem;
            }

            /* Modal responsive for small screens */
            .design-modal {
                width: 95%;
                max-width: 95%;
                margin: 1rem;
            }

            .design-modal-header {
                padding: 1rem;
            }

            .design-modal-title {
                font-size: 1rem;
            }

            .design-modal-content {
                padding: 1rem;
                max-height: 60vh;
            }

            .design-modal-footer {
                padding: 1rem;
                flex-direction: column;
                gap: 0.75rem;
            }

            .save-designs-btn,
            .cancel-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</div>
