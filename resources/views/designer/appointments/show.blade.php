@extends('designer.layouts.app')

@section('pageTitle', __('dashboard.appointment_details'))
@section('title', __('dashboard.appointment_details'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
                    <div>
            <h1 class="text-2xl font-bold" style="color: var(--brand-brown);">{{ __('dashboard.appointment_details') }}</h1>
            <p class="text-gray-600">#{{ $appointment->id }} - {{ $appointment->user->full_name ?? $appointment->user->email }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('designer.appointments.index') }}" 
               class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                <span>{{ __('dashboard.back_to_appointments') }}</span>
            </a>
            @if($appointment->order)
                <a href="{{ route('designer.orders.edit', $appointment) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    <span>{{ __('dashboard.edit_order') }}</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Appointment Status and Actions -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-lg flex items-center justify-center text-white text-2xl font-bold" 
                     style="background-color: var(--brand-brown);">
                    #{{ $appointment->id }}
                    </div>
                    <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.appointment_status') }}</h3>
                    <span class="badge badge-{{ $appointment->status }} text-lg px-4 py-2">
                        {{ __('status.appointment.' . $appointment->status) ?: $appointment->status }}
                    </span>
                </div>
                    </div>
            <div class="flex items-center gap-2">
                @if($appointment->status === 'pending')
                    <form action="{{ route('designer.appointments.accept', $appointment) }}" 
                          method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-success"
                                onclick="return confirm('{{ __('dashboard.confirm_accept_appointment') }}')">
                            <i class="fas fa-check"></i>
                            <span>{{ __('dashboard.accept_appointment') }}</span>
                        </button>
                    </form>
                    
                    <form action="{{ route('designer.appointments.reject', $appointment) }}" 
                          method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-danger"
                                onclick="return confirm('{{ __('dashboard.confirm_reject_appointment') }}')">
                            <i class="fas fa-times"></i>
                            <span>{{ __('dashboard.reject_appointment') }}</span>
                        </button>
                    </form>
                @elseif(in_array($appointment->status, ['accepted', 'confirmed']))
                    <form action="{{ route('designer.appointments.complete', $appointment) }}" 
                          method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-primary"
                                onclick="return confirm('{{ __('dashboard.confirm_complete_appointment') }}')">
                            <i class="fas fa-flag-checkered"></i>
                            <span>{{ __('dashboard.complete_appointment') }}</span>
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('dashboard.appointment_information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.appointment_date') }}</label>
                        <p class="text-gray-900">
                            {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : __('dashboard.not_scheduled') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.appointment_time') }}</label>
                        <p class="text-gray-900">
                            {{ $appointment->appointment_time ? $appointment->appointment_time->format('H:i') : __('dashboard.not_scheduled') }}
                                    </p>
                                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.duration') }}</label>
                        <p class="text-gray-900">
                            {{ $appointment->duration_minutes ? $appointment->duration_minutes . ' ' . __('dashboard.minutes') : __('dashboard.not_specified') }}
                                    </p>
                                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.created_at') }}</label>
                        <p class="text-gray-900">{{ $appointment->created_at ? $appointment->created_at->format('Y-m-d H:i') : __('dashboard.not_available') }}</p>
                    </div>
                </div>

                @if($appointment->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.notes') }}</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-900">{{ $appointment->notes }}</p>
                        </div>
                    </div>
                @endif
                
                @if($appointment->designer_notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.designer_notes') }}</label>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-gray-900">{{ $appointment->designer_notes }}</p>
                        </div>
                                </div>
                @endif
                            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('dashboard.customer_information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.full_name') }}</label>
                        <p class="text-gray-900">{{ $appointment->user->full_name ?? __('dashboard.not_provided') }}</p>
                                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.email') }}</label>
                        <p class="text-gray-900">{{ $appointment->user->email }}</p>
                                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.phone') }}</label>
                        <p class="text-gray-900">{{ $appointment->user->phone ?? __('dashboard.not_provided') }}</p>
                            </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.customer_since') }}</label>
                        <p class="text-gray-900">{{ $appointment->user->created_at ? $appointment->user->created_at->format('Y-m-d') : __('dashboard.not_available') }}</p>
                                    </div>
                                </div>
            </div>

            <!-- Order Information -->
            @if($appointment->order)
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.order_information') }}</h3>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('designer.orders.edit', $appointment) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                                <span>{{ __('dashboard.edit_order') }}</span>
                            </a>
                            <form action="{{ route('designer.appointments.unlink-order', $appointment) }}" 
                                  method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('{{ __('dashboard.confirm_unlink_order') }}')">
                                    <i class="fas fa-unlink"></i>
                                    <span>{{ __('dashboard.unlink_order') }}</span>
                                </button>
                            </form>
                        </div>
                            </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.order_id') }}</label>
                            <p class="text-gray-900">#{{ $appointment->order->id }}</p>
                                </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.order_total') }}</label>
                            <p class="text-gray-900 font-semibold">{{ number_format($appointment->order->total, 2) }} ر.س</p>
                            </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.items_count') }}</label>
                            <p class="text-gray-900">{{ $appointment->order->items->count() }} {{ __('dashboard.items') }}</p>
                        </div>
                    </div>

                    @if($appointment->order_notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('dashboard.order_notes') }}</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-gray-900">{{ $appointment->order_notes }}</p>
                            </div>
                                                                        </div>
                                                                    @endif

                    <!-- Order Items -->
                    @if($appointment->order->items->count() > 0)
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-semibold text-gray-900">{{ __('dashboard.order_items') }}</h4>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">{{ $appointment->order->items->count() }} {{ __('dashboard.items') }}</span>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($appointment->order->total, 2) }} ر.س</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                @foreach($appointment->order->items as $item)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-12 h-12 object-cover rounded-lg">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900">{{ $item->product->name }}</h5>
                                                    <p class="text-sm text-gray-500">{{ $item->product->description }}</p>
                                                    @if($item->notes)
                                                        <p class="text-xs text-blue-600 mt-1">
                                                            <i class="fas fa-sticky-note mr-1"></i>
                                                            {{ $item->notes }}
                                                        </p>
                                                    @endif
                                                    @if($item->options && count($item->options) > 0)
                                                        <div class="mt-1">
                                                            @foreach($item->options as $optionId => $valueId)
                                                                @php
                                                                    $option = \App\Models\ProductOption::find($optionId);
                                                                    $value = \App\Models\OptionValue::find($valueId);
                                                                @endphp
                                                                @if($option && $value)
                                                                    <span class="inline-block px-2 py-1 bg-gray-100 text-xs rounded mr-1">
                                                                        {{ $option->name }}: {{ $value->value }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-gray-900">{{ $item->quantity }}x</p>
                                                <p class="text-sm text-gray-500">{{ number_format($item->unit_price, 2) }} ر.س</p>
                                                <p class="text-sm font-semibold text-gray-900">{{ number_format($item->total_price, 2) }} ر.س</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Order Summary -->
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">{{ __('dashboard.subtotal') }}:</span>
                                    <span class="font-medium">{{ number_format($appointment->order->subtotal, 2) }} ر.س</span>
                                </div>
                                <div class="flex justify-between items-center text-sm mt-1">
                                    <span class="text-gray-600">{{ __('dashboard.tax') }} (15%):</span>
                                    <span class="font-medium">{{ number_format($appointment->order->tax, 2) }} ر.س</span>
                                </div>
                                <div class="flex justify-between items-center text-lg font-semibold mt-2 pt-2 border-t border-gray-200">
                                    <span>{{ __('dashboard.total') }}:</span>
                                    <span>{{ number_format($appointment->order->total, 2) }} ر.س</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Order Editing Section -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">{{ __('dashboard.order_editing') }}</h4>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('designer.orders.edit', $appointment) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                    <span>{{ __('dashboard.edit_order') }}</span>
                                </a>
                                <button onclick="toggleOrderNotes()" 
                                        class="btn btn-secondary btn-sm">
                                    <i class="fas fa-sticky-note"></i>
                                    <span>{{ __('dashboard.add_notes') }}</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Order Notes Editor -->
                        <div id="order-notes-editor" class="hidden mb-4">
                            <form action="{{ route('designer.appointments.update', $appointment) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.order_notes') }}</label>
                                    <textarea name="order_notes" 
                                              rows="3" 
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                              placeholder="{{ __('dashboard.add_order_notes') }}">{{ $appointment->order_notes }}</textarea>
                                    <div class="mt-3 flex items-center gap-2">
                                        <button type="submit" 
                                                class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i>
                                            <span>{{ __('dashboard.save_notes') }}</span>
                                        </button>
                                        <button type="button" 
                                                onclick="toggleOrderNotes()"
                                                class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i>
                                            <span>{{ __('dashboard.cancel') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <i class="fas fa-plus-circle text-2xl text-green-600 mb-2"></i>
                                <h5 class="font-medium text-gray-900 mb-1">{{ __('dashboard.add_products') }}</h5>
                                <p class="text-sm text-gray-500 mb-3">{{ __('dashboard.add_products_description') }}</p>
                                <a href="{{ route('designer.orders.edit', $appointment) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus"></i>
                                    {{ __('dashboard.add_products') }}
                                </a>
                            </div>
                            
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <i class="fas fa-edit text-2xl text-blue-600 mb-2"></i>
                                <h5 class="font-medium text-gray-900 mb-1">{{ __('dashboard.edit_quantities') }}</h5>
                                <p class="text-sm text-gray-500 mb-3">{{ __('dashboard.edit_quantities_description') }}</p>
                                <a href="{{ route('designer.orders.edit', $appointment) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                    {{ __('dashboard.edit_quantities') }}
                                </a>
                            </div>
                            
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <i class="fas fa-calculator text-2xl text-purple-600 mb-2"></i>
                                <h5 class="font-medium text-gray-900 mb-1">{{ __('dashboard.recalculate') }}</h5>
                                <p class="text-sm text-gray-500 mb-3">{{ __('dashboard.recalculate_description') }}</p>
                                <button onclick="recalculateOrder()" 
                                        class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-calculator"></i>
                                    {{ __('dashboard.recalculate') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.link_order') }}</h3>
                        <span class="text-sm text-gray-500">{{ __('dashboard.available_orders') }}: {{ $availableOrders->count() }}</span>
                    </div>
                    
                    @if($availableOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($availableOrders as $order)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="font-semibold text-gray-900">#{{ $order->order_number }}</h4>
                                                <span class="badge badge-{{ $order->status }}">
                                                    {{ $order->status }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">{{ __('dashboard.total') }}:</span>
                                                    {{ number_format($order->total, 2) }} ر.س
                                                </div>
                                                <div>
                                                    <span class="font-medium">{{ __('dashboard.items') }}:</span>
                                                    {{ $order->items->count() }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">{{ __('dashboard.created') }}:</span>
                                                    {{ $order->created_at->format('Y-m-d') }}
                                                </div>
                            </div>
                                            @if($order->items->count() > 0)
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">{{ __('dashboard.products') }}:</p>
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        @foreach($order->items->take(3) as $item)
                                                            <span class="px-2 py-1 bg-gray-100 text-xs rounded">{{ $item->product->name }}</span>
                                                        @endforeach
                                                        @if($order->items->count() > 3)
                                                            <span class="px-2 py-1 bg-gray-100 text-xs rounded">+{{ $order->items->count() - 3 }} {{ __('dashboard.more') }}</span>
                                                        @endif
                            </div>
                        </div>
                    @endif
                        </div>
                                        <div class="ml-4">
                                            <form action="{{ route('designer.appointments.link-order', $appointment) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <button type="submit" 
                                                        class="btn btn-primary btn-sm"
                                                        onclick="return confirm('{{ __('dashboard.confirm_link_order') }}')">
                                                    <i class="fas fa-link"></i>
                                                    <span>{{ __('dashboard.link') }}</span>
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
                            <p class="text-gray-500">{{ __('dashboard.no_available_orders') }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ __('dashboard.no_available_orders_description') }}</p>
                        </div>
                    @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('dashboard.quick_actions') }}</h3>
                <div class="space-y-3">
                    <a href="{{ route('designer.appointments.index') }}" 
                       class="w-full flex items-center gap-3 p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-list text-gray-400"></i>
                        <span>{{ __('dashboard.all_appointments') }}</span>
                    </a>
                    <a href="{{ route('designer.appointments.dashboard') }}" 
                       class="w-full flex items-center gap-3 p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-hand-paper text-gray-400"></i>
                        <span>{{ __('dashboard.new_appointments') }}</span>
                    </a>
                    <a href="{{ route('designer.appointments.upcoming') }}" 
                       class="w-full flex items-center gap-3 p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                        <span>{{ __('dashboard.upcoming_appointments') }}</span>
                    </a>
                </div>
                    </div>

            <!-- Appointment Timeline -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('dashboard.appointment_timeline') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('dashboard.appointment_created') }}</p>
                            <p class="text-xs text-gray-500">{{ $appointment->created_at ? $appointment->created_at->format('Y-m-d H:i') : __('dashboard.not_available') }}</p>
                    </div>
                </div>

                    @if($appointment->accepted_at)
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ __('dashboard.appointment_accepted') }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->accepted_at ? $appointment->accepted_at->format('Y-m-d H:i') : __('dashboard.not_available') }}</p>
                            </div>
                        </div>
                                    @endif
                    
                    @if($appointment->rejected_at)
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ __('dashboard.appointment_rejected') }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->rejected_at ? $appointment->rejected_at->format('Y-m-d H:i') : __('dashboard.not_available') }}</p>
                            </div>
                        </div>
                                    @endif
                    
                    @if($appointment->completed_at)
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ __('dashboard.appointment_completed') }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->completed_at ? $appointment->completed_at->format('Y-m-d H:i') : __('dashboard.not_available') }}</p>
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
    if (confirm('{{ __("dashboard.confirm_recalculate_order") }}')) {
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
                alert('{{ __("dashboard.error_recalculating_order") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("dashboard.error_recalculating_order") }}');
        });
    }
}
</script>
@endsection