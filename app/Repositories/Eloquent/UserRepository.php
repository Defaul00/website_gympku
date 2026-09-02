<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new User);
    }

    public function paginateMembers(string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'member'))
            ->with('memberCards.membership', 'role');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function members(): Collection
    {
        return User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'member'))
            ->with('memberCards.membership')
            ->get();
    }

    public function trainers(): Collection
    {
        return User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'trainer'))
            ->with('trainerProfile')
            ->get();
    }

    public function countByRole(string $role): int
    {
        return User::query()
            ->whereHas('role', fn ($q) => $q->where('name', $role))
            ->count();
    }
}
