<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TrainerBookingRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithRelations(string $search = null, string $status = null, int $perPage = 15): LengthAwarePaginator;
}
