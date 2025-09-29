<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-100 text-green-700 rounded-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="px-2">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Error Message -->
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-100 text-red-700 rounded-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span class="px-2">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('appointments.search') }}</label>
                <input type="text" wire:model.live.debounce.500ms="search" wire:key="search-input"
                    placeholder="{{ trans('appointments.appointments_search_placeholder') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label for="status_filter"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ trans('appointments.status_filter') }}</label>
                <select wire:model.live="status_filter" wire:key="status-filter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">{{ trans('appointments.all_statuses') }}</option>
                    <option value="pending">{{ trans('appointments.pending') }}</option>
                    <option value="accepted">{{ trans('appointments.accepted') }}</option>
                    <option value="rejected">{{ trans('appointments.rejected') }}</option>
                    <option value="completed">{{ trans('appointments.completed') }}</option>
                    <option value="cancelled">{{ trans('appointments.cancelled') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('appointments.appointment_id') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('appointments.customer') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('appointments.designer') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('appointments.appointment_date') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('appointments.status') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('appointments.order_total') }}
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans('actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50" wire:key="appointment-row-{{ $appointment->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $appointment->id }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->duration_minutes }} {{ trans('appointments.minutes') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($appointment->designer)
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->designer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->designer->email }}</div>
                                @else
                                    <span class="text-sm text-gray-500 italic">{{ trans('appointments.unassigned') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-medium text-gray-900">{{ $appointment->formatted_date }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->formatted_time }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $appointment->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $appointment->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $appointment->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ trans('appointments.' . $appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                @if($appointment->order)
                                    {{ number_format($appointment->order->total, 2) }} {{ trans('orders.currency') }}
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-4">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.appointments.show', $appointment) }}"
                                        class="inline-flex items-center px-3 ml-2 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-eye mx-1.5"></i>
                                        <span>{{ trans('show') }}</span>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.appointments.edit', $appointment) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition duration-150 ease-in-out">
                                        <i class="fas fa-pen mx-1.5"></i>
                                        <span>{{ trans('Edit') }}</span>
                                    </a>

                                    <!-- Delete Button (only for pending appointments) -->
                                    @if ($appointment->isPending())
                                        <button wire:click="confirmDelete({{ $appointment->id }})"
                                            wire:key="delete-{{ $appointment->id }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition duration-150 ease-in-out">
                                            <i class="fas fa-trash mx-1.5"></i>
                                            <span>{{ trans('appointments.delete') }}</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                {{ trans('appointments.no_appointments_exist') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $appointments->links() }}
        </div>

    </div>

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
                        {{ trans('appointments.confirm_delete_title') }}</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            {{ trans('appointments.confirm_delete_appointment') }}
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="deleteAppointment" wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50">
                            <span wire:loading.remove wire:target="deleteAppointment">{{ trans('appointments.delete') }}</span>
                            <span wire:loading wire:target="deleteAppointment" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
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
                            {{ trans('appointments.cancel') }}
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
