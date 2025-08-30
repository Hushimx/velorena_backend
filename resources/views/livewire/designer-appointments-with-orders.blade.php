<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">My Appointments</h2>

    <!-- Filters -->
    <div class="mb-6 flex gap-4">
        <select wire:model.live="status_filter" class="border rounded px-3 py-2">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <input type="date" wire:model.live="date_filter" class="border rounded px-3 py-2">

        <button wire:click="clearFilters" class="bg-gray-500 text-white px-4 py-2 rounded">
            Clear Filters
        </button>
    </div>

    <!-- Appointments List -->
    <div class="space-y-4">
        @forelse($appointments as $appointment)
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <!-- Appointment Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">
                            Appointment #{{ $appointment->id }}
                        </h3>
                        <p class="text-gray-600">
                            {{ $appointment->user->full_name }} - {{ $appointment->user->email }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $appointment->appointment_date->format('l, F j, Y') }} at
                            {{ $appointment->appointment_time->format('g:i A') }}
                        </p>
                    </div>

                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $appointment->status_badge }}">
                            {{ $appointment->status_text }}
                        </span>
                        <p class="text-sm text-gray-500 mt-1">
                            Duration: {{ $appointment->duration_minutes }} minutes
                        </p>
                    </div>
                </div>

                <!-- Linked Order Summary -->
                @if ($appointment->hasOrder())
                    <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-4">
                        <h4 class="font-semibold text-blue-800 mb-2">
                            📦 Linked Order
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Total Products:</span>
                                {{ $appointment->getTotalProductsCount() }}
                            </div>
                            <div>
                                <span class="font-medium">Total Value:</span>
                                ${{ number_format($appointment->getTotalOrderValue(), 2) }}
                            </div>
                            <div>
                                <span class="font-medium">Order:</span>
                                {{ $appointment->order->order_number }}
                            </div>
                        </div>
                    </div>

                    <!-- Products Details -->
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">📋 Products for this Appointment:</h4>
                        <div class="space-y-2">
                            @foreach ($appointment->getProductsSummary() as $productName => $details)
                                <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                    <div>
                                        <span class="font-medium">{{ $productName }}</span>
                                        <span class="text-sm text-gray-600">(Qty: {{ $details['quantity'] }})</span>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="text-sm text-gray-600">${{ number_format($details['unit_price'], 2) }}
                                            each</span>
                                        <span
                                            class="font-medium">${{ number_format($details['total_price'], 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">📋 Order Details:</h4>
                        @if ($appointment->order)
                            <div class="border rounded p-3 mb-2">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="font-medium">Order #{{ $appointment->order->order_number }}</span>
                                        <span class="text-sm text-gray-600">({{ $appointment->order->status }})</span>
                                    </div>
                                    <span
                                        class="font-medium">${{ number_format($appointment->order->total, 2) }}</span>
                                </div>

                                @if ($appointment->order_notes)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <strong>Notes:</strong> {{ $appointment->order_notes }}
                                    </p>
                                @endif

                                <div class="text-sm">
                                    @foreach ($appointment->order->items as $item)
                                        <div class="flex justify-between py-1">
                                            <span>{{ $item->product->name ?? 'Unknown Product' }}
                                                (x{{ $item->quantity }})
                                            </span>
                                            <span>${{ number_format($item->total_price, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4">
                        <p class="text-yellow-800">⚠️ No orders linked to this appointment</p>
                    </div>
                @endif

                <!-- Appointment Notes -->
                @if ($appointment->notes)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-1">📝 Client Notes:</h4>
                        <p class="text-gray-700 bg-gray-50 p-2 rounded">{{ $appointment->notes }}</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    @if ($appointment->isPending())
                        <button wire:click="acceptAppointment({{ $appointment->id }})"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Accept
                        </button>
                        <button wire:click="rejectAppointment({{ $appointment->id }})"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Reject
                        </button>
                    @endif

                    @if ($appointment->isAccepted())
                        <button wire:click="completeAppointment({{ $appointment->id }})"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Mark Complete
                        </button>
                    @endif

                    <button wire:click="viewAppointmentDetails({{ $appointment->id }})"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        View Details
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-gray-500">No appointments found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($appointments->hasPages())
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    @endif
</div>
