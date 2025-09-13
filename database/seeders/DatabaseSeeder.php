<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DesignerSeeder;
use Database\Seeders\MarketerSeeder;
use Database\Seeders\LeadSeeder;
use Database\Seeders\SupportTicketSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(50)->create();

        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            DesignerSeeder::class,
            MarketerSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            AppointmentSeeder::class,
            LeadSeeder::class,
            OrderSeeder::class,
            SupportTicketSeeder::class,
        ]);
    }
}
