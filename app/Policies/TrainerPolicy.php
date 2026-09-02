<?php

namespace App\Policies;

use App\Models\Trainer;
use App\Models\User;

class TrainerPolicy
{
    use HasAdminGate;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Trainer $trainer): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Trainer $trainer): bool
    {
        return false;
    }

    public function delete(User $user, Trainer $trainer): bool
    {
        return false;
    }
}
