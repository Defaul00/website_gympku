<?php

namespace App\Repositories\Eloquent;

use App\Models\Announcement;
use App\Repositories\Contracts\AnnouncementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AnnouncementRepository extends BaseRepository implements AnnouncementRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Announcement);
    }

    public function paginateFiltered(string $search = null, string $type = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }
}
