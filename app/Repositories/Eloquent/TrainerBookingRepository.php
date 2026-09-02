<?php

namespace App\Repositories\Eloquent;

use App\Models\TrainerBooking;
use App\Repositories\Contracts\TrainerBookingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TrainerBookingRepository extends BaseRepository implements TrainerBookingRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new TrainerBooking);
    }

    public function paginateWithRelations(string $search = null, string $status = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with('user', 'trainer.user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('trainer.user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('booking_date')->paginate($perPage)->withQueryString();
    }
}
