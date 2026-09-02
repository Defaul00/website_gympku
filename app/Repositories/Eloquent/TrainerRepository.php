<?php

namespace App\Repositories\Eloquent;

use App\Models\Trainer;
use App\Repositories\Contracts\TrainerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TrainerRepository extends BaseRepository implements TrainerRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Trainer);
    }

    public function available(): Collection
    {
        return $this->newQuery()->where('is_available', true)->with('user')->get();
    }

    public function paginateWithUser(string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with('user', 'bookings');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('specialization', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
