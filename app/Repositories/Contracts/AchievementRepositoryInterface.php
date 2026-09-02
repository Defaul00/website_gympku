<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AchievementRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithCounts(string $search = null, int $perPage = 15): LengthAwarePaginator;
}
