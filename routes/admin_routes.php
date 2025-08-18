<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\UsersController;

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    // Public routes (accessible without auth)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [LoginController::class, 'login']);
    });

    // Protected routes (require admin auth)
    Route::middleware(RedirectIfNotAdmin::class)->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // Registration Routes... commented since no need for admins to register
    // Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('admin.register');
    // Route::post('/register', [RegisterController::class, 'register']);

    // Password Reset Routes...
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('admin.password.update');

    // Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
    // Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
    Route::name('admin.')->group(function () {
        Route::resource('users', UsersController::class);
    });
});
