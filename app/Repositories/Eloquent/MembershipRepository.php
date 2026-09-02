<?php

namespace App\Repositories\Eloquent;

use App\Models\Membership;
use App\Repositories\Contracts\MembershipRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MembershipRepository extends BaseRepository implements MembershipRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Membership);
    }

    public function active(): Collection
    {
        return $this->newQuery()->where('is_active', true)->get();
    }

    public function paginateWithCounts(string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->withCount('memberCards');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
