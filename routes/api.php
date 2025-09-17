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
use App\Http\Controllers\Api\SupportTicketController;
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
    Route::get('/latest', [ProductController::class, 'latest']);
    Route::get('/best-selling', [ProductController::class, 'bestSelling']);
    Route::get('/{product}', [ProductController::class, 'show']);
});

Route::prefix('highlights')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\HighlightController::class, 'index']);
    Route::get('/{highlight}', [App\Http\Controllers\Api\HighlightController::class, 'show']);
    Route::get('/{highlight}/products', [App\Http\Controllers\Api\HighlightController::class, 'products']);
});

Route::prefix('home-banners')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\HomeBannerController::class, 'index']);
    Route::get('/{banner}', [App\Http\Controllers\Api\HomeBannerController::class, 'show']);
});

// ========================================
// DESIGN SYSTEM ROUTES (PUBLIC - Basic Info Only)
// ========================================
Route::prefix('designs')->group(function () {
    // GET /api/designs/categories - Get available design categories
    Route::get('/categories', [DesignController::class, 'categories']);
    
    // GET /api/designs/search - Search designs (public)
    Route::get('/search', [DesignController::class, 'search']);
    
    // GET /api/designs/external/search - Search external designs from Freepik API
    Route::get('/external/search', [DesignController::class, 'searchExternal']);
    
    // GET /api/designs/external/categories - Get external categories from Freepik API
    Route::get('/external/categories', [DesignController::class, 'getExternalCategories']);
    
    // GET /api/designs/external/featured - Get featured designs from Freepik API
    Route::get('/external/featured', [DesignController::class, 'getExternalFeatured']);
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

    // ========================================
    // DESIGN SYSTEM ROUTES (AUTHENTICATED)
    // ========================================
    Route::prefix('designs')->group(function () {
        
        // GET /api/designs/search
        // Search designs using Freepik API
        Route::get('/search', [DesignController::class, 'searchExternal']);
        
        // GET /api/designs/saved
        // Get user's saved/favorite designs
        Route::get('/saved', [DesignController::class, 'getFavorites']);
        
        // POST /api/designs/save
        // Save design to favorites
        Route::post('/save', [DesignController::class, 'addToFavorites']);
        
        // PUT /api/designs/favorite/{design}
        // Edit favorite design and replace with new image
        Route::put('/favorite/{design}', [DesignController::class, 'updateFavorite']);
        
        // DELETE /api/designs/favorite/{design}
        // Remove design from favorites
        Route::delete('/favorite/{design}', [DesignController::class, 'removeFromFavorites']);
        
        // GET /api/designs/{design}
        // Get specific design details
        Route::get('/{design}', [DesignController::class, 'show']);
        
        // ========================================
        // CART DESIGN INTEGRATION
        // ========================================
        
        // POST /api/designs/select-for-product
        // Select designs for a product in cart
        Route::post('/select-for-product', [DesignController::class, 'selectDesignsForProduct']);
        
        // GET /api/designs/selected-for-product/{productId}
        // Get selected designs for a product
        Route::get('/selected-for-product/{productId}', [DesignController::class, 'getSelectedDesignsForProduct']);
        
        // DELETE /api/designs/selected-for-product/{productId}/{designId}
        // Remove design from product selection
        Route::delete('/selected-for-product/{productId}/{designId}', [DesignController::class, 'removeDesignFromProduct']);
        
    });

    // ========================================
    // SUPPORT TICKETS ROUTES
    // ========================================
    Route::prefix('support-tickets')->group(function () {
        
        // GET /api/support-tickets
        // Get user's support tickets with filtering and pagination
        Route::get('/', [SupportTicketController::class, 'index']);
        
        // POST /api/support-tickets
        // Create a new support ticket
        Route::post('/', [SupportTicketController::class, 'store']);
        
        // GET /api/support-tickets/statistics
        // Get support ticket statistics for the authenticated user
        Route::get('/statistics', [SupportTicketController::class, 'statistics']);
        
        // GET /api/support-tickets/{id}
        // Get a specific support ticket with replies
        Route::get('/{supportTicket}', [SupportTicketController::class, 'show']);
        
        // POST /api/support-tickets/{id}/replies
        // Add a reply to a support ticket
        Route::post('/{supportTicket}/replies', [SupportTicketController::class, 'addReply']);
        
        // GET /api/support-tickets/{id}/replies
        // Get replies for a specific support ticket
        Route::get('/{supportTicket}/replies', [SupportTicketController::class, 'getReplies']);
        
    });

    // Payment routes
    Route::prefix('payments')->group(function () {
        // POST /api/payments/create-charge
        // Create a payment charge
        Route::post('/create-charge', [App\Http\Controllers\Api\TapPaymentController::class, 'createCharge']);
        
        // GET /api/payments/status
        // Get payment status by charge ID
        Route::get('/status', [App\Http\Controllers\Api\TapPaymentController::class, 'getPaymentStatus']);
        
        // POST /api/payments/refund
        // Create a refund
        Route::post('/refund', [App\Http\Controllers\Api\TapPaymentController::class, 'createRefund']);
        
        // GET /api/payments/test-cards
        // Get test card numbers for testing
        Route::get('/test-cards', [App\Http\Controllers\Api\TapPaymentController::class, 'getTestCards']);
    });
});

// Unauthenticated routes for testing
Route::prefix('test')->group(function () {
    // Test payment routes (no authentication required)
    Route::prefix('payments')->group(function () {
        // POST /api/test/payments/create-charge
        // Create a test payment charge (no auth required)
        Route::post('/create-charge', [App\Http\Controllers\Api\TapPaymentController::class, 'createTestCharge']);
        
        // GET /api/test/payments/status
        // Get test payment status by charge ID (no auth required)
        Route::get('/status', [App\Http\Controllers\Api\TapPaymentController::class, 'getTestPaymentStatus']);
        
        // GET /api/test/payments/test-cards
        // Get test card numbers for testing (no auth required)
        Route::get('/test-cards', [App\Http\Controllers\Api\TapPaymentController::class, 'getTestCards']);
    });
});

// Webhook routes (no authentication required)
Route::post('/webhooks/tap', [App\Http\Controllers\Api\TapPaymentController::class, 'webhook'])->name('api.webhooks.tap');
