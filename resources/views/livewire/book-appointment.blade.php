<div class="row">
    <!-- Main Form Card -->
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form wire:submit.prevent="bookAppointment">
                    <!-- Step 1: Date Selection -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="fas fa-calendar text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-semibold">Select Your Date</h4>
                                <p class="text-muted mb-0">Choose when you'd like to meet</p>
                            </div>
                        </div>

                        <div class="alert alert-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="selectedDate" class="form-label fw-medium">
                                        Available Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" wire:model.live="selectedDate" min="{{ $this->minDate }}"
                                        max="{{ $this->maxDate }}" id="selectedDate" class="form-control">
                                    @error('selectedDate')
                                        <div class="form-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <small class="text-muted">Available dates: {{ $this->minDate }} to
                                        {{ $this->maxDate }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Time Selection -->
                    @if ($selectedDate)
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-clock text-success fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-semibold">Choose Your Time</h4>
                                    <p class="text-muted mb-0">Pick your preferred time slot</p>
                                </div>
                            </div>

                            <div class="alert alert-light">
                                @if ($loading)
                                    <div class="d-flex justify-content-center align-items-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary me-3" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="text-muted">Loading available time slots...</span>
                                    </div>
                                @elseif(count($availableSlots) > 0)
                                    <div class="row g-2">
                                        @foreach ($availableSlots as $slot)
                                            <div class="col-6 col-md-3 col-lg-2">
                                                <button type="button"
                                                    wire:click="$set('selectedTime', '{{ $slot }}')"
                                                    class="btn w-100 position-relative {{ $selectedTime === $slot ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                    {{ \Carbon\Carbon::parse($slot)->format('g:i A') }}
                                                    @if ($selectedTime === $slot)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    @endif
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('selectedTime')
                                        <div class="form-text text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                @else
                                    <div class="text-center py-4">
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                            style="width: 60px; height: 60px;">
                                            <i class="fas fa-calendar-times fa-lg text-muted"></i>
                                        </div>
                                        <h5 class="fw-semibold mb-1">No Available Slots</h5>
                                        <p class="text-muted mb-0">This date is fully booked. Please select another
                                            date.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Step 3: Notes -->
                    @if ($selectedTime)
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-edit text-info fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-semibold">Additional Information</h4>
                                    <p class="text-muted mb-0">Tell us about your project (optional)</p>
                                </div>
                            </div>

                            <div class="alert alert-light">
                                <label for="notes" class="form-label fw-medium">Project Details</label>
                                <textarea wire:model="notes" rows="4" id="notes"
                                    placeholder="Describe your project, requirements, or any specific questions you'd like to discuss..."
                                    class="form-control"></textarea>
                                @error('notes')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Maximum 500 characters</small>
                            </div>
                        </div>
                    @endif

                    <!-- Appointment Summary -->
                    @if ($selectedDate && $selectedTime)
                        <div class="alert alert-primary mb-4">
                            <h5 class="alert-heading d-flex align-items-center mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Appointment Summary
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-white rounded">
                                        <div class="rounded bg-primary bg-opacity-10 p-2 me-3">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted">Designer</small>
                                            <p class="mb-0 fw-semibold">Will be assigned after booking</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-white rounded">
                                        <div class="rounded bg-success bg-opacity-10 p-2 me-3">
                                            <i class="fas fa-calendar text-success"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted">Date</small>
                                            <p class="mb-0 fw-semibold">
                                                {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-white rounded">
                                        <div class="rounded bg-info bg-opacity-10 p-2 me-3">
                                            <i class="fas fa-clock text-info"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted">Time</small>
                                            <p class="mb-0 fw-semibold">
                                                {{ \Carbon\Carbon::parse($selectedTime)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($selectedTime)->addMinutes(15)->format('g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-white rounded">
                                        <div class="rounded bg-warning bg-opacity-10 p-2 me-3">
                                            <i class="fas fa-stopwatch text-warning"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted">Duration</small>
                                            <p class="mb-0 fw-semibold">15 minutes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    @if ($selectedDate && $selectedTime)
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-calendar-check me-2"></i>Book My Consultation
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="col-12">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-bolt text-primary fa-lg"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Quick & Easy</h5>
                        <p class="text-muted mb-0">Book your consultation in just a few clicks with our streamlined
                            process.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-shield-alt text-success fa-lg"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Secure & Reliable</h5>
                        <p class="text-muted mb-0">Your data is protected with enterprise-grade security and
                            encryption.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-star text-info fa-lg"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Expert Designers</h5>
                        <p class="text-muted mb-0">Connect with verified professionals who deliver exceptional results.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
