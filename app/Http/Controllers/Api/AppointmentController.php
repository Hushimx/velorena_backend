<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAppointmentRequest;
use App\Http\Requests\Api\UpdateAppointmentRequest;
use App\Http\Resources\Api\AppointmentResource;
use App\Http\Resources\Api\AppointmentCollection;
use App\Services\AppointmentService;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService
    ) {}

    /**
     * Get user's appointments
     */
    public function index(Request $request): AppointmentCollection
    {
        $user = Auth::user();

        $query = Appointment::where('user_id', $user->id)
            ->with(['designer', 'order']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('designer_id') && $request->designer_id) {
            $query->where('designer_id', $request->designer_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('service_type', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('notes', 'like', '%' . $request->search . '%');
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'appointment_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $appointments = $query->paginate($perPage);

        return new AppointmentCollection($appointments);
    }

    /**
     * Get designer's appointments
     */
    public function designerAppointments(Request $request): AppointmentCollection
    {
        $user = Auth::user();

        $query = Appointment::where('designer_id', $user->id)
            ->with(['user', 'order']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'appointment_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $appointments = $query->paginate($perPage);

        return new AppointmentCollection($appointments);
    }

    /**
     * Get unassigned appointments (for designers to claim)
     */
    public function unassignedAppointments(Request $request): AppointmentCollection
    {
        $query = Appointment::whereNull('designer_id')
            ->where('status', 'pending')
            ->with(['user', 'order']);

        // Apply filters
        if ($request->has('service_type') && $request->service_type) {
            $query->where('service_type', 'like', '%' . $request->service_type . '%');
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'appointment_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $appointments = $query->paginate($perPage);

        return new AppointmentCollection($appointments);
    }

    /**
     * Claim an unassigned appointment (for designers)
     */
    public function claimAppointment(Appointment $appointment): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user is a designer
            if (!$user->designer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only designers can claim appointments'
                ], 403);
            }

            // Check if appointment is unassigned
            if ($appointment->designer_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment is already assigned to a designer'
                ], 400);
            }

            // Check if designer is available at this time
            $this->appointmentService->checkDesignerAvailability(
                $user->designer->id,
                $appointment->appointment_date,
                $appointment->appointment_time
            );

            // Claim the appointment
            $appointment->update([
                'designer_id' => $user->designer->id,
                'status' => 'confirmed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment claimed successfully',
                'data' => new AppointmentResource($appointment->load(['designer', 'user', 'order']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific appointment details
     */
    public function show(Appointment $appointment): JsonResponse
    {
        // Check if user can access this appointment
        if (!$this->appointmentService->canAccessAppointment($appointment)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to appointment'
            ], 403);
        }

        $appointment->load(['designer', 'user', 'order']);

        return response()->json([
            'success' => true,
            'message' => 'Appointment retrieved successfully',
            'data' => new AppointmentResource($appointment)
        ]);
    }

    /**
     * Create a new appointment
     */
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->createAppointment($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'data' => new AppointmentResource($appointment)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an appointment
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        try {
            // Check if user can access this appointment
            if (!$this->appointmentService->canAccessAppointment($appointment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to appointment'
                ], 403);
            }

            $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully',
                'data' => new AppointmentResource($updatedAppointment)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an appointment
     */
    public function destroy(Appointment $appointment): JsonResponse
    {
        try {
            // Check if user can access this appointment
            if (!$this->appointmentService->canAccessAppointment($appointment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to appointment'
                ], 403);
            }

            $this->appointmentService->deleteAppointment($appointment);

            return response()->json([
                'success' => true,
                'message' => 'Appointment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an appointment
     */
    public function cancel(Request $request, Appointment $appointment): JsonResponse
    {
        try {
            // Check if user can access this appointment
            if (!$this->appointmentService->canAccessAppointment($appointment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to appointment'
                ], 403);
            }

            $reason = $request->input('reason');
            $updatedAppointment = $this->appointmentService->cancelAppointment($appointment, $reason);

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully',
                'data' => new AppointmentResource($updatedAppointment)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm an appointment
     */
    public function confirm(Appointment $appointment): JsonResponse
    {
        try {
            // Check if user can access this appointment
            if (!$this->appointmentService->canAccessAppointment($appointment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to appointment'
                ], 403);
            }

            $updatedAppointment = $this->appointmentService->confirmAppointment($appointment);

            return response()->json([
                'success' => true,
                'message' => 'Appointment confirmed successfully',
                'data' => new AppointmentResource($updatedAppointment)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete an appointment
     */
    public function complete(Appointment $appointment): JsonResponse
    {
        try {
            // Check if user can access this appointment
            if (!$this->appointmentService->canAccessAppointment($appointment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to appointment'
                ], 403);
            }

            $updatedAppointment = $this->appointmentService->completeAppointment($appointment);

            return response()->json([
                'success' => true,
                'message' => 'Appointment completed successfully',
                'data' => new AppointmentResource($updatedAppointment)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available time slots for a designer
     */
    public function availableTimeSlots(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'designer_id' => 'required|integer|exists:designers,id',
                'date' => 'required|date|after:today'
            ]);

            $timeSlots = $this->appointmentService->getAvailableTimeSlots(
                $request->designer_id,
                $request->date
            );

            return response()->json([
                'success' => true,
                'message' => 'Available time slots retrieved successfully',
                'data' => [
                    'designer_id' => $request->designer_id,
                    'date' => $request->date,
                    'available_slots' => $timeSlots
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available time slots',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming appointments
     */
    public function upcoming(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $limit = $request->get('limit', 10);

            $appointments = $this->appointmentService->getUpcomingAppointments($user->id, $limit);

            return response()->json([
                'success' => true,
                'message' => 'Upcoming appointments retrieved successfully',
                'data' => AppointmentResource::collection($appointments)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get upcoming appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
