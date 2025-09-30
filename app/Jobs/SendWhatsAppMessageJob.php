<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $phoneNumber,
        public string $message,
        public ?string $orderId = null,
        public ?string $customerName = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsappService): void
    {
        try {
            Log::info('Processing WhatsApp message job', [
                'phone' => $this->phoneNumber,
                'order_id' => $this->orderId,
                'customer_name' => $this->customerName
            ]);

            $result = $whatsappService->sendTextMessage($this->phoneNumber, $this->message);

            Log::info('WhatsApp message sent successfully', [
                'phone' => $this->phoneNumber,
                'order_id' => $this->orderId,
                'result' => $result
            ]);

        } catch (Exception $e) {
            Log::error('WhatsApp message job failed', [
                'phone' => $this->phoneNumber,
                'order_id' => $this->orderId,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // Re-throw the exception to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        Log::error('WhatsApp message job permanently failed', [
            'phone' => $this->phoneNumber,
            'order_id' => $this->orderId,
            'error' => $exception->getMessage()
        ]);
    }
}

