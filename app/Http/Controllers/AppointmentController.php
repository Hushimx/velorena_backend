<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Designer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Store a new appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'designer_id' => 'required|exists:designers,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $designerId = $request->designer_id;
        $date = $request->appointment_date;
        $time = $request->appointment_time;

        // Check if time slot is available
        if (!Appointment::isTimeSlotAvailable($designerId, $date, $time)) {
            return back()->withErrors(['appointment_time' => trans('dashboard.time_slot_unavailable', ['default' => 'This time slot is not available. Please choose another time.'])]);
        }

        // Create the appointment
        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'designer_id' => $designerId,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'duration_minutes' => 15,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('appointments.index')
            ->with('success', trans('dashboard.appointment_booked_success', ['default' => 'Appointment booked successfully! The designer will review your request.']));
    }

    /**
     * Show user's appointments
     */
    public function index()
    {
        $appointments = Appointment::with(['designer'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.appointments.index', compact('appointments'));
    }

    /**
     * Show appointment details
     */
    public function show(Appointment $appointment)
    {
        // Check if user owns this appointment or is the designer
        if ($appointment->user_id !== Auth::id() && $appointment->designer_id !== Auth::user()->designer?->id) {
            abort(403);
        }

        // Load the designer relationship to avoid N+1 queries
        $appointment->load('designer', 'user');

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
    public function designerAppointments(Request $request)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer) {
            abort(403, 'Designer access required');
        }

        $status = $request->get('status', '');
        $date = $request->get('date', '');

        $appointments = Appointment::with('user')
            ->where('designer_id', $designer->id)
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($date, function ($query, $date) {
                return $query->where('appointment_date', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('designer.appointments.index', compact('appointments', 'status', 'date'));
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
}
