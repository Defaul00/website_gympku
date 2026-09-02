<?php

namespace App\Policies;

use App\Models\GymEquipment;
use App\Models\User;

class GymEquipmentPolicy
{
    use HasAdminGate;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, GymEquipment $equipment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, GymEquipment $equipment): bool
    {
        return false;
    }

    public function delete(User $user, GymEquipment $equipment): bool
    {
        return false;
    }
}
