<?php

namespace App\Policies;

use App\Models\Achievement;
use App\Models\User;

class AchievementPolicy
{
    use HasAdminGate;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Achievement $achievement): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Achievement $achievement): bool
    {
        return false;
    }

    public function delete(User $user, Achievement $achievement): bool
    {
        return false;
    }
}
