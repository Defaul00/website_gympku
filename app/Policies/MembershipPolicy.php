<?php

namespace App\Policies;

use App\Models\Membership;
use App\Models\User;

class MembershipPolicy
{
    use HasAdminGate;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Membership $membership): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isMember();
    }

    public function update(User $user, Membership $membership): bool
    {
        return false;
    }

    public function delete(User $user, Membership $membership): bool
    {
        return false;
    }
}
