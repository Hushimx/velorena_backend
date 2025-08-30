<div class="space-y-6">
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
                                <input id="flatpickr" type="datetime-local" wire:model.live="selectedDate"
                                    min="{{ $this->minDate }}T09:00" max="{{ $this->maxDate }}T17:00" id="selectedDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('selectedDate')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex items-end">
                                <small class="text-gray-500">
                                    {{ trans('dashboard.available_dates', ['default' => 'Available dates']) }}:
                                    {{ \Carbon\Carbon::parse($this->minDate)->format('M j, Y g:i A') }} to
                                    {{ \Carbon\Carbon::parse($this->maxDate)->format('M j, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Step 3: Notes -->
                @if ($selectedDate)
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
                @if ($selectedDate)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h5 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            {{ trans('dashboard.appointment_summary', ['default' => 'Appointment Summary']) }}
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center p-4 bg-white rounded-lg">
                                <div class="bg-blue-100 rounded-lg p-2 mr-3">
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
                            <div class="flex items-center p-4 bg-white rounded-lg">
                                <div class="bg-green-100 rounded-lg p-2 mr-3">
                                    <i class="fas fa-calendar text-green-600"></i>
                                </div>
                                <div>
                                    <small
                                        class="text-gray-500 text-sm">{{ trans('dashboard.date', ['default' => 'Date']) }}</small>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-white rounded-lg">
                                <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                    <i class="fas fa-clock text-blue-600"></i>
                                </div>
                                <div>
                                    <small
                                        class="text-gray-500 text-sm">{{ trans('dashboard.time', ['default' => 'Time']) }}</small>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($selectedDate)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($selectedDate)->addMinutes(15)->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 bg-white rounded-lg">
                                <div class="bg-yellow-100 rounded-lg p-2 mr-3">
                                    <i class="fas fa-stopwatch text-yellow-600"></i>
                                </div>
                                <div>
                                    <small
                                        class="text-gray-500 text-sm">{{ trans('dashboard.duration', ['default' => 'Duration']) }}</small>
                                    <p class="font-semibold text-gray-900">
                                        {{ trans('dashboard.fifteen_minutes', ['default' => '15 minutes']) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                @if ($selectedDate)
                    <div class="text-center">
                        <button type="submit" wire:loading.attr="disabled" wire:target="bookAppointment"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white px-8 py-3 rounded-lg inline-flex items-center transition-colors duration-200 text-lg font-medium">
                            <div wire:loading.remove wire:target="bookAppointment">
                                <i class="fas fa-calendar-check mr-2"></i>
                                {{ trans('dashboard.book_consultation', ['default' => 'Book My Consultation']) }}
                            </div>
                            <div wire:loading wire:target="bookAppointment" class="inline-flex items-center">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                            </div>
                        </button>
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
                <i class="fas fa-shield-alt text-green-600 text-xl"></i>
            </div>
            <h5 class="font-semibold text-gray-900 mb-2">
                {{ trans('dashboard.secure_reliable', ['default' => 'Secure & Reliable']) }}
            </h5>
            <p class="text-gray-500 text-sm">
                {{ trans('dashboard.secure_reliable_desc', ['default' => 'Your data is protected with enterprise-grade security and encryption.']) }}
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
