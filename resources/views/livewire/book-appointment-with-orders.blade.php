<div class="appointment-booking-container">
    <!-- Success/Error Messages -->
    <x-session-message type="message" />
    <x-session-message type="error" />

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="error-messages-card">
            <div class="error-messages-content">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="error-details">
                    <h4 class="error-title">{{ trans('dashboard.fix_errors') }}</h4>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Appointment Form -->
    <div class="appointment-form-card">
        <div class="appointment-form-content">
            <form wire:submit.prevent="bookAppointment">

                <!-- Step 1: Date Selection -->
                <div class="form-step">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="step-info">
                            <h4 class="step-title">{{ trans('dashboard.select_date') }}</h4>
                            <p class="step-description">{{ trans('dashboard.choose_meeting_time') }}</p>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="appointment_date" class="form-label">
                                    {{ trans('dashboard.available_date') }} <span class="required">*</span>
                                </label>
                                <input type="datetime-local" wire:model.live="appointment_date"
                                    min="{{ now()->addMinutes(1)->format('Y-m-d\TH:i') }}"
                                    max="{{ now()->addMonths(3)->format('Y-m-d\TH:i') }}" id="appointment_date"
                                    class="form-input">
                                @error('appointment_date')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-info">
                                <div class="info-text">
                                    <strong>{{ trans('dashboard.available_dates') }}:</strong><br>
                                    {{ now()->addMinutes(1)->format('M j, Y g:i A') }} to
                                    {{ now()->addMonths(3)->format('M j, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Order Selection -->
                <div class="form-step">
                    <div class="step-header">
                        <div class="step-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="step-info">
                            <h4 class="step-title">{{ trans('dashboard.select_order') }}</h4>
                            <p class="step-description">{{ trans('dashboard.link_order_description') }}</p>
                        </div>
                    </div>

                    <div class="step-content">
                        @if ($user_orders->count() > 0)
                            <div class="orders-section">
                                <div class="orders-header">
                                    <h5 class="orders-title">
                                        {{ trans('dashboard.your_orders') }} ({{ $user_orders->count() }})
                                    </h5>
                                    <div class="orders-actions">
                                        <button type="button" wire:click="toggleUsedOrders" class="toggle-btn">
                                            {{ $show_used_orders ? trans('dashboard.hide_used_orders') : trans('dashboard.show_all_orders') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Orders Grid -->
                                <div class="orders-grid">
                                    @foreach ($user_orders as $order)
                                        @php
                                            $isUsed = $order->appointment;
                                        @endphp
                                        <div class="order-card {{ $selected_order_id == $order->id ? 'selected' : '' }} {{ $isUsed ? 'used' : '' }}"
                                            wire:click="selectOrder({{ $order->id }})">

                                            @if ($isUsed)
                                                <div class="order-badge used">
                                                    <i class="fas fa-link"></i>
                                                    {{ trans('dashboard.already_linked_to_appointment') }}
                                                </div>
                                            @endif

                                            <div class="order-content">
                                                <div class="order-radio">
                                                    <div
                                                        class="radio-circle {{ $selected_order_id == $order->id ? 'checked' : '' }}">
                                                        @if ($selected_order_id == $order->id)
                                                            <div class="radio-dot"></div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="order-details">
                                                    <div class="order-header">
                                                        <div class="order-info">
                                                            <div class="order-number">{{ $order->order_number }}</div>
                                                            <div class="order-date">
                                                                {{ $order->created_at->format('M j, Y') }}</div>
                                                        </div>
                                                        <div class="order-summary">
                                                            <div class="order-total">
                                                                ${{ number_format($order->total, 2) }}</div>
                                                            <div class="order-items">{{ $order->items->count() }}
                                                                {{ trans('dashboard.items') }}</div>
                                                        </div>
                                                    </div>

                                                    <!-- Order Items Preview -->
                                                    <div class="order-items-preview">
                                                        @foreach ($order->items->take(3) as $item)
                                                            <div class="order-item">
                                                                <span class="item-name">
                                                                    {{ $item->product->name ?? trans('dashboard.unknown_product') }}
                                                                    (x{{ $item->quantity }})
                                                                </span>
                                                                <span
                                                                    class="item-price">${{ number_format($item->total_price, 2) }}</span>
                                                            </div>
                                                        @endforeach
                                                        @if ($order->items->count() > 3)
                                                            <div class="more-items">
                                                                +{{ $order->items->count() - 3 }}
                                                                {{ trans('dashboard.more_items') }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Order Notes Input -->
                                                    @if ($selected_order_id == $order->id)
                                                        <div class="order-notes-section">
                                                            <label
                                                                class="notes-label">{{ trans('dashboard.order_notes') }}</label>
                                                            <textarea wire:model="order_notes" rows="2" placeholder="{{ trans('dashboard.order_notes_placeholder') }}"
                                                                class="notes-textarea"></textarea>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('selected_order_id')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="empty-orders">
                                <div class="empty-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h5 class="empty-title">{{ trans('dashboard.no_orders_yet') }}</h5>
                                <p class="empty-message">{{ trans('dashboard.no_orders_message') }}</p>
                                <a href="{{ route('user.products.index') }}" class="browse-products-btn">
                                    <i class="fas fa-plus"></i>
                                    {{ trans('dashboard.browse_products') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Selected Order Summary -->
                @if ($selected_order_id)
                    <div class="selected-order-summary">
                        <div class="summary-header">
                            <i class="fas fa-check-circle"></i>
                            <h5 class="summary-title">{{ trans('dashboard.selected_order_summary') }}</h5>
                        </div>
                        <div class="summary-stats">
                            <div class="stat-item">
                                <span class="stat-label">{{ trans('dashboard.order_selected') }}:</span>
                                <span class="stat-value">1</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">{{ trans('dashboard.total_products') }}:</span>
                                <span class="stat-value">{{ $selected_order_products_count }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">{{ trans('dashboard.total_value') }}:</span>
                                <span class="stat-value">${{ number_format($selected_order_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 3: Notes -->
                @if ($appointment_date)
                    <div class="form-step">
                        <div class="step-header">
                            <div class="step-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="step-info">
                                <h4 class="step-title">{{ trans('dashboard.additional_information') }}</h4>
                                <p class="step-description">{{ trans('dashboard.tell_about_project') }}</p>
                            </div>
                        </div>

                        <div class="step-content">
                            <div class="form-group">
                                <label for="notes"
                                    class="form-label">{{ trans('dashboard.project_details') }}</label>
                                <textarea wire:model="notes" rows="4" id="notes"
                                    placeholder="{{ trans('dashboard.project_placeholder') }}" class="form-textarea"></textarea>
                                @error('notes')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                                <small class="form-help">{{ trans('dashboard.max_characters') }}</small>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Appointment Summary -->
                @if ($appointment_date)
                    <div class="appointment-summary">
                        <div class="summary-header">
                            <i class="fas fa-check-circle"></i>
                            <h5 class="summary-title">{{ trans('dashboard.appointment_summary') }}</h5>
                        </div>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="summary-info">
                                    <span class="summary-label">{{ trans('dashboard.designer') }}</span>
                                    <span class="summary-value">{{ trans('dashboard.will_be_assigned') }}</span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="summary-info">
                                    <span class="summary-label">{{ trans('dashboard.date') }}</span>
                                    <span
                                        class="summary-value">{{ \Carbon\Carbon::parse($appointment_date)->format('l, F j, Y') }}</span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="summary-info">
                                    <span class="summary-label">{{ trans('dashboard.time') }}</span>
                                    <span class="summary-value">
                                        {{ \Carbon\Carbon::parse($appointment_date)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($appointment_date)->addMinutes($duration_minutes)->format('g:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-stopwatch"></i>
                                </div>
                                <div class="summary-info">
                                    <span class="summary-label">{{ trans('dashboard.duration') }}</span>
                                    <span class="summary-value">{{ $duration_minutes }}
                                        {{ trans('dashboard.minutes') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary in Appointment Summary -->
                        @if ($selected_order_id)
                            <div class="linked-order-section">
                                <h6 class="linked-order-title">{{ trans('dashboard.linked_order') }}:</h6>
                                <div class="linked-order-stats">
                                    <div class="linked-stat">
                                        <span class="linked-label">{{ trans('dashboard.order_count') }}:</span>
                                        <span class="linked-value">1</span>
                                    </div>
                                    <div class="linked-stat">
                                        <span class="linked-label">{{ trans('dashboard.total_value') }}:</span>
                                        <span
                                            class="linked-value">${{ number_format($selected_order_total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Submit Button -->
                @if ($appointment_date && $selected_order_id)
                    <div class="submit-section">
                        <button type="submit" wire:loading.attr="disabled" wire:target="bookAppointment"
                            class="submit-btn">
                            <span wire:loading.remove wire:target="bookAppointment">
                                <i class="fas fa-calendar-check"></i>
                                {{ trans('dashboard.book_consultation') }}
                            </span>
                            <span wire:loading wire:target="bookAppointment" class="loading-content">
                                <i class="fas fa-spinner fa-spin"></i>
                                {{ trans('dashboard.booking') }}
                            </span>
                        </button>
                    </div>
                @elseif ($appointment_date && !$selected_order_id)
                    <div class="warning-section">
                        <div class="warning-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ trans('dashboard.select_order_required') }}
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="features-section">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h5 class="feature-title">{{ trans('dashboard.quick_easy') }}</h5>
                <p class="feature-description">{{ trans('dashboard.quick_easy_desc') }}</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h5 class="feature-title">{{ trans('dashboard.link_orders') }}</h5>
                <p class="feature-description">{{ trans('dashboard.link_orders_desc') }}</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="feature-title">{{ trans('dashboard.expert_designers') }}</h5>
                <p class="feature-description">{{ trans('dashboard.expert_designers_desc') }}</p>
            </div>
        </div>
    </div>

    <style>
        /* Appointment Booking Styles - Based on Product Show Page Design */
        .appointment-booking-container {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }

        /* Error Messages */
        .error-messages-card {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 1px solid #f5c6cb;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
        }

        .error-messages-content {
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .error-icon {
            color: #dc3545;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .error-title {
            color: #721c24;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0 0 0.5rem 0;
        }

        .error-list {
            color: #721c24;
            margin: 0;
            padding-right: 1rem;
        }

        /* Main Form Card */
        .appointment-form-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .appointment-form-content {
            padding: 2rem;
        }

        /* Form Steps */
        .form-step {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid rgba(255, 235, 198, 0.3);
        }

        .form-step:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .step-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4513;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.3);
        }

        .step-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0 0 0.25rem 0;
        }

        .step-description {
            color: #6c757d;
            font-size: 1rem;
            margin: 0;
        }

        /* Form Elements */
        .step-content {
            background: linear-gradient(135deg, rgba(255, 235, 198, 0.1) 0%, rgba(244, 208, 63, 0.05) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 235, 198, 0.3);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .required {
            color: #dc3545;
        }

        .form-input,
        .form-textarea {
            padding: 1rem 1.25rem;
            border: 2px solid rgba(255, 235, 198, 0.5);
            border-radius: 12px;
            font-size: 1rem;
            background: #ffffff;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #F4D03F;
            box-shadow: 0 0 0 3px rgba(244, 208, 63, 0.2);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .form-help {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-info {
            display: flex;
            align-items: center;
        }

        .info-text {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Orders Section */
        .orders-section {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 235, 198, 0.3);
        }

        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .orders-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
        }

        .toggle-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1rem;
            max-height: 400px;
            overflow-y: auto;
            padding: 0.5rem;
        }

        /* Order Card */
        .order-card {
            background: #ffffff;
            border: 2px solid rgba(255, 235, 198, 0.3);
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .order-card:hover {
            border-color: #F4D03F;
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.2);
        }

        .order-card.selected {
            border-color: #28a745;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.1) 100%);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        }

        .order-card.used {
            opacity: 0.7;
        }

        .order-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: #8B4513;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .order-content {
            display: flex;
            gap: 1rem;
        }

        .order-radio {
            flex-shrink: 0;
            display: flex;
            align-items: flex-start;
            padding-top: 0.25rem;
        }

        .radio-circle {
            width: 20px;
            height: 20px;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .radio-circle.checked {
            border-color: #28a745;
            background: #28a745;
        }

        .radio-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        .order-details {
            flex: 1;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .order-number {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .order-date {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .order-total {
            font-weight: 700;
            color: #28a745;
            font-size: 1.1rem;
        }

        .order-items {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .order-items-preview {
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .item-name {
            color: #6c757d;
        }

        .item-price {
            color: #495057;
            font-weight: 600;
        }

        .more-items {
            color: #6c757d;
            font-size: 0.75rem;
            font-style: italic;
        }

        .order-notes-section {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .notes-label {
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .notes-textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid rgba(255, 235, 198, 0.5);
            border-radius: 8px;
            font-size: 0.875rem;
            resize: vertical;
            font-family: 'Cairo', sans-serif;
        }

        .notes-textarea:focus {
            outline: none;
            border-color: #F4D03F;
            box-shadow: 0 0 0 2px rgba(244, 208, 63, 0.2);
        }

        /* Empty Orders */
        .empty-orders {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .empty-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .empty-message {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .browse-products-btn {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .browse-products-btn:hover {
            background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        /* Summary Cards */
        .selected-order-summary,
        .appointment-summary {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.1) 100%);
            border: 2px solid rgba(40, 167, 69, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .summary-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .summary-header i {
            color: #28a745;
            font-size: 1.25rem;
        }

        .summary-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            color: #28a745;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .summary-item {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4513;
            flex-shrink: 0;
        }

        .summary-info {
            display: flex;
            flex-direction: column;
        }

        .summary-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .summary-value {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1rem;
        }

        .linked-order-section {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(40, 167, 69, 0.2);
        }

        .linked-order-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .linked-order-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
        }

        .linked-stat {
            background: white;
            padding: 0.75rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }

        .linked-label {
            color: #6c757d;
            font-size: 0.875rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .linked-value {
            color: #007bff;
            font-weight: 700;
        }

        /* Submit Section */
        .submit-section {
            text-align: center;
            margin-top: 2rem;
        }

        .submit-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 1.25rem 3rem;
            border-radius: 16px;
            font-size: 1.25rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .submit-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Warning Section */
        .warning-section {
            text-align: center;
            margin-top: 2rem;
        }

        .warning-message {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            padding: 1rem 2rem;
            border-radius: 12px;
            border: 1px solid #ffeaa7;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        /* Features Section */
        .features-section {
            margin-top: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #8B4513;
            font-size: 2rem;
            box-shadow: 0 4px 15px rgba(244, 208, 63, 0.3);
        }

        .feature-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #6c757d;
            line-height: 1.6;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .appointment-form-content {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .orders-grid {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .summary-stats {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .submit-btn {
                padding: 1rem 2rem;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .step-header {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .step-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .step-title {
                font-size: 1.25rem;
            }

            .order-content {
                flex-direction: column;
                gap: 0.75rem;
            }

            .order-radio {
                align-self: flex-start;
            }
        }
    </style>
</div>
