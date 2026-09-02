<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AnnouncementRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateFiltered(string $search = null, string $type = null, int $perPage = 15): LengthAwarePaginator;
}
