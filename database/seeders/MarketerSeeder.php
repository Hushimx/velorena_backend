<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marketer;
use Illuminate\Support\Facades\Hash;

class MarketerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketers = [
            [
                'name' => 'أحمد المسوق',
                'email' => 'ahmed@marketer.com',
                'password' => Hash::make('password'),
                'phone' => '+966501234567',
                'is_active' => true,
            ],
            [
                'name' => 'فاطمة المسوقة',
                'email' => 'fatima@marketer.com',
                'password' => Hash::make('password'),
                'phone' => '+966501234568',
                'is_active' => true,
            ],
            [
                'name' => 'محمد المسوق',
                'email' => 'mohammed@marketer.com',
                'password' => Hash::make('password'),
                'phone' => '+966501234569',
                'is_active' => true,
            ],
        ];

        foreach ($marketers as $marketer) {
            Marketer::updateOrCreate(
                ['email' => $marketer['email']],
                $marketer
            );
        }
    }
}
