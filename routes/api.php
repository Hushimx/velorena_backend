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
    // USER DESIGN MANAGEMENT ROUTES
    // ========================================
    Route::prefix('designs')->group(function () {

        // POST /api/designs/{design}/favorite - Add design to user favorites
        Route::post('/{design}/favorite', [DesignController::class, 'addToFavorites']);

        // DELETE /api/designs/{design}/favorite - Remove design from user favorites
        Route::delete('/{design}/favorite', [DesignController::class, 'removeFromFavorites']);

        // GET /api/designs/favorites - Get user's favorite designs
        Route::get('/favorites', [DesignController::class, 'getFavorites']);

        // POST /api/designs/collections - Create a new design collection
        Route::post('/collections', [DesignController::class, 'createCollection']);

        // GET /api/designs/collections - Get user's design collections
        Route::get('/collections', [DesignController::class, 'getCollections']);

        // GET /api/designs/collections/{collection} - Get specific collection
        Route::get('/collections/{collection}', [DesignController::class, 'getCollection']);

        // PUT /api/designs/collections/{collection} - Update collection
        Route::put('/collections/{collection}', [DesignController::class, 'updateCollection']);

        // DELETE /api/designs/collections/{collection} - Delete collection
        Route::delete('/collections/{collection}', [DesignController::class, 'deleteCollection']);

        // POST /api/designs/collections/{collection}/designs - Add design to collection
        Route::post('/collections/{collection}/designs', [DesignController::class, 'addDesignToCollection']);

        // DELETE /api/designs/collections/{collection}/designs/{design} - Remove design from collection
        Route::delete('/collections/{collection}/designs/{design}', [DesignController::class, 'removeDesignFromCollection']);

        // POST /api/designs/{design}/appointments/{appointment} - Link design to appointment
        Route::post('/{design}/appointments/{appointment}', [DesignController::class, 'linkToAppointment']);

        // DELETE /api/designs/{design}/appointments/{appointment} - Unlink design from appointment
        Route::delete('/{design}/appointments/{appointment}', [DesignController::class, 'unlinkFromAppointment']);

        // POST /api/designs/{design}/orders/{order} - Link design to order
        Route::post('/{design}/orders/{order}', [DesignController::class, 'linkToOrder']);

        // DELETE /api/designs/{design}/orders/{order} - Unlink design from order
        Route::delete('/{design}/orders/{order}', [DesignController::class, 'unlinkFromOrder']);

        // GET /api/designs/history - Get user's design interaction history
        Route::get('/history', [DesignController::class, 'getDesignHistory']);
    });

    // ========================================
    // CART MANAGEMENT ROUTES
    // ========================================
    Route::prefix('cart')->group(function () {

        // GET /api/cart/items - Get user's cart items with designs
        Route::get('/items', [CartController::class, 'getCartItems']);

        // POST /api/cart/items - Add item to cart
        Route::post('/items', [CartController::class, 'addToCart']);

        // PUT /api/cart/items/{cartItemId} - Update cart item quantity
        Route::put('/items/{cartItemId}', [CartController::class, 'updateCartItem']);

        // DELETE /api/cart/items/{cartItemId} - Remove item from cart
        Route::delete('/items/{cartItemId}', [CartController::class, 'removeFromCart']);

        // DELETE /api/cart/clear - Clear entire cart
        Route::delete('/clear', [CartController::class, 'clearCart']);

        // POST /api/cart/designs - Add design to cart item
        Route::post('/designs', [CartController::class, 'addDesignToCartItem']);

        // DELETE /api/cart/designs - Remove design from cart item
        Route::delete('/designs', [CartController::class, 'removeDesignFromCartItem']);

        // PUT /api/cart/designs/notes - Update design notes in cart item
        Route::put('/designs/notes', [CartController::class, 'updateDesignNotes']);

        // GET /api/cart/items/{productId}/designs - Get designs for specific cart item
        Route::get('/items/{productId}/designs', [CartController::class, 'getCartItemDesigns']);

        // POST /api/cart/checkout - Create order from cart with designs
        Route::post('/checkout', [CartController::class, 'createOrderFromCart']);
    });
});
