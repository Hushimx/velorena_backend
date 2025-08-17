<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $userData = [
            'client_type' => 'individual',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'user' => ['id', 'client_type', 'full_name', 'email', 'phone'],
                        'token',
                        'otp_sent'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'full_name' => 'John Doe',
        ]);
    }

    public function test_user_can_register_as_company()
    {
        $userData = [
            'client_type' => 'company',
            'company_name' => 'Test Company',
            'contact_person' => 'Jane Smith',
            'email' => 'jane@company.com',
            'phone' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'user' => ['id', 'client_type', 'company_name', 'email', 'phone'],
                        'token',
                        'otp_sent'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@company.com',
            'company_name' => 'Test Company',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'user' => ['id', 'email'],
                        'token'
                    ]
                ]);
    }

    public function test_otp_can_be_sent()
    {
        $response = $this->postJson('/api/auth/send-otp', [
            'identifier' => 'test@example.com',
            'type' => 'email',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'otp_id',
                        'expires_at',
                        'type'
                    ]
                ]);

        $this->assertDatabaseHas('otps', [
            'identifier' => 'test@example.com',
            'type' => 'email',
        ]);
    }

    public function test_otp_can_be_verified()
    {
        // Create an OTP
        $otp = Otp::createOtp('test@example.com', 'email', 10);

        $response = $this->postJson('/api/auth/verify-otp', [
            'identifier' => 'test@example.com',
            'code' => $otp->code,
            'type' => 'email',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'verified_at',
                        'otp_id'
                    ]
                ]);
    }

    public function test_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'user' => ['id', 'email']
                    ]
                ]);
    }
}
