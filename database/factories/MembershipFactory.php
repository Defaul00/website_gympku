<?php

namespace Database\Factories;

use App\Models\Membership;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Membership>
 */
class MembershipFactory extends Factory
{
    protected $model = Membership::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['1 Bulan', '3 Bulan', '6 Bulan', '12 Bulan']),
            'duration_months' => fake()->randomElement([1, 3, 6, 12]),
            'price' => fake()->randomElement([150000, 425000, 800000, 1600000]),
            'description' => fake()->sentence(),
            'features' => [
                'Akses semua fasilitas',
                'Personal training',
                'Group classes',
            ],
            'is_active' => true,
        ];
    }
}
