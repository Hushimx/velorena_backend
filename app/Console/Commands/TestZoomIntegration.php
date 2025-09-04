<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ZoomService;
use App\Models\Appointment;

class TestZoomIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoom:test {--appointment-id= : Test with specific appointment ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Zoom integration and meeting creation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Zoom Integration...');
        
        $zoomService = app(ZoomService::class);
        
        // Test configuration
        $this->info('1. Testing Zoom configuration...');
        if (!$zoomService->isConfigured()) {
            $this->error('❌ Zoom is not properly configured. Please check your .env file for:');
            $this->line('   - ZOOM_CLIENT_ID');
            $this->line('   - ZOOM_CLIENT_SECRET');
            $this->line('   - ZOOM_ACCOUNT_ID');
            return 1;
        }
        $this->info('✅ Zoom configuration is valid');
        
        // Test connection
        $this->info('2. Testing Zoom API connection...');
        if (!$zoomService->testConnection()) {
            $this->error('❌ Failed to connect to Zoom API. Please check your credentials.');
            return 1;
        }
        $this->info('✅ Zoom API connection successful');
        
        // Test with appointment if provided
        $appointmentId = $this->option('appointment-id');
        if ($appointmentId) {
            $this->info("3. Testing with appointment ID: {$appointmentId}");
            
            $appointment = Appointment::find($appointmentId);
            if (!$appointment) {
                $this->error('❌ Appointment not found');
                return 1;
            }
            
            if (!$appointment->designer_id) {
                $this->error('❌ Appointment must have a designer assigned');
                return 1;
            }
            
            if ($appointment->hasZoomMeeting()) {
                $this->info('✅ Appointment already has a Zoom meeting');
                $this->line("   Meeting ID: {$appointment->zoom_meeting_id}");
                $this->line("   Join URL: {$appointment->zoom_meeting_url}");
                $this->line("   Start URL: {$appointment->zoom_start_url}");
            } else {
                $this->info('Creating Zoom meeting for appointment...');
                $success = $appointment->createZoomMeeting();
                
                if ($success) {
                    $appointment->refresh();
                    $this->info('✅ Zoom meeting created successfully');
                    $this->line("   Meeting ID: {$appointment->zoom_meeting_id}");
                    $this->line("   Join URL: {$appointment->zoom_meeting_url}");
                    $this->line("   Start URL: {$appointment->zoom_start_url}");
                } else {
                    $this->error('❌ Failed to create Zoom meeting');
                    return 1;
                }
            }
        } else {
            $this->info('3. Skipping appointment test (use --appointment-id to test with specific appointment)');
        }
        
        $this->info('🎉 Zoom integration test completed successfully!');
        return 0;
    }
}