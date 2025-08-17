<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_type' => 'individual',
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a company user.
     */
    public function company(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_type' => 'company',
            'full_name' => null,
            'company_name' => fake()->company(),
            'contact_person' => fake()->name(),
            'vat_number' => fake()->numerify('VAT#######'),
            'cr_number' => fake()->numerify('CR#######'),
            'cr_document_path' => null,
            'vat_document_path' => null,
        ]);
    }

    /**
     * Create an individual user.
     */
    public function individual(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_type' => 'individual',
            'full_name' => fake()->name(),
            'company_name' => null,
            'contact_person' => null,
            'vat_number' => null,
            'cr_number' => null,
            'cr_document_path' => null,
            'vat_document_path' => null,
        ]);
    }
}
