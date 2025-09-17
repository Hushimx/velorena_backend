<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

    // Handle GET requests to designer logout (redirect to designer login)
    Route::get('logout', function () {
        return redirect()->route('designer.login');
    });

    // Protected routes (require designer auth)
    Route::middleware('designer.auth')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('designer.logout');
        Route::get('/dashboard', [DesignerController::class, 'index'])->name('designer.dashboard');

        // Appointment routes
        Route::get('/appointments/list', [App\Http\Controllers\AppointmentController::class, 'designerAppointments'])->name('designer.appointments.index');
        Route::get('/appointments/dashboard', [App\Http\Controllers\AppointmentController::class, 'designerDashboard'])->name('designer.appointments.dashboard');
        Route::get('/appointments/upcoming', [App\Http\Controllers\AppointmentController::class, 'designerUpcoming'])->name('designer.appointments.upcoming');
        Route::get('/appointments/details/{appointment}', [App\Http\Controllers\AppointmentController::class, 'designerShow'])->name('designer.appointments.show');
        Route::post('/appointments/{appointment}/accept', [App\Http\Controllers\AppointmentController::class, 'accept'])->name('designer.appointments.accept');
        Route::post('/appointments/{appointment}/reject', [App\Http\Controllers\AppointmentController::class, 'reject'])->name('designer.appointments.reject');
        Route::post('/appointments/{appointment}/complete', [App\Http\Controllers\AppointmentController::class, 'complete'])->name('designer.appointments.complete');
        Route::post('/appointments/{appointment}/link-order', [App\Http\Controllers\AppointmentController::class, 'linkOrder'])->name('designer.appointments.link-order');
        Route::post('/appointments/{appointment}/unlink-order', [App\Http\Controllers\AppointmentController::class, 'unlinkOrder'])->name('designer.appointments.unlink-order');
        Route::put('/appointments/{appointment}', [App\Http\Controllers\AppointmentController::class, 'update'])->name('designer.appointments.update');
        Route::post('/appointments/{appointment}/recalculate-order', [App\Http\Controllers\AppointmentController::class, 'recalculateOrder'])->name('designer.appointments.recalculate-order');

        // Order editing routes
        Route::get('/appointments/{appointment}/edit-order', [App\Http\Controllers\AppointmentController::class, 'designerEditOrder'])->name('designer.orders.edit');

        // Orders routes
        Route::get('/orders', [App\Http\Controllers\AppointmentController::class, 'designerAppointments'])->name('designer.orders.index');

        // Profile routes
        Route::get('/profile/edit', function () {
            return view('designer.profile.edit');
        })->name('designer.profile.edit');

        // Portfolio routes
        Route::get('/portfolio', function () {
            return view('designer.portfolio.index');
        })->name('designer.portfolio.index');
    });

    // Temporary test route without middleware for debugging
    Route::get('/test-auth', function () {
        return response()->json([
            'auth_check' => Auth::check(),
            'designer_auth_check' => Auth::guard('designer')->check(),
            'current_user' => Auth::user(),
            'current_designer' => Auth::guard('designer')->user(),
            'session_data' => session()->all()
        ]);
    })->name('designer.test-auth');

    // Debug route for appointments
    Route::get('/debug-appointments', function () {
        return response()->json([
            'designer_auth_check' => Auth::guard('designer')->check(),
            'current_designer' => Auth::guard('designer')->user(),
            'session_id' => session()->getId(),
            'session_data' => session()->all()
        ]);
    })->name('designer.debug-appointments');
});
