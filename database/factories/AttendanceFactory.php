<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\MemberCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('-12 months', 'now');
        $duration = fake()->numberBetween(30, 150);

        return [
            'user_id' => User::factory(),
            'member_card_id' => MemberCard::factory(),
            'check_in' => $checkIn,
            'check_out' => (clone $checkIn)->modify("+{$duration} minutes"),
            'duration_minutes' => $duration,
        ];
    }
}
