<?php

namespace App\Policies;

use App\Models\MemberCard;
use App\Models\User;

class MemberCardPolicy
{
    use HasAdminGate;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MemberCard $card): bool
    {
        return $user->id === $card->user_id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, MemberCard $card): bool
    {
        return false;
    }

    public function delete(User $user, MemberCard $card): bool
    {
        return false;
    }
}
