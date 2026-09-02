<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface GymEquipmentRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateFiltered(string $search = null, string $category = null, int $perPage = 15): LengthAwarePaginator;
}
