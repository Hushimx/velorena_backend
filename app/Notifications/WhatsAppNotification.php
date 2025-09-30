<?php

namespace App\Notifications;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $timeout = 60;
    public $tries = 3;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $message,
        public ?string $orderId = null
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
        return ['whatsapp'];
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'order_id' => $this->orderId,
        ];
    }

    /**
     * Send the notification via WhatsApp.
     */
    public function sendWhatsApp(object $notifiable, string $channel): void
    {
        try {
            $whatsappService = app(WhatsAppService::class);
            
            // Get the phone number from the notifiable
            $phoneNumber = $notifiable->phone ?? $notifiable->mobile;
            
            if (!$phoneNumber) {
                Log::warning('No phone number found for WhatsApp notification', [
                    'notifiable_id' => $notifiable->id,
                    'notifiable_type' => get_class($notifiable)
                ]);
                return;
            }

            $formattedPhone = $whatsappService->formatPhoneNumber($phoneNumber);
            
            if (!$whatsappService->validatePhoneNumber($formattedPhone)) {
                Log::warning('Invalid phone number for WhatsApp notification', [
                    'phone' => $phoneNumber,
                    'formatted' => $formattedPhone,
                    'notifiable_id' => $notifiable->id
                ]);
                return;
            }

            $result = $whatsappService->sendTextMessage($formattedPhone, $this->message);

            Log::info('WhatsApp notification sent successfully', [
                'notifiable_id' => $notifiable->id,
                'phone' => $formattedPhone,
                'order_id' => $this->orderId,
                'result' => $result
            ]);

        } catch (Exception $e) {
            Log::error('WhatsApp notification failed', [
                'notifiable_id' => $notifiable->id,
                'phone' => $phoneNumber ?? 'unknown',
                'order_id' => $this->orderId,
                'error' => $e->getMessage()
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }
}

