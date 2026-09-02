<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Achievement>
 */
class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['First Workout', '10 Hari Beruntun', '100 Jam Latihan', 'Early Bird', 'Night Owl', 'Marathon Runner', 'Streak Champion', 'Fitness Milestone']),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['trophy', 'fire', 'star', 'bolt', 'medal', 'crown']),
            'points' => fake()->randomElement([50, 100, 150, 250, 500]),
            'badge_color' => fake()->randomElement(['#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#0ea5e9']),
        ];
    }
}
