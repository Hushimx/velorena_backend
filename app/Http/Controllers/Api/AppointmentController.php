<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="Qaads API",
 *     version="2.0.0",
 *     description="Complete API for Qaads Backend - E-commerce, Orders, Appointments, and Availability Management System",
 *     @OA\Contact(
 *         email="admin@Qaads.com",
 *         name="Qaads Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="https://api.Qaads.com",
 *     description="Production Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum Authentication"
 * )
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAppointmentRequest;
use App\Http\Requests\Api\UpdateAppointmentRequest;
use App\Http\Resources\Api\AppointmentResource;
use App\Http\Resources\Api\AppointmentCollection;
use App\Services\AppointmentService;
use App\Services\OrderService;
use App\Models\Appointment;
use App\Models\CartItem;
use App\Models\CartDesign;
use App\Models\OrderDesign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Appointments",
 *     description="Complete appointment management system - Create, read, update, and manage appointments with real-time availability checking"
 * )
 * 
 * @OA\Tag(
 *     name="Availability",
 *     description="Real-time availability checking - Get available time slots for booking appointments"
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
     *     description="Create a new appointment with optional designer assignment. The appointment will be created with 'pending' status and can be claimed by designers later. Use the available-slots endpoint to check for available time slots before booking.",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"appointment_date", "appointment_time", "service_type"},
     *             @OA\Property(property="designer_id", type="integer", example=1, description="Designer ID (optional - can be assigned later)"),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2025-09-08", description="Appointment date (must be today or future)"),
     *             @OA\Property(property="appointment_time", type="string", format="time", example="10:00", description="Appointment time (HH:MM) - must be from available slots"),
     *             @OA\Property(property="service_type", type="string", example="Interior Design Consultation", maxLength=100, description="Type of service requested"),
     *             @OA\Property(property="description", type="string", example="Need help with living room design", maxLength=500, description="Detailed description of the service needed"),
     *             @OA\Property(property="duration", type="integer", example=30, minimum=30, maximum=480, description="Duration in minutes (default: 30)"),
     *             @OA\Property(property="location", type="string", example="123 Main St, City", maxLength=200, description="Appointment location (optional)"),
     *             @OA\Property(property="notes", type="string", example="Please bring color samples", maxLength=500, description="Additional notes for the appointment"),
     *             @OA\Property(property="order_id", type="integer", example=5, description="Link to specific order (optional)"),
     *             @OA\Property(property="order_notes", type="string", example="Related to order #123", maxLength=500, description="Notes related to the linked order")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Appointment created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=11),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="designer_id", type="integer", example=null),
     *                 @OA\Property(property="appointment_date", type="string", format="date", example="2025-09-08"),
     *                 @OA\Property(property="appointment_time", type="string", format="time", example="10:00"),
     *                 @OA\Property(property="service_type", type="string", example="Interior Design Consultation"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error - Invalid input data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated - Bearer token required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to create appointment"),
     *             @OA\Property(property="error", type="string", example="Database error details")
     *         )
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











    /**
     * Get available time slots for a specific date
     * 
     * @OA\Get(
     *     path="/api/appointments/available-slots",
     *     summary="Get available time slots",
     *     description="Retrieve available time slots for today or a specific date. This endpoint shows real-time availability by excluding already booked appointments. Returns 30-minute slots from 8 AM to 4 PM for all 7 days of the week (Monday-Sunday).",
     *     tags={"Appointments", "Availability"},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date for available slots (optional, defaults to today). Format: YYYY-MM-DD. Must be today or in the future.",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-09-08")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Available time slots retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="date", type="string", format="date", example="2025-09-08"),
     *                 @OA\Property(property="day_of_week", type="string", example="Monday"),
     *                 @OA\Property(
     *                     property="available_slots",
     *                     type="array",
     *                     @OA\Items(type="string", example="08:00"),
     *                     example={"08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30"},
     *                     description="Array of available time slots in HH:MM format"
     *                 ),
     *                 @OA\Property(property="total_slots", type="integer", example=16, description="Total number of available slots"),
     *                 @OA\Property(property="slot_duration", type="integer", example=30, description="Duration of each slot in minutes")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed - Invalid date format or past date",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="date",
     *                     type="array",
     *                     @OA\Items(type="string", example="The date must be a date after or equal to today.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to get available slots"),
     *             @OA\Property(property="error", type="string", example="Database connection error")
     *         )
     *     )
     * )
     */
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            "date" => "nullable|date|after_or_equal:today",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $date = $request->date ?? \Carbon\Carbon::today()->format("Y-m-d");

        try {
            // Get available time slots excluding booked appointments
            $availableSlots = \App\Models\AvailabilitySlot::getAvailableTimeSlotsExcludingBooked($date);
            
            // Filter out past time slots if the date is today
            $today = \Carbon\Carbon::today()->format('Y-m-d');
            if ($date === $today) {
                $now = \Carbon\Carbon::now();
                $bufferTime = $now->addMinutes(30); // Add 30-minute buffer
                $bufferTimeString = $bufferTime->format('H:i');
                
                \Log::info('Filtering past time slots for today', [
                    'date' => $date,
                    'current_time' => $now->format('H:i'),
                    'buffer_time' => $bufferTimeString,
                    'original_slots_count' => count($availableSlots)
                ]);
                
                // Filter out slots that are in the past (with 30-minute buffer)
                $availableSlots = array_filter($availableSlots, function($slot) use ($bufferTimeString) {
                    $isAfterBuffer = $slot >= $bufferTimeString;
                    
                    \Log::info('Slot filtering', [
                        'slot' => $slot,
                        'buffer_time' => $bufferTimeString,
                        'is_after_buffer' => $isAfterBuffer
                    ]);
                    
                    return $isAfterBuffer;
                });
                
                \Log::info('Filtered slots result', [
                    'filtered_slots_count' => count($availableSlots),
                    'filtered_slots' => array_values($availableSlots)
                ]);
            }
            
            // Get day of week for additional info
            $dayOfWeek = \Carbon\Carbon::parse($date)->format("l");
            
            // Get the actual slot duration from the availability configuration
            $slotDuration = 30; // Default
            $availabilityConfig = \App\Models\AvailabilitySlot::where('day_of_week', strtolower($dayOfWeek))
                                                            ->where('is_active', true)
                                                            ->first();
            if ($availabilityConfig) {
                $slotDuration = $availabilityConfig->slot_duration_minutes;
            }
            
            return response()->json([
                "success" => true,
                "data" => [
                    "date" => $date,
                    "day_of_week" => $dayOfWeek,
                    "available_slots" => array_values($availableSlots), // Re-index array
                    "total_slots" => count($availableSlots),
                    "slot_duration" => $slotDuration,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to get available slots",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create appointment with order from cart
     * 
     * @OA\Post(
     *     path="/api/appointments/create-from-cart",
     *     summary="Create appointment with order from cart",
     *     description="Create a new appointment and order from cart items with 'waiting_for_appointment' status. This endpoint creates an order from cart items and links it to the appointment.",
     *     tags={"Appointments", "Cart"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"appointment_date", "appointment_time", "service_type"},
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2025-09-08", description="Appointment date (must be today or future)"),
     *             @OA\Property(property="appointment_time", type="string", format="time", example="10:00", description="Appointment time (HH:MM) - must be from available slots"),
     *             @OA\Property(property="service_type", type="string", example="Interior Design Consultation", maxLength=100, description="Type of service requested"),
     *             @OA\Property(property="description", type="string", example="Need help with living room design", maxLength=500, description="Detailed description of the service needed"),
     *             @OA\Property(property="duration", type="integer", example=60, minimum=30, maximum=480, description="Duration in minutes (default: 60)"),
     *             @OA\Property(property="location", type="string", example="123 Main St, City", maxLength=200, description="Appointment location (optional)"),
     *             @OA\Property(property="notes", type="string", example="Please bring color samples", maxLength=500, description="Additional notes for the appointment"),
     *             @OA\Property(property="order_notes", type="string", example="Order created from cart for appointment", maxLength=500, description="Notes related to the order")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment and order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Appointment and order created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="appointment", type="object"),
     *                 @OA\Property(property="order", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or cart is empty",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated - Bearer token required"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function createFromCart(Request $request): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'service_type' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:30|max:480',
            'location' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
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
            $user = Auth::user();
            
            // Check if user has items in cart
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
            $cartDesigns = CartDesign::where('user_id', $user->id)->where('is_active', true)->get();
            
            if ($cartItems->isEmpty() && $cartDesigns->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty. Please add items to cart before booking an appointment.'
                ], 422);
            }

            return DB::transaction(function () use ($request, $user, $cartItems, $cartDesigns) {
                // Create order from cart items
                $orderService = new OrderService();
                
                // Prepare order data
                $orderData = [
                    'phone' => $user->phone ?? '',
                    'shipping_address' => $user->address ?? '',
                    'billing_address' => $user->address ?? '',
                    'notes' => $request->order_notes ?? 'Order created from cart for appointment booking',
                    'items' => []
                ];

                // Add cart items to order
                foreach ($cartItems as $cartItem) {
                    $orderData['items'][] = [
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'unit_price' => $cartItem->unit_price,
                        'total_price' => $cartItem->total_price,
                        'options' => $cartItem->selected_options,
                        'notes' => $cartItem->notes
                    ];
                }

                // Create order
                $order = $orderService->createOrder($orderData);

                // Set order status to waiting_for_appointment
                $order->update(['status' => 'waiting_for_appointment']);

                // Copy cart designs to order
                foreach ($cartDesigns as $design) {
                    OrderDesign::create([
                        'order_id' => $order->id,
                        'title' => $design->title ?? 'Design',
                        'image_url' => $design->image_url ?? '',
                        'thumbnail_url' => $design->thumbnail_url,
                        'design_data' => $design->design_data,
                        'priority' => 1
                    ]);
                }

                // Clear cart items and designs
                CartItem::where('user_id', $user->id)->delete();
                CartDesign::where('user_id', $user->id)->delete();

                // Create appointment data
                $appointmentData = [
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'service_type' => $request->service_type,
                    'description' => $request->description,
                    'duration' => $request->duration ?? 60,
                    'location' => $request->location,
                    'notes' => $request->notes,
                    'order_id' => $order->id,
                    'order_notes' => $request->order_notes ?? "موعد مرتبط بالطلب #{$order->id}"
                ];

                // Create appointment
                $appointment = $this->appointmentService->createAppointment($appointmentData);

                return response()->json([
                    'success' => true,
                    'message' => 'Appointment and order created successfully',
                    'data' => [
                        'appointment' => new AppointmentResource($appointment),
                        'order' => [
                            'id' => $order->id,
                            'order_number' => $order->order_number,
                            'status' => $order->status,
                            'total' => $order->total,
                            'created_at' => $order->created_at
                        ]
                    ]
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment and order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
