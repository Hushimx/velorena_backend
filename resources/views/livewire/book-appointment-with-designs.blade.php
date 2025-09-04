<div class="book-appointment-with-designs">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Book Appointment with Design Selection</h2>

            <!-- Appointment Form -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Appointment Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Date and Time Selection -->
                    <div>
                        <label for="selectedDate" class="block text-sm font-medium text-gray-700 mb-2">
                            Appointment Date & Time
                        </label>
                        <input type="datetime-local" id="selectedDate" wire:model="selectedDate"
                            min="{{ $this->minDate }}" max="{{ $this->maxDate }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('selectedDate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Appointment Notes
                        </label>
                        <textarea id="notes" wire:model="notes" rows="3" placeholder="Any special requirements or notes..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Design Selection Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Select Designs</h3>
                    <div class="text-sm text-gray-500">
                        Selected: {{ count($selectedDesigns) }} designs
                    </div>
                </div>

                <!-- Design Selector Component -->
                @livewire('design-selector')

                <!-- Selected Designs Summary -->
                @if (!empty($selectedDesigns))
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Selected Designs:</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($this->getSelectedDesignsData() as $design)
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $design['thumbnail_url'] }}" alt="{{ $design['title'] }}"
                                            class="w-12 h-12 object-cover rounded">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $design['title'] }}
                                            </p>
                                            <textarea wire:model="designNotes.{{ $design['id'] }}" placeholder="Add notes..."
                                                class="mt-1 w-full text-xs border border-gray-300 rounded px-2 py-1" rows="2"></textarea>
                                        </div>
                                        <button wire:click="removeDesign({{ $design['id'] }})"
                                            class="text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button wire:click="bookAppointment" wire:loading.attr="disabled"
                    class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                    <span wire:loading.remove>Book Appointment</span>
                    <span wire:loading>Booking...</span>
                </button>
            </div>
        </div>
    </div>
</div>
