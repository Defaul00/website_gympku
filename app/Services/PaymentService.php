<?php

namespace App\Services;

use App\Models\MemberCard;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function record(
        User $user,
        ?MemberCard $memberCard,
        float $amount,
        string $method = 'transfer',
        string $status = 'paid',
    ): Payment {
        $payment = Payment::create([
            'user_id' => $user->id,
            'member_card_id' => $memberCard?->id,
            'amount' => $amount,
            'method' => $method,
            'status' => $status,
            'reference' => $this->generateReference(),
            'paid_at' => now(),
        ]);

        $this->notificationService->send(
            userId: $user->id,
            type: 'payment',
            title: 'Pembayaran Diterima',
            body: "Pembayaran sebesar Rp " . number_format($amount, 0, ',', '.') . " telah diterima ({$method}).",
        );

        return $payment;
    }

    public function generateReference(): string
    {
        return 'PAY-' . strtoupper(Str::random(3)) . '-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
    }
}
