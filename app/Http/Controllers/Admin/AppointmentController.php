<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'designer', 'order'])
            ->latest()
            ->paginate(20);

        return view('admin.dashboard.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        $designers = \App\Models\Designer::where('is_active', true)->get();
        $orders = \App\Models\Order::latest()->get();

        return view('admin.dashboard.appointments.create', compact('users', 'designers', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'designer_id' => 'nullable|exists:designers,id',
            'order_id' => 'nullable|exists:orders,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'notes' => 'nullable|string|max:1000',
            'order_notes' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'designer_id' => $request->designer_id,
            'order_id' => $request->order_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'duration_minutes' => $request->duration_minutes,
            'notes' => $request->notes,
            'order_notes' => $request->order_notes,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', __('admin.appointment_created_success'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'designer', 'order', 'order.items.product', 'order.designs']);
        return view('admin.dashboard.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $appointment->load(['user', 'designer', 'order']);
        return view('admin.dashboard.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,completed,cancelled',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
            'designer_notes' => 'nullable|string',
        ]);

        $data = $request->only(['appointment_date', 'appointment_time', 'notes', 'designer_notes']);

        // Handle status transitions with proper business logic
        $currentStatus = $appointment->status;
        $newStatus = $request->status;

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
            $data['status'] = $newStatus;
            $appointment->update($data);
            return redirect()->route('admin.appointments.show', $appointment)
                ->with('success', __('admin.appointment_updated_success'));
        }

        // Update other fields
        if (!empty($data)) {
            $appointment->update($data);
        }

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', __('admin.appointment_updated_success'));
    }

    public function destroy(Appointment $appointment)
    {
        // Only allow deletion of pending appointments
        if ($appointment->status !== 'pending') {
            return redirect()->back()
                ->with('error', __('admin.cannot_delete_non_pending_appointment'));
        }

        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', __('admin.appointment_deleted_success'));
    }
}
