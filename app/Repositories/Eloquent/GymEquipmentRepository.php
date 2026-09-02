<?php

namespace App\Repositories\Eloquent;

use App\Models\GymEquipment;
use App\Repositories\Contracts\GymEquipmentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GymEquipmentRepository extends BaseRepository implements GymEquipmentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new GymEquipment);
    }

    public function paginateFiltered(string $search = null, string $category = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
