<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AvailabilitySlot;

class AvailabilitySlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating general availability slots for all 7 days');

        // Default availability schedule (All 7 days, 8 AM to 4 PM, 30-minute slots)
        $defaultSchedule = [
            'monday' => ['08:00', '16:00'],
            'tuesday' => ['08:00', '16:00'],
            'wednesday' => ['08:00', '16:00'],
            'thursday' => ['08:00', '16:00'],
            'friday' => ['08:00', '16:00'],
            'saturday' => ['08:00', '16:00'],
            'sunday' => ['08:00', '16:00'],
        ];

        foreach ($defaultSchedule as $dayOfWeek => $times) {
            AvailabilitySlot::updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                [
                    'start_time' => $times[0],
                    'end_time' => $times[1],
                    'slot_duration_minutes' => 30,
                    'is_active' => true,
                    'notes' => 'General working hours - 30 minute slots',
                ]
            );
            
            $this->command->info("Created availability for {$dayOfWeek}: {$times[0]} - {$times[1]} (30 min slots)");
        }

        $this->command->info('General availability slots created successfully for all 7 days.');
    }
}