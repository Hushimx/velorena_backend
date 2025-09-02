<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API route working', 'timestamp' => now()]);
});

// Swagger documentation routes are now handled by RouteServiceProvider

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // OTP routes
    Route::post('/send-otp', [OtpController::class, 'sendOtp']);
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
    Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
});

// Public product and category routes
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Document routes
    Route::post('/documents/upload', [DocumentController::class, 'uploadDocument']);
    Route::delete('/documents/delete', [DocumentController::class, 'deleteDocument']);
    Route::get('/documents/info', [DocumentController::class, 'getDocumentInfo']);

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::delete('/{order}', [OrderController::class, 'destroy']);
        Route::post('/{order}/items', [OrderController::class, 'addItem']);
        Route::delete('/{order}/items', [OrderController::class, 'removeItem']);
    });

    // ========================================
    // APPOINTMENT ROUTES
    // ========================================
    // All appointment endpoints require authentication
    Route::prefix('appointments')->group(function () {

        // ========================================
        // USER APPOINTMENT MANAGEMENT
        // ========================================

        // GET /api/appointments
        // Get all appointments for the authenticated user with filtering and pagination
        // Query params: status, designer_id, date_from, date_to, search, sort_by, sort_order, per_page
        Route::get('/', [AppointmentController::class, 'index']);

        // POST /api/appointments
        // Create a new appointment (designer_id is optional - can be claimed later)
        // Required: appointment_date, appointment_time, service_type
        // Optional: designer_id, description, duration, location, notes, order_id, order_notes
        Route::post('/', [AppointmentController::class, 'store']);

        // GET /api/appointments/upcoming
        // Get upcoming appointments for the authenticated user (future dates, pending/confirmed status)
        // Query params: limit (default: 10)
        Route::get('/upcoming', [AppointmentController::class, 'upcoming']);

        // GET /api/appointments/available-slots
        // Get available time slots for a specific designer on a specific date
        // Query params: designer_id (required), date (required)
        Route::get('/available-slots', [AppointmentController::class, 'availableTimeSlots']);

        // ========================================
        // DESIGNER APPOINTMENT MANAGEMENT
        // ========================================

        // GET /api/appointments/unassigned
        // Get all unassigned appointments (for designers to browse and claim)
        // Query params: service_type, date_from, date_to, sort_by, sort_order, per_page
        Route::get('/unassigned', [AppointmentController::class, 'unassignedAppointments']);

        // POST /api/appointments/{appointment}/claim
        // Claim an unassigned appointment (designers only)
        // Requires: authenticated user must be a designer
        // Updates: designer_id, status (to 'confirmed')
        Route::post('/{appointment}/claim', [AppointmentController::class, 'claimAppointment']);

        // ========================================
        // INDIVIDUAL APPOINTMENT OPERATIONS
        // ========================================

        // GET /api/appointments/{appointment}
        // Get details of a specific appointment
        // Access: user who created the appointment or assigned designer
        Route::get('/{appointment}', [AppointmentController::class, 'show']);

        // PUT /api/appointments/{appointment}
        // Update an existing appointment
        // Access: user who created the appointment or assigned designer
        // Status: only pending or confirmed appointments can be updated
        Route::put('/{appointment}', [AppointmentController::class, 'update']);

        // DELETE /api/appointments/{appointment}
        // Delete an appointment
        // Access: user who created the appointment or assigned designer
        // Status: only pending appointments can be deleted
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy']);

        // ========================================
        // APPOINTMENT STATUS MANAGEMENT
        // ========================================

        // POST /api/appointments/{appointment}/cancel
        // Cancel an appointment
        // Access: user who created the appointment or assigned designer
        // Status: pending or confirmed appointments can be cancelled
        Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel']);

        // POST /api/appointments/{appointment}/confirm
        // Confirm an appointment (change status to confirmed)
        // Access: user who created the appointment or assigned designer
        // Status: only pending appointments can be confirmed
        Route::post('/{appointment}/confirm', [AppointmentController::class, 'confirm']);

        // POST /api/appointments/{appointment}/complete
        // Mark an appointment as completed
        // Access: user who created the appointment or assigned designer
        // Status: only confirmed appointments can be completed
        Route::post('/{appointment}/complete', [AppointmentController::class, 'complete']);
    });

    // ========================================
    // DESIGNER-SPECIFIC APPOINTMENT ROUTES
    // ========================================
    // These routes are specifically for designers to manage their assigned appointments
    // Different from the designer endpoints in /appointments (which are for claiming unassigned appointments)
    Route::prefix('designer/appointments')->group(function () {

        // GET /api/designer/appointments
        // Get all appointments assigned to the authenticated designer
        // Query params: status, date_from, date_to, sort_by, sort_order, per_page
        // Access: Only authenticated designers
        // Purpose: Designer dashboard - see all appointments assigned to them
        Route::get('/', [AppointmentController::class, 'designerAppointments']);
    });
});
