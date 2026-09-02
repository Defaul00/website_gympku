<?php

namespace App\Policies;

use App\Models\User;

trait HasAdminGate
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }
}
