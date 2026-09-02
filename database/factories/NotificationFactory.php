<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['info', 'membership', 'payment', 'booking', 'achievement', 'warning']),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraph(2),
            'read_at' => fake()->optional(0.6)->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
