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
        Designer::create([
            'name' => 'John Designer',
            'email' => 'designer@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'address' => '123 Design Street, Creative City',
            'bio' => 'A passionate designer with 5+ years of experience in UI/UX design.',
            'portfolio_url' => 'https://johndesigner.portfolio.com',
            'is_active' => true,
        ]);

        Designer::create([
            'name' => 'Sarah Creative',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1987654321',
            'address' => '456 Art Avenue, Design Town',
            'bio' => 'Graphic designer specializing in branding and visual identity.',
            'portfolio_url' => 'https://sarahcreative.design',
            'is_active' => true,
        ]);
    }
}
