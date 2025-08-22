<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Designer;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $designers = Designer::all();

        if ($users->isEmpty() || $designers->isEmpty()) {
            $this->command->info('No users or designers found. Skipping appointment seeding.');
            return;
        }

        // Create some sample appointments
        $appointments = [
            // Today's appointments (some unassigned, some assigned)
            [
                'user_id' => $users->random()->id,
                'designer_id' => null, // Unassigned
                'appointment_date' => today(),
                'appointment_time' => '10:00:00',
                'status' => 'pending',
                'notes' => 'Need consultation for website redesign project.',
            ],
            [
                'user_id' => $users->random()->id,
                'designer_id' => $designers->random()->id,
                'appointment_date' => today(),
                'appointment_time' => '14:00:00',
                'status' => 'accepted',
                'notes' => 'Logo design consultation for new startup.',
                'accepted_at' => now()->subHours(2),
            ],
            [
                'user_id' => $users->random()->id,
                'designer_id' => $designers->random()->id,
                'appointment_date' => today(),
                'appointment_time' => '16:00:00',
                'status' => 'completed',
                'notes' => 'Brand identity discussion.',
                'accepted_at' => now()->subDays(1),
                'completed_at' => now()->subHours(1),
            ],

            // Tomorrow's appointments (unassigned)
            [
                'user_id' => $users->random()->id,
                'designer_id' => null, // Unassigned
                'appointment_date' => Carbon::tomorrow(),
                'appointment_time' => '09:00:00',
                'status' => 'pending',
                'notes' => 'Mobile app UI/UX consultation.',
            ],
            [
                'user_id' => $users->random()->id,
                'designer_id' => $designers->random()->id,
                'appointment_date' => Carbon::tomorrow(),
                'appointment_time' => '11:00:00',
                'status' => 'accepted',
                'notes' => 'E-commerce website design discussion.',
                'accepted_at' => now()->subHours(1),
            ],

            // Future appointments (unassigned)
            [
                'user_id' => $users->random()->id,
                'designer_id' => null, // Unassigned
                'appointment_date' => now()->addDays(3),
                'appointment_time' => '13:00:00',
                'status' => 'pending',
                'notes' => 'Social media graphics consultation.',
            ],
            [
                'user_id' => $users->random()->id,
                'designer_id' => $designers->random()->id,
                'appointment_date' => now()->addDays(5),
                'appointment_time' => '15:00:00',
                'status' => 'accepted',
                'notes' => 'Print design consultation for marketing materials.',
                'accepted_at' => now()->subDays(1),
            ],

            // Past appointments
            [
                'user_id' => $users->random()->id,
                'designer_id' => $designers->random()->id,
                'appointment_date' => now()->subDays(2),
                'appointment_time' => '10:00:00',
                'status' => 'completed',
                'notes' => 'Website consultation completed successfully.',
                'accepted_at' => now()->subDays(3),
                'completed_at' => now()->subDays(2)->addHours(1),
            ],
            [
                'user_id' => $users->random()->id,
                'designer_id' => $designers->random()->id,
                'appointment_date' => now()->subDays(1),
                'appointment_time' => '14:00:00',
                'status' => 'rejected',
                'notes' => 'Requested consultation for unavailable service.',
                'rejected_at' => now()->subDays(1),
                'designer_notes' => 'Sorry, we don\'t provide this type of service.',
            ],
        ];

        foreach ($appointments as $appointmentData) {
            Appointment::create($appointmentData);
        }

        $this->command->info('Appointments seeded successfully!');
    }
}
