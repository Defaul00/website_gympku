<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function find(int $id): ?Model;

    public function findOrFail(int $id): Model;

    public function create(array $attributes): Model;

    public function update(Model $model, array $attributes): bool;

    public function updateWhere(array $criteria, array $attributes): int;

    public function delete(Model $model): bool;

    public function deleteWhere(array $criteria): int;

    public function findBy(array $criteria, array $columns = ['*']): Collection;

    public function firstBy(array $criteria): ?Model;

    public function search(string $term, array $columns, array $with = [], int $perPage = 15): LengthAwarePaginator;
}
