<div>
    <!-- Add to Cart Button -->
    <button wire:click="openModal" class="add-to-cart-btn" wire:loading.attr="disabled">
        <i class="fas fa-shopping-cart"></i>
        {{ trans('cart.add_to_cart') }}
    </button>

    <!-- Error Messages (outside modal) -->
    @if (session()->has('error'))
        <div class="mt-2 p-2 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Modal -->
    @if ($showModal)
        <div class="add-to-cart-modal-overlay" wire:click.self="closeModal">
            <div class="add-to-cart-modal-container" wire:click.stop>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">
                            {{ trans('cart.add_to_cart') }}
                        </h3>
                        <button wire:click="closeModal" class="modal-close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Error Messages -->
                        @if (session()->has('error'))
                            <div class="modal-error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Product Info -->
                        <div class="modal-product-info">
                            <h4 class="modal-product-name">{{ $product->name }}</h4>
                            <p class="modal-product-price">
                                {{ number_format($product->base_price, 2) }} {{ trans('products.currency') }}
                            </p>
                        </div>

                        <!-- Quantity -->
                        <div class="modal-quantity-section">
                            <label for="quantity" class="modal-label">
                                {{ trans('cart.quantity') }}
                            </label>
                            <input type="number" wire:model.blur="quantity" min="1" max="100"
                                class="modal-input" value="{{ $quantity }}">
                            @error('quantity')
                                <span class="modal-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Product Options -->
                        @if ($product->options->count() > 0)
                            <div class="modal-options-section">
                                <label class="modal-label">
                                    {{ trans('cart.options') }}
                                </label>
                                <div class="modal-options-container">
                                    @foreach ($product->options as $option)
                                        <div
                                            class="modal-option-card {{ $errors->has('selectedOptions.' . $option->id) ? 'error' : '' }}">
                                            <div class="modal-option-header">
                                                <span class="modal-option-name">{{ $option->name }}</span>
                                                @if ($option->is_required)
                                                    <span
                                                        class="modal-required-badge">{{ trans('products.required') }}</span>
                                                @endif
                                            </div>

                                            @if ($option->values->count() > 0)
                                                <div class="modal-option-values">
                                                    @foreach ($option->values as $value)
                                                        <label class="modal-option-value">
                                                            <input type="radio"
                                                                wire:model.blur="selectedOptions.{{ $option->id }}"
                                                                value="{{ $value->id }}"
                                                                name="option_{{ $option->id }}">
                                                            <span class="modal-option-text">
                                                                {{ $value->value }}
                                                                @if ($value->price_adjustment != 0)
                                                                    <span
                                                                        class="modal-price-adjustment {{ $value->price_adjustment > 0 ? 'positive' : 'negative' }}">
                                                                        ({{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 2) }}
                                                                        {{ trans('products.currency') }})
                                                                    </span>
                                                                @endif
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                    @error('selectedOptions.' . $option->id)
                                                        <span class="modal-error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Order Summary -->
                        <div class="modal-order-summary">
                            <h4 class="modal-summary-title">
                                {{ trans('cart.order_summary') }}
                            </h4>
                            <div class="modal-summary-details">
                                <div class="modal-summary-row">
                                    <span class="modal-summary-label">{{ trans('cart.base_price') }}:</span>
                                    <span
                                        class="modal-summary-value">${{ number_format($product->base_price, 2) }}</span>
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
                                    <div class="modal-summary-row">
                                        <span class="modal-summary-label">{{ trans('cart.options_price') }}:</span>
                                        <span
                                            class="modal-summary-value positive">+${{ number_format($optionsPrice, 2) }}</span>
                                    </div>
                                @endif
                                <div class="modal-summary-row">
                                    <span class="modal-summary-label">{{ trans('cart.quantity') }}:</span>
                                    <span class="modal-summary-value">{{ $quantity }}</span>
                                </div>
                                <div class="modal-summary-total">
                                    <div class="modal-summary-row">
                                        <span class="modal-summary-label">{{ trans('cart.total') }}:</span>
                                        <span
                                            class="modal-summary-total-price">${{ number_format($finalPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="modal-notes-section">
                            <label for="notes" class="modal-label">
                                {{ trans('cart.notes') }}
                                <span class="modal-optional">({{ trans('cart.optional') }})</span>
                            </label>
                            <textarea wire:model.blur="notes" rows="3" placeholder="{{ trans('cart.notes_placeholder') }}"
                                class="modal-textarea"></textarea>
                            @error('notes')
                                <span class="modal-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="modal-actions">
                        <button type="button" wire:click="addToCart" wire:loading.attr="disabled"
                            wire:loading.class="loading" class="modal-confirm-btn">
                            <span wire:loading.remove wire:target="addToCart">
                                <i class="fas fa-shopping-cart"></i>
                                {{ trans('cart.add_to_cart') }}
                            </span>
                            <span wire:loading wire:target="addToCart" class="modal-loading">
                                <i class="fas fa-spinner fa-spin"></i>
                                {{ trans('cart.adding') }}
                            </span>
                        </button>
                        <button type="button" wire:click="closeModal" class="modal-cancel-btn">
                            {{ trans('cart.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for success toast event
            Livewire.on('showSuccessToast', (event) => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '{{ trans('cart.success') }}',
                        text: event.message || '{{ trans('cart.product_added_to_cart') }}',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    alert(event.message || '{{ trans('cart.product_added_to_cart') }}');
                }
            });
        });
    </script>

    <style>
        /* Prevent Bootstrap modal conflicts */
        .modal-backdrop.show {
            opacity: 0 !important;
        }

        .modal.show {
            display: none !important;
        }

        /* Add to Cart Button Styles */
        .add-to-cart-btn {
            background: linear-gradient(135deg, #c4a700 0%, #FFD700 100%);
            color: #2C2C2C;
            border: 2px solid #c4a700;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(196, 167, 0, 0.3);
        }

        .add-to-cart-btn:hover {
            background: linear-gradient(135deg, #FFD700 0%, #FFEBC6 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(196, 167, 0, 0.4);
        }

        /* Modal Overlay Styles */
        .add-to-cart-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            z-index: 9999;
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .add-to-cart-modal-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideIn 0.3s ease-out;
            transform-origin: center;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-content {
            padding: 2rem;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .modal-title {
            font-family: 'Cairo', cursive;
            font-size: 1.8rem;
            font-weight: 700;
            color: #2C2C2C;
            margin: 0;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: #666;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .modal-close-btn:hover {
            background: #f3f4f6;
            color: #2C2C2C;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .modal-error-message {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            border: 1px solid #ef5350;
            border-radius: 10px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #c62828;
        }

        .modal-product-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .modal-product-name {
            font-family: 'Cairo', cursive;
            font-size: 1.3rem;
            font-weight: 700;
            color: #2C2C2C;
            margin: 0 0 0.5rem 0;
        }

        .modal-product-price {
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
        }

        .modal-label {
            display: block;
            font-weight: 600;
            color: #2C2C2C;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .modal-optional {
            color: #666;
            font-weight: 400;
            font-size: 0.9rem;
        }

        .modal-input,
        .modal-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .modal-input:focus,
        .modal-textarea:focus {
            outline: none;
            border-color: #c4a700;
            box-shadow: 0 0 0 3px rgba(196, 167, 0, 0.1);
        }

        .modal-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-options-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .modal-option-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .modal-option-card.error {
            border-color: #ef5350;
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        }

        .modal-option-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .modal-option-name {
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1.1rem;
        }

        .modal-required-badge {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #c62828;
            border: 1px solid #ef5350;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .modal-option-values {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .modal-option-value {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-option-value:hover {
            border-color: #c4a700;
            box-shadow: 0 2px 8px rgba(196, 167, 0, 0.1);
        }

        .modal-option-value input[type="radio"] {
            margin: 0;
            accent-color: #c4a700;
        }

        .modal-option-text {
            color: #2C2C2C;
            font-weight: 500;
        }

        .modal-price-adjustment {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .modal-price-adjustment.positive {
            color: #2e7d32;
        }

        .modal-price-adjustment.negative {
            color: #c62828;
        }

        .modal-order-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .modal-summary-title {
            font-family: 'Cairo', cursive;
            font-size: 1.3rem;
            font-weight: 700;
            color: #2C2C2C;
            margin: 0 0 1rem 0;
        }

        .modal-summary-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .modal-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .modal-summary-label {
            color: #666;
            font-weight: 500;
        }

        .modal-summary-value {
            color: #2C2C2C;
            font-weight: 600;
        }

        .modal-summary-value.positive {
            color: #2e7d32;
        }

        .modal-summary-total {
            border-top: 2px solid #e5e7eb;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }

        .modal-summary-total-price {
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .modal-notes-section {
            margin-top: 1rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e5e7eb;
        }

        .modal-confirm-btn {
            flex: 1;
            background: linear-gradient(135deg, #c4a700 0%, #FFD700 100%);
            color: #2C2C2C;
            border: 2px solid #c4a700;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(196, 167, 0, 0.3);
        }

        .modal-confirm-btn:hover {
            background: linear-gradient(135deg, #FFD700 0%, #FFEBC6 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(196, 167, 0, 0.4);
        }

        .modal-confirm-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .modal-cancel-btn {
            background: #fff;
            color: #2C2C2C;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
        }

        .modal-cancel-btn:hover {
            background: #FFEBC6;
            border-color: #c4a700;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(196, 167, 0, 0.2);
        }

        .modal-loading {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-error {
            color: #c62828;
            font-size: 0.9rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .add-to-cart-modal-overlay {
                padding: 10px;
            }

            .add-to-cart-modal-container {
                max-width: none;
                margin: 0;
            }

            .modal-content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .add-to-cart-modal-overlay {
                padding: 5px;
            }

            .modal-content {
                padding: 1rem;
            }
        }
    </style>
</div>
