<?php

namespace App\Repositories\Eloquent;

use App\Models\Achievement;
use App\Repositories\Contracts\AchievementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AchievementRepository extends BaseRepository implements AchievementRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Achievement);
    }

    public function paginateWithCounts(string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->withCount('users');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
