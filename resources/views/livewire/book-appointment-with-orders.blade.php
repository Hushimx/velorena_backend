<div class="space-y-6">
    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 mx-3"></i>
                <div class="text-red-800">
                    <h4 class="font-medium">
                        {{ trans('dashboard.fix_errors', ['default' => 'Please fix the following errors:']) }}</h4>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6">
            <form wire:submit.prevent="bookAppointment">
                <!-- Step 1: Date Selection -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 gap-3">
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-calendar text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">
                                {{ trans('dashboard.select_date', ['default' => 'Select Your Date']) }}</h4>
                            <p class="text-gray-500 text-sm">
                                {{ trans('dashboard.choose_meeting_time', ['default' => 'Choose when you\'d like to meet']) }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="selectedDate" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ trans('dashboard.available_date', ['default' => 'Date & Time']) }} <span
                                        class="text-red-500">*</span>
                                </label>
                                <input id="flatpickr" type="datetime-local" wire:model.live="appointment_date"
                                    min="{{ now()->addMinutes(1)->format('Y-m-d\TH:i') }}"
                                    max="{{ now()->addMonths(3)->format('Y-m-d\TH:i') }}" id="appointment_date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('appointment_date')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex items-end">
                                <small class="text-gray-500">
                                    {{ trans('dashboard.available_dates', ['default' => 'Available dates']) }}:
                                    {{ now()->addMinutes(1)->format('M j, Y g:i A') }} to
                                    {{ now()->addMonths(3)->format('M j, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Order Selection -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 gap-3">
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-shopping-cart text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">
                                {{ trans('dashboard.select_order', ['default' => 'Select Order to Link']) }}</h4>
                            <p class="text-gray-500 text-sm">
                                {{ trans('dashboard.link_order_description', ['default' => 'Choose which order you want to discuss during this appointment']) }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        @if ($user_orders->count() > 0)
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h5 class="font-medium text-gray-900">
                                        {{ trans('dashboard.your_orders', ['default' => 'Your Orders']) }}
                                        ({{ $user_orders->count() }})
                                    </h5>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-sm text-gray-500">
                                            {{ trans('dashboard.select_order_help', ['default' => 'Select an order to link with this appointment']) }}
                                        </div>
                                        <button type="button" wire:click="toggleUsedOrders"
                                            class="text-sm text-blue-600 hover:text-blue-800 underline">
                                            {{ $show_used_orders ? trans('dashboard.hide_used_orders', ['default' => 'Hide Used Orders']) : trans('dashboard.show_all_orders', ['default' => 'Show All Orders']) }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Orders List -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                                    @foreach ($user_orders as $order)
                                        @php
                                            $isUsed = $order->appointment;
                                        @endphp
                                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer
                                            {{ $selected_order_id == $order->id ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white' }}
                                            {{ $isUsed ? 'opacity-75' : '' }}"
                                            wire:click="selectOrder({{ $order->id }})">
                                            @if ($isUsed)
                                                <div class="mb-2">
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-link mr-1"></i>
                                                        {{ trans('dashboard.already_linked_to_appointment', ['default' => 'Already linked to an appointment']) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="flex items-start space-x-3">
                                                <div class="mt-1">
                                                    <div
                                                        class="w-4 h-4 rounded-full border-2 {{ $selected_order_id == $order->id ? 'bg-green-500 border-green-500' : 'border-gray-300' }} flex items-center justify-center">
                                                        @if ($selected_order_id == $order->id)
                                                            <div class="w-2 h-2 bg-white rounded-full"></div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <div>
                                                            <div class="font-medium text-gray-900">
                                                                {{ $order->order_number }}
                                                            </div>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $order->created_at->format('M j, Y') }}
                                                            </p>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-lg font-semibold text-gray-900">
                                                                ${{ number_format($order->total, 2) }}
                                                            </span>
                                                            <div class="text-sm text-gray-500">
                                                                {{ $order->items->count() }}
                                                                {{ trans('dashboard.items', ['default' => 'items']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Order Items Preview -->
                                                    <div class="space-y-1 mb-3">
                                                        @foreach ($order->items->take(3) as $item)
                                                            <div class="flex justify-between text-sm">
                                                                <span class="text-gray-600">
                                                                    {{ $item->product->name ?? trans('dashboard.unknown_product', ['default' => 'Unknown Product']) }}
                                                                    (x{{ $item->quantity }})
                                                                </span>
                                                                <span
                                                                    class="text-gray-900">${{ number_format($item->total_price, 2) }}</span>
                                                            </div>
                                                        @endforeach
                                                        @if ($order->items->count() > 3)
                                                            <div class="text-xs text-gray-500">
                                                                +{{ $order->items->count() - 3 }}
                                                                {{ trans('dashboard.more_items', ['default' => 'more items']) }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Order Notes Input -->
                                                    @if ($selected_order_id == $order->id)
                                                        <div class="mt-3">
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                                                {{ trans('dashboard.order_notes', ['default' => 'Notes for this order']) }}
                                                            </label>
                                                            <textarea wire:model="order_notes" rows="2"
                                                                placeholder="{{ trans('dashboard.order_notes_placeholder', ['default' => 'Any specific notes about this order for the appointment...']) }}"
                                                                class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500 focus:border-green-500"></textarea>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('selected_order_id')
                                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-4">
                                    <i class="fas fa-shopping-cart text-4xl"></i>
                                </div>
                                <h5 class="text-lg font-medium text-gray-900 mb-2">
                                    {{ trans('dashboard.no_orders_yet', ['default' => 'No Orders Yet']) }}
                                </h5>
                                <p class="text-gray-500 mb-4">
                                    {{ trans('dashboard.no_orders_message', ['default' => 'You don\'t have any orders yet. Create an order first, then come back to book an appointment.']) }}
                                </p>
                                <a href="{{ route('user.products.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    {{ trans('dashboard.browse_products', ['default' => 'Browse Products']) }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Selected Order Summary -->
                @if ($selected_order_id)
                    <div class="mb-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h5 class="font-semibold text-green-800 mb-3 flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                {{ trans('dashboard.selected_order_summary', ['default' => 'Selected Order Summary']) }}
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="bg-white rounded p-3">
                                    <span
                                        class="font-medium text-gray-700">{{ trans('dashboard.order_selected', ['default' => 'Order Selected']) }}:</span>
                                    <span class="text-green-600 font-semibold">1</span>
                                </div>
                                <div class="bg-white rounded p-3">
                                    <span
                                        class="font-medium text-gray-700">{{ trans('dashboard.total_products', ['default' => 'Total Products']) }}:</span>
                                    <span
                                        class="text-green-600 font-semibold">{{ $selected_order_products_count }}</span>
                                </div>
                                <div class="bg-white rounded p-3">
                                    <span
                                        class="font-medium text-gray-700">{{ trans('dashboard.total_value', ['default' => 'Total Value']) }}:</span>
                                    <span
                                        class="text-green-600 font-semibold">${{ number_format($selected_order_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 3: Notes -->
                @if ($appointment_date)
                    <div class="mb-6">
                        <div class="flex items-center mb-4 gap-3">
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-edit text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">
                                    {{ trans('dashboard.additional_information', ['default' => 'Additional Information']) }}
                                </h4>
                                <p class="text-gray-500 text-sm">
                                    {{ trans('dashboard.tell_about_project', ['default' => 'Tell us about your project (optional)']) }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ trans('dashboard.project_details', ['default' => 'Project Details']) }}
                            </label>
                            <textarea wire:model="notes" rows="4" id="notes"
                                placeholder="{{ trans('dashboard.project_placeholder', ['default' => 'Describe your project, requirements, or any specific questions you\'d like to discuss...']) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('notes')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-gray-500 text-sm mt-1">
                                {{ trans('dashboard.max_characters', ['default' => 'Maximum 500 characters']) }}
                            </small>
                        </div>
                    </div>
                @endif

                <!-- Appointment Summary -->
                @if ($appointment_date)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h5 class="font-semibold text-gray-900 mb-4 flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            {{ trans('dashboard.appointment_summary', ['default' => 'Appointment Summary']) }}
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center p-4 bg-white rounded-lg gap-3">
                                <div class="bg-blue-100 rounded-lg p-2">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <small
                                        class="text-gray-500 text-sm">{{ trans('dashboard.designer', ['default' => 'Designer']) }}</small>
                                    <p class="font-semibold text-gray-900">
                                        {{ trans('dashboard.will_be_assigned', ['default' => 'Will be assigned after booking']) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-white rounded-lg gap-3">
                                <div class="bg-green-100 rounded-lg p-2">
                                    <i class="fas fa-calendar text-green-600"></i>
                                </div>
                                <div>
                                    <small
                                        class="text-gray-500 text-sm">{{ trans('dashboard.date', ['default' => 'Date']) }}</small>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($appointment_date)->format('l, F j, Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-white rounded-lg gap-3">
                                <div class="bg-blue-100 rounded-lg p-2">
                                    <i class="fas fa-clock text-blue-600"></i>
                                </div>
                                <div>
                                    <small
                                        class="text-gray-500 text-sm">{{ trans('dashboard.time', ['default' => 'Time']) }}</small>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($appointment_date)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($appointment_date)->addMinutes($duration_minutes)->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-white rounded-lg gap-3">
                                <div class="bg-yellow-100 rounded-lg p-2">
                                    <i class="fas fa-stopwatch text-yellow-600"></i>
                                </div>
                                <div>
                                    <small
                                        class="text-gray-500 text-sm">{{ trans('dashboard.duration', ['default' => 'Duration']) }}</small>
                                    <p class="font-semibold text-gray-900">
                                        {{ $duration_minutes }}
                                        {{ trans('dashboard.minutes', ['default' => 'minutes']) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary in Appointment Summary -->
                        @if ($selected_order_id)
                            <div class="mt-4 pt-4 border-t border-blue-200">
                                <h6 class="font-medium text-gray-900 mb-3">
                                    {{ trans('dashboard.linked_order', ['default' => 'Order to be Linked']) }}:
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded p-3">
                                        <span
                                            class="text-sm text-gray-600">{{ trans('dashboard.order_count', ['default' => 'Order']) }}:</span>
                                        <span class="font-semibold text-blue-600">1</span>
                                    </div>
                                    <div class="bg-white rounded p-3">
                                        <span
                                            class="text-sm text-gray-600">{{ trans('dashboard.total_value', ['default' => 'Total Value']) }}:</span>
                                        <span
                                            class="font-semibold text-blue-600">${{ number_format($selected_order_total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Submit Button -->
                @if ($appointment_date && $selected_order_id)
                    <div class="text-center">
                        <button type="submit" wire:loading.attr="disabled" wire:target="bookAppointment"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white px-8 py-3 rounded-lg inline-flex items-center transition-colors duration-200 text-lg font-medium">
                            <div wire:loading.remove wire:target="bookAppointment">
                                <i class="fas fa-calendar-check mr-2"></i>
                                {{ trans('dashboard.book_consultation', ['default' => 'Book My Consultation']) }}
                            </div>
                            <div wire:loading wire:target="bookAppointment" class="inline-flex items-center">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                {{ trans('dashboard.booking', ['default' => 'Booking...']) }}
                            </div>
                        </button>
                    </div>
                @elseif ($appointment_date && !$selected_order_id)
                    <div class="text-center">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ trans('dashboard.select_order_required', ['default' => 'Please select an order to link with this appointment.']) }}
                            </p>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bolt text-blue-600 text-xl"></i>
            </div>
            <h5 class="font-semibold text-gray-900 mb-2">
                {{ trans('dashboard.quick_easy', ['default' => 'Quick & Easy']) }}
            </h5>
            <p class="text-gray-500 text-sm">
                {{ trans('dashboard.quick_easy_desc', ['default' => 'Book your consultation in just a few clicks with our streamlined process.']) }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
            </div>
            <h5 class="font-semibold text-gray-900 mb-2">
                {{ trans('dashboard.link_orders', ['default' => 'Link Your Orders']) }}
            </h5>
            <p class="text-gray-500 text-sm">
                {{ trans('dashboard.link_orders_desc', ['default' => 'Connect your orders with appointments for better consultation.']) }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-star text-blue-600 text-xl"></i>
            </div>
            <h5 class="font-semibold text-gray-900 mb-2">
                {{ trans('dashboard.expert_designers', ['default' => 'Expert Designers']) }}
            </h5>
            <p class="text-gray-500 text-sm">
                {{ trans('dashboard.expert_designers_desc', ['default' => 'Connect with verified professionals who deliver exceptional results.']) }}
            </p>
        </div>
    </div>
</div>
