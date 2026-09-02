<?php

namespace App\Policies;

use App\Models\TrainerBooking;
use App\Models\User;

class TrainerBookingPolicy
{
    use HasAdminGate;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TrainerBooking $booking): bool
    {
        return $user->id === $booking->user_id || $booking->trainer?->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isMember();
    }

    public function update(User $user, TrainerBooking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    public function delete(User $user, TrainerBooking $booking): bool
    {
        return $user->id === $booking->user_id;
    }
}
