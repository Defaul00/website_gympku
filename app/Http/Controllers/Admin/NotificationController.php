<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private NotificationRepositoryInterface $notifications)
    {
    }

    public function index(Request $request): View
    {
        $notifications = $this->notifications->paginateForUser($request->user()->id, $request->query('q'), 15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, Notification $notification): RedirectResponse
    {
        $this->authorize('view', $notification);

        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $this->notifications->markAllReadForUser($request->user()->id);

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }
}
