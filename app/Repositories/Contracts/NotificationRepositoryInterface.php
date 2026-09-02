<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateForUser(int $userId, string $search = null, int $perPage = 15): LengthAwarePaginator;

    public function unreadCountForUser(int $userId): int;

    public function markAllReadForUser(int $userId): int;
}
