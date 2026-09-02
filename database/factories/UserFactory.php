<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        return [
            'name' => fake()->name($gender),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => '08' . fake()->numerify('##########'),
            'gender' => $gender,
            'birth_date' => fake()->dateTimeBetween('-50 years', '-17 years')->format('Y-m-d'),
            'address' => fake()->streetAddress() . ', ' . fake()->city() . ', ' . fake()->randomElement(['Riau', 'Sumatera Barat', 'Sumatera Utara', 'Jambi']),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin Physio Gym',
            'email' => 'admin@physiogym.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
    }

    public function withRole(int $roleId): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => $roleId,
        ]);
    }
}
