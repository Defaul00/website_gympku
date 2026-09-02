<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'body' => fake()->paragraph(),
            'type' => fake()->randomElement(['info', 'promo', 'maintenance', 'event']),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
