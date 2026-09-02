<?php

namespace App\Services;

use App\Models\MemberCard;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembershipService
{
    public function __construct(
        private PaymentService $paymentService,
        private NotificationService $notificationService,
    ) {
    }

    public function activate(User $user, Membership $membership, string $paymentMethod = 'transfer'): MemberCard
    {
        return DB::transaction(function () use ($user, $membership, $paymentMethod) {
            $card = MemberCard::create([
                'user_id' => $user->id,
                'membership_id' => $membership->id,
                'card_number' => $this->generateCardNumber(),
                'start_date' => today(),
                'end_date' => today()->addMonths($membership->duration_months),
                'status' => 'active',
            ]);

            $this->paymentService->record(
                user: $user,
                memberCard: $card,
                amount: (float) $membership->price,
                method: $paymentMethod,
            );

            $this->notificationService->send(
                userId: $user->id,
                type: 'membership',
                title: 'Membership Aktif',
                body: "Membership {$membership->name} Anda aktif hingga " . $card->end_date->translatedFormat('d F Y') . '.',
            );

            return $card;
        });
    }

    public function renew(MemberCard $card, string $paymentMethod = 'transfer'): MemberCard
    {
        return DB::transaction(function () use ($card, $paymentMethod) {
            $base = $card->end_date->isFuture() ? $card->end_date : today();

            $newCard = MemberCard::create([
                'user_id' => $card->user_id,
                'membership_id' => $card->membership_id,
                'card_number' => $this->generateCardNumber(),
                'start_date' => $base,
                'end_date' => $base->addMonths($card->membership->duration_months),
                'status' => 'active',
            ]);

            $this->paymentService->record(
                user: $card->user,
                memberCard: $newCard,
                amount: (float) $card->membership->price,
                method: $paymentMethod,
            );

            $card->update(['status' => 'expired']);

            $this->notificationService->send(
                userId: $card->user_id,
                type: 'membership',
                title: 'Membership Diperpanjang',
                body: "Membership Anda diperpanjang hingga {$newCard->end_date->translatedFormat('d F Y')}.",
            );

            return $newCard;
        });
    }

    public function deactivate(User $user): int
    {
        return DB::transaction(function () use ($user) {
            $count = MemberCard::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            if ($count > 0) {
                $this->notificationService->send(
                    userId: $user->id,
                    type: 'membership',
                    title: 'Membership Dinonaktifkan',
                    body: 'Membership Anda telah dinonaktifkan oleh admin.',
                );
            }

            return $count;
        });
    }

    public function syncExpired(): int
    {
        $count = MemberCard::where('status', 'active')
            ->whereDate('end_date', '<', today())
            ->update(['status' => 'expired']);

        if ($count > 0) {
            $this->notificationService->send(
                userId: null,
                type: 'system',
                title: 'Sinkronisasi Membership',
                body: "{$count} membership telah ditandai expired secara otomatis.",
            );
        }

        return $count;
    }

    public function generateCardNumber(): string
    {
        do {
            $number = 'PG-' . strtoupper(Str::random(4)) . '-' . now()->format('ymd') . '-' . Str::upper(Str::random(4));
        } while (MemberCard::where('card_number', $number)->exists());

        return $number;
    }

    public function payment(): Payment
    {
        return new Payment;
    }
}
