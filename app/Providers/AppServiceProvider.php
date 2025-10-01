<?php

namespace App\Providers;

use App\Notifications\Channels\ExpoPushChannel;
use App\Services\ExpoPushService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Expo push notification channel
        Notification::extend('expo_push', function ($app) {
            return new ExpoPushChannel($app->make(ExpoPushService::class));
        });
    }
}
