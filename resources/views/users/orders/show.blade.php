@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ trans('orders.order_details') }}</h1>
                    <p class="text-gray-600">{{ trans('orders.view_order_information') }}</p>
                </div>
                <a href="{{ route('user.orders.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-left pl-2"></i>
                    <span>{{ trans('orders.back_to_orders') }}</span>
                </a>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Header with Order Number -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div class="text-white">
                            <h2 class="text-3xl font-bold">{{ $order->order_number }}</h2>
                            <p class="text-blue-100 text-lg">{{ trans('orders.order_date') }}:
                                {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                            <div class="flex items-center mt-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'processing' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ trans('orders.' . $order->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-white text-right">
                            <p class="text-2xl font-bold">{{ number_format($order->total, 2) }}
                                {{ trans('orders.currency') }}</p>
                            <p class="text-blue-100">{{ trans('orders.total_amount') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Order Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                {{ trans('orders.order_information') }}
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500">{{ trans('orders.status') }}</label>
                                    <div class="mt-1">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ trans('orders.' . $order->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500">{{ trans('orders.subtotal') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ number_format($order->subtotal, 2) }}
                                        {{ trans('orders.currency') }}</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500">{{ trans('orders.tax') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ number_format($order->tax, 2) }}
                                        {{ trans('orders.currency') }}</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500">{{ trans('orders.total') }}</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ number_format($order->total, 2) }} {{ trans('orders.currency') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                {{ trans('orders.contact_information') }}
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500">{{ trans('orders.phone') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $order->phone ?: trans('orders.not_provided') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Addresses -->
                    @if ($order->shipping_address || $order->billing_address)
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                @if ($order->shipping_address)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                                            {{ trans('orders.shipping_address') }}
                                        </h3>
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->shipping_address }}
                                        </p>
                                    </div>
                                @endif

                                @if ($order->billing_address)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                                            {{ trans('orders.billing_address') }}
                                        </h3>
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->billing_address }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if ($order->notes)
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                                {{ trans('orders.notes') }}
                            </h3>
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->notes }}</p>
                        </div>
                    @endif

                    <!-- Order Items -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ trans('orders.order_items') }} ({{ $order->items->count() }})
                        </h3>

                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans('orders.product') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans('orders.quantity') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans('orders.unit_price') }}
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ trans('orders.total_price') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if ($item->product->image)
                                                        <img class="h-10 w-10 rounded object-cover ml-3"
                                                            src="{{ asset($item->product->image) }}"
                                                            alt="{{ $item->product->name }}">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center ml-3">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->product->name }}</div>
                                                        @if ($item->formatted_options)
                                                            <div class="text-sm text-gray-500">
                                                                {{ $item->formatted_options }}</div>
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
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                                                {{ number_format($item->total_price, 2) }} {{ trans('orders.currency') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
