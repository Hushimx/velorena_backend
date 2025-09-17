<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Designer;
use Illuminate\Support\Facades\Hash;

class DesignerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 designers using the factory for testing pagination
        Designer::factory(20)->create();

        // Create a few specific designers for testing
        Designer::create([
            'name' => 'John Designer',
            'email' => 'designer@example.com',
            'password' => Hash::make('password'),
            'phone' => '+966501234567',
            'address' => 'Riyadh, Saudi Arabia',
            'bio' => 'Experienced designer with 5+ years in the industry. Specializes in modern web design and user experience.',
            'portfolio_url' => 'https://portfolio.example.com',
            'is_active' => true,
        ]);

        Designer::create([
            'name' => 'Sarah Creative',
            'email' => 'sarah@Qaads.com',
            'password' => Hash::make('password'),
            'phone' => '+966507654321',
            'address' => 'Jeddah, Saudi Arabia',
            'bio' => 'Creative designer specializing in modern web design and branding solutions.',
            'portfolio_url' => 'https://sarah-portfolio.com',
            'is_active' => true,
        ]);

        Designer::create([
            'name' => 'Ahmed Al-Rashid',
            'email' => 'ahmed@Qaads.com',
            'password' => Hash::make('password'),
            'phone' => '+966508888888',
            'address' => 'Dammam, Saudi Arabia',
            'bio' => 'UI/UX designer with expertise in mobile app design and user interface development.',
            'portfolio_url' => 'https://ahmed-designs.com',
            'is_active' => true,
        ]);
    }
}
