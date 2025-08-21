<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductOptionController;

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
    Route::get('/{product}/options', [ProductOptionController::class, 'index']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    
    // Document routes
    Route::post('/documents/upload', [DocumentController::class, 'uploadDocument']);
    Route::delete('/documents/delete', [DocumentController::class, 'deleteDocument']);
    Route::get('/documents/info', [DocumentController::class, 'getDocumentInfo']);
    
    // Admin/Designer routes for managing categories and products
    Route::prefix('admin')->group(function () {
        // Categories management
        Route::prefix('categories')->group(function () {
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{category}', [CategoryController::class, 'update']);
            Route::delete('/{category}', [CategoryController::class, 'destroy']);
        });
        
        // Products management
        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{product}', [ProductController::class, 'update']);
            Route::delete('/{product}', [ProductController::class, 'destroy']);
            
            // Product options management
            Route::prefix('{product}/options')->group(function () {
                Route::post('/', [ProductOptionController::class, 'store']);
                Route::get('/{option}', [ProductOptionController::class, 'show']);
                Route::put('/{option}', [ProductOptionController::class, 'update']);
                Route::delete('/{option}', [ProductOptionController::class, 'destroy']);
                
                // Option values management
                Route::post('/{option}/values', [ProductOptionController::class, 'addValue']);
                Route::put('/{option}/values/{value}', [ProductOptionController::class, 'updateValue']);
                Route::delete('/{option}/values/{value}', [ProductOptionController::class, 'removeValue']);
            });
        });
    });
});
