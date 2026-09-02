<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MembershipRepositoryInterface extends BaseRepositoryInterface
{
    public function active(): \Illuminate\Database\Eloquent\Collection;

    public function paginateWithCounts(string $search = null, int $perPage = 15): LengthAwarePaginator;
}
