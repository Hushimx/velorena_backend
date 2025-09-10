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

    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'designer', 'order', 'order.items.product']);
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
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
            'designer_notes' => 'nullable|string',
        ]);

        $data = $request->only(['status', 'appointment_date', 'appointment_time', 'notes', 'designer_notes']);

        // Handle status transitions
        switch ($request->status) {
            case 'scheduled':
                if (!$appointment->accepted_at) {
                    $data['accepted_at'] = now();
                }
                break;
            case 'completed':
                if (!$appointment->completed_at) {
                    $data['completed_at'] = now();
                }
                break;
            case 'cancelled':
                if (!$appointment->cancelled_at) {
                    $data['cancelled_at'] = now();
                }
                break;
        }

        $appointment->update($data);

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
