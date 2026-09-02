<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    protected function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->newQuery()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->newQuery()->paginate($perPage, $columns);
    }

    public function find(int $id): ?Model
    {
        return $this->newQuery()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->newQuery()->findOrFail($id);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(Model $model, array $attributes): bool
    {
        return $model->update($attributes);
    }

    public function updateWhere(array $criteria, array $attributes): int
    {
        return $this->newQuery()->where($criteria)->update($attributes);
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    public function deleteWhere(array $criteria): int
    {
        return $this->newQuery()->where($criteria)->delete();
    }

    public function findBy(array $criteria, array $columns = ['*']): Collection
    {
        return $this->newQuery()->where($criteria)->get($columns);
    }

    public function firstBy(array $criteria): ?Model
    {
        return $this->newQuery()->where($criteria)->first();
    }

    public function search(string $term, array $columns, array $with = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()->with($with);

        foreach ($columns as $index => $column) {
            if ($index === 0) {
                $query->where($column, 'like', "%{$term}%");
            } else {
                $query->orWhere($column, 'like', "%{$term}%");
            }
        }

        return $query->paginate($perPage);
    }
}
