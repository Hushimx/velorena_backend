<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfNotDesigner;
use App\Http\Controllers\Designer\DesignerController;
use App\Http\Controllers\Designer\Auth\LoginController;
use App\Http\Controllers\Designer\Auth\RegisterController;
use App\Http\Controllers\Designer\Auth\ResetPasswordController;
use App\Http\Controllers\Designer\Auth\ForgotPasswordController;

// Designer Auth Routes
Route::prefix('designer')->group(function () {
    Route::redirect('/', '/designer/dashboard');

    // Public routes (accessible without auth)
    Route::middleware('designer.guest')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('designer.login');
        Route::post('login', [LoginController::class, 'login']);
        Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('designer.register');
        Route::post('register', [RegisterController::class, 'register']);
    });

    // Password Reset Routes
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('designer.password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('designer.password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('designer.password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('designer.password.update');

    // Protected routes (require designer auth)
    Route::post('logout', [LoginController::class, 'logout'])->name('designer.logout');
    Route::get('/dashboard', [DesignerController::class, 'index'])->name('designer.dashboard');

    // Add more designer-specific routes here
    Route::name('designer.')->group(function () {
        // Example: Route::resource('projects', ProjectController::class);
    });
});
