<?php

namespace App\Notifications\Channels;

use App\Services\ExpoPushService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ExpoPushChannel
{
    /**
     * Create a new Expo push channel instance.
     */
    public function __construct(
        private ExpoPushService $expoService
    ) {}

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        try {
            // Get the notification data
            $expoData = $notification->toExpoPush($notifiable);
            
            // Get Expo push tokens from the notifiable
            $tokens = $notifiable->routeNotificationForExpoPush();
            
            if (empty($tokens)) {
                Log::warning('No Expo push tokens found for notification', [
                    'notifiable_id' => $notifiable->id,
                    'notifiable_type' => get_class($notifiable)
                ]);
                return;
            }

            // Send notification
            $result = $this->expoService->sendToDevices($tokens, $expoData);

            if ($result['success']) {
                Log::info('Expo push notification sent successfully', [
                    'notifiable_id' => $notifiable->id,
                    'tokens_count' => count($tokens),
                    'successful' => $result['data']['total_sent'] ?? 0,
                    'failed' => $result['data']['total_failed'] ?? 0
                ]);
            } else {
                Log::error('Expo push notification failed', [
                    'notifiable_id' => $notifiable->id,
                    'error' => $result['message'] ?? 'Unknown error'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Expo push notification exception', [
                'notifiable_id' => $notifiable->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
