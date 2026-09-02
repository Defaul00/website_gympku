<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $card = $user->memberCards()
            ->where('status', 'active')
            ->whereDate('end_date', '>=', today())
            ->with('membership')
            ->orderByDesc('end_date')
            ->first();

        $checkInsCount = Attendance::where('user_id', $user->id)->count();
        $checkInsThisMonth = Attendance::where('user_id', $user->id)
            ->whereBetween('check_in', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $totalSpent = (float) Payment::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');

        $recentPayments = Payment::with('memberCard.membership')
            ->where('user_id', $user->id)
            ->latest('paid_at')
            ->take(4)
            ->get();

        $announcements = Announcement::where('published_at', '<=', now())
            ->latest('published_at')
            ->take(4)
            ->get();

        $achievements = $user->achievements()
            ->latest('user_achievements.unlocked_at')
            ->take(6)
            ->get();

        $daysLeft = $card
            ? max(0, today()->diffInDays($card->end_date) + 1)
            : 0;

        return view('user.dashboard', compact(
            'card',
            'checkInsCount',
            'checkInsThisMonth',
            'totalSpent',
            'recentPayments',
            'announcements',
            'achievements',
            'daysLeft',
        ));
    }
}
