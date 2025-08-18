<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeededUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_individual_user_can_login()
    {
        // Run the seeder
        $this->seed(UserSeeder::class);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@user.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'user' => ['id', 'client_type', 'full_name', 'email'],
                        'token'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'user' => [
                            'email' => 'user@user.com',
                            'client_type' => 'individual',
                            'full_name' => 'Regular User'
                        ]
                    ]
                ]);
    }

    public function test_seeded_company_user_can_login()
    {
        // Run the seeder
        $this->seed(UserSeeder::class);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'company@company.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'user' => ['id', 'client_type', 'company_name', 'email'],
                        'token'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'user' => [
                            'email' => 'company@company.com',
                            'client_type' => 'company',
                            'company_name' => 'Test Company Ltd'
                        ]
                    ]
                ]);
    }

    public function test_seeded_users_have_correct_data()
    {
        // Run the seeder
        $this->seed(UserSeeder::class);

        $individualUser = User::where('email', 'user@user.com')->first();
        $companyUser = User::where('email', 'company@company.com')->first();

        $this->assertNotNull($individualUser);
        $this->assertEquals('individual', $individualUser->client_type);
        $this->assertEquals('Regular User', $individualUser->full_name);
        $this->assertEquals('+1234567890', $individualUser->phone);
        $this->assertEquals('New York', $individualUser->city);

        $this->assertNotNull($companyUser);
        $this->assertEquals('company', $companyUser->client_type);
        $this->assertEquals('Test Company Ltd', $companyUser->company_name);
        $this->assertEquals('John Manager', $companyUser->contact_person);
        $this->assertEquals('VAT123456789', $companyUser->vat_number);
        $this->assertEquals('CR987654321', $companyUser->cr_number);
    }
}
