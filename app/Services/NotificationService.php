<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function send(?int $userId, string $type, string $title, string $body): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
        ]);
    }

    public function broadcast(string $type, string $title, string $body): int
    {
        $users = \App\Models\User::query()->whereHas('role', fn ($q) => $q->where('name', 'member'))->pluck('id');

        $data = $users->map(fn ($id) => [
            'user_id' => $id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        if (empty($data)) {
            return 0;
        }

        return \Illuminate\Support\Facades\DB::table('notifications')->insertOrIgnore($data);
    }
}
