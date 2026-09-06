<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\View\View;

class MemberNotificationController extends Controller
{
    public function __construct(private NotificationRepositoryInterface $notifications)
    {
    }

    public function index(Request $request): View
    {
        $notifications = $this->notifications->paginateForUser(
            $request->user()->id,
            $request->query('q'),
            15,
        );

        return view('user.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== $request->user()->id) {
            throw new AccessDeniedHttpException;
        }

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
