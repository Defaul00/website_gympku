<?php

namespace App\Repositories\Eloquent;

use App\Models\MemberCard;
use App\Repositories\Contracts\MemberCardRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MemberCardRepository extends BaseRepository implements MemberCardRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new MemberCard);
    }

    public function activeCards(): Collection
    {
        return $this->newQuery()
            ->where('status', 'active')
            ->whereDate('end_date', '>=', today())
            ->with('user', 'membership')
            ->get();
    }

    public function expiredCards(): Collection
    {
        return $this->newQuery()
            ->where('status', 'expired')
            ->orWhereDate('end_date', '<', today())
            ->with('user', 'membership')
            ->get();
    }

    public function expiringSoon(int $days = 7): Collection
    {
        return $this->newQuery()
            ->where('status', 'active')
            ->whereBetween('end_date', [today(), today()->addDays($days)])
            ->with('user', 'membership')
            ->get();
    }

    public function paginateWithRelations(string $search = null, string $status = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with('user', 'membership');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
