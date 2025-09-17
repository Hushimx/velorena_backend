<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\User;
use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Show the appointment booking form
     */
    public function create(Request $request)
    {
        $designers = Designer::where('is_active', true)->get();
        $orderId = $request->get('order_id');
        return view('users.appointments.create', compact('designers', 'orderId'));
    }

    /**
     * Show the appointment booking success page
     */
    public function success()
    {
        return view('users.appointments.success');
    }

    /**
     * Create a new appointment and link it with orders
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'designer_id' => 'nullable|exists:designers,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'duration_minutes' => 'integer|min:15|max:480', // 15 minutes to 8 hours
            'notes' => 'nullable|string|max:1000',
            'order_id' => 'required|exists:orders,id',
            'order_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check if time slot is available
            $designerId = $request->designer_id;
            $date = $request->appointment_date;
            $time = $request->appointment_time;
            $duration = $request->duration_minutes ?? 15;

            if ($designerId && !Appointment::isTimeSlotAvailable($designerId, $date, $time, $duration)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected time slot is not available'
                ], 409);
            }

            // Create appointment
            $appointment = Appointment::create([
                'user_id' => $request->user_id,
                'designer_id' => $designerId,
                'appointment_date' => $date,
                'appointment_time' => $time,
                'duration_minutes' => $duration,
                'notes' => $request->notes,
                'order_id' => $request->order_id,
                'order_notes' => $request->order_notes,
                'status' => $designerId ? 'pending' : 'pending'
            ]);

            // Load relationships for response
            $appointment->load(['user', 'designer', 'order.items.product']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'data' => [
                    'appointment' => $appointment,
                    'total_products' => $appointment->getTotalProductsCount(),
                    'total_value' => $appointment->getTotalOrderValue()
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user's appointments
     */
    public function index()
    {
        $appointments = Appointment::with(['designer', 'order.items.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.appointments.index', compact('appointments'));
    }

    /**
     * Get appointment details with linked orders and products
     */
    public function show(Appointment $appointment, Request $request)
    {
        // Check if user owns this appointment
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load([
            'user:id,full_name,email,phone',
            'designer:id,name,email,phone',
            'order.items.product',
            'order.items.product.options.values',
            'order.items.designs.design'
        ]);

        // Ensure options are properly cast as arrays
        if ($appointment->order && $appointment->order->items) {
            foreach ($appointment->order->items as $item) {
                if (is_string($item->options)) {
                    $item->options = json_decode($item->options, true) ?? [];
                }
            }
        }

        // If it's an API request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'appointment' => $appointment,
                    'products_summary' => $appointment->getProductsSummary(),
                    'total_products_count' => $appointment->getTotalProductsCount(),
                    'total_order_value' => $appointment->getTotalOrderValue(),
                    'has_order' => $appointment->hasOrder()
                ]
            ]);
        }

        // For web requests, render the view
        return view('users.appointments.show', compact('appointment'));
    }

    /**
     * Cancel an appointment
     */
    public function cancel(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$appointment->canBeCancelled()) {
            return back()->withErrors(['appointment' => trans('dashboard.appointment_cannot_cancel', ['default' => 'This appointment cannot be cancelled.'])]);
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', trans('dashboard.appointment_cancelled_success'));
    }

    /**
     * Update appointment and manage linked orders
     */
    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'designer_id' => 'nullable|exists:designers,id',
            'appointment_date' => 'sometimes|date|after_or_equal:today',
            'appointment_time' => 'sometimes|date_format:H:i',
            'duration_minutes' => 'sometimes|integer|min:15|max:480',
            'notes' => 'nullable|string|max:1000',
            'designer_notes' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:pending,accepted,rejected,completed,cancelled',
            'order_id' => 'sometimes|exists:orders,id',
            'order_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check time slot availability if date/time is being changed
            if ($request->has('appointment_date') || $request->has('appointment_time')) {
                $designerId = $request->designer_id ?? $appointment->designer_id;
                $date = $request->appointment_date ?? $appointment->appointment_date;
                $time = $request->appointment_time ?? $appointment->appointment_time;
                $duration = $request->duration_minutes ?? $appointment->duration_minutes;

                if ($designerId && !Appointment::isTimeSlotAvailable($designerId, $date, $time, $duration, $appointment->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected time slot is not available'
                    ], 409);
                }
            }

            // Handle status changes with proper methods
            $updateData = $request->only([
                'designer_id',
                'appointment_date',
                'appointment_time',
                'duration_minutes',
                'notes',
                'designer_notes',
                'order_id',
                'order_notes'
            ]);

            // Handle status changes with proper business logic
            if ($request->has('status')) {
                $newStatus = $request->status;
                $currentStatus = $appointment->status;

                if ($newStatus === 'accepted' && $currentStatus === 'pending') {
                    $appointment->accept($request->designer_notes);
                } elseif ($newStatus === 'rejected' && $currentStatus === 'pending') {
                    $appointment->reject($request->designer_notes);
                } elseif ($newStatus === 'completed' && $currentStatus === 'accepted') {
                    $appointment->complete();
                } elseif ($newStatus === 'cancelled') {
                    $appointment->cancel();
                } else {
                    // For other status changes, update directly
                    $updateData['status'] = $newStatus;
                }
            }

            // Update other fields
            if (!empty($updateData)) {
                $appointment->update($updateData);
            }

            // Load relationships for response
            $appointment->load(['user', 'designer', 'order.items.product']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully',
                'data' => [
                    'appointment' => $appointment,
                    'total_products' => $appointment->getTotalProductsCount(),
                    'total_value' => $appointment->getTotalOrderValue()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show designer's appointment dashboard
     */
    public function designerDashboard()
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer) {
            abort(403, 'Designer access required');
        }

        // Get today's appointments
        $todayAppointments = Appointment::with('user')
            ->where('designer_id', $designer->id)
            ->where('appointment_date', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_time')
            ->get();

        // Get upcoming appointments
        $upcomingAppointments = Appointment::with('user')
            ->where('designer_id', $designer->id)
            ->where('appointment_date', '>', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        // Get pending appointments
        $pendingAppointments = Appointment::with('user')
            ->where('designer_id', $designer->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get appointment statistics
        $totalAppointments = Appointment::where('designer_id', $designer->id)->count();
        $completedAppointments = Appointment::where('designer_id', $designer->id)->where('status', 'completed')->count();
        $pendingCount = Appointment::where('designer_id', $designer->id)->where('status', 'pending')->count();
        $cancelledCount = Appointment::where('designer_id', $designer->id)->where('status', 'cancelled')->count();

        return view('designer.appointments.dashboard', compact(
            'todayAppointments',
            'upcomingAppointments',
            'pendingAppointments',
            'totalAppointments',
            'completedAppointments',
            'pendingCount',
            'cancelledCount'
        ));
    }

    /**
     * Show designer's appointments list
     */
    public function designerAppointments(Request $request)
    {
        // Get the authenticated designer (the middleware should have already verified this)
        $authenticatedDesigner = Auth::guard('designer')->user();

        // If no designer is authenticated, abort
        if (!$authenticatedDesigner) {
            abort(403, 'Designer authentication required');
        }

        // Use the authenticated designer's ID instead of the route parameter
        $designerId = $authenticatedDesigner->id;

        $appointments = Appointment::where('designer_id', $designerId)
            ->with([
                'user:id,full_name,email,phone',
                'order.items.product',
                'order.items.product.options.values',
                'order.items.designs.design'
            ])
            ->when($request->date, function ($query, $date) {
                return $query->where('appointment_date', $date);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate($request->per_page ?? 15);

        // If it's an API request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        }

        // For web requests, render the view
        return view('designer.appointments.index', compact('appointments'));
    }

    /**
     * Show designer's upcoming appointments
     */
    public function designerUpcoming(Request $request)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer) {
            abort(403, 'Designer access required');
        }

        // Get current date/time for comparison
        $now = Carbon::now();
        $today = Carbon::today();

        // Get upcoming appointments (future dates or today with future times)
        $upcomingAppointments = Appointment::with([
            'user:id,full_name,email,phone',
            'order.items.product',
            'order.items.product.options.values',
            'order.items.designs.design'
        ])
            ->where('designer_id', $designer->id)
            ->where(function ($query) use ($today, $now) {
                // Future dates
                $query->where('appointment_date', '>', $today)
                    // Or today with future times
                    ->orWhere(function ($subQuery) use ($today, $now) {
                        $subQuery->where('appointment_date', '=', $today)
                                ->where('appointment_time', '>=', $now->format('H:i:s'));
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->when($request->filter, function ($query, $filter) use ($now) {
                switch ($filter) {
                    case 'tomorrow':
                        return $query->where('appointment_date', Carbon::tomorrow());
                    case 'this_week':
                        return $query->whereBetween('appointment_date', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
                    case 'next_week':
                        $nextWeekStart = $now->copy()->addWeek()->startOfWeek();
                        $nextWeekEnd = $now->copy()->addWeek()->endOfWeek();
                        return $query->whereBetween('appointment_date', [$nextWeekStart->toDateString(), $nextWeekEnd->toDateString()]);
                    case 'this_month':
                        return $query->whereMonth('appointment_date', $now->month)
                                    ->whereYear('appointment_date', $now->year);
                    default:
                        return $query;
                }
            })
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate($request->per_page ?? 15);

        // Add debug information for development
        $debug = [];
        if (config('app.debug')) {
            $debug = [
                'designer_id' => $designer->id,
                'total_appointments' => Appointment::where('designer_id', $designer->id)->count(),
                'total_future_appointments' => Appointment::where('designer_id', $designer->id)
                    ->where('appointment_date', '>=', $today)
                    ->count(),
                'current_time' => $now->toDateTimeString(),
                'current_date' => $today->toDateString(),
                'query_results_count' => $upcomingAppointments->total()
            ];
        }

        // If it's an API request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $upcomingAppointments,
                'debug' => $debug
            ]);
        }

        // For web requests, render the view
        return view('designer.appointments.upcoming', compact('upcomingAppointments', 'debug'));
    }

    /**
     * Show designer's appointment details
     */
    public function designerShow(Appointment $appointment)
    {
        // Get the authenticated designer
        $authenticatedDesigner = Auth::guard('designer')->user();

        // If no designer is authenticated, abort
        if (!$authenticatedDesigner) {
            abort(403, 'Designer authentication required');
        }

        // Check if the appointment belongs to this designer
        if ($appointment->designer_id !== $authenticatedDesigner->id) {
            abort(403, 'You can only view your own appointments');
        }

        // Load the appointment with all necessary relationships
        $appointment->load([
            'user:id,full_name,email,phone',
            'order.items.product',
            'order.items.product.options.values',
            'order.items.designs.design'
        ]);

        // Ensure options are properly cast as arrays for each item
        if ($appointment->order && $appointment->order->items) {
            foreach ($appointment->order->items as $item) {
                if (is_string($item->options)) {
                    $item->options = json_decode($item->options, true) ?? [];
                }
            }
        }

        // Get available orders for this user that are not linked to any appointment
        $availableOrders = Order::where('user_id', $appointment->user_id)
            ->whereDoesntHave('appointment')
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('designer.appointments.show', compact('appointment', 'availableOrders'));
    }

    /**
     * Link an order to an appointment
     */
    public function linkOrder(Request $request, Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_notes' => 'nullable|string|max:500'
        ]);

        // Check if the order belongs to the same user as the appointment
        $order = Order::findOrFail($request->order_id);
        if ($order->user_id !== $appointment->user_id) {
            return back()->withErrors(['order_id' => 'Order does not belong to this appointment\'s customer.']);
        }

        // Check if the order is already linked to another appointment
        if ($order->appointment) {
            return back()->withErrors(['order_id' => 'This order is already linked to another appointment.']);
        }

        // Link the order to the appointment
        $appointment->update([
            'order_id' => $request->order_id,
            'order_notes' => $request->order_notes
        ]);

        return back()->with('success', 'Order linked to appointment successfully.');
    }

    /**
     * Unlink an order from an appointment
     */
    public function unlinkOrder(Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403, 'Unauthorized');
        }

        $appointment->update([
            'order_id' => null,
            'order_notes' => null
        ]);

        return back()->with('success', 'Order unlinked from appointment successfully.');
    }

    /**
     * Recalculate order totals
     */
    public function recalculateOrder(Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403, 'Unauthorized');
        }

        if (!$appointment->order) {
            return response()->json([
                'success' => false,
                'message' => 'No order found for this appointment.'
            ], 404);
        }

        try {
            $appointment->order->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Order recalculated successfully.',
                'data' => [
                    'subtotal' => $appointment->order->subtotal,
                    'tax' => $appointment->order->tax,
                    'total' => $appointment->order->total
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to recalculate order.'
            ], 500);
        }
    }

    /**
     * Show designer's order editing page
     */
    public function designerEditOrder(Appointment $appointment)
    {
        // Get the authenticated designer
        $authenticatedDesigner = Auth::guard('designer')->user();

        // If no designer is authenticated, abort
        if (!$authenticatedDesigner) {
            abort(403, 'Designer authentication required');
        }

        // Check if the appointment belongs to this designer
        if ($appointment->designer_id !== $authenticatedDesigner->id) {
            abort(403, 'You can only edit orders for your own appointments');
        }

        // Check if appointment has an order
        if (!$appointment->order) {
            abort(404, 'No order found for this appointment');
        }

        return view('designer.orders.edit', compact('appointment'));
    }

    /**
     * Show user's appointments
     */
    public function userAppointments(Request $request, User $user)
    {
        // Check if the authenticated user is accessing their own appointments
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser || $authenticatedUser->id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $appointments = $user->appointments()
            ->with([
                'designer:id,name,email,phone',
                'order.items.product'
            ])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate($request->per_page ?? 15);

        // If it's an API request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        }

        // For web requests, render the view
        return view('users.appointments.index', compact('appointments'));
    }

    /**
     * Accept an appointment
     */
    public function accept(Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status !== 'pending') {
            return back()->withErrors(['appointment' => 'Only pending appointments can be accepted.']);
        }

        $appointment->accept();

        return back()->with('success', 'Appointment accepted successfully.');
    }

    /**
     * Reject an appointment
     */
    public function reject(Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403, 'Unauthorized');
        }

        if ($appointment->status !== 'pending') {
            return back()->withErrors(['appointment' => 'Only pending appointments can be rejected.']);
        }

        $appointment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Appointment rejected successfully.');
    }

    /**
     * Complete an appointment
     */
    public function complete(Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($appointment->status, ['accepted', 'confirmed'])) {
            return back()->withErrors(['appointment' => 'Only accepted or confirmed appointments can be completed.']);
        }

        $appointment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Appointment marked as completed.');
    }

    /**
     * Get available time slots for a specific date
     * GET /api/appointments/available-slots?date=2024-01-15
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $date = $request->date ?? Carbon::today()->format('Y-m-d');

        try {
            // Get available time slots excluding booked appointments
            $availableSlots = \App\Models\AvailabilitySlot::getAvailableTimeSlotsExcludingBooked($date);

            // Get day of week for additional info
            $dayOfWeek = Carbon::parse($date)->format('l');

            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'day_of_week' => $dayOfWeek,
                    'available_slots' => array_values($availableSlots), // Re-index array
                    'total_slots' => count($availableSlots),
                    'slot_duration' => 15, // Default 15 minutes
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available slots',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
