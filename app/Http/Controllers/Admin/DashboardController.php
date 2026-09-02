<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\MemberCard;
use App\Models\Payment;
use App\Models\TrainerBooking;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private UserRepositoryInterface $users)
    {
    }

    public function index(): View
    {
        $todayCheckIns = Attendance::whereDate('check_in', today())->count();
        $activeMemberships = MemberCard::where('status', 'active')->whereDate('end_date', '>=', today())->count();
        $monthlyRevenue = (float) Payment::where('status', 'paid')
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
        $pendingBookings = TrainerBooking::where('status', 'pending')->count();
        $expiringSoon = MemberCard::where('status', 'active')
            ->whereBetween('end_date', [today(), today()->addDays(7)])
            ->count();

        $yesterdayCheckIns = Attendance::whereDate('check_in', today()->subDay())->count();
        $checkInsDelta = $yesterdayCheckIns > 0
            ? round((($todayCheckIns - $yesterdayCheckIns) / $yesterdayCheckIns) * 100)
            : ($todayCheckIns > 0 ? 100 : null);

        $lastMonthRevenue = (float) Payment::where('status', 'paid')
            ->whereBetween('paid_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount');
        $revenueDelta = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)
            : ($monthlyRevenue > 0 ? 100 : null);

        $revenueTrend = Payment::query()
            ->where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(11)->startOfMonth())
            ->get()
            ->groupBy(fn (Payment $p) => $p->paid_at->format('Y-m'))
            ->map(fn ($group) => (float) $group->sum('amount'));

        $revenueLabels = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i)->translatedFormat('M'));
        $revenueData = $revenueLabels->map(function ($label, $i) use ($revenueTrend) {
            $key = now()->subMonths(11 - $i)->format('Y-m');

            return $revenueTrend[$key] ?? 0;
        });

        $attendanceTrend = Attendance::where('check_in', '>=', now()->subDays(13)->startOfDay())
            ->get()
            ->groupBy(fn (Attendance $a) => $a->check_in->toDateString())
            ->map->count();

        $attendanceLabels = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->translatedFormat('d M'));
        $attendanceData = $attendanceLabels->map(function ($label, $i) use ($attendanceTrend) {
            $key = now()->subDays(13 - $i)->toDateString();

            return $attendanceTrend[$key] ?? 0;
        });

        $membershipDistribution = MemberCard::with('membership')
            ->where('status', 'active')
            ->get()
            ->groupBy(fn ($c) => $c->membership->name)
            ->map->count();

        $recentAttendances = Attendance::with('user')->latest('check_in')->take(6)->get();
        $recentPayments = Payment::with('user')->latest('paid_at')->take(6)->get();

        return view('admin.dashboard', compact(
            'todayCheckIns',
            'checkInsDelta',
            'activeMemberships',
            'monthlyRevenue',
            'revenueDelta',
            'pendingBookings',
            'expiringSoon',
            'revenueLabels',
            'revenueData',
            'attendanceLabels',
            'attendanceData',
            'membershipDistribution',
            'recentAttendances',
            'recentPayments',
        ));
    }
}
