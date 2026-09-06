<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\MemberCard;
use App\Models\Notification;
use App\Services\NotificationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('memberships:notify-expiring', function (NotificationService $notifications) {
    $expiryDate = today()->addDays(7);
    $sent = 0;

    MemberCard::query()
        ->with(['user', 'membership'])
        ->where('status', 'active')
        ->whereDate('end_date', $expiryDate)
        ->each(function (MemberCard $card) use ($notifications, $expiryDate, &$sent): void {
            $body = sprintf(
                'Membership %s akan berakhir pada %s. Silakan lakukan perpanjangan agar tetap dapat menggunakan fasilitas gym.',
                $card->membership?->name ?? 'Anda',
                $expiryDate->format('d/m/Y'),
            );

            $alreadySent = Notification::query()
                ->where('user_id', $card->user_id)
                ->where('type', 'membership_expiring')
                ->where('body', $body)
                ->exists();

            if (! $alreadySent) {
                $notifications->send(
                    $card->user_id,
                    'membership_expiring',
                    'Membership Segera Berakhir',
                    $body,
                );
                $sent++;
            }
        });

    $this->info("{$sent} notifikasi membership dikirim.");
})->purpose('Kirim notifikasi tujuh hari sebelum membership berakhir');
