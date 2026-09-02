<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateMembers(string $search = null, int $perPage = 15): LengthAwarePaginator;

    public function members(): Collection;

    public function trainers(): Collection;

    public function countByRole(string $role): int;
}
