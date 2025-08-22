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
        return view('appointments.create', compact('designers'));
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
            return back()->withErrors(['appointment_time' => 'This time slot is not available. Please choose another time.']);
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
            ->with('success', 'Appointment booked successfully! The designer will review your request.');
    }

    /**
     * Show user's appointments
     */
    public function index()
    {
        $appointments = Appointment::with(['designer'])
            ->where('user_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
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

        return view('appointments.show', compact('appointment'));
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
            return back()->withErrors(['appointment' => 'This appointment cannot be cancelled.']);
        }

        $appointment->cancel();

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Get available time slots for a designer on a specific date
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'designer_id' => 'required|exists:designers,id',
            'date' => 'required|date|after:today',
        ]);

        $slots = Appointment::getAvailableTimeSlots(
            $request->designer_id,
            $request->date
        );

        return response()->json(['slots' => $slots]);
    }

    /**
     * Designer dashboard - show appointments for the logged-in designer
     */
    public function designerDashboard()
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer) {
            abort(403);
        }

        $pendingAppointments = Appointment::with(['user'])
            ->where('designer_id', $designer->id)
            ->pending()
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $todayAppointments = Appointment::with(['user'])
            ->where('designer_id', $designer->id)
            ->today()
            ->whereIn('status', ['accepted', 'pending'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        $upcomingAppointments = Appointment::with(['user'])
            ->where('designer_id', $designer->id)
            ->upcoming()
            ->whereIn('status', ['accepted'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->limit(10)
            ->get();

        return view('designer.appointments.dashboard', compact(
            'pendingAppointments',
            'todayAppointments',
            'upcomingAppointments'
        ));
    }

    /**
     * Accept an appointment (designer action)
     */
    public function accept(Request $request, Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403);
        }

        if (!$appointment->canBeAccepted()) {
            return back()->withErrors(['appointment' => 'This appointment cannot be accepted.']);
        }

        $appointment->accept($request->designer_notes);

        return back()->with('success', 'Appointment accepted successfully.');
    }

    /**
     * Reject an appointment (designer action)
     */
    public function reject(Request $request, Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403);
        }

        if (!$appointment->canBeRejected()) {
            return back()->withErrors(['appointment' => 'This appointment cannot be rejected.']);
        }

        $appointment->reject($request->designer_notes);

        return back()->with('success', 'Appointment rejected successfully.');
    }

    /**
     * Complete an appointment (designer action)
     */
    public function complete(Appointment $appointment)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer || $appointment->designer_id !== $designer->id) {
            abort(403);
        }

        if (!$appointment->canBeCompleted()) {
            return back()->withErrors(['appointment' => 'This appointment cannot be completed.']);
        }

        $appointment->complete();

        return back()->with('success', 'Appointment marked as completed.');
    }

    /**
     * Show all appointments for a designer (with filtering)
     */
    public function designerAppointments(Request $request)
    {
        $designer = Auth::guard('designer')->user();

        if (!$designer) {
            abort(403);
        }

        $query = Appointment::with(['user'])
            ->where('designer_id', $designer->id);

        // Apply filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->where('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('designer.appointments.index', compact('appointments'));
    }
}
