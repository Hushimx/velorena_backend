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

    // Handle GET requests to admin logout (redirect to admin login)
    Route::get('logout', function () {
        return redirect()->route('admin.login');
    });

    // Protected routes (require admin auth)
    Route::middleware(RedirectIfNotAdmin::class)->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/sales-data', [AdminController::class, 'getSalesDataForPeriod'])->name('admin.dashboard.sales-data');
        Route::get('/language/{locale}', function ($locale) {
            if (in_array($locale, ['ar', 'en'])) {
                session(['locale' => $locale]);
                app()->setLocale($locale);
            }
            return redirect()->back();
        })->name('admin.language.switch');

        // Users management routes
        Route::name('admin.')->group(function () {
            Route::resource('users', UsersController::class);
            Route::resource('designers', DesignersController::class);
            Route::resource('products', ProductsController::class)->parameters(['products' => 'id']);
            Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
            Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
            Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class);
            Route::resource('marketers', \App\Http\Controllers\Admin\MarketerController::class);

            // Bulk upload routes for leads (must be before resource route)
            Route::get('leads/bulk-upload', [\App\Http\Controllers\Admin\LeadController::class, 'bulkUpload'])->name('leads.bulk-upload');
            Route::post('leads/bulk-upload', [\App\Http\Controllers\Admin\LeadController::class, 'processBulkUpload'])->name('leads.bulk-upload.process');
            Route::get('leads/download-template', [\App\Http\Controllers\Admin\LeadController::class, 'downloadTemplate'])->name('leads.download-template');

            Route::resource('leads', \App\Http\Controllers\Admin\LeadController::class);
            Route::resource('admins', \App\Http\Controllers\Admin\AdminResourceController::class);
            Route::resource('availability-slots', \App\Http\Controllers\Admin\AvailabilitySlotController::class);
            Route::patch('availability-slots/{availabilitySlot}/toggle-status', [\App\Http\Controllers\Admin\AvailabilitySlotController::class, 'toggleStatus'])->name('availability-slots.toggle-status');

            // Highlights management routes
            Route::resource('highlights', \App\Http\Controllers\Admin\HighlightController::class);
            Route::get('products/{product}/assign-highlights', [\App\Http\Controllers\Admin\HighlightController::class, 'assignToProduct'])->name('products.assign-highlights');
            Route::post('products/{product}/assign-highlights', [\App\Http\Controllers\Admin\HighlightController::class, 'storeProductHighlights'])->name('products.store-highlights');

            // Home banners management routes
            Route::get('home-banners', [\App\Http\Controllers\Admin\HomeBannerController::class, 'index'])->name('home-banners.index');
            Route::get('home-banners/create', [\App\Http\Controllers\Admin\HomeBannerController::class, 'create'])->name('home-banners.create');
            Route::post('home-banners', [\App\Http\Controllers\Admin\HomeBannerController::class, 'store'])->name('home-banners.store');
            Route::get('home-banners/{home_banner}', [\App\Http\Controllers\Admin\HomeBannerController::class, 'show'])->name('home-banners.show');
            Route::get('home-banners/{home_banner}/edit', [\App\Http\Controllers\Admin\HomeBannerController::class, 'edit'])->name('home-banners.edit');
            Route::put('home-banners/{home_banner}', [\App\Http\Controllers\Admin\HomeBannerController::class, 'update'])->name('home-banners.update');
            Route::delete('home-banners/{home_banner}', [\App\Http\Controllers\Admin\HomeBannerController::class, 'destroy'])->name('home-banners.destroy');


            // Support Tickets management routes
            Route::resource('support-tickets', \App\Http\Controllers\Admin\SupportTicketController::class);
            Route::post('support-tickets/{supportTicket}/assign', [\App\Http\Controllers\Admin\SupportTicketController::class, 'assign'])->name('support-tickets.assign');
            Route::post('support-tickets/{supportTicket}/replies', [\App\Http\Controllers\Admin\SupportTicketController::class, 'addReply'])->name('support-tickets.add-reply');
            Route::put('support-tickets/replies/{reply}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'updateReply'])->name('support-tickets.update-reply');
            Route::delete('support-tickets/replies/{reply}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'deleteReply'])->name('support-tickets.delete-reply');
            Route::post('support-tickets/bulk-action', [\App\Http\Controllers\Admin\SupportTicketController::class, 'bulkAction'])->name('support-tickets.bulk-action');
            Route::get('support-tickets-statistics', [\App\Http\Controllers\Admin\SupportTicketController::class, 'statistics'])->name('support-tickets.statistics');

            // Pages management routes
            Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);

            // Site Settings management routes
            Route::resource('site-settings', \App\Http\Controllers\Admin\SiteSettingsController::class);
            Route::post('site-settings/bulk-update', [\App\Http\Controllers\Admin\SiteSettingsController::class, 'updateBulk'])->name('site-settings.bulk-update');

            // Posts management routes
            Route::resource('posts', \App\Http\Controllers\Admin\PostsController::class);
            Route::get('posts/{post}/preview', [\App\Http\Controllers\Admin\PostsController::class, 'preview'])->name('posts.preview');

            // Image upload for CKEditor
            Route::post('upload/image', [\App\Http\Controllers\Admin\ImageUploadController::class, 'upload'])->name('upload.image');
        });
    });
});
