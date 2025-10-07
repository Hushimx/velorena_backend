<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
use App\Http\Controllers\Api\WhatsAppController;
use App\Http\Controllers\Api\ExpoPushTokenController;
use App\Http\Controllers\Api\AddressController;

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

// Guest-friendly test notification endpoint
Route::post('/test-notification-guest', function () {
    try {
        // Send a test notification to all registered tokens (both authenticated and guest)
        $allTokens = \App\Models\ExpoPushToken::where('is_active', true)->get();
        
        if ($allTokens->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active push tokens found. Make sure you have registered a token first.'
            ]);
        }
        
        // Use the ExpoPushService to send notifications
        $expoService = new \App\Services\ExpoPushService();
        
        $notification = [
            'title' => 'Test Notification (Guest)',
            'body' => 'This is a test notification sent to all registered devices',
            'data' => ['type' => 'test', 'timestamp' => now()->toISOString()]
        ];
        
        $tokens = $allTokens->pluck('token')->toArray();
        $result = $expoService->sendToDevices($tokens, $notification);
        
        return response()->json([
            'success' => true,
            'message' => 'Test notification sent to ' . count($tokens) . ' devices',
            'tokens_sent_to' => $tokens,
            'expo_response' => $result
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send test notification: ' . $e->getMessage()
        ], 500);
    }
});

// Guest token registration endpoint (no authentication required)
Route::post('/expo-push/register-guest', [ExpoPushTokenController::class, 'registerGuest']);

// Debug route for notifications (works for both authenticated and guest users)
Route::get('/debug/notifications', function () {
    $user = Auth::user();
    
    if (!$user) {
        // Show guest user info
        $guestTokens = \App\Models\ExpoPushToken::whereNull('tokenable_id')->get();
        return response()->json([
            'authenticated' => false,
            'message' => 'Not logged in - using guest mode',
            'guest_tokens_count' => $guestTokens->count(),
            'guest_tokens' => $guestTokens->pluck('token')->toArray()
        ]);
    }
    
    $tokens = $user->expoPushTokens ?? collect();
    return response()->json([
        'authenticated' => true,
        'user_id' => $user->id,
        'tokens_count' => $tokens->count(),
        'tokens' => $tokens->pluck('token')->toArray()
    ]);
});

// Test file upload route
Route::post('/test-upload', function (Request $request) {
        Log::info('Test upload endpoint called', [
        'files' => $request->allFiles(),
        'content_type' => $request->header('Content-Type'),
        'all_data' => $request->all(),
        'method' => $request->method()
    ]);
    
    return response()->json([
        'message' => 'Upload test endpoint working',
        'files_received' => count($request->allFiles()),
        'content_type' => $request->header('Content-Type'),
        'all_data' => $request->all(),
        'timestamp' => now()
    ]);
});

// Test endpoint removed - using authenticated endpoint now

// Swagger documentation routes are now handled by RouteServiceProvider

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    
    // Phone check with rate limiting (10 requests per minute)
    Route::post('/check-phone', [AuthController::class, 'checkPhone'])
        ->middleware('throttle:10,1');
    
    // Email check with rate limiting (10 requests per minute)
    Route::post('/check-email', [AuthController::class, 'checkEmail'])
        ->middleware('throttle:10,1');

    // Password reset routes
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->middleware('throttle:3,1');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->middleware('throttle:5,1');

    // OTP routes with rate limiting (5 requests per minute)
    Route::post('/send-otp', [OtpController::class, 'sendOtp'])
        ->middleware('throttle:5,1');
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])
        ->middleware('throttle:10,1');
    Route::post('/resend-otp', [OtpController::class, 'resendOtp'])
        ->middleware('throttle:3,1');
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
    Route::get('/id/{product}', [ProductController::class, 'showById']); // Backward compatibility - fetch by ID
    Route::get('/{product:slug}', [ProductController::class, 'show']); // New - fetch by slug
    
    // Product reviews routes (public)
    Route::get('/{productId}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'index']);
});

// Test endpoint
Route::get('/test-cart', function() {
    return response()->json([
        'success' => true,
        'message' => 'Test endpoint working',
        'user_authenticated' => Auth::check(),
        'timestamp' => now()
    ]);
});

