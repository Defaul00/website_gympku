<?php

namespace Database\Factories;

use App\Models\GymEquipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GymEquipment>
 */
class GymEquipmentFactory extends Factory
{
    protected $model = GymEquipment::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Treadmill Pro', 'Bench Press', 'Barbell 20kg', 'Dumbbell Set', 'Squat Rack', 'Leg Press', 'Elliptical', 'Spin Bike', 'Kettlebell', 'Lat Pulldown']),
            'category' => fake()->randomElement(['Cardio', 'Strength', 'Free Weights', 'Functional']),
            'condition' => fake()->randomElement(['good', 'good', 'needs_maintenance', 'poor']),
            'last_maintenance' => fake()->dateTimeBetween('-6 months', 'today')->format('Y-m-d'),
            'next_maintenance' => fake()->dateTimeBetween('today', '+3 months')->format('Y-m-d'),
        ];
    }
}
