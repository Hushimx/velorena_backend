<div class="add-to-cart-component">
    <!-- Product Options Section -->
    <div class="product-options-section">
        <div class="options-container">
            <!-- Product Title -->
            <div class="product-header">
                <h1 class="product-title">{{ $product->name }}</h1>
                <p class="product-description">
                    {{ $product->description ?? 'استمتع بتجربة فريدة مع مجموعة متنوعة من الفواكه المجففة المختارة بعناية من أجود الأنواع حول العالم. تم تحضيرها بأحدث التقنيات لضمان الحفاظ على قيمتها الغذائية العالية وطعمها اللذيذ من الطبيعة.' }}
                </p>
            </div>

            <!-- Dynamic Product Options from Database -->
            @if ($product->options->count() > 0)
                @foreach ($product->options as $option)
                    <div class="option-group">
                        <h3 class="option-title">{{ $option->name_ar ?? $option->name }} :</h3>
                        @if ($option->values->count() > 0)
                            @if ($option->type === 'select')
                                <!-- Dropdown for select type -->
                                <div class="shape-selector">
                                    <select class="form-select" wire:model.live="selectedOptions.{{ $option->id }}">
                                        @foreach ($option->values as $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->value_ar ?? $value->value }}
                                                @if ($value->price_adjustment != 0)
                                                    ({{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 0) }}
                                                    ريال)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif ($option->type === 'checkbox')
                                <!-- Checkboxes for multiple selection -->
                                <div class="option-buttons">
                                    @foreach ($option->values as $value)
                                        <label class="option-btn">
                                            <input type="checkbox" wire:model.live="selectedOptions.{{ $option->id }}"
                                                value="{{ $value->id }}">
                                            <span>
                                                {{ $value->value_ar ?? $value->value }}
                                                @if ($value->price_adjustment != 0)
                                                    ({{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 0) }}
                                                    ريال)
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <!-- Radio buttons for single selection (default) -->
                                <div class="option-buttons">
                                    @foreach ($option->values as $value)
                                        <label class="option-btn">
                                            <input type="radio" wire:model.live="selectedOptions.{{ $option->id }}"
                                                value="{{ $value->id }}">
                                            <span>
                                                {{ $value->value_ar ?? $value->value }}
                                                @if ($value->price_adjustment != 0)
                                                    ({{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 0) }}
                                                    ريال)
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        @error('selectedOptions.' . $option->id)
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            @else
                <!-- Fallback static options if no database options -->
                <div class="option-group">
                    <h3 class="option-title">نوع الخامة :</h3>
                    <div class="option-buttons">
                        <label class="option-btn">
                            <input type="radio" name="type" value="مات" checked>
                            <span>مات</span>
                        </label>
                        <label class="option-btn">
                            <input type="radio" name="type" value="لامع">
                            <span>لامع</span>
                        </label>
                        <label class="option-btn">
                            <input type="radio" name="type" value="بلاستيك">
                            <span>بلاستيك</span>
                        </label>
                        <label class="option-btn">
                            <input type="radio" name="type" value="كرافت">
                            <span>كرافت</span>
                        </label>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">الطباعة الألوان :</h3>
                    <div class="option-buttons">
                        <label class="option-btn">
                            <input type="checkbox" name="colors[]" value="ألوان كاملة" checked>
                            <span>ألوان كاملة</span>
                        </label>
                        <label class="option-btn">
                            <input type="checkbox" name="colors[]" value="ألوان خاصة" checked>
                            <span>ألوان خاصة</span>
                        </label>
                        <label class="option-btn">
                            <input type="checkbox" name="colors[]" value="لون واحد">
                            <span>لون واحد</span>
                        </label>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">حجم الكيس :</h3>
                    <div class="option-buttons">
                        <label class="option-btn">
                            <input type="radio" name="size" value="صغير (50جم)">
                            <span>صغير (50جم)</span>
                        </label>
                        <label class="option-btn">
                            <input type="radio" name="size" value="وسط (100جم)">
                            <span>وسط (100جم)</span>
                        </label>
                        <label class="option-btn">
                            <input type="radio" name="size" value="كبير (250جم)">
                            <span>كبير (250جم)</span>
                        </label>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">مكان الطباعة :</h3>
                    <div class="option-buttons">
                        <label class="option-btn">
                            <input type="radio" name="location" value="وجهين" checked>
                            <span>وجهين</span>
                        </label>
                        <label class="option-btn">
                            <input type="radio" name="location" value="وجه واحد">
                            <span>وجه واحد</span>
                        </label>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">شكل الكيس :</h3>
                    <div class="shape-selector">
                        <select class="form-select" name="shape">
                            <option value="قابل للغلق">قابل للغلق</option>
                            <option value="مستطيل">مستطيل</option>
                            <option value="مربع">مربع</option>
                            <option value="دائري">دائري</option>
                        </select>
                    </div>
                </div>
            @endif

            <!-- Notes Section -->
            <div class="option-group">
                <h3 class="option-title">ملاحظات إضافية :</h3>
                <div class="notes-input">
                    <textarea wire:model="notes" class="form-control" rows="3" placeholder="أضف أي ملاحظات خاصة بالطلب..."></textarea>
                </div>
                @error('notes')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>




            <!-- Action Buttons -->
        </div>
    </div>

    <!-- Sticky Floating Cart Button -->
    <div class="sticky-cart-container">
        <div class="sticky-cart-content">
            <div class="sticky-cart-info">
                <div class="sticky-cart-price">
                    <span class="sticky-price">{{ number_format($this->totalPrice, 2, '.', ',') }}</span>
                    <span class="sticky-currency">ريال</span>
                </div>
                <div class="sticky-cart-quantity">
                    <button type="button" class="sticky-qty-btn minus" wire:click="decrementQuantity">-</button>
                    <input type="number" class="sticky-qty-input" wire:model.live="quantity" min="1"
                        max="100">
                    <button type="button" class="sticky-qty-btn plus" wire:click="incrementQuantity">+</button>
                </div>
            </div>
            <div class="sticky-cart-actions">
                <button class="sticky-btn-add-cart" wire:click="addToCart" wire:loading.attr="disabled"
                    wire:target="addToCart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="sticky-btn-text" wire:loading.remove wire:target="addToCart">إضافة إلى السلة</span>
                    <span class="sticky-btn-loading" wire:loading wire:target="addToCart">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
                <button class="sticky-btn-buy-now" wire:click="buyNow" wire:loading.attr="disabled"
                    wire:target="buyNow">
                    <i class="fas fa-credit-card"></i>
                    <span class="sticky-btn-text" wire:loading.remove wire:target="buyNow">شراء</span>
                    <span class="sticky-btn-loading" wire:loading wire:target="buyNow">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Design Selection Modal -->
    @if ($showDesignModal)
        <div class="design-modal-overlay" wire:click="closeDesignModal">
            <div class="design-modal" wire:click.stop>
                <div class="design-modal-header">
                    <h3 class="design-modal-title">اختر تصميم - {{ $product->name }}</h3>
                    <button wire:click="closeDesignModal" class="design-modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="design-modal-content">
                    @livewire('design-selector', ['productId' => $product->id])
                </div>
                <div class="design-modal-footer">
                    <button wire:click="saveSelectedDesigns" class="save-designs-btn">
                        <i class="fas fa-save"></i>
                        <span>حفظ التصاميم</span>
                    </button>
                    <button wire:click="closeDesignModal" class="cancel-btn">
                        <span>إلغاء</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');

        /* Brand Colors */
        :root {
            /* Brand Colors */
            --brand-yellow: #ffde9f;
            --brand-yellow-dark: #f5d182;
            --brand-brown: #2a1e1e;
            --brand-brown-light: #3a2e2e;

            /* Extended Brand Palette */
            --brand-yellow-light: #fff4e6;
            --brand-yellow-hover: #f0d4a0;
            --brand-brown-dark: #1a1414;
            --brand-brown-hover: #4a3e3e;

            /* Status Colors */
            --status-pending: #fbbf24;
            --status-processing: #3b82f6;
            --status-completed: #10b981;
            --status-cancelled: #ef4444;
            --status-active: #10b981;
            --status-inactive: #6b7280;
            --status-suspended: #f59e0b;
        }

        .add-to-cart-component {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }

        .product-options-section {
            padding: 2rem;
            display: flex;
        }

        .options-container {
            width: 100%;
            max-width: 500px;
        }

        .product-header {
            margin-bottom: 2rem;
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: #2C2C2C;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .product-description {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .option-group {
            margin-bottom: 2rem;
        }

        .option-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2C2C2C;
            margin-bottom: 1rem;
        }

        .option-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .option-btn {
            position: relative;
            cursor: pointer;
            margin: 0;
        }

        .option-btn input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .option-btn span {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #fff;
            border: 2px solid #E0E0E0;
            border-radius: 25px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .option-btn input:checked+span {
            background: #2C2C2C;
            color: #fff;
            border-color: #2C2C2C;
        }

        .option-btn:hover span {
            border-color: #2C2C2C;
        }

        .shape-selector select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #E0E0E0;
            border-radius: 25px;
            background: #fff;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .shape-selector select:focus {
            border-color: #2C2C2C;
        }

        .notes-input textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #E0E0E0;
            border-radius: 15px;
            background: #fff;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
            resize: vertical;
        }

        .notes-input textarea:focus {
            border-color: #2C2C2C;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        .price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0;
            padding: 1.5rem;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .price-display {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .price {
            font-size: 2.5rem;
            font-weight: 900;
            color: #2C2C2C;
        }

        .currency {
            font-size: 1.2rem;
            font-weight: 600;
            color: #666;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            background: #F5F5F5;
            border-radius: 25px;
            overflow: hidden;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--brand-brown);
            color: var(--brand-yellow-light);
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .qty-btn:hover {
            background: var(--brand-brown-hover);
        }

        .qty-input {
            width: 60px;
            height: 40px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--brand-brown);
            outline: none;
        }

        .design-selection-section {
            margin-bottom: 1.5rem;
        }

        .btn-choose-design {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            color: var(--brand-brown);
            border: 2px solid var(--brand-brown);
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.3);
        }

        .btn-choose-design:hover {
            background: linear-gradient(135deg, #F4D03F 0%, #FFEBC6 100%);
            color: var(--brand-brown);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 208, 63, 0.4);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-add-cart,
        .btn-buy-now {
            flex: 1;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add-cart {
            background: var(--brand-yellow-light);
            color: var(--brand-brown);
            border: 2px solid var(--brand-brown);
        }

        .btn-add-cart:hover {
            background: var(--brand-brown);
            color: var(--brand-yellow-light);
        }

        .btn-buy-now {
            background: var(--brand-brown);
            color: var(--brand-yellow-light);
        }

        .btn-buy-now:hover {
            background: var(--brand-brown-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.3);
        }

        .btn-add-cart:disabled,
        .btn-buy-now:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
        }

        .design-modal-title {
            color: #8B4513;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }

        .design-modal-close {
            background: rgba(139, 69, 19, 0.1);
            color: #8B4513;
            border: 1px solid rgba(139, 69, 19, 0.2);
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
            background: rgba(139, 69, 19, 0.2);
        }

        .design-modal-content {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            background: #f8f9fa;
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
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }

        .save-designs-btn:hover {
            background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);
            box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
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

        /* Sticky Cart Container */
        .sticky-cart-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-light) 100%);
            border-top: 3px solid var(--brand-yellow-dark);
            box-shadow: 0 -8px 25px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .sticky-cart-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            gap: 2rem;
        }

        .sticky-cart-info {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .sticky-cart-price {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .sticky-price {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--brand-yellow);
            font-family: 'Cairo', cursive;
        }

        .sticky-currency {
            font-size: 1rem;
            font-weight: 600;
            color: var(--brand-yellow-light);
        }

        .sticky-cart-quantity {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sticky-qty-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sticky-qty-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .sticky-qty-input {
            width: 50px;
            height: 35px;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            outline: none;
        }

        .sticky-cart-actions {
            display: flex;
            gap: 1rem;
        }

        .sticky-btn-add-cart,
        .sticky-btn-buy-now {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-family: 'Cairo', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .sticky-btn-add-cart {
            background: linear-gradient(135deg, var(--brand-yellow-light) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
            border: 2px solid var(--brand-yellow-dark);
        }

        .sticky-btn-add-cart:hover {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-hover) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }

        .sticky-btn-buy-now {
            background: linear-gradient(135deg, var(--brand-yellow-dark) 0%, var(--brand-yellow) 100%);
            color: var(--brand-brown);
        }

        .sticky-btn-buy-now:hover {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-hover) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }

        .sticky-btn-add-cart:disabled,
        .sticky-btn-buy-now:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .product-options-section {
                order: 2;
            }

            .product-title {
                font-size: 2rem;
            }

            .price {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .product-options-section {
                padding: 1rem;
            }

            .product-title {
                font-size: 1.8rem;
            }

            .option-buttons {
                flex-direction: column;
            }

            .option-btn span {
                display: block;
                text-align: center;
            }

            .price-section {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            /* Sticky cart responsive */
            .sticky-cart-content {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .sticky-cart-info {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }

            .sticky-cart-actions {
                width: 100%;
                justify-content: center;
            }

            .sticky-btn-add-cart,
            .sticky-btn-buy-now {
                flex: 1;
                justify-content: center;
            }
        }
    </style>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('cartUpdated', () => {
                // Optional: Add any client-side cart update logic here
                console.log('Cart updated');
            });

            // Handle redirect to login
            Livewire.on('redirectToLogin', () => {
                // Store the current URL for redirect after login
                sessionStorage.setItem('url.intended', window.location.href);
                
                // Redirect to login page
                window.location.href = '{{ route('login') }}';
            });

            // Handle redirect to cart
            Livewire.on('redirectToCart', () => {
                window.location.href = '{{ route('cart.index') }}';
            });

            // Test toaster functionality
            Livewire.on('showToast', (...args) => {
                console.log('Toast event received:', args);
            });
        });

        // Test function for debugging
        function testToaster() {
            if (window.toaster) {
                window.toaster.show('This is a test message', 'success', 'Test Title', 3000);
            } else {
                console.error('Toaster not available');
            }
        }
    </script>
</div>
