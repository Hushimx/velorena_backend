<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Designer;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookAppointmentWithOrders extends Component
{
    use WithPagination;

    public $user_id;
    public $designer_id;
    public $appointment_date;
    public $appointment_time;
    public $duration_minutes = 15;
    public $notes;
    public $selected_order_id;
    public $order_notes;

    public $available_time_slots = [];
    public $user_orders = [];
    public $designers = [];
    public $show_used_orders = false;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'designer_id' => 'nullable|exists:designers,id',
        'appointment_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        'duration_minutes' => 'integer|min:15|max:480',
        'notes' => 'nullable|string|max:1000',
        'selected_order_id' => 'required|exists:orders,id',
        'order_notes' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'selected_order_id.required' => 'Please select an order to link with this appointment.',
        'appointment_date.required' => 'Please select a date and time for your appointment.',
        'appointment_date.date_format' => 'Please select a valid date and time.',
        'appointment_date.after' => 'Appointments must be scheduled for a future date and time.',
    ];

    public function mount($userId = null)
    {
        $this->user_id = $userId ?? (Auth::check() ? Auth::id() : null);
        $this->designers = Designer::where('is_active', true)->get();
        $this->loadUserOrders();
    }

    public function loadUserOrders()
    {
        $query = Order::where('user_id', $this->user_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['items.product', 'appointment']);

        // If not showing used orders, filter them out
        if (!$this->show_used_orders) {
            $query->whereDoesntHave('appointment');
        }

        $this->user_orders = $query->get();
    }

    public function updatedAppointmentDate()
    {
        $this->available_time_slots = [];
        if ($this->appointment_date) {
            $this->extractDateTimeFromInput();
        }
    }

    private function extractDateTimeFromInput()
    {
        try {
            // The appointment_date field now contains the full datetime in Y-m-d\TH:i format
            // We just need to validate that it's a valid datetime
            if ($this->appointment_date) {
                $dateTime = Carbon::parse($this->appointment_date);

                Log::info('DateTime extracted:', [
                    'original_input' => $this->appointment_date,
                    'parsed_datetime' => $dateTime->format('Y-m-d H:i:s')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error extracting datetime:', [
                'input' => $this->appointment_date,
                'error' => $e->getMessage()
            ]);
            $this->addError('appointment_date', 'Invalid date/time format.');
        }
    }

    public function selectTimeSlot($time)
    {
        $this->appointment_time = $time;
    }

    public function selectOrder($orderId)
    {
        $this->selected_order_id = $orderId;
    }

    public function toggleUsedOrders()
    {
        $this->show_used_orders = !$this->show_used_orders;
        $this->loadUserOrders();
    }

    private function validateWithCustomRules()
    {
        // Debug: Log what we're validating
        Log::info('Validating appointment:', [
            'appointment_date' => $this->appointment_date,
            'now' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // Check if appointment datetime is in the future
        if ($this->appointment_date) {
            $selectedDateTime = Carbon::parse($this->appointment_date);
            $now = Carbon::now();

            Log::info('DateTime comparison:', [
                'selected' => $selectedDateTime->format('Y-m-d H:i:s'),
                'now' => $now->format('Y-m-d H:i:s'),
                'is_future' => $selectedDateTime->gt($now)
            ]);

            if ($selectedDateTime->lte($now)) {
                $this->addError('appointment_date', 'Appointments must be scheduled for a future date and time.');
                Log::error('Validation failed: Appointment time is not in the future');
                return false;
            }

            // Check if appointment time is within business hours (9 AM to 5 PM)
            $time = $selectedDateTime->format('H:i');
            $startTime = Carbon::parse('09:00');
            $endTime = Carbon::parse('17:00');
            $appointmentTime = Carbon::parse($time);

            Log::info('Business hours check:', [
                'appointment_time' => $time,
                'start_time' => $startTime->format('H:i'),
                'end_time' => $endTime->format('H:i'),
                'within_hours' => $appointmentTime->gte($startTime) && $appointmentTime->lte($endTime)
            ]);

            if ($appointmentTime->lt($startTime) || $appointmentTime->gt($endTime)) {
                $this->addError('appointment_date', 'Appointments must be scheduled between 9:00 AM and 5:00 PM.');
                Log::error('Validation failed: Appointment time is outside business hours');
                return false;
            }
        }

        Log::info('Custom validation passed');
        return true;
    }

    public function bookAppointment()
    {
        try {
            // Debug: Log the data being processed
            Log::info('Booking appointment with data:', [
                'user_id' => $this->user_id,
                'appointment_date' => $this->appointment_date,
                'selected_order_id' => $this->selected_order_id,
                'notes' => $this->notes
            ]);

            // Validate the form
            $this->validate();

            // Custom validation for datetime
            $this->validateWithCustomRules();

            Log::info('Validation passed successfully');

            // Use database transaction
            DB::beginTransaction();

            // Parse the datetime and extract date and time for database storage
            $dateTime = Carbon::parse($this->appointment_date);
            $appointmentDate = $dateTime->format('Y-m-d');
            $appointmentTime = $dateTime->format('H:i:s');

            // Create appointment
            $appointment = Appointment::create([
                'user_id' => $this->user_id,
                'designer_id' => null, // No designer assigned yet
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
                'duration_minutes' => $this->duration_minutes,
                'notes' => $this->notes,
                'order_id' => $this->selected_order_id,
                'order_notes' => $this->order_notes,
                'status' => 'pending'
            ]);

            DB::commit();

            // Reset form
            $this->reset(['appointment_date', 'appointment_time', 'notes', 'selected_order_id', 'order_notes']);
            $this->loadUserOrders();

            // Show success message
            session()->flash('success', 'Appointment booked successfully! The designer will review your request.');

            // Use Livewire redirect
            $this->redirect(route('appointments.index'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation exception:', [
                'errors' => $e->errors()
            ]);
            // Let Livewire handle the validation errors automatically
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Appointment booking error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('general', 'Failed to book appointment: ' . $e->getMessage());
        }
    }

    public function getSelectedOrderTotal()
    {
        if ($this->selected_order_id) {
            return Order::find($this->selected_order_id)->total ?? 0;
        }
        return 0;
    }

    public function getSelectedOrderProductsCount()
    {
        if ($this->selected_order_id) {
            return Order::find($this->selected_order_id)->items->sum('quantity') ?? 0;
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.book-appointment-with-orders', [
            'user_orders' => $this->user_orders,
            'designers' => $this->designers,
            'selected_order_total' => $this->getSelectedOrderTotal(),
            'selected_order_products_count' => $this->getSelectedOrderProductsCount(),
            'show_used_orders' => $this->show_used_orders
        ]);
    }
}
