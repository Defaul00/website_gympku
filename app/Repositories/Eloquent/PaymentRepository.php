<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Payment);
    }

    public function paginateWithRelations(string $search = null, string $status = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with('user', 'memberCard.membership');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('paid_at')->paginate($perPage)->withQueryString();
    }
}
