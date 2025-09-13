<div class="orders-table-container">
    <!-- Success/Error Messages -->
    <x-session-message type="message" />
    <x-session-message type="error" />

    <!-- Search and Filters -->
    <div class="search-filters-card">
        <div class="search-filters-content">
            <div class="search-filter-item">
                <label for="search" class="search-filter-label">{{ trans('orders.search') }}</label>
                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                    placeholder="{{ trans('orders.my_orders_search_placeholder') }}" class="search-input">
            </div>
            <div class="search-filter-item">
                <label for="status_filter" class="search-filter-label">{{ trans('orders.status_filter') }}</label>
                <select wire:model.live="status_filter" wire:key="status-filter" class="search-select">
                    <option value="">{{ trans('orders.all_statuses') }}</option>
                    <option value="pending">{{ trans('orders.pending') }}</option>
                    <option value="confirmed">{{ trans('orders.confirmed') }}</option>
                    <option value="processing">{{ trans('orders.processing') }}</option>
                    <option value="shipped">{{ trans('orders.shipped') }}</option>
                    <option value="delivered">{{ trans('orders.delivered') }}</option>
                    <option value="cancelled">{{ trans('orders.cancelled') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-table-card">
        <div class="table-container">
            <table class="orders-table">
                <thead class="table-header">
                    <tr>
                        <th class="table-header-cell">{{ trans('orders.order_number') }}</th>
                        <th class="table-header-cell">{{ trans('orders.status') }}</th>
                        <th class="table-header-cell">{{ trans('orders.total') }}</th>
                        <th class="table-header-cell">{{ trans('orders.items_count') }}</th>
                        <th class="table-header-cell">{{ trans('orders.created_at') }}</th>
                        <th class="table-header-cell">{{ trans('orders.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @forelse($orders as $order)
                        <tr class="table-row" wire:key="order-row-{{ $order->id }}">
                            <td class="table-cell">
                                <div class="order-number-info">
                                    <div class="order-number">{{ $order->order_number }}</div>
                                    @if ($order->phone)
                                        <div class="order-phone">{{ $order->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="table-cell">
                                <span class="order-status-badge {{ $order->status }}">
                                    {{ trans('orders.' . $order->status) }}
                                </span>
                            </td>
                            <td class="table-cell">
                                <div class="order-total">
                                    {{ number_format($order->total, 2) }} {{ trans('orders.currency') }}
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="order-items-count">
                                    {{ $order->items->count() }} {{ trans('orders.items') }}
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="order-date">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="order-actions">
                                    <!-- View Button -->
                                    <a href="{{ route('user.orders.show', $order) }}" class="action-btn view-btn">
                                        <i class="fas fa-eye"></i>
                                        <span>{{ trans('orders.show') }}</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <button wire:click="confirmDelete({{ $order->id }})"
                                        wire:key="delete-order-{{ $order->id }}" class="action-btn delete-btn">
                                        <i class="fas fa-trash"></i>
                                        <span>{{ trans('orders.delete') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-state-content">
                                    <i class="fas fa-box-open"></i>
                                    <h3>{{ trans('orders.no_orders_exist') }}</h3>
                                    <p>{{ trans('orders.no_orders_description') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
            <div class="pagination-container">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

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
                        <p>{{ trans('orders.confirm_delete_order') }}</p>
                    </div>
                    <div class="delete-modal-actions">
                        <button wire:click="deleteOrder" wire:loading.attr="disabled" wire:loading.class="loading"
                            class="modal-btn delete-btn">
                            <span wire:loading.remove wire:target="deleteOrder">
                                <i class="fas fa-trash"></i>
                                {{ trans('orders.delete') }}
                            </span>
                            <span wire:loading wire:target="deleteOrder" class="loading-spinner">
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
        /* Orders Table Styles - Based on Product Show Page Design */
        .orders-table-container {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }


        /* Search and Filters */
        .search-filters-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .search-filters-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #c4a700;
        }

        .search-filters-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .search-filter-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .search-filter-label {
            font-weight: 600;
            color: #2C2C2C;
            font-size: 1rem;
        }

        .search-input,
        .search-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            background: #fff;
            font-family: 'Cairo', sans-serif;
        }

        .search-input:focus,
        .search-select:focus {
            outline: none;
            border-color: #c4a700;
            box-shadow: 0 0 0 3px rgba(196, 167, 0, 0.1);
        }

        /* Orders Table */
        .orders-table-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .orders-table-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #c4a700;
        }

        .table-container {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Table Header */
        .table-header {
            background: linear-gradient(135deg, #FFEBC6 0%, #FFD700 100%);
        }

        .table-header-cell {
            padding: 1rem;
            text-align: right;
            font-weight: 700;
            color: #2C2C2C;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #c4a700;
            font-family: 'Cairo', cursive;
        }

        /* Table Body */
        .table-body {
            background: #fff;
        }

        .table-row {
            border-bottom: 1px solid #e5e7eb;
        }

        .table-row:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .table-cell {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Order Information */
        .order-number-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .order-number {
            font-weight: 700;
            color: #2C2C2C;
            font-size: 1rem;
            font-family: 'Cairo', cursive;
        }

        .order-phone {
            font-size: 0.85rem;
            color: #666;
        }

        /* Status Badges */
        .order-status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            border: 2px solid;
            display: inline-block;
        }

        .order-status-badge.pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-color: #ffc107;
        }

        .order-status-badge.confirmed {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border-color: #17a2b8;
        }

        .order-status-badge.processing {
            background: linear-gradient(135deg, #e2e3f0 0%, #c7c9e8 100%);
            color: #383d61;
            border-color: #6f42c1;
        }

        .order-status-badge.shipped {
            background: linear-gradient(135deg, #cce5ff 0%, #99d6ff 100%);
            color: #004085;
            border-color: #007bff;
        }

        .order-status-badge.delivered {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-color: #28a745;
        }

        .order-status-badge.cancelled {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-color: #dc3545;
        }

        /* Order Details */
        .order-total {
            font-weight: 700;
            color: #c4a700;
            font-family: 'Cairo', cursive;
            font-size: 1.1rem;
        }

        .order-items-count {
            font-weight: 600;
            color: #2C2C2C;
        }

        .order-date {
            color: #666;
            font-size: 0.9rem;
        }

        /* Action Buttons */
        .order-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: 'Cairo', cursive;
        }

        .view-btn {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #28a745;
        }

        .view-btn:hover {
            background: linear-gradient(135deg, #c3e6cb 0%, #a3d9a4 100%);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .delete-btn {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #dc3545;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #f5c6cb 0%, #f1aeb5 100%);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .empty-state-content i {
            font-size: 3rem;
            color: #c4a700;
            margin-bottom: 1rem;
        }

        .empty-state-content h3 {
            font-family: 'Cairo', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2C2C2C;
            margin: 0;
        }

        .empty-state-content p {
            color: #666;
            font-size: 1rem;
            margin: 0;
        }

        /* Pagination */
        .pagination-container {
            padding: 1.5rem;
            border-top: 2px solid #e5e7eb;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Delete Modal */
        .delete-modal-overlay {
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
        }


        .delete-modal-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            position: relative;
            transform-origin: center;
        }


        .delete-modal-content {
            padding: 2rem;
            text-align: center;
        }

        .delete-modal-icon {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 2px solid #dc3545;
        }

        .delete-modal-icon i {
            font-size: 1.5rem;
            color: #dc3545;
        }

        .delete-modal-title {
            font-family: 'Cairo', cursive;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2C2C2C;
            margin: 0 0 1rem 0;
        }

        .delete-modal-message {
            margin-bottom: 2rem;
        }

        .delete-modal-message p {
            color: #666;
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
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Cairo', cursive;
            font-size: 1rem;
            border: 2px solid;
            min-width: 120px;
            justify-content: center;
        }

        .modal-btn.delete-btn {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-color: #dc3545;
        }

        .modal-btn.delete-btn:hover {
            background: linear-gradient(135deg, #f5c6cb 0%, #f1aeb5 100%);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .modal-btn.cancel-btn {
            background: #fff;
            color: #2C2C2C;
            border-color: #e5e7eb;
        }

        .modal-btn.cancel-btn:hover {
            background: #FFEBC6;
            border-color: #c4a700;
            box-shadow: 0 4px 15px rgba(196, 167, 0, 0.2);
        }

        .modal-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .loading-spinner {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-filters-content {
                grid-template-columns: 1fr;
            }

            .table-container {
                font-size: 0.9rem;
            }

            .table-header-cell,
            .table-cell {
                padding: 0.75rem 0.5rem;
            }

            .order-actions {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }

            .delete-modal-actions {
                flex-direction: column;
            }

            .modal-btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {

            .search-filters-card,
            .orders-table-card {
                padding: 1rem;
            }

            .table-header-cell {
                font-size: 0.8rem;
                padding: 0.5rem 0.25rem;
            }

            .table-cell {
                padding: 0.5rem 0.25rem;
            }

            .order-number,
            .order-total {
                font-size: 0.9rem;
            }

            .order-phone,
            .order-date {
                font-size: 0.8rem;
            }
        }
    </style>
</div>
