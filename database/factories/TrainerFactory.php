<?php

namespace Database\Factories;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trainer>
 */
class TrainerFactory extends Factory
{
    protected $model = Trainer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialization' => fake()->randomElement(['Bodybuilding', 'Strength Training', 'Cardio', 'Yoga', 'HIIT', 'Physiotherapy', 'Nutrition']),
            'bio' => fake()->paragraph(2),
            'experience_years' => fake()->numberBetween(1, 15),
            'hourly_rate' => fake()->randomElement([50000, 75000, 100000, 150000]),
            'is_available' => true,
        ];
    }
}
