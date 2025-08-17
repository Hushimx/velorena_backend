<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Send OTP to the specified identifier
     */
    public function sendOtp(string $identifier, string $type = 'email', int $expiryMinutes = 10): array
    {
        try {
            // Create OTP record
            $otp = Otp::createOtp($identifier, $type, $expiryMinutes);

            // Send OTP based on type
            switch ($type) {
                case 'email':
                    $this->sendEmailOtp($identifier, $otp->code);
                    break;
                case 'sms':
                    $this->sendSmsOtp($identifier, $otp->code);
                    break;
                case 'whatsapp':
                    $this->sendWhatsAppOtp($identifier, $otp->code);
                    break;
                case 'fake':
                    // For fake OTP, we just log it
                    Log::info("Fake OTP sent to {$identifier}: {$otp->code}");
                    break;
                default:
                    throw new \InvalidArgumentException("Unsupported OTP type: {$type}");
            }

            return [
                'success' => true,
                'message' => "OTP sent successfully via {$type}",
                'otp_id' => $otp->id,
                'expires_at' => $otp->expires_at,
            ];

        } catch (\Exception $e) {
            Log::error("Failed to send OTP: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $identifier, string $code, string $type = 'email'): array
    {
        try {
            $otp = Otp::verify($identifier, $code, $type);

            if ($otp) {
                return [
                    'success' => true,
                    'message' => 'OTP verified successfully',
                    'otp' => $otp,
                ];
            }

            return [
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ];

        } catch (\Exception $e) {
            Log::error("Failed to verify OTP: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to verify OTP',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send OTP via email
     */
    private function sendEmailOtp(string $email, string $code): void
    {
        // For now, we'll just log the email OTP
        // In production, you would integrate with a real email service
        Log::info("Email OTP sent to {$email}: {$code}");
        
        // Example of how to send real email (uncomment when email service is configured)
        /*
        Mail::send('emails.otp', ['code' => $code], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your OTP Code');
        });
        */
    }

    /**
     * Send OTP via SMS
     */
    private function sendSmsOtp(string $phone, string $code): void
    {
        // For now, we'll just log the SMS OTP
        // In production, you would integrate with SMS service like Twilio
        Log::info("SMS OTP sent to {$phone}: {$code}");
        
        // Example of how to send real SMS (uncomment when SMS service is configured)
        /*
        // Twilio example
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $twilio->messages->create(
            $phone,
            [
                'from' => env('TWILIO_PHONE'),
                'body' => "Your OTP code is: {$code}"
            ]
        );
        */
    }

    /**
     * Send OTP via WhatsApp
     */
    private function sendWhatsAppOtp(string $phone, string $code): void
    {
        // For now, we'll just log the WhatsApp OTP
        // In production, you would integrate with WhatsApp Business API
        Log::info("WhatsApp OTP sent to {$phone}: {$code}");
        
        // Example of how to send real WhatsApp message (uncomment when WhatsApp service is configured)
        /*
        // WhatsApp Business API example
        $whatsapp = new WhatsAppService();
        $whatsapp->sendMessage($phone, "Your OTP code is: {$code}");
        */
    }

    /**
     * Resend OTP
     */
    public function resendOtp(string $identifier, string $type = 'email'): array
    {
        // Invalidate existing unused OTPs
        Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Send new OTP
        return $this->sendOtp($identifier, $type);
    }
}
