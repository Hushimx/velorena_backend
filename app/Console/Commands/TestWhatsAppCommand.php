<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Exception;

class TestWhatsAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test {phone} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp service by sending a message';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsappService)
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $this->info("Testing WhatsApp service...");
        $this->info("Phone: {$phone}");
        $this->info("Message: {$message}");

        // Check if service is configured
        if (!$whatsappService->isConfigured()) {
            $this->error('WhatsApp service is not properly configured!');
            $this->error('Please check your .env file for WHATSAPP_ACCESS_TOKEN and WHATSAPP_INSTANCE_ID');
            return 1;
        }

        // Validate phone number
        $formattedPhone = $whatsappService->formatPhoneNumber($phone);
        if (!$whatsappService->validatePhoneNumber($formattedPhone)) {
            $this->error("Invalid phone number format: {$phone}");
            return 1;
        }

        $this->info("Formatted phone number: {$formattedPhone}");

        try {
            $this->info('Sending message...');
            $result = $whatsappService->sendTextMessage($formattedPhone, $message);
            
            $this->info('✅ Message sent successfully!');
            $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
            
            return 0;
        } catch (Exception $e) {
            $this->error('❌ Failed to send message: ' . $e->getMessage());
            return 1;
        }
    }
}

