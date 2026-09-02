<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TrainerRepositoryInterface extends BaseRepositoryInterface
{
    public function available(): Collection;

    public function paginateWithUser(string $search = null, int $perPage = 15): LengthAwarePaginator;
}
