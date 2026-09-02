<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;

class AttendanceService
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function checkIn(User $user): Attendance
    {
        $card = $user->activeMemberCard();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'member_card_id' => $card?->id,
            'check_in' => now(),
        ]);

        if ($card === null) {
            $this->notificationService->send(
                userId: $user->id,
                type: 'warning',
                title: 'Membership Tidak Aktif',
                body: 'Check-in tercatat tanpa membership aktif. Segera perpanjang membership Anda.',
            );
        }

        return $attendance;
    }

    public function checkOut(User $user): ?Attendance
    {
        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->whereNull('check_out')
            ->latest('check_in')
            ->first();

        if ($attendance === null) {
            return null;
        }

        $duration = now()->diffInMinutes($attendance->check_in) ?: 1;

        $attendance->update([
            'check_out' => now(),
            'duration_minutes' => (int) $duration,
        ]);

        return $attendance;
    }

    public function isCurrentlyCheckedIn(User $user): bool
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereNull('check_out')
            ->exists();
    }
}