// Cart preview endpoint
Route::get('/cart/preview', function() {
    try {
        Log::info('Cart preview API called', [
            'authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);
        
        if (Auth::check()) {
            // Authenticated user - get from database
            $user = Auth::user();
            $cartItems = \App\Models\CartItem::where('user_id', $user->id)->with('product')->get();
            
            Log::info('Authenticated cart items found', [
                'count' => $cartItems->count(),
                'items' => $cartItems->pluck('id')
            ]);
            
            $items = $cartItems->map(function($item) {
                return [
                    'product_name' => app()->getLocale() === 'ar' ? ($item->product->name_ar ?? $item->product->name) : $item->product->name,
                    'product_image' => $item->product->image_url ?? $item->product->image,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'item_count' => $cartItems->count(),
                    'total_price' => $cartItems->sum('total_price')
                ],
                'debug' => [
                    'user_type' => 'authenticated',
                    'user_id' => $user->id,
                    'raw_count' => $cartItems->count()
                ]
            ]);
        } else {
            // Guest user - get from session
            $guestCartService = app(\App\Services\GuestCartService::class);
            $cartSummary = $guestCartService->getCartSummary();
            $cartItems = $guestCartService->getCartItemsWithProducts();
            
            Log::info('Guest cart data', [
                'summary' => $cartSummary,
                'items_count' => count($cartItems)
            ]);
            
            $items = collect($cartItems)->map(function($item) {
                return [
                    'product_name' => app()->getLocale() === 'ar' ? ($item['product']['name_ar'] ?? $item['product']['name']) : $item['product']['name'],
                    'product_image' => $item['product']['image_url'] ?? $item['product']['image'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'item_count' => $cartSummary['item_count'],
                    'total_price' => $cartSummary['total_price']
                ],
                'debug' => [
                    'user_type' => 'guest',
                    'session_id' => session()->getId(),
                    'raw_summary' => $cartSummary,
                    'raw_items_count' => count($cartItems)
                ]
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Cart preview API error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Failed to load cart preview: ' . $e->getMessage(),
            'debug' => [
                'exception_class' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]
        ], 500);
    }
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
// DESIGN SYSTEM ROUTES (PROTECTED)
// ========================================
Route::middleware('auth:sanctum')->prefix('designs')->group(function () {
    // GET /api/designs/search - Search external designs from Freepik API (like web design.search)
    Route::get('/search', [DesignController::class, 'freepikSearch']);
    
    // GET /api/designs/cart - Get all designs in cart
    Route::get('/cart', [DesignController::class, 'getCartDesigns']);
    
    // POST /api/designs/save-to-cart - Save design to cart (like web design.save-to-cart)
    Route::post('/save-to-cart', [DesignController::class, 'saveToCart']);
    
    // POST /api/designs/delete-from-cart - Delete design from cart (like web design.delete-from-cart)
    Route::post('/delete-from-cart', [DesignController::class, 'deleteFromCart']);
    
    // POST /api/designs/add-to-favorites - Add design to favorites (like web design.add-to-favorites)
    Route::post('/add-to-favorites', [DesignController::class, 'addToFavorites']);
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

    // User notification preferences
    Route::get('/user/notification-preferences', [AuthController::class, 'getNotificationPreferences']);
    Route::put('/user/notification-preferences', [AuthController::class, 'updateNotificationPreferences']);

    // Document routes
    Route::post('/documents/upload', [DocumentController::class, 'uploadDocument']);
    Route::delete('/documents/delete', [DocumentController::class, 'deleteDocument']);
    Route::get('/documents/info', [DocumentController::class, 'getDocumentInfo']);

    // Expo Push Token routes
    Route::prefix('expo-push')->group(function () {
        Route::post('/register', [ExpoPushTokenController::class, 'register']);
        Route::get('/tokens', [ExpoPushTokenController::class, 'index']);
        Route::post('/deactivate', [ExpoPushTokenController::class, 'deactivate']);
        Route::delete('/delete', [ExpoPushTokenController::class, 'delete']);
        Route::post('/test', [ExpoPushTokenController::class, 'sendTest']);
    });


    // ========================================
    // CART MANAGEMENT ROUTES
    // ========================================
    Route::prefix('cart')->group(function () {
        // GET /api/cart/items - Get user's cart items with product details
        Route::get('/items', [CartController::class, 'getCartItems']);
        
        // POST /api/cart/add - Add item to cart
        Route::post('/add', [CartController::class, 'addToCart']);
        
        // PUT /api/cart/items/{cartItemId} - Update cart item quantity
        Route::put('/items/{cartItemId}', [CartController::class, 'updateCartItem']);
        
        // DELETE /api/cart/items/{cartItemId} - Remove item from cart
        Route::delete('/items/{cartItemId}', [CartController::class, 'removeFromCart']);
        
        // DELETE /api/cart/clear - Clear entire cart
        Route::delete('/clear', [CartController::class, 'clearCart']);
    });

    // ========================================
    // ADDRESS MANAGEMENT ROUTES
    // ========================================
    Route::prefix('addresses')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\AddressController::class, 'index']);
        Route::get('/{address}', [App\Http\Controllers\Api\AddressController::class, 'show']);
        Route::post('/', [App\Http\Controllers\Api\AddressController::class, 'store']);
        Route::put('/{address}', [App\Http\Controllers\Api\AddressController::class, 'update']);
        Route::delete('/{address}', [App\Http\Controllers\Api\AddressController::class, 'destroy']);
        Route::post('/{address}/set-default', [App\Http\Controllers\Api\AddressController::class, 'setDefault']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/payment', [OrderController::class, 'initiatePayment']);
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

        // POST /api/appointments/create-from-cart
        // Create a new appointment with order from cart items
        // Required: appointment_date, appointment_time, service_type
        // Optional: description, duration, location, notes, order_notes
        Route::post('/create-from-cart', [AppointmentController::class, 'createFromCart']);







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
        
        // Note: Search endpoint is defined as public above
        
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
        
        // Note: These routes are commented out as the corresponding controller methods don't exist
        // GET /api/designs/selected-for-product/{productId}
        // Get selected designs for a product
        // Route::get('/selected-for-product/{productId}', [DesignController::class, 'getSelectedDesignsForProduct']);
        
        // DELETE /api/designs/selected-for-product/{productId}/{designId}
        // Remove design from product selection
        // Route::delete('/selected-for-product/{productId}/{designId}', [DesignController::class, 'removeDesignFromProduct']);
        
        // POST /api/designs/upload-ready-design
        // Upload a ready design file (image)
        Route::post('/upload-ready-design', [DesignController::class, 'uploadReadyDesign']);
        
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

    // ========================================
    // REVIEW ROUTES
    // ========================================
    Route::prefix('reviews')->group(function () {
        // POST /api/reviews - Submit a new review
        Route::post('/', [App\Http\Controllers\Api\ReviewController::class, 'store']);
        
        // GET /api/reviews/user - Get user's own reviews
        Route::get('/user', [App\Http\Controllers\Api\ReviewController::class, 'userReviews']);
        
        // PUT /api/reviews/{id} - Update user's own review
        Route::put('/{id}', [App\Http\Controllers\Api\ReviewController::class, 'update']);
        
        // DELETE /api/reviews/{id} - Delete user's own review
        Route::delete('/{id}', [App\Http\Controllers\Api\ReviewController::class, 'destroy']);
        
        // GET /api/reviews/can-review/{productId} - Check if user can review a product
        Route::get('/can-review/{productId}', [App\Http\Controllers\Api\ReviewController::class, 'canReview']);
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
        
        // GET /api/payments/success
        // Payment success page for mobile redirects
        Route::get('/success', function() {
            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully'
            ]);
        });
    });

    // ========================================
    // ADDRESS MANAGEMENT ROUTES
    // ========================================
    Route::prefix('addresses')->group(function () {
        // GET /api/addresses - Get all user addresses
        Route::get('/', [AddressController::class, 'index']);
        
        // POST /api/addresses - Create new address
        Route::post('/', [AddressController::class, 'store']);
        
        // GET /api/addresses/{id} - Get specific address
        Route::get('/{id}', [AddressController::class, 'show']);
        
        // PUT /api/addresses/{id} - Update address
        Route::put('/{id}', [AddressController::class, 'update']);
        
        // DELETE /api/addresses/{id} - Delete address
        Route::delete('/{id}', [AddressController::class, 'destroy']);
        
        // POST /api/addresses/{id}/set-default - Set address as default
        Route::post('/{id}/set-default', [AddressController::class, 'setDefault']);
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

// ========================================
// WHATSAPP ROUTES (Temporarily disabled - controller missing)
// ========================================
// Route::prefix('whatsapp')->group(function () {
//     // Public routes (no authentication required)
//     Route::post('/send', [WhatsAppController::class, 'sendMessage']);
//     Route::post('/send-bulk', [WhatsAppController::class, 'sendBulkMessage']);
//     Route::get('/qr-code', [WhatsAppController::class, 'getQRCode']);
//     Route::post('/webhook', [WhatsAppController::class, 'setWebhook']);
//     Route::post('/reboot', [WhatsAppController::class, 'rebootInstance']);
//     Route::get('/status', [WhatsAppController::class, 'getStatus']);
//     Route::post('/validate-phone', [WhatsAppController::class, 'validatePhoneNumber']);
// });

// Webhook routes (no authentication required)
Route::post('/webhooks/tap', [App\Http\Controllers\Api\TapPaymentController::class, 'webhook'])->name('api.webhooks.tap');
