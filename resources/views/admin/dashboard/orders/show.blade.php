@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Order Details')
@section('title', trans('orders.order_details'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('orders.order_details') }}</h1>
                <p class="text-gray-600">{{ trans('orders.view_order_information') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.orders.edit', $order) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-edit pl-2"></i>
                    <span>{{ trans('orders.edit_order') }}</span>
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-left pl-2"></i>
                    <span>{{ trans('orders.back_to_orders') }}</span>
                </a>
            </div>
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
                        <p class="text-2xl font-bold">{{ number_format($order->total, 2) }} {{ trans('orders.currency') }}
                        </p>
                        <p class="text-blue-100">{{ trans('orders.total_amount') }}</p>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Customer Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            {{ trans('orders.customer_information') }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('orders.customer_name') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->user->name }}</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ trans('orders.customer_email') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->user->email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ trans('orders.phone') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->phone ?: trans('orders.not_provided') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            {{ trans('orders.order_information') }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ trans('orders.status') }}</label>
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
                                <label class="block text-sm font-medium text-gray-500">{{ trans('orders.tax') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ number_format($order->tax, 2) }}
                                    {{ trans('orders.currency') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ trans('orders.total') }}</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($order->total, 2) }}
                                    {{ trans('orders.currency') }}</p>
                            </div>

                            @php
                                $totalOptionsAdjustment = $order->items->sum(function($item) {
                                    return $item->options_price_adjustment * $item->quantity;
                                });
                            @endphp
                            
                            @if ($totalOptionsAdjustment != 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">{{ trans('orders.options_adjustment') }}</label>
                                    <p class="mt-1 text-sm {{ $totalOptionsAdjustment > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $totalOptionsAdjustment > 0 ? '+' : '' }}{{ number_format($totalOptionsAdjustment, 2) }}
                                        {{ trans('orders.currency') }}
                                    </p>
                                </div>
                            @endif
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
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->shipping_address }}</p>
                                </div>
                            @endif

                            @if ($order->billing_address)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                                        {{ trans('orders.billing_address') }}
                                    </h3>
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->billing_address }}</p>
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
                                                @php
                                                    $productImage = null;
                                                    // Try to get primary image first
                                                    $primaryImage = $item->product->images()->where('is_primary', true)->first();
                                                    if ($primaryImage && file_exists(public_path($primaryImage->image_path))) {
                                                        $productImage = asset($primaryImage->image_path);
                                                    } else {
                                                        // Fallback to first image
                                                        $firstImage = $item->product->images()->first();
                                                        if ($firstImage && file_exists(public_path($firstImage->image_path))) {
                                                            $productImage = asset($firstImage->image_path);
                                                        } elseif ($item->product->image && file_exists(public_path($item->product->image))) {
                                                            $productImage = asset($item->product->image);
                                                        }
                                                    }
                                                @endphp
                                                @if ($productImage)
                                                    <img class="h-10 w-10 rounded object-cover ml-3"
                                                        src="{{ $productImage }}"
                                                        alt="{{ $item->product->name }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center ml-3">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name }}
                                                    </div>
                                                    
                                                    <!-- Product Options Display -->
                                                    @if ($item->options && is_array($item->options) && !empty($item->options))
                                                        <div class="mt-2">
                                                            <div class="text-xs font-medium text-gray-700 mb-2 flex items-center">
                                                                <i class="fas fa-cog mr-1"></i>
                                                                {{ trans('orders.selected_options') }}:
                                                            </div>
                                                            <div class="space-y-1">
                                                                @foreach ($item->options as $optionId => $valueId)
                                                                    @php
                                                                        $option = \App\Models\ProductOption::find($optionId);
                                                                        $value = \App\Models\OptionValue::find($valueId);
                                                                    @endphp
                                                                    @if ($option && $value)
                                                                        <div class="flex items-center justify-between text-xs bg-blue-50 px-2 py-1 rounded">
                                                                            <div class="flex items-center">
                                                                                <span class="font-medium text-blue-800 bg-blue-100 px-2 py-0.5 rounded mr-2 flex items-center">
                                                                                    {{ $option->name }}
                                                                                    @if ($option->is_required)
                                                                                        <span class="ml-1 text-red-500" title="{{ trans('orders.required') }}">*</span>
                                                                                    @endif
                                                                                </span>
                                                                                <span class="text-blue-700">{{ $value->value }}</span>
                                                                            </div>
                                                                            @if ($value->price_adjustment != 0)
                                                                                <span class="text-xs font-medium {{ $value->price_adjustment > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                                    {{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 2) }} {{ trans('orders.currency') }}
                                                                                </span>
                                                                            @else
                                                                                <span class="text-xs text-gray-500">{{ trans('orders.no_price_change') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="mt-1">
                                                            <span class="text-xs text-gray-500 italic">
                                                                <i class="fas fa-info-circle mr-1"></i>
                                                                {{ trans('orders.no_options_selected') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Item Notes -->
                                                    @if ($item->notes)
                                                        <div class="mt-2">
                                                            <div class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded border-l-2 border-amber-300">
                                                                <i class="fas fa-sticky-note mr-1"></i>
                                                                <span class="font-medium">{{ trans('orders.item_notes') }}:</span>
                                                                <span class="ml-1">{{ $item->notes }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Order Item Designs -->
                                                    @if($item->designs && $item->designs->count() > 0)
                                                        <div class="mt-3">
                                                            <div class="text-xs font-medium text-gray-700 mb-2 flex items-center">
                                                                <i class="fas fa-palette mr-1"></i>
                                                                {{ trans('orders.attached_designs') }}:
                                                            </div>
                                                            <div class="space-y-2">
                                                                @foreach($item->designs as $orderItemDesign)
                                                                    @if($orderItemDesign->design)
                                                                        <div class="flex items-center gap-2 bg-purple-50 px-2 py-1 rounded border-l-2 border-purple-300">
                                                                            <div class="flex-shrink-0">
                                                                                @if($orderItemDesign->design->thumbnail_url)
                                                                                    <img src="{{ $orderItemDesign->design->thumbnail_url }}" 
                                                                                         alt="{{ $orderItemDesign->design->title }}" 
                                                                                         class="w-8 h-8 rounded object-cover">
                                                                                @else
                                                                                    <div class="w-8 h-8 bg-purple-200 rounded flex items-center justify-center">
                                                                                        <i class="fas fa-image text-purple-600 text-xs"></i>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="flex-1 min-w-0">
                                                                                <p class="text-xs font-medium text-purple-800 truncate">
                                                                                    {{ $orderItemDesign->design->title }}
                                                                                </p>
                                                                                @if($orderItemDesign->notes)
                                                                                    <p class="text-xs text-purple-600 truncate">
                                                                                        <i class="fas fa-sticky-note mr-1"></i>
                                                                                        {{ $orderItemDesign->notes }}
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                            @if($orderItemDesign->priority)
                                                                                <div class="flex-shrink-0">
                                                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                                        #{{ $orderItemDesign->priority }}
                                                                                    </span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="mt-2">
                                                            <span class="text-xs text-gray-500 italic">
                                                                <i class="fas fa-palette mr-1"></i>
                                                                {{ trans('orders.no_designs_attached') }}
                                                            </span>
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
@endsection
