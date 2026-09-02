<?php

namespace App\Repositories\Eloquent;

use App\Models\Attendance;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttendanceRepository extends BaseRepository implements AttendanceRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Attendance);
    }

    public function paginateWithRelations(string $search = null, ?string $date = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with('user', 'memberCard.membership');

        if ($search) {
            $query->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
        }

        if ($date) {
            $query->whereDate('check_in', $date);
        }

        return $query->latest('check_in')->paginate($perPage)->withQueryString();
    }
}
