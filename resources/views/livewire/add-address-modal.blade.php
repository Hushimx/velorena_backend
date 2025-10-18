<div>
    <!-- Modal Trigger Button -->
    <button type="button" wire:click="openModal" class="add-address-btn" style="display: none;">
        <i class="fas fa-plus"></i>
        <span>{{ trans('orders.add_new_address') }}</span>
    </button>

    <!-- Modal -->
    @if($showModal)
        <div class="modal-overlay" wire:click="closeModal" wire:keydown.escape="closeModal" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; z-index: 99999 !important;">
            <div class="modal-content" wire:click.stop>
                <div class="modal-header">
                    <h3 class="modal-title">{{ trans('orders.add_new_address') }}</h3>
                    <button type="button" wire:click="closeModal" class="modal-close" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="saveAddress">
                        <!-- Address Name -->
                        <div class="form-group">
                            <label for="name">{{ trans('orders.address_name') }} *</label>
                            <input type="text" wire:model="name" id="name" class="form-control"
                                   placeholder="{{ trans('orders.enter_address_name') }}" required>
                            @error('name') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <!-- Contact Information -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_name">{{ trans('orders.contact_name') }} *</label>
                                <input type="text" wire:model="contact_name" id="contact_name" class="form-control"
                                       placeholder="{{ trans('orders.enter_contact_name') }}" required>
                                @error('contact_name') <div class="error-message">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label for="contact_phone">{{ trans('orders.contact_phone') }} *</label>
                                <input type="tel" wire:model="contact_phone" id="contact_phone" class="form-control"
                                       placeholder="{{ trans('orders.enter_contact_phone') }}" required>
                                @error('contact_phone') <div class="error-message">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Address Line -->
                        <div class="form-group">
                            <label for="address_line">{{ trans('orders.address_line') }} *</label>
                            <textarea wire:model="address_line" id="address_line" class="form-control" rows="3"
                                      placeholder="{{ trans('orders.enter_address_line') }}" required></textarea>
                            @error('address_line') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <!-- City and District -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">{{ trans('orders.city') }} *</label>
                                <input type="text" wire:model="city" id="city" class="form-control"
                                       placeholder="{{ trans('orders.enter_city') }}" required>
                                @error('city') <div class="error-message">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label for="district">{{ trans('orders.district') }} *</label>
                                <input type="text" wire:model="district" id="district" class="form-control"
                                       placeholder="{{ trans('orders.enter_district') }}" required>
                                @error('district') <div class="error-message">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Postal Code and Country -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="postal_code">{{ trans('orders.postal_code') }}</label>
                                <input type="text" wire:model="postal_code" id="postal_code" class="form-control"
                                       placeholder="{{ trans('orders.enter_postal_code') }}">
                                @error('postal_code') <div class="error-message">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label for="country">{{ trans('orders.country') }} *</label>
                                <input type="text" wire:model="country" id="country" class="form-control"
                                       placeholder="{{ trans('orders.enter_country') }}" required>
                                @error('country') <div class="error-message">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Delivery Instruction -->
                        <div class="form-group">
                            <label for="delivery_instruction">{{ trans('orders.delivery_instruction') }} *</label>
                            <select wire:model="delivery_instruction" id="delivery_instruction" class="form-control" required>
                                <option value="hand_to_me">{{ trans('orders.hand_to_me') }}</option>
                                <option value="leave_at_spot">{{ trans('orders.leave_at_spot') }}</option>
                            </select>
                            @error('delivery_instruction') <div class="error-message">{{ $message }}</div> @enderror
                        </div>

                        <!-- Default Address -->
                        <div class="form-group">
                            <div class="default-address-toggle">
                                <label class="toggle-label">
                                    <input type="checkbox" wire:model="is_default" class="toggle-checkbox">
                                    <span class="toggle-slider"></span>
                                    <span class="toggle-text">{{ trans('orders.set_as_default_address') }}</span>
                                </label>
                                <p class="toggle-description">{{ trans('orders.default_address_description') }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="modal-actions">
                            <button type="button" wire:click="closeModal" class="btn-cancel">
                                {{ trans('orders.cancel') }}
                            </button>
                            <button type="submit" class="btn-save" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ trans('orders.save_address') }}</span>
                                <span wire:loading>{{ trans('orders.saving') }}...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <style>
        /* Add Address Button */
        .add-address-btn {
            background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
            color: var(--brand-brown);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Cairo', cursive;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(255, 222, 159, 0.3);
            margin-top: 1rem;
            width: 100%;
            justify-content: center;
        }

        .add-address-btn:hover {
            background: linear-gradient(135deg, var(--brand-yellow-dark) 0%, var(--brand-yellow) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 222, 159, 0.4);
            color: var(--brand-brown);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999 !important;
            padding: 1rem;
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
            position: relative;
            border: 1px solid rgba(255, 222, 159, 0.3);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Modal Header Enhancement */
        .modal-header {
            padding: 2rem 2rem 1rem;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 20px 20px 0 0;
        }

        .modal-title {
            font-family: 'Cairo', cursive;
            font-size: 1.6rem;
            font-weight: 700;
            color: #2C2C2C;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-title::before {
            content: '📍';
            font-size: 1.4rem;
        }

        .modal-close {
            background: #f3f4f6;
            border: none;
            font-size: 1.5rem;
            color: #666;
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 50%;
            transition: all 0.3s ease;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #e5e7eb;
            color: #2C2C2C;
            transform: scale(1.1);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2C2C2C;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand-yellow);
            box-shadow: 0 0 0 3px rgba(255, 222, 159, 0.2);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Toggle Switch Styles */
        .default-address-toggle {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .default-address-toggle:hover {
            border-color: var(--brand-yellow);
            background: linear-gradient(135deg, #fff9e6 0%, #fef3c7 100%);
        }

        .toggle-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            color: #2C2C2C;
            margin-bottom: 0.5rem;
        }

        .toggle-checkbox {
            display: none;
        }

        .toggle-slider {
            position: relative;
            width: 50px;
            height: 26px;
            background: #cbd5e1;
            border-radius: 26px;
            margin-left: 0.75rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 22px;
            height: 22px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-checkbox:checked + .toggle-slider {
            background: var(--brand-yellow);
        }

        .toggle-checkbox:checked + .toggle-slider::before {
            transform: translateX(24px);
        }

        .toggle-text {
            font-size: 1rem;
            font-weight: 600;
        }

        .toggle-description {
            color: #64748b;
            font-size: 0.875rem;
            margin: 0;
            line-height: 1.5;
        }

        /* Modal Actions */
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #666;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
            color: #2C2C2C;
        }

        .btn-save {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-save:hover:not(:disabled) {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-1px);
        }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
                border-radius: 16px;
            }

            .modal-header,
            .modal-body {
                padding: 1.5rem;
            }

            .modal-title {
                font-size: 1.4rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-actions {
                flex-direction: column;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
            }

            .default-address-toggle {
                padding: 1rem;
            }
        }
    </style>
</div>
