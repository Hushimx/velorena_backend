<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create individual user
        User::updateOrCreate(
            ['email' => 'user@user.com'],
            [
                'client_type' => 'individual',
                'full_name' => 'Regular User',
                'email' => 'user@user.com',
                'phone' => '+1234567890',
                'address' => '123 Main Street',
                'city' => 'New York',
                'country' => 'USA',
                'password' => Hash::make('password123'),
            ]
        );

        // Create company user
        User::updateOrCreate(
            ['email' => 'company@company.com'],
            [
                'client_type' => 'company',
                'company_name' => 'Test Company Ltd',
                'contact_person' => 'John Manager',
                'email' => 'company@company.com',
                'phone' => '+1234567891',
                'address' => '456 Business Ave',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'vat_number' => 'VAT123456789',
                'cr_number' => 'CR987654321',
                'password' => Hash::make('password123'),
            ]
        );

        // Create additional test users
        User::factory()->count(5)->individual()->create();
        User::factory()->count(3)->company()->create();
    }
}
