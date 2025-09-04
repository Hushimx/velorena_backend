<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ZoomService
{
    private string $clientId;
    private string $clientSecret;
    private string $accountId;
    private string $baseUrl = 'https://api.zoom.us/v2';
    private string $authUrl = 'https://zoom.us/oauth/token';

    public function __construct()
    {
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->accountId = config('services.zoom.account_id');
    }

    /**
     * Get access token for Zoom API
     */
    public function getAccessToken(): string
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post($this->authUrl, [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'];
            }

            throw new \Exception('Failed to get Zoom access token: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Zoom access token error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a Zoom meeting
     */
    public function createMeeting(array $meetingData): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/users/me/meetings', $meetingData);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to create Zoom meeting: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Zoom meeting creation error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a meeting for an appointment
     */
    public function createAppointmentMeeting($appointment): array
    {
        $meetingData = [
            'topic' => $this->generateMeetingTopic($appointment),
            'type' => 2, // Scheduled meeting
            'start_time' => $this->formatDateTimeForZoom($appointment->appointment_date, $appointment->appointment_time),
            'duration' => $appointment->duration_minutes,
            'timezone' => config('app.timezone', 'UTC'),
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
                'mute_upon_entry' => false,
                'waiting_room' => true,
                'auto_recording' => 'none',
                'approval_type' => 0, // Automatically approve
            ],
            'agenda' => $this->generateMeetingAgenda($appointment),
        ];

        return $this->createMeeting($meetingData);
    }

    /**
     * Update a Zoom meeting
     */
    public function updateMeeting(string $meetingId, array $meetingData): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->patch($this->baseUrl . '/meetings/' . $meetingId, $meetingData);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to update Zoom meeting: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Zoom meeting update error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a Zoom meeting
     */
    public function deleteMeeting(string $meetingId): bool
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->delete($this->baseUrl . '/meetings/' . $meetingId);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Zoom meeting deletion error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get meeting details
     */
    public function getMeeting(string $meetingId): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($this->baseUrl . '/meetings/' . $meetingId);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get Zoom meeting: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Zoom meeting retrieval error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate meeting topic based on appointment
     */
    private function generateMeetingTopic($appointment): string
    {
        $designerName = $appointment->designer ? $appointment->designer->name : 'Designer';
        $userName = $appointment->user ? $appointment->user->name : 'Client';
        
        return "Design Consultation - {$designerName} & {$userName}";
    }

    /**
     * Generate meeting agenda based on appointment
     */
    private function generateMeetingAgenda($appointment): string
    {
        $agenda = "Design consultation appointment\n\n";
        
        if ($appointment->notes) {
            $agenda .= "Client Notes: {$appointment->notes}\n\n";
        }
        
        if ($appointment->designer_notes) {
            $agenda .= "Designer Notes: {$appointment->designer_notes}\n\n";
        }
        
        if ($appointment->hasOrder()) {
            $agenda .= "Related Order: #{$appointment->order_id}\n";
            $agenda .= "Order Value: $" . number_format($appointment->getTotalOrderValue(), 2) . "\n";
            $agenda .= "Products: {$appointment->getTotalProductsCount()} items\n\n";
        }
        
        $agenda .= "Appointment Date: {$appointment->formatted_date}\n";
        $agenda .= "Time: {$appointment->formatted_time} - {$appointment->formatted_end_time}\n";
        $agenda .= "Duration: {$appointment->duration_minutes} minutes";
        
        return $agenda;
    }

    /**
     * Format date and time for Zoom API
     */
    private function formatDateTimeForZoom($date, $time): string
    {
        try {
            // Handle different input formats
            if ($date instanceof \DateTime) {
                $dateString = $date->format('Y-m-d');
            } else {
                // If it's a string, extract just the date part
                $dateString = Carbon::parse($date)->format('Y-m-d');
            }
            
            if ($time instanceof \DateTime) {
                $timeString = $time->format('H:i:s');
            } else {
                // If it's a string, extract just the time part
                $timeString = Carbon::parse($time)->format('H:i:s');
            }
            
            // Create a proper datetime by combining date and time
            $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ' ' . $timeString);
            return $dateTime->format('Y-m-d\TH:i:s\Z');
            
        } catch (\Exception $e) {
            // Fallback: try to parse as a single datetime string
            try {
                $dateTime = Carbon::parse($date);
                return $dateTime->format('Y-m-d\TH:i:s\Z');
            } catch (\Exception $e2) {
                // Last resort: use current time
                Log::error('Failed to parse appointment datetime for Zoom: ' . $e2->getMessage(), [
                    'date' => $date,
                    'time' => $time
                ]);
                return now()->format('Y-m-d\TH:i:s\Z');
            }
        }
    }

    /**
     * Check if Zoom is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->clientId) && !empty($this->clientSecret) && !empty($this->accountId);
    }

    /**
     * Test Zoom API connection
     */
    public function testConnection(): bool
    {
        try {
            $this->getAccessToken();
            return true;
        } catch (\Exception $e) {
            Log::error('Zoom connection test failed: ' . $e->getMessage());
            return false;
        }
    }
}
