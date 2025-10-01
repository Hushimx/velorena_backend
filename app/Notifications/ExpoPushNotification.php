<?php

namespace App\Notifications;

use App\Services\ExpoPushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class ExpoPushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $timeout = 60;
    public $tries = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $title,
        public string $body,
        public ?array $data = null,
        public ?string $sound = 'default',
        public ?int $badge = null
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['expo_push'];
    }

    /**
     * Get the Expo push representation of the notification.
     */
    public function toExpoPush(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'sound' => $this->sound,
            'badge' => $this->badge,
        ];
    }

    /**
     * Send the notification via Expo push.
     */
    public function sendExpoPush(object $notifiable, string $channel): void
    {
        try {
            $expoService = app(ExpoPushService::class);
            
            // Get Expo push tokens from the notifiable
            $tokens = $notifiable->routeNotificationForExpoPush();
            
            if (empty($tokens)) {
                Log::warning('No Expo push tokens found for notification', [
                    'notifiable_id' => $notifiable->id,
                    'notifiable_type' => get_class($notifiable)
                ]);
                return;
            }

            // Create notification payload
            $notification = $expoService->createNotification(
                $this->title,
                $this->body,
                $this->data,
                $this->sound,
                $this->badge
            );

            // Send notification
            $result = $expoService->sendToDevices($tokens, $notification);

            if ($result['success']) {
                Log::info('Expo push notification sent successfully', [
                    'notifiable_id' => $notifiable->id,
                    'tokens_count' => count($tokens),
                    'successful' => $result['data']['total_sent'] ?? 0,
                    'failed' => $result['data']['total_failed'] ?? 0
                ]);

                // Mark tokens as used
                $this->markTokensAsUsed($notifiable, $result['data']['successful'] ?? []);
            } else {
                Log::error('Expo push notification failed', [
                    'notifiable_id' => $notifiable->id,
                    'error' => $result['message'] ?? 'Unknown error'
                ]);
            }

        } catch (Exception $e) {
            Log::error('Expo push notification exception', [
                'notifiable_id' => $notifiable->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Mark successfully used tokens as used
     */
    private function markTokensAsUsed($notifiable, array $successfulTokens): void
    {
        if (empty($successfulTokens)) {
            return;
        }

        $notifiable->expoPushTokens()
            ->whereIn('token', $successfulTokens)
            ->update(['last_used_at' => now()]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'sound' => $this->sound,
            'badge' => $this->badge,
        ];
    }
}
