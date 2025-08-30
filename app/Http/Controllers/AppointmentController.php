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
    public function create()
    {
        $designers = Designer::where('is_active', true)->get();
        return view('users.appointments.create', compact('designers'));
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
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'order_notes' => 'nullable|array',
            'order_notes.*' => 'nullable|string|max:500'
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
                'status' => $designerId ? 'pending' : 'pending'
            ]);

            // Link orders to appointment
            $orderIds = $request->order_ids;
            $orderNotes = $request->order_notes ?? [];

            $pivotData = [];
            foreach ($orderIds as $index => $orderId) {
                $pivotData[$orderId] = [
                    'notes' => $orderNotes[$index] ?? null
                ];
            }

            $appointment->orders()->attach($pivotData);

            // Load relationships for response
            $appointment->load(['user', 'designer', 'orders.items.product']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'data' => [
                    'appointment' => $appointment,
                    'linked_orders_count' => count($orderIds),
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
        $appointments = Appointment::with(['designer', 'orders.items.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.appointments.index', compact('appointments'));
    }

    /**
     * Get appointment details with linked orders and products
     */
    public function show(Appointment $appointment): JsonResponse
    {
        $appointment->load([
            'user:id,name,email,phone',
            'designer:id,name,email,phone',
            'orders.items.product',
            'orders.items.product.options.values'
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'appointment' => $appointment,
                'products_summary' => $appointment->getProductsSummary(),
                'total_products_count' => $appointment->getTotalProductsCount(),
                'total_order_value' => $appointment->getTotalOrderValue(),
                'linked_orders_count' => $appointment->orders->count()
            ]
        ]);
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
            'order_ids' => 'sometimes|array',
            'order_ids.*' => 'exists:orders,id',
            'order_notes' => 'nullable|array',
            'order_notes.*' => 'nullable|string|max:500'
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

            // Update appointment
            $appointment->update($request->only([
                'designer_id',
                'appointment_date',
                'appointment_time',
                'duration_minutes',
                'notes',
                'designer_notes',
                'status'
            ]));

            // Update linked orders if provided
            if ($request->has('order_ids')) {
                // Remove existing links
                $appointment->orders()->detach();

                // Add new links
                if (!empty($request->order_ids)) {
                    $orderIds = $request->order_ids;
                    $orderNotes = $request->order_notes ?? [];

                    $pivotData = [];
                    foreach ($orderIds as $index => $orderId) {
                        $pivotData[$orderId] = [
                            'notes' => $orderNotes[$index] ?? null
                        ];
                    }

                    $appointment->orders()->attach($pivotData);
                }
            }

            // Load relationships for response
            $appointment->load(['user', 'designer', 'orders.items.product']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully',
                'data' => [
                    'appointment' => $appointment,
                    'linked_orders_count' => $appointment->orders->count(),
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
     * Get available time slots for a specific date
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'designer_id' => 'required|exists:designers,id',
            'date' => 'required|date|after:today',
        ]);

        $designerId = $request->designer_id;
        $date = $request->date;
        $availableSlots = Appointment::getAvailableTimeSlots($designerId, $date);

        return response()->json(['slots' => $availableSlots]);
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
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
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
    public function designerAppointments(Request $request, Designer $designer): JsonResponse
    {
        $appointments = $designer->appointments()
            ->with([
                'user:id,name,email,phone',
                'orders.items.product',
                'orders.items.product.options.values'
            ])
            ->when($request->date, function ($query, $date) {
                return $query->where('appointment_date', $date);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Show user's appointments
     */
    public function userAppointments(Request $request, User $user): JsonResponse
    {
        $appointments = $user->appointments()
            ->with([
                'designer:id,name,email,phone',
                'orders.items.product'
            ])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
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

        $appointment->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

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
     * Link additional orders to an existing appointment
     */
    public function linkOrders(Request $request, Appointment $appointment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'order_notes' => 'nullable|array',
            'order_notes.*' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $orderIds = $request->order_ids;
            $orderNotes = $request->order_notes ?? [];

            $pivotData = [];
            foreach ($orderIds as $index => $orderId) {
                $pivotData[$orderId] = [
                    'notes' => $orderNotes[$index] ?? null
                ];
            }

            $appointment->orders()->attach($pivotData);

            $appointment->load(['orders.items.product']);

            return response()->json([
                'success' => true,
                'message' => 'Orders linked successfully',
                'data' => [
                    'appointment' => $appointment,
                    'linked_orders_count' => $appointment->orders->count(),
                    'total_products' => $appointment->getTotalProductsCount(),
                    'total_value' => $appointment->getTotalOrderValue()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to link orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unlink orders from an appointment
     */
    public function unlinkOrders(Request $request, Appointment $appointment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $appointment->orders()->detach($request->order_ids);

            $appointment->load(['orders.items.product']);

            return response()->json([
                'success' => true,
                'message' => 'Orders unlinked successfully',
                'data' => [
                    'appointment' => $appointment,
                    'linked_orders_count' => $appointment->orders->count(),
                    'total_products' => $appointment->getTotalProductsCount(),
                    'total_value' => $appointment->getTotalOrderValue()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unlink orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
