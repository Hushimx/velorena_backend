<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DesignersController;
use App\Http\Controllers\ProductsController;

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    // Public routes (accessible without auth)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [LoginController::class, 'login']);
    });

    // Password Reset Routes...
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('admin.password.update');

    // Protected routes (require admin auth)
    Route::middleware(RedirectIfNotAdmin::class)->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Users management routes
        Route::name('admin.')->group(function () {
            Route::resource('users', UsersController::class);
            Route::resource('designers', DesignersController::class);
            Route::resource('products', ProductsController::class);
            Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
            Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
            Route::resource('marketers', \App\Http\Controllers\Admin\MarketerController::class);
            Route::resource('leads', \App\Http\Controllers\Admin\LeadController::class);
            
            // Bulk upload routes for leads
            Route::get('leads/bulk-upload', [\App\Http\Controllers\Admin\LeadController::class, 'bulkUpload'])->name('leads.bulk-upload');
            Route::post('leads/bulk-upload', [\App\Http\Controllers\Admin\LeadController::class, 'processBulkUpload'])->name('leads.bulk-upload.process');
            Route::get('leads/download-template', [\App\Http\Controllers\Admin\LeadController::class, 'downloadTemplate'])->name('leads.download-template');
        });
    });
});
