<?php

namespace Database\Factories;

use App\Models\Trainer;
use App\Models\TrainerBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainerBooking>
 */
class TrainerBookingFactory extends Factory
{
    protected $model = TrainerBooking::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-12 months', '+2 months');
        $hour = fake()->numberBetween(7, 19);

        return [
            'user_id' => User::factory(),
            'trainer_id' => Trainer::factory(),
            'booking_date' => $start->format('Y-m-d'),
            'start_time' => sprintf('%02d:00:00', $hour),
            'end_time' => sprintf('%02d:00:00', $hour + 1),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
