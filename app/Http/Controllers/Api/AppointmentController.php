<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="Velorena API",
 *     version="1.0.0",
 *     description="API for Velorena Backend - Orders and Appointments Management",
 *     @OA\Contact(
 *         email="admin@velorena.com"
 *     )
 * )
 */

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

/**
 * @OA\Tag(
 *     name="Appointments",
 *     description="API Endpoints for appointment management"
 * )
 */
class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService
    ) {}

    /**
     * Get user's appointments
     * 
     * @OA\Get(
     *     path="/api/appointments",
     *     summary="Get user's appointments",
     *     description="Retrieve all appointments for the authenticated user with filtering, sorting, and pagination",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by appointment status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "accepted", "rejected", "completed", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="designer_id",
     *         in="query",
     *         description="Filter by designer ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter from date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter to date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in service type, description, or notes",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Sort field",
     *         required=false,
     *         @OA\Schema(type="string", default="appointment_date", enum={"appointment_date", "created_at", "status"})
     *     ),
     *     @OA\Parameter(
     *         name="sort_order",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", default="asc", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15, minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointments retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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
     * Get specific appointment details
     * 
     * @OA\Get(
     *     path="/api/appointments/{appointment}",
     *     summary="Get appointment details",
     *     description="Retrieve detailed information about a specific appointment",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         description="Appointment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Appointment retrieved successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to appointment"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Appointment not found"
     *     )
     * )
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
     * 
     * @OA\Post(
     *     path="/api/appointments",
     *     summary="Create new appointment",
     *     description="Create a new appointment with optional designer assignment",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"appointment_date", "appointment_time", "service_type"},
     *             @OA\Property(property="designer_id", type="integer", example=1, description="Designer ID (optional)"),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2025-01-20", description="Appointment date (must be future)"),
     *             @OA\Property(property="appointment_time", type="string", format="time", example="14:00", description="Appointment time (HH:MM)"),
     *             @OA\Property(property="service_type", type="string", example="Interior Design Consultation", maxLength=100),
     *             @OA\Property(property="description", type="string", example="Need help with living room design", maxLength=500),
     *             @OA\Property(property="duration", type="integer", example=60, minimum=30, maximum=480, description="Duration in minutes"),
     *             @OA\Property(property="location", type="string", example="123 Main St, City", maxLength=200),
     *             @OA\Property(property="notes", type="string", example="Please bring color samples", maxLength=500),
     *             @OA\Property(property="order_id", type="integer", example=5, description="Link to specific order (optional)"),
     *             @OA\Property(property="order_notes", type="string", example="Related to order #123", maxLength=500)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Appointment created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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
     * 
     * @OA\Put(
     *     path="/api/appointments/{appointment}",
     *     summary="Update appointment",
     *     description="Update an existing appointment (only if status is pending or confirmed)",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         description="Appointment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="designer_id", type="integer", example=1, description="Designer ID (optional)"),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2025-01-20"),
     *             @OA\Property(property="appointment_time", type="string", format="time", example="14:00"),
     *             @OA\Property(property="service_type", type="string", example="Interior Design Consultation", maxLength=100),
     *             @OA\Property(property="description", type="string", example="Updated description", maxLength=500),
     *             @OA\Property(property="duration", type="integer", example=90, minimum=30, maximum=480),
     *             @OA\Property(property="location", type="string", example="456 Oak St, City", maxLength=200),
     *             @OA\Property(property="notes", type="string", example="Updated notes", maxLength=500),
     *             @OA\Property(property="order_id", type="integer", example=5),
     *             @OA\Property(property="order_notes", type="string", example="Updated order notes", maxLength=500)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Appointment updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or cannot modify appointment"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to appointment"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Appointment not found"
     *     )
     * )
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
     * Cancel an appointment
     * 
     * @OA\Delete(
     *     path="/api/appointments/{appointment}",
     *     summary="Cancel appointment",
     *     description="Cancel a specific appointment (only if status is pending or confirmed)",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         description="Appointment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment cancelled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Appointment cancelled successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot cancel appointment"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to appointment"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Appointment not found"
     *     )
     * )
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

            // Cancel the appointment instead of deleting
            $this->appointmentService->cancelAppointment($appointment);

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }










}
