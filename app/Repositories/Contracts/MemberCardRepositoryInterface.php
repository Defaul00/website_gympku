<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MemberCardRepositoryInterface extends BaseRepositoryInterface
{
    public function activeCards(): Collection;

    public function expiredCards(): Collection;

    public function expiringSoon(int $days = 7): Collection;

    public function paginateWithRelations(string $search = null, string $status = null, int $perPage = 15): LengthAwarePaginator;
}
