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
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\CartController;

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
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/{product}', [ProductController::class, 'show']);
});

// ========================================
// DESIGN SYSTEM ROUTES (PUBLIC)
// ========================================
Route::prefix('designs')->group(function () {
    // GET /api/designs - List all designs with pagination and filtering
    Route::get('/', [DesignController::class, 'index']);

    // GET /api/designs/search - Search designs by query
    Route::get('/search', [DesignController::class, 'search']);

    // GET /api/designs/categories - Get available design categories
    Route::get('/categories', [DesignController::class, 'categories']);

    // GET /api/designs/{design} - Get specific design details
    Route::get('/{design}', [DesignController::class, 'show']);

    // POST /api/designs/sync - Sync designs from external API (admin only)
    Route::post('/sync', [DesignController::class, 'sync']);
});

// ========================================
// EXTERNAL DESIGN API ROUTES (PUBLIC - PROTECTED API KEY)
// ========================================
Route::prefix('external/designs')->group(function () {
    // GET /api/external/designs/search - Search designs from external API
    Route::get('/search', [DesignController::class, 'searchExternal']);

    // GET /api/external/designs/category - Get designs by category from external API
    Route::get('/category', [DesignController::class, 'getExternalByCategory']);

    // GET /api/external/designs/categories - Get available categories from external API
    Route::get('/categories', [DesignController::class, 'getExternalCategories']);

    // GET /api/external/designs/featured - Get featured designs from external API
    Route::get('/featured', [DesignController::class, 'getExternalFeatured']);
});

// ========================================
// APPOINTMENT AVAILABILITY ROUTES (Public)
// ========================================
// Public route for checking availability without authentication
Route::prefix('appointments')->group(function () {
    // GET /api/appointments/available-slots?date=2024-01-15
    // Get available time slots for a specific date (defaults to today)
    Route::get('/available-slots', [AppointmentController::class, 'getAvailableSlots']);
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



    });
});
