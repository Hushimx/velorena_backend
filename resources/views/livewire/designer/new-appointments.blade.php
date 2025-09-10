<div wire:poll.5s="refreshAppointments">
    <!-- New Appointments Available to Claim -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.new_appointments') }}</h3>
                <!-- Live Indicator -->
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-red-600">{{ __('dashboard.live') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $availableCount }} {{ __('dashboard.available') }}
                </span>
                <div class="flex items-center gap-2">
                    <div wire:loading class="animate-spin">
                        <i class="fas fa-sync-alt text-gray-400"></i>
                    </div>
                    <i class="fas fa-hand-paper" style="color: #2a1e1e;"></i>
                </div>
            </div>
        </div>
        
        <!-- Last Updated & Refresh Button -->
        <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center gap-3 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-gray-400"></i>
                    <span>{{ __('dashboard.last_updated') }}:</span>
                    <span class="font-medium" wire:key="last-updated-{{ $lastUpdated->timestamp }}">
                        {{ $lastUpdated->format('H:i:s') }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i class="fas fa-sync-alt text-gray-400"></i>
                    <span>{{ __('dashboard.auto_refresh') }}:</span>
                    <span class="font-medium text-green-600" id="countdown-timer">5s</span>
                </div>
            </div>
            <button wire:click="refreshAppointments" 
                    class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    wire:loading.attr="disabled">
                <i class="fas fa-sync-alt" wire:loading.class="animate-spin"></i>
                <span wire:loading.remove>{{ __('dashboard.refresh') }}</span>
                <span wire:loading>{{ __('dashboard.updating') }}...</span>
            </button>
        </div>
        
        <div class="space-y-3">
            @forelse($availableAppointments as $appointment)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->user->full_name ?? $appointment->user->email }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : 'غير محدد' }}
                                    @if($appointment->appointment_time)
                                        - {{ $appointment->appointment_time->format('H:i') }}
                                    @endif
                                </p>
                                @if($appointment->service_type)
                                    <p class="text-xs text-gray-400">{{ $appointment->service_type }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="claimAppointment({{ $appointment->id }})" 
                                wire:confirm="{{ __('dashboard.confirm_claim_appointment') }}"
                                class="px-3 py-1.5 text-xs font-medium text-white rounded-lg transition-colors hover:shadow-sm"
                                style="background-color: #2a1e1e;">
                            <i class="fas fa-hand-paper mr-1"></i>
                            {{ __('dashboard.claim') }}
                        </button>
                        <button wire:click="passAppointment({{ $appointment->id }})" 
                                wire:confirm="{{ __('dashboard.confirm_pass_appointment') }}"
                                class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-200 rounded-lg transition-colors hover:bg-gray-300">
                            <i class="fas fa-times mr-1"></i>
                            {{ __('dashboard.pass') }}
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-check text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">{{ __('dashboard.no_available_appointments') }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ __('dashboard.all_appointments_claimed') }}</p>
                </div>
            @endforelse
        </div>
        
        @if($availableCount > 0)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('designer.appointments.dashboard') }}" 
                   class="text-sm font-medium hover:underline flex items-center gap-2"
                   style="color: #2a1e1e;">
                    <span>{{ __('dashboard.view_all_available') }}</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    let countdown = 5;
    let countdownInterval;
    
    function startCountdown() {
        countdown = 5;
        const timerElement = document.getElementById('countdown-timer');
        if (timerElement) {
            countdownInterval = setInterval(() => {
                countdown--;
                timerElement.textContent = countdown + 's';
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    timerElement.textContent = '5s';
                }
            }, 1000);
        }
    }
    
    // Start countdown when component loads
    startCountdown();
    
    // Restart countdown after each update
    Livewire.on('appointment-claimed', () => {
        clearInterval(countdownInterval);
        startCountdown();
    });
    
    Livewire.on('appointment-passed', () => {
        clearInterval(countdownInterval);
        startCountdown();
    });
    
    // Restart countdown when manual refresh happens
    document.addEventListener('livewire:update', () => {
        clearInterval(countdownInterval);
        startCountdown();
    });
});
</script>