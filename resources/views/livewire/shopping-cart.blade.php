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
                                <span class="price-value">${{ number_format($item['base_price'] ?? 0, 2) }}</span>
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

                        <!-- Remove Button -->
                        <div class="cart-item-actions">
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
                        <span class="summary-value">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ trans('cart.tax') }}:</span>
                        <span class="summary-value">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="summary-total">
                        <span class="summary-label">{{ trans('cart.total') }}:</span>
                        <span class="summary-total-value">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
                <div class="summary-actions">
                    <a href="{{ route('user.products.index') }}" class="continue-shopping-btn">
                        <i class="fas fa-arrow-left"></i>
                        <span>{{ trans('cart.continue_shopping') }}</span>
                    </a>
                    <button class="checkout-btn">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ trans('cart.checkout') }}</span>
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
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(139, 69, 19, 0.2);
            box-shadow: 0 8px 24px rgba(244, 208, 63, 0.3);
        }

        .cart-icon i {
            font-size: 2rem;
            color: #8B4513;
        }

        .cart-header-text {
            flex: 1;
        }

        .cart-header-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .cart-header-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin: 0;
        }

        .item-count {
            color: #28a745;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .clear-cart-btn {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #d32f2f;
            border: 2px solid #ffcdd2;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .clear-cart-btn:hover {
            background: linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%);
            color: #b71c1c;
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.3);
        }

        /* Empty Cart */
        .empty-cart-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(108, 117, 125, 0.2);
        }

        .empty-cart-icon i {
            font-size: 3rem;
            color: #6c757d;
            opacity: 0.7;
        }

        .empty-cart-title {
            color: #495057;
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
        }

        .empty-cart-message {
            color: #6c757d;
            font-size: 1.1rem;
            margin: 0;
            max-width: 400px;
        }

        .continue-shopping-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .continue-shopping-btn:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
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
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .cart-item-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .cart-item-content {
            padding: 2rem;
            display: grid;
            grid-template-columns: 120px 1fr auto auto;
            gap: 2rem;
            align-items: start;
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
            border: 2px solid rgba(255, 235, 198, 0.3);
        }

        .cart-item-image img:hover {
            border-color: #F4D03F;
        }

        /* Cart Item Details */
        .cart-item-details {
            flex: 1;
        }

        .cart-item-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .cart-item-base-price {
            color: #495057;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .price-value {
            color: #28a745;
            font-weight: 700;
        }

        /* Cart Item Options */
        .cart-item-options {
            margin-bottom: 1rem;
        }

        .options-title {
            color: #6c757d;
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
            color: #6c757d;
            font-size: 0.9rem;
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
            color: #28a745;
        }

        .price-adjustment.negative {
            color: #dc3545;
        }

        /* Cart Item Notes */
        .cart-item-notes {
            margin-bottom: 1rem;
        }

        .notes-title {
            color: #6c757d;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .notes-content {
            color: #28a745;
            font-size: 0.9rem;
            background: rgba(40, 167, 69, 0.1);
            padding: 0.5rem;
            border-radius: 6px;
            font-style: italic;
            margin: 0;
        }

        /* Quantity Controls */
        .cart-item-quantity {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .quantity-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 12px;
            border: 2px solid rgba(139, 69, 19, 0.2);
            overflow: hidden;
        }

        .quantity-btn {
            background: transparent;
            border: none;
            padding: 0.75rem;
            cursor: pointer;
            color: #8B4513;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .quantity-btn:hover:not(.disabled) {
            background: rgba(139, 69, 19, 0.1);
        }

        .quantity-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-value {
            padding: 0.75rem 1rem;
            font-weight: 700;
            color: #8B4513;
            min-width: 50px;
            text-align: center;
            border-left: 1px solid rgba(139, 69, 19, 0.2);
            border-right: 1px solid rgba(139, 69, 19, 0.2);
        }

        /* Remove Button */
        .cart-item-actions {
            display: flex;
            align-items: center;
        }

        .remove-item-btn {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #d32f2f;
            border: 2px solid #ffcdd2;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remove-item-btn:hover {
            background: linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%);
            color: #b71c1c;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        /* Order Summary */
        .order-summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .order-summary-content {
            padding: 2rem;
        }

        .summary-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(255, 235, 198, 0.3);
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
            color: #6c757d;
            font-weight: 600;
            font-size: 1rem;
        }

        .summary-value {
            color: #495057;
            font-weight: 700;
            font-size: 1rem;
        }

        .summary-total {
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1rem;
            border: 2px solid rgba(139, 69, 19, 0.2);
        }

        .summary-total-value {
            color: #8B4513;
            font-weight: 800;
            font-size: 1.25rem;
        }

        .summary-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .checkout-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .checkout-btn:hover {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
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
            }

            .cart-item-quantity {
                order: -1;
            }

            .summary-actions {
                flex-direction: column;
            }

            .continue-shopping-btn,
            .checkout-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {

            .cart-header-content,
            .cart-item-content,
            .order-summary-content {
                padding: 1.5rem;
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
                width: 80px;
                height: 80px;
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
        }
    </style>
</div>
