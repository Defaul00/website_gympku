<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AttendanceRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithRelations(string $search = null, ?string $date = null, int $perPage = 15): LengthAwarePaginator;
}
