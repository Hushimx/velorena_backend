@extends('designer.layouts.app')

@section('pageTitle', trans('dashboard.appointment_details'))
@section('title', trans('dashboard.appointment_details'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="rounded-xl p-6 text-white" style="background-color: #2a1e1e;">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        <i class="fas fa-calendar-check me-2" style="color: #ffde9f;"></i>
                        {{ trans('dashboard.appointment_details') }}
                    </h1>
                    <p class="mt-1" style="color: #ffde9f;">
                        #{{ $appointment->id }} - {{ $appointment->user->full_name ?? $appointment->user->email }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('designer.appointments.index') }}"
                        class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        <span class="hidden sm:block">{{ trans('dashboard.back_to_appointments') }}</span>
                    </a>
                    @if ($appointment->order)
                        <a href="{{ route('designer.orders.edit', $appointment) }}"
                            class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            <span class="hidden sm:block">{{ trans('dashboard.edit_order') }}</span>
                        </a>
                    @endif
                    <div class="hidden md:block">
                        <i class="fas fa-user-clock text-4xl" style="color: #ffde9f;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Status and Actions -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-lg flex items-center justify-center text-white text-2xl font-bold"
                        style="background-color: #2a1e1e;">
                        #{{ $appointment->id }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ trans('dashboard.appointment_status') }}</h3>
                        <span
                            class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                        @if ($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($appointment->status === 'accepted') bg-blue-100 text-blue-800
                        @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                            {{ trans('status.appointment.' . $appointment->status) ?: $appointment->status }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if ($appointment->status === 'pending')
                        <form action="{{ route('designer.appointments.accept', $appointment) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors flex items-center gap-2"
                                onclick="return confirm('{{ trans('dashboard.confirm_accept_appointment') }}')">
                                <i class="fas fa-check"></i>
                                <span>{{ trans('dashboard.accept_appointment') }}</span>
                            </button>
                        </form>

                        <form action="{{ route('designer.appointments.reject', $appointment) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors flex items-center gap-2"
                                onclick="return confirm('{{ trans('dashboard.confirm_reject_appointment') }}')">
                                <i class="fas fa-times"></i>
                                <span>{{ trans('dashboard.reject_appointment') }}</span>
                            </button>
                        </form>
                    @elseif(in_array($appointment->status, ['accepted', 'confirmed']))
                        <form action="{{ route('designer.appointments.complete', $appointment) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 text-white rounded-lg font-medium hover:opacity-90 transition-opacity flex items-center gap-2"
                                style="background-color: #2a1e1e;"
                                onclick="return confirm('{{ trans('dashboard.confirm_complete_appointment') }}')">
                                <i class="fas fa-flag-checkered"></i>
                                <span>{{ trans('dashboard.complete_appointment') }}</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Appointment Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('dashboard.appointment_information') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.appointment_date') }}</label>
                            <p class="text-gray-900">
                                {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : trans('dashboard.not_scheduled') }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.appointment_time') }}</label>
                            <p class="text-gray-900">
                                {{ $appointment->appointment_time ? $appointment->appointment_time->format('H:i') : trans('dashboard.not_scheduled') }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.duration') }}</label>
                            <p class="text-gray-900">
                                {{ $appointment->duration_minutes ? $appointment->duration_minutes . ' ' . trans('dashboard.minutes') : trans('dashboard.not_specified') }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.created_at') }}</label>
                            <p class="text-gray-900">
                                {{ $appointment->created_at ? $appointment->created_at->format('Y-m-d H:i') : trans('dashboard.not_available') }}
                            </p>
                        </div>
                    </div>

                    @if ($appointment->notes)
                        <div class="mt-6">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.notes') }}</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $appointment->notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($appointment->designer_notes)
                        <div class="mt-6">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.designer_notes') }}</label>
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <p class="text-gray-900">{{ $appointment->designer_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('dashboard.customer_information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.full_name') }}</label>
                            <p class="text-gray-900">{{ $appointment->user->full_name ?? trans('dashboard.not_provided') }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.email') }}</label>
                            <p class="text-gray-900">{{ $appointment->user->email }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.phone') }}</label>
                            <p class="text-gray-900">{{ $appointment->user->phone ?? trans('dashboard.not_provided') }}</p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.customer_since') }}</label>
                            <p class="text-gray-900">
                                {{ $appointment->user->created_at ? $appointment->user->created_at->format('Y-m-d') : trans('dashboard.not_available') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                @if ($appointment->order)
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ trans('dashboard.order_information') }}</h3>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('designer.orders.edit', $appointment) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                    <span>{{ trans('dashboard.edit_order') }}</span>
                                </a>
                                <form action="{{ route('designer.appointments.unlink-order', $appointment) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('{{ trans('dashboard.confirm_unlink_order') }}')">
                                        <i class="fas fa-unlink"></i>
                                        <span>{{ trans('dashboard.unlink_order') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.order_id') }}</label>
                                <p class="text-gray-900">#{{ $appointment->order->id }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.order_total') }}</label>
                                <p class="text-gray-900 font-semibold">{{ number_format($appointment->order->total, 2) }}
                                    ر.س</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.items_count') }}</label>
                                <p class="text-gray-900">{{ $appointment->order->items->count() }}
                                    {{ trans('dashboard.items') }}</p>
                            </div>
                        </div>

                        @if ($appointment->order_notes)
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('dashboard.order_notes') }}</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ $appointment->order_notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Order Items -->
                        @if ($appointment->order->items->count() > 0)
                            <div class="mt-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-md font-semibold text-gray-900">{{ trans('dashboard.order_items') }}
                                    </h4>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">{{ $appointment->order->items->count() }}
                                            {{ trans('dashboard.items') }}</span>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ number_format($appointment->order->total, 2) }}
                                            ر.س</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($appointment->order->items as $item)
                                        <div
                                            class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    @if ($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="w-12 h-12 object-cover rounded-lg">
                                                    @else
                                                        <div
                                                            class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-1">
                                                        <h5 class="font-medium text-gray-900">{{ $item->product->name }}
                                                        </h5>
                                                        <p class="text-sm text-gray-500">{{ $item->product->description }}
                                                        </p>
                                                        @if ($item->notes)
                                                            <p class="text-xs text-blue-600 mt-1">
                                                                <i class="fas fa-sticky-note mr-1"></i>
                                                                {{ $item->notes }}
                                                            </p>
                                                        @endif
                                                        @php
                                                            $itemOptions = is_array($item->options)
                                                                ? $item->options
                                                                : (is_string($item->options)
                                                                    ? json_decode($item->options, true)
                                                                    : []);
                                                            $itemOptions = $itemOptions ?: [];
                                                        @endphp
                                                        @if (!empty($itemOptions))
                                                            <div class="mt-1">
                                                                @foreach ($itemOptions as $optionId => $valueId)
                                                                    @php
                                                                        $option = \App\Models\ProductOption::find(
                                                                            $optionId,
                                                                        );
                                                                        $value = \App\Models\OptionValue::find(
                                                                            $valueId,
                                                                        );
                                                                    @endphp
                                                                    @if ($option && $value)
                                                                        <span
                                                                            class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded mr-1 mb-1">
                                                                            <i
                                                                                class="fas fa-cog mr-1"></i>{{ $option->name }}:
                                                                            {{ $value->value }}
                                                                            @if ($value->price_adjustment != 0)
                                                                                <span
                                                                                    class="ml-1 {{ $value->price_adjustment > 0 ? 'text-green-700' : 'text-red-700' }}">
                                                                                    ({{ $value->price_adjustment > 0 ? '+' : '' }}{{ number_format($value->price_adjustment, 2) }}
                                                                                    ر.س)
                                                                                </span>
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        @if ($item->formatted_options)
                                                            <div class="mt-1">
                                                                <span
                                                                    class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">
                                                                    <i
                                                                        class="fas fa-list mr-1"></i>{{ $item->formatted_options }}
                                                                </span>
                                                            </div>
                                                        @endif

                                                        <!-- Designs are now shown at order level -->
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-medium text-gray-900">{{ $item->quantity }}x</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ number_format($item->unit_price, 2) }} ر.س</p>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ number_format($item->total_price, 2) }} ر.س</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Order Summary -->
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">{{ trans('dashboard.subtotal') }}:</span>
                                        <span class="font-medium">{{ number_format($appointment->order->subtotal, 2) }}
                                            ر.س</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm mt-1">
                                        <span class="text-gray-600">{{ trans('dashboard.tax') }} (15%):</span>
                                        <span class="font-medium">{{ number_format($appointment->order->tax, 2) }}
                                            ر.س</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center text-lg font-semibold mt-2 pt-2 border-t border-gray-200">
                                        <span>{{ trans('dashboard.total') }}:</span>
                                        <span>{{ number_format($appointment->order->total, 2) }} ر.س</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Order Editing Section -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-900">{{ trans('dashboard.order_editing') }}
                                </h4>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('designer.orders.edit', $appointment) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                        <span>{{ trans('dashboard.edit_order') }}</span>
                                    </a>
                                    <button onclick="toggleOrderNotes()" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-sticky-note"></i>
                                        <span>{{ trans('dashboard.add_notes') }}</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Order Notes Editor -->
                            <div id="order-notes-editor" class="hidden mb-4">
                                <form action="{{ route('designer.appointments.update', $appointment) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-2">{{ trans('dashboard.order_notes') }}</label>
                                        <textarea name="order_notes" rows="3"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="{{ trans('dashboard.add_order_notes') }}">{{ $appointment->order_notes }}</textarea>
                                        <div class="mt-3 flex items-center gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-save"></i>
                                                <span>{{ trans('dashboard.save_notes') }}</span>
                                            </button>
                                            <button type="button" onclick="toggleOrderNotes()"
                                                class="btn btn-secondary btn-sm">
                                                <i class="fas fa-times"></i>
                                                <span>{{ trans('dashboard.cancel') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Quick Actions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <i class="fas fa-plus-circle text-2xl text-green-600 mb-2"></i>
                                    <h5 class="font-medium text-gray-900 mb-1">{{ trans('dashboard.add_products') }}</h5>
                                    <p class="text-sm text-gray-500 mb-3">
                                        {{ trans('dashboard.add_products_description') }}
                                    </p>
                                    <div class="flex flex-col gap-2">
                                        <button onclick="openQuickProductModal()" class="btn btn-primary btn-sm">
                                            <i class="fas fa-bolt"></i>
                                            {{ trans('dashboard.quick_add') }}
                                        </button>
                                        <a href="{{ route('designer.orders.edit', $appointment) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-plus"></i>
                                            {{ trans('dashboard.full_editor') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <i class="fas fa-edit text-2xl text-blue-600 mb-2"></i>
                                    <h5 class="font-medium text-gray-900 mb-1">{{ trans('dashboard.edit_quantities') }}
                                    </h5>
                                    <p class="text-sm text-gray-500 mb-3">
                                        {{ trans('dashboard.edit_quantities_description') }}</p>
                                    <a href="{{ route('designer.orders.edit', $appointment) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                        {{ trans('dashboard.edit_quantities') }}
                                    </a>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <i class="fas fa-calculator text-2xl text-purple-600 mb-2"></i>
                                    <h5 class="font-medium text-gray-900 mb-1">{{ trans('dashboard.recalculate') }}</h5>
                                    <p class="text-sm text-gray-500 mb-3">
                                        {{ trans('dashboard.recalculate_description') }}
                                    </p>
                                    <button onclick="recalculateOrder()" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-calculator"></i>
                                        {{ trans('dashboard.recalculate') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Designs Panel -->
                    @if ($appointment->order->designs && $appointment->order->designs->count() > 0)
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-palette mr-2 text-purple-600"></i>
                                {{ trans('Order Designs') }} ({{ $appointment->order->designs->count() }})
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                @foreach($appointment->order->designs as $design)
                                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 hover:shadow-md transition-shadow">
                                        <!-- Design Image -->
                                        <div class="aspect-square mb-3 bg-white rounded-lg overflow-hidden">
                                            @if($design->thumbnail_url)
                                                <img src="{{ $design->thumbnail_url }}" 
                                                     alt="{{ $design->title }}" 
                                                     class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer"
                                                     onclick="openImageModal('{{ $design->image_url }}', '{{ addslashes($design->title) }}')">
                                            @else
                                                <div class="w-full h-full bg-purple-100 flex items-center justify-center">
                                                    <i class="fas fa-image text-purple-400 text-3xl"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Design Info -->
                                        <div class="space-y-2">
                                            <h4 class="font-medium text-purple-900 text-sm truncate" title="{{ $design->title }}">
                                                {{ $design->title }}
                                            </h4>
                                            
                                            @if($design->notes)
                                                <div class="text-xs text-purple-700 bg-purple-100 rounded px-2 py-1">
                                                    <i class="fas fa-sticky-note mr-1"></i>
                                                    {{ $design->notes }}
                                                </div>
                                            @endif

                                            <div class="flex justify-between items-center text-xs text-purple-600">
                                                <span class="flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $design->created_at->diffForHumans() }}
                                                </span>
                                                @if($design->priority > 1)
                                                    <span class="bg-purple-200 text-purple-800 px-2 py-1 rounded-full">
                                                        #{{ $design->priority }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ trans('dashboard.link_order') }}</h3>
                            <span class="text-sm text-gray-500">{{ trans('dashboard.available_orders') }}:
                                {{ $availableOrders->count() }}</span>
                        </div>

                        @if ($availableOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach ($availableOrders as $order)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h4 class="font-semibold text-gray-900">#{{ $order->order_number }}
                                                    </h4>
                                                    <span class="badge badge-{{ $order->status }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                                    <div>
                                                        <span class="font-medium">{{ trans('dashboard.total') }}:</span>
                                                        {{ number_format($order->total, 2) }} ر.س
                                                    </div>
                                                    <div>
                                                        <span class="font-medium">{{ trans('dashboard.items') }}:</span>
                                                        {{ $order->items->count() }}
                                                    </div>
                                                    <div>
                                                        <span
                                                            class="font-medium">{{ trans('dashboard.created') }}:</span>
                                                        {{ $order->created_at->format('Y-m-d') }}
                                                    </div>
                                                </div>
                                                @if ($order->items->count() > 0)
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            {{ trans('dashboard.products') }}:
                                                        </p>
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach ($order->items->take(3) as $item)
                                                                <span
                                                                    class="px-2 py-1 bg-gray-100 text-xs rounded">{{ $item->product->name }}</span>
                                                            @endforeach
                                                            @if ($order->items->count() > 3)
                                                                <span
                                                                    class="px-2 py-1 bg-gray-100 text-xs rounded">+{{ $order->items->count() - 3 }}
                                                                    {{ trans('dashboard.more') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <form
                                                    action="{{ route('designer.appointments.link-order', $appointment) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                        onclick="return confirm('{{ trans('dashboard.confirm_link_order') }}')">
                                                        <i class="fas fa-link"></i>
                                                        <span>{{ trans('dashboard.link') }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500">{{ trans('dashboard.no_available_orders') }}</p>
                                <p class="text-sm text-gray-400 mt-1">
                                    {{ trans('dashboard.no_available_orders_description') }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                            <i class="fas fa-bolt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ trans('dashboard.quick_actions') }}</h3>
                            <p class="text-sm text-gray-500">{{ trans('dashboard.navigate_to_sections') }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('designer.appointments.index') }}"
                            class="w-full flex items-center gap-3 p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="p-2 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                                <i class="fas fa-list text-sm"></i>
                            </div>
                            <span>{{ trans('dashboard.all_appointments') }}</span>
                        </a>
                        <a href="{{ route('designer.appointments.dashboard') }}"
                            class="w-full flex items-center gap-3 p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="p-2 rounded-full" style="background-color: #ffde9f; color: #2a1e1e;">
                                <i class="fas fa-hand-paper text-sm"></i>
                            </div>
                            <span>{{ trans('dashboard.new_appointments') }}</span>
                        </a>
                        <a href="{{ route('designer.appointments.upcoming') }}"
                            class="w-full flex items-center gap-3 p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="p-2 rounded-full" style="background-color: #2a1e1e; color: #ffde9f;">
                                <i class="fas fa-calendar-alt text-sm"></i>
                            </div>
                            <span>{{ trans('dashboard.upcoming_appointments') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Appointment Timeline -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ trans('dashboard.appointment_timeline') }}
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ trans('dashboard.appointment_created') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $appointment->created_at ? $appointment->created_at->format('Y-m-d H:i') : trans('dashboard.not_available') }}
                                </p>
                            </div>
                        </div>

                        @if ($appointment->accepted_at)
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ trans('dashboard.appointment_accepted') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $appointment->accepted_at ? $appointment->accepted_at->format('Y-m-d H:i') : trans('dashboard.not_available') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($appointment->rejected_at)
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ trans('dashboard.appointment_rejected') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $appointment->rejected_at ? $appointment->rejected_at->format('Y-m-d H:i') : trans('dashboard.not_available') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($appointment->completed_at)
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ trans('dashboard.appointment_completed') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $appointment->completed_at ? $appointment->completed_at->format('Y-m-d H:i') : trans('dashboard.not_available') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleOrderNotes() {
            const editor = document.getElementById('order-notes-editor');
            if (editor.classList.contains('hidden')) {
                editor.classList.remove('hidden');
            } else {
                editor.classList.add('hidden');
            }
        }

        function recalculateOrder() {
            if (confirm('{{ trans('dashboard.confirm_recalculate_order') }}')) {
                // This would typically make an AJAX request to recalculate the order
                fetch(`/designer/appointments/{{ $appointment->id }}/recalculate-order`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('{{ trans('dashboard.error_recalculating_order') }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('{{ trans('dashboard.error_recalculating_order') }}');
                    });
            }
        }

        function openQuickProductModal() {
            // Create and show a simple quick add modal
            const modal = document.createElement('div');
            modal.id = 'quickProductModal';
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-bolt mr-2"></i>
                        {{ trans('dashboard.quick_add_products') }}
                    </h3>
                    <button onclick="closeQuickProductModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium mb-3 text-blue-800">
                            <i class="fas fa-star mr-2"></i>{{ trans('dashboard.popular_services') }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-white border rounded-lg p-3 hover:shadow-md cursor-pointer transition-shadow" onclick="addQuickProduct(1, '{{ trans('dashboard.basic_consultation') }}', 50.00)">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ trans('dashboard.basic_consultation') }}</h5>
                                        <p class="text-sm text-gray-500">{{ trans('dashboard.basic_consultation_desc') }}</p>
                                    </div>
                                    <span class="text-green-600 font-semibold">$50.00</span>
                                </div>
                            </div>
                            <div class="bg-white border rounded-lg p-3 hover:shadow-md cursor-pointer transition-shadow" onclick="addQuickProduct(2, '{{ trans('dashboard.premium_package') }}', 150.00)">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ trans('dashboard.premium_package') }}</h5>
                                        <p class="text-sm text-gray-500">{{ trans('dashboard.premium_package_desc') }}</p>
                                    </div>
                                    <span class="text-green-600 font-semibold">$150.00</span>
                                </div>
                            </div>
                            <div class="bg-white border rounded-lg p-3 hover:shadow-md cursor-pointer transition-shadow" onclick="addQuickProduct(3, '{{ trans('dashboard.custom_design') }}', 200.00)">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ trans('dashboard.custom_design') }}</h5>
                                        <p class="text-sm text-gray-500">{{ trans('dashboard.custom_design_desc') }}</p>
                                    </div>
                                    <span class="text-green-600 font-semibold">$200.00</span>
                                </div>
                            </div>
                            <div class="bg-white border rounded-lg p-3 hover:shadow-md cursor-pointer transition-shadow" onclick="addQuickProduct(4, '{{ trans('dashboard.revision_service') }}', 30.00)">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ trans('dashboard.revision_service') }}</h5>
                                        <p class="text-sm text-gray-500">{{ trans('dashboard.revision_service_desc') }}</p>
                                    </div>
                                    <span class="text-green-600 font-semibold">$30.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button onclick="closeQuickProductModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            {{ trans('dashboard.cancel') }}
                        </button>
                        <a href="{{ route('designer.orders.edit', $appointment) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>{{ trans('dashboard.full_editor') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;

            document.body.appendChild(modal);
        }

        function closeQuickProductModal() {
            const modal = document.getElementById('quickProductModal');
            if (modal) {
                modal.remove();
            }
        }

        function addQuickProduct(productId, productName, price) {
            // This would typically make an AJAX request to add the product
            if (confirm(`Add "${productName}" ($${price}) to the order?`)) {
                // Simulate adding the product
                alert(`"${productName}" has been added to the order. Redirecting to full editor...`);
                window.location.href = '{{ route('designer.orders.edit', $appointment) }}';
            }
        }
    </script>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            <div id="modalTitle" class="absolute bottom-4 left-4 right-4 text-white text-center bg-black bg-opacity-50 rounded p-2"></div>
        </div>
    </div>

    <script>
        function openImageModal(imageUrl, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            
            modalImage.src = imageUrl;
            modalImage.alt = title;
            modalTitle.textContent = title;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection
