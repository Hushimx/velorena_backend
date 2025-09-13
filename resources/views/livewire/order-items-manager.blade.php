<div class="order-items-manager-container">
    <!-- Success/Error Messages -->
    <x-session-message type="message">
        <button wire:click="$refresh" class="refresh-btn">
            <i class="fas fa-sync-alt"></i>
            {{ trans('dashboard.refresh') }}
        </button>
    </x-session-message>

    <x-session-message type="error">
        <button wire:click="$refresh" class="refresh-btn">
            <i class="fas fa-sync-alt"></i>
            {{ trans('dashboard.refresh') }}
        </button>
    </x-session-message>

    <!-- Order Items Table -->
    <div class="items-table-card">
        <div class="table-container">
            <table class="items-table">
                <thead class="table-header">
                    <tr>
                        <th class="table-header-cell">{{ trans('orders.product') }}</th>
                        <th class="table-header-cell text-center">{{ trans('orders.quantity') }}</th>
                        <th class="table-header-cell text-center">{{ trans('orders.unit_price') }}</th>
                        <th class="table-header-cell text-center">{{ trans('orders.total_price') }}</th>
                        @if ($order->status === 'pending')
                            <th class="table-header-cell text-center">{{ trans('orders.actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="table-body">
                    @forelse($orderItems as $item)
                        <tr class="table-row" wire:key="item-row-{{ $item->id }}">
                            <td class="table-cell">
                                <div class="product-info">
                                    <div class="product-image">
                                        @if ($item->product->image)
                                            <img src="{{ asset($item->product->image) }}"
                                                alt="{{ $item->product->name }}">
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-details">
                                        <div class="product-name">{{ $item->product->name }}</div>
                                        @if ($item->formatted_options)
                                            <div class="product-options">{{ $item->formatted_options }}</div>
                                        @endif
                                        @if ($item->notes)
                                            <div class="product-notes">{{ $item->notes }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell text-center">
                                <div class="quantity-badge">{{ $item->quantity }}</div>
                            </td>
                            <td class="table-cell text-center">
                                <div class="price-value">{{ number_format($item->unit_price, 2) }}
                                    {{ trans('orders.currency') }}</div>
                            </td>
                            <td class="table-cell text-center">
                                <div class="total-price">{{ number_format($item->total_price, 2) }}
                                    {{ trans('orders.currency') }}</div>
                            </td>
                            @if ($order->status === 'pending')
                                <td class="table-cell text-center">
                                    <div class="item-actions">
                                        <button wire:click="confirmDelete({{ $item->id }})"
                                            wire:key="delete-item-{{ $item->id }}" class="action-btn delete-btn">
                                            <i class="fas fa-trash"></i>
                                            <span>{{ trans('orders.delete') }}</span>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $order->status === 'pending' ? 5 : 4 }}" class="empty-state">
                                <div class="empty-state-content">
                                    <i class="fas fa-box-open"></i>
                                    <h3>{{ trans('orders.no_items_in_order') }}</h3>
                                    <p>{{ trans('orders.no_items_description') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Summary (if pending) -->
    @if ($order->status === 'pending' && $orderItems->count() > 0)
        <div class="order-summary-card">
            <div class="order-summary-content">
                <h4 class="summary-title">{{ trans('orders.order_summary') }}</h4>
                <div class="summary-details">
                    <div class="summary-left">
                        <div class="summary-item">
                            <span class="summary-label">{{ trans('orders.items_count') }}:</span>
                            <span class="summary-value">{{ $orderItems->count() }}</span>
                        </div>
                    </div>
                    <div class="summary-right">
                        <div class="summary-item">
                            <span class="summary-label">{{ trans('orders.subtotal') }}:</span>
                            <span class="summary-value">{{ number_format($order->subtotal, 2) }}
                                {{ trans('orders.currency') }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">{{ trans('orders.tax') }}:</span>
                            <span class="summary-value">{{ number_format($order->tax, 2) }}
                                {{ trans('orders.currency') }}</span>
                        </div>
                        <div class="summary-total">
                            <span class="summary-label">{{ trans('orders.total') }}:</span>
                            <span class="summary-total-value px-2">{{ number_format($order->total, 2) }}
                                {{ trans('orders.currency') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="delete-modal-overlay">
            <div class="delete-modal-container">
                <div class="delete-modal-content">
                    <div class="delete-modal-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="delete-modal-title">{{ trans('orders.confirm_delete_title') }}</h3>
                    <div class="delete-modal-message">
                        <p>{{ trans('orders.confirm_delete_order_item') }}</p>
                    </div>
                    <div class="delete-modal-actions">
                        <button wire:click="deleteOrderItem" wire:loading.attr="disabled" wire:loading.class="loading"
                            class="modal-btn delete-btn">
                            <span wire:loading.remove wire:target="deleteOrderItem">
                                <i class="fas fa-trash"></i>
                                {{ trans('orders.delete') }}
                            </span>
                            <span wire:loading wire:target="deleteOrderItem" class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                                {{ trans('orders.deleting') }}
                            </span>
                        </button>
                        <button wire:click="cancelDelete" wire:loading.attr="disabled" class="modal-btn cancel-btn">
                            <i class="fas fa-times"></i>
                            {{ trans('orders.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('modal-closed', () => {
                // Ensure modal is properly closed
                console.log('Modal closed event received');
            });
        });
    </script>

    <style>
        /* Order Items Manager Styles - Based on Product Show Page Design */
        .order-items-manager-container {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }


        /* Items Table Card */
        .items-table-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .items-table-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 16px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .table-header {
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
        }

        .table-header-cell {
            padding: 1rem 1.5rem;
            text-align: right;
            font-weight: 700;
            color: #8B4513;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(139, 69, 19, 0.1);
            position: relative;
        }

        .table-header-cell:first-child {
            border-top-right-radius: 16px;
        }

        .table-header-cell:last-child {
            border-top-left-radius: 16px;
        }

        .table-body {
            background: #ffffff;
        }

        .table-row {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table-row:hover {
            background: linear-gradient(135deg, rgba(255, 235, 198, 0.1) 0%, rgba(244, 208, 63, 0.05) 100%);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-cell {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
        }

        /* Product Info */
        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            flex-shrink: 0;
        }

        .product-image img {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255, 235, 198, 0.3);
        }

        .product-image img:hover {
            border-color: #F4D03F;
        }

        .no-image {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 235, 198, 0.3);
            color: #6c757d;
            font-size: 1.5rem;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .product-options {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            font-style: italic;
        }

        .product-notes {
            color: #28a745;
            font-size: 0.8rem;
            background: rgba(40, 167, 69, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            display: inline-block;
        }

        /* Quantity Badge */
        .quantity-badge {
            background: linear-gradient(135deg, #FFEBC6 0%, #F4D03F 100%);
            color: #8B4513;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.875rem;
            display: inline-block;
            border: 2px solid rgba(139, 69, 19, 0.2);
            box-shadow: 0 2px 8px rgba(244, 208, 63, 0.3);
        }

        /* Price Values */
        .price-value {
            color: #495057;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .total-price {
            color: #28a745;
            font-weight: 700;
            font-size: 1rem;
            background: rgba(40, 167, 69, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
        }

        /* Item Actions */
        .item-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn.delete-btn {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #d32f2f;
            border: 1px solid #ffcdd2;
        }

        .action-btn.delete-btn:hover {
            background: linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%);
            color: #b71c1c;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .empty-state-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .empty-state-content i {
            font-size: 3rem;
            color: #6c757d;
            opacity: 0.7;
        }

        .empty-state-content h3 {
            color: #495057;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
        }

        .empty-state-content p {
            color: #6c757d;
            font-size: 0.95rem;
            margin: 0;
            max-width: 400px;
        }

        /* Order Summary Card */
        .order-summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 1.5rem;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .order-summary-content {
            padding: 1.5rem;
        }

        .summary-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            text-align: center;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(255, 235, 198, 0.3);
        }

        .summary-details {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .summary-left,
        .summary-right {
            flex: 1;
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
            font-size: 0.9rem;
        }

        .summary-value {
            color: #495057;
            font-weight: 700;
            font-size: 0.9rem;
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
            font-size: 1.1rem;
        }

        /* Delete Modal */
        .delete-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .delete-modal-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 90%;
            margin: 1rem;
            overflow: hidden;
        }

        .delete-modal-content {
            padding: 2rem;
            text-align: center;
        }

        .delete-modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 3px solid #ffcdd2;
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.2);
        }

        .delete-modal-icon i {
            font-size: 2rem;
            color: #d32f2f;
        }

        .delete-modal-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .delete-modal-message {
            margin-bottom: 2rem;
        }

        .delete-modal-message p {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        .delete-modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            min-width: 120px;
            justify-content: center;
        }

        .modal-btn.delete-btn {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            color: #d32f2f;
            border: 2px solid #ffcdd2;
        }

        .modal-btn.delete-btn:hover {
            background: linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%);
            color: #b71c1c;
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.3);
        }

        .modal-btn.cancel-btn {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #6c757d;
            border: 2px solid #e9ecef;
        }

        .modal-btn.cancel-btn:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            color: #495057;
            box-shadow: 0 8px 24px rgba(108, 117, 125, 0.3);
        }

        .modal-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading-spinner {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            .table-container {
                font-size: 0.875rem;
            }

            .table-header-cell,
            .table-cell {
                padding: 0.75rem 1rem;
            }

            .product-info {
                gap: 0.75rem;
            }

            .product-image img,
            .no-image {
                width: 50px;
                height: 50px;
            }

            .summary-details {
                flex-direction: column;
                gap: 1rem;
            }

            .delete-modal-actions {
                flex-direction: column;
            }

            .modal-btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {

            .items-table-card,
            .order-summary-card {
                margin: 0 -1rem;
                border-radius: 0;
            }

            .table-container {
                border-radius: 0;
            }

            .table-header-cell:first-child,
            .table-header-cell:last-child {
                border-radius: 0;
            }

            .delete-modal-container {
                margin: 0.5rem;
                border-radius: 16px;
            }

            .delete-modal-content {
                padding: 1.5rem;
            }
        }
    </style>
</div>
