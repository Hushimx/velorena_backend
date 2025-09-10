<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AvailabilitySlot;

class AvailabilitySlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availabilitySlots = [
            // Monday
            [
                'day_of_week' => 'monday',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 30,
                'is_active' => true,
                'notes' => 'Regular business hours for Monday',
            ],
            // Tuesday
            [
                'day_of_week' => 'tuesday',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 30,
                'is_active' => true,
                'notes' => 'Regular business hours for Tuesday',
            ],
            // Wednesday
            [
                'day_of_week' => 'wednesday',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 30,
                'is_active' => true,
                'notes' => 'Regular business hours for Wednesday',
            ],
            // Thursday
            [
                'day_of_week' => 'thursday',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 30,
                'is_active' => true,
                'notes' => 'Regular business hours for Thursday',
            ],
            // Friday
            [
                'day_of_week' => 'friday',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 30,
                'is_active' => true,
                'notes' => 'Regular business hours for Friday',
            ],
            // Saturday (shorter hours)
            [
                'day_of_week' => 'saturday',
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'slot_duration_minutes' => 30,
                'is_active' => true,
                'notes' => 'Weekend hours for Saturday',
            ],
            // Sunday (closed)
            [
                'day_of_week' => 'sunday',
                'start_time' => '00:00:00',
                'end_time' => '00:00:00',
                'slot_duration_minutes' => 30,
                'is_active' => false,
                'notes' => 'Closed on Sunday',
            ],
        ];

        foreach ($availabilitySlots as $slot) {
            AvailabilitySlot::create($slot);
        }
    }
}