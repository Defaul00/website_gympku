<?php

namespace Database\Factories;

use App\Models\MemberCard;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'member_card_id' => MemberCard::factory(),
            'amount' => fake()->randomElement([150000, 425000, 800000, 1600000]),
            'method' => fake()->randomElement(['transfer', 'qris', 'cash', 'card']),
            'status' => fake()->randomElement(['paid', 'paid', 'paid', 'pending', 'failed']),
            'reference' => 'PAY-' . strtoupper(Str::random(12)),
            'paid_at' => fake()->dateTimeBetween('-12 months', 'now'),
        ];
    }
}
