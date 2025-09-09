<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfNotMarketer;
use App\Http\Controllers\Marketer\MarketerController;
use App\Http\Controllers\Marketer\LeadController;

// Marketer Auth Routes
Route::prefix('marketer')->group(function () {
    Route::redirect('/', '/marketer/dashboard');

    // Public routes (accessible without auth)
    Route::middleware('guest:marketer')->group(function () {
        Route::get('login', [App\Http\Controllers\Marketer\Auth\LoginController::class, 'showLoginForm'])->name('marketer.login');
        Route::post('login', [App\Http\Controllers\Marketer\Auth\LoginController::class, 'login']);
    });

    // Password Reset Routes
    Route::get('/password/reset', [App\Http\Controllers\Marketer\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('marketer.password.request');
    Route::post('/password/email', [App\Http\Controllers\Marketer\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('marketer.password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Marketer\Auth\ResetPasswordController::class, 'showResetForm'])->name('marketer.password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Marketer\Auth\ResetPasswordController::class, 'reset'])->name('marketer.password.update');

    // Protected routes (require marketer auth)
    Route::middleware(RedirectIfNotMarketer::class)->group(function () {
        Route::post('logout', [App\Http\Controllers\Marketer\Auth\LoginController::class, 'logout'])->name('marketer.logout');
        Route::get('/dashboard', [MarketerController::class, 'index'])->name('marketer.dashboard');

        // Lead routes
        Route::name('marketer.')->group(function () {
            Route::resource('leads', LeadController::class)->only(['index', 'show', 'edit', 'update']);
            Route::post('leads/{lead}/communication', [LeadController::class, 'addCommunication'])->name('leads.communication');
            Route::post('leads/request-new', [LeadController::class, 'requestNewLeads'])->name('leads.request-new');
        });
    });
});
