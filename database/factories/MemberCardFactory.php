<?php

namespace Database\Factories;

use App\Models\MemberCard;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MemberCard>
 */
class MemberCardFactory extends Factory
{
    protected $model = MemberCard::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-12 months', 'today');
        $duration = fake()->randomElement([1, 3, 6, 12]);

        return [
            'user_id' => User::factory(),
            'membership_id' => Membership::factory(),
            'card_number' => 'PG-' . strtoupper(Str::random(12)),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => (clone $start)->modify("+{$duration} months")->format('Y-m-d'),
            'status' => 'active',
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'end_date' => fake()->dateTimeBetween('-11 months', '-1 day')->format('Y-m-d'),
        ]);
    }
}
