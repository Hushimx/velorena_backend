<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\CartItem;
// ProductDesign removed - designs are now order-level only
use App\Models\OrderDesign;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Carbon\Carbon;

class BookAppointment extends Component
{
    public $selectedDate;
    public $selectedTime;
    public $notes;
    public $cartItems = [];
    public $cartEmpty = false;
    public $availableSlots = [];

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->loadCartItems();
    }

    public function loadCartItems()
    {
        if (!Auth::check()) {
            $this->cartEmpty = true;
            return;
        }

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['product.options.values'])
            ->get();

        $this->cartItems = [];
        foreach ($cartItems as $item) {
            // Update prices if not set
            if (!$item->unit_price || !$item->total_price) {
                $item->updatePrices();
            }

            // No more product-specific designs - designs are order-level now
            $designs = [];

            $this->cartItems[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'notes' => $item->notes ?? '',
                'designs' => $designs,
            ];
        }

        $this->cartEmpty = empty($this->cartItems);
    }

    public function updatedSelectedDate()
    {
        if ($this->selectedDate) {
            // Extract date and time from the selected date input
            $this->extractDateTimeFromInput();
            // Generate available time slots for the selected date
            $this->generateTimeSlots();
        } else {
            $this->selectedTime = null;
            $this->availableSlots = [];
        }
    }

    private function extractDateTimeFromInput()
    {
        // The selectedDate field now contains just the date in Y-m-d format
        // We just need to validate that it's a valid date
        if ($this->selectedDate) {
            $dateTime = Carbon::parse($this->selectedDate);
        }
    }

    private function generateTimeSlots()
    {
        $this->availableSlots = [];
        
        if (!$this->selectedDate) {
            return;
        }

        $selectedDate = Carbon::parse($this->selectedDate)->format('Y-m-d');
        
        try {
            // Get real available slots from database using the same method as the API
            $this->availableSlots = \App\Models\AvailabilitySlot::getAvailableTimeSlotsExcludingBooked($selectedDate);
        } catch (\Exception $e) {
            Log::error('Failed to get available slots: ' . $e->getMessage());
            $this->availableSlots = [];
        }
    }

    public function selectTimeSlot($time)
    {
        $this->selectedTime = $time;
    }



    public function bookAppointment()
    {
        // Check if cart is empty
        if ($this->cartEmpty || empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty. Please add products to your cart before booking an appointment.');
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            // Create order from cart items
            $order = $this->createOrderFromCart();
            
            if (!$order) {
                throw new \Exception('Failed to create order from cart');
            }

            // Combine selected date and time for database storage
            $appointmentDate = $this->selectedDate;
            $appointmentTime = $this->selectedTime . ':00';

            // Create the appointment and link it to the order
            $appointment = Appointment::create([
                'user_id' => Auth::id(),
                'designer_id' => null, // No designer assigned yet
                'order_id' => $order->id, // Link to the created order
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
                'duration_minutes' => 15,
                'status' => 'pending',
                'notes' => $this->notes,
            ]);

            DB::commit();

            // Reset form
            $this->reset(['selectedDate', 'selectedTime', 'notes']);
            $this->loadCartItems(); // Reload cart items (should be empty now)

            // Dispatch event to notify other components about the new appointment
            $this->dispatch('appointment-created', $appointment->id);

            // Show success message and redirect
            session()->flash('success', 'Appointment booked successfully! Order #' . $order->order_number . ' has been created and linked to your appointment.');

            return redirect()->route('appointments.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Appointment booking failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to book appointment: ' . $e->getMessage());
        }
    }

    private function createOrderFromCart()
    {
        if (!Auth::check() || empty($this->cartItems)) {
            return null;
        }

        try {
            $user = Auth::user();
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

            $orderService = new OrderService();

            // Prepare items data for OrderService
            $items = [];
            foreach ($cartItems as $cartItem) {
                $items[] = [
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'selected_options' => $cartItem->selected_options,
                    'notes' => $cartItem->notes
                ];
            }

            $orderData = [
                'phone' => $user->phone ?? '',
                'shipping_address' => $user->address ?? '',
                'billing_address' => $user->address ?? '',
                'notes' => 'Order created from cart for appointment booking',
                'items' => $items
            ];

            $order = $orderService->createOrder($orderData);

            // Set order status to waiting_for_appointment
            $order->update(['status' => 'waiting_for_appointment']);

            // Copy cart designs to order
            $cartDesigns = \App\Models\CartDesign::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            foreach ($cartDesigns as $design) {
                OrderDesign::create([
                    'order_id' => $order->id,
                    'title' => $design->title,
                    'image_url' => $design->image_url,
                    'thumbnail_url' => $design->thumbnail_url,
                    'design_data' => $design->design_data,
                    'notes' => $design->notes ?? 'Design from cart',
                    'priority' => 1
                ]);
            }

            // Clear cart after successful order creation
            CartItem::where('user_id', $user->id)->delete();
            
            // Clear cart designs after successful order creation
            \App\Models\CartDesign::where('user_id', $user->id)->delete();

            return $order;
        } catch (\Exception $e) {
            Log::error('Order creation from cart failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getMinDateProperty()
    {
        return now()->format('Y-m-d');
    }

    public function getMaxDateProperty()
    {
        return now()->addMonths(3)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.book-appointment');
    }
}
