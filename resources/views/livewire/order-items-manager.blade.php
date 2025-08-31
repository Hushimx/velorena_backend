<div>
    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
            <button wire:click="$refresh" class="ml-2 px-2 py-1 bg-green-600 text-white rounded text-xs">
                Refresh
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
            <button wire:click="$refresh" class="ml-2 px-2 py-1 bg-red-600 text-white rounded text-xs">
                Refresh
            </button>
        </div>
    @endif

    <!-- Order Items Table -->
    <div class="bg-gray-50 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ trans('orders.product') }}
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ trans('orders.quantity') }}
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ trans('orders.unit_price') }}
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ trans('orders.total_price') }}
                    </th>
                    @if ($order->status === 'pending')
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('actions') }}
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orderItems as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if ($item->product->image)
                                    <img class="h-10 w-10 rounded object-cover ml-3"
                                        src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}">
                                @else
                                    <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center ml-3">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->product->name }}
                                    </div>
                                    @if ($item->formatted_options)
                                        <div class="text-sm text-gray-500">
                                            {{ $item->formatted_options }}
                                        </div>
                                    @endif
                                    @if ($item->notes)
                                        <div class="text-sm text-gray-400">
                                            {{ $item->notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ number_format($item->unit_price, 2) }} {{ trans('orders.currency') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                            {{ number_format($item->total_price, 2) }} {{ trans('orders.currency') }}
                        </td>
                        @if ($order->status === 'pending')
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="confirmDelete({{ $item->id }})"
                                    wire:key="delete-item-{{ $item->id }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition duration-150 ease-in-out">
                                    <i class="fas fa-trash mx-1.5"></i>
                                    <span>{{ trans('orders.delete') }}</span>
                                </button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $order->status === 'pending' ? 5 : 4 }}"
                            class="px-6 py-4 text-center text-gray-500">
                            {{ trans('orders.no_items_in_order') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Order Summary (if pending) -->
    @if ($order->status === 'pending' && $orderItems->count() > 0)
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">{{ trans('orders.order_summary') }}</h4>
                    <p class="text-sm text-gray-600">{{ trans('orders.items_count') }}: {{ $orderItems->count() }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">{{ trans('orders.subtotal') }}:
                        {{ number_format($order->subtotal, 2) }} {{ trans('orders.currency') }}</p>
                    <p class="text-sm text-gray-600">{{ trans('orders.tax') }}: {{ number_format($order->tax, 2) }}
                        {{ trans('orders.currency') }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ trans('orders.total') }}:
                        {{ number_format($order->total, 2) }} {{ trans('orders.currency') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="delete-modal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">
                        {{ trans('orders.confirm_delete_title') }}</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            {{ trans('orders.confirm_delete_order_item') }}
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="deleteOrderItem" wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50">
                            <span wire:loading.remove wire:target="deleteOrderItem">{{ trans('orders.delete') }}</span>
                            <span wire:loading wire:target="deleteOrderItem" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <button wire:click="cancelDelete" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50">
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
</div>
