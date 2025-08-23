@extends('admin.layouts.app')

@section('pageTitle', 'Admin | Edit Order')
@section('title', trans('orders.edit_order'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ trans('orders.edit_order') }}</h1>
                <p class="text-gray-600">{{ trans('orders.edit_order_description') }}</p>
            </div>
            <a href="{{ route('admin.orders.show', $order) }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-left pl-2"></i>
                <span>{{ trans('orders.back_to_order') }}</span>
            </a>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Order Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            {{ trans('orders.order_information') }}
                        </h3>

                        <div>
                            <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('orders.order_number') }}
                            </label>
                            <input type="text" id="order_number" value="{{ $order->order_number }}" disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('orders.status') }}
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('status') border-red-500 @enderror">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                    {{ trans('orders.pending') }}
                                </option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>
                                    {{ trans('orders.confirmed') }}
                                </option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                    {{ trans('orders.processing') }}
                                </option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>
                                    {{ trans('orders.shipped') }}
                                </option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                                    {{ trans('orders.delivered') }}
                                </option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                    {{ trans('orders.cancelled') }}
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('orders.phone') }}
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $order->phone) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('phone') border-red-500 @enderror"
                                placeholder="{{ trans('orders.enter_phone') }}">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            {{ trans('orders.customer_information') }}
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('orders.customer_name') }}
                            </label>
                            <input type="text" value="{{ $order->user->name }}" disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('orders.customer_email') }}
                            </label>
                            <input type="email" value="{{ $order->user->email }}" disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
                        </div>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('orders.shipping_address') }}
                            </label>
                            <textarea name="shipping_address" id="shipping_address" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('shipping_address') border-red-500 @enderror"
                                placeholder="{{ trans('orders.enter_shipping_address') }}">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('orders.billing_address') }}
                            </label>
                            <textarea name="billing_address" id="billing_address" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('billing_address') border-red-500 @enderror"
                                placeholder="{{ trans('orders.enter_billing_address') }}">{{ old('billing_address', $order->billing_address) }}</textarea>
                            @error('billing_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ trans('orders.notes') }}
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('notes') border-red-500 @enderror"
                        placeholder="{{ trans('orders.enter_notes') }}">{{ old('notes', $order->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.orders.show', $order) }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        {{ trans('orders.cancel') }}
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        {{ trans('orders.update_order') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
