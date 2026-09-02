<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TrainerDashboardController extends Controller
{
    public function index(): View
    {
        $trainer = auth()->user()->trainerProfile;

        if ($trainer === null) {
            return view('trainer.dashboard', [
                'trainer' => null,
                'todaySchedule' => collect(),
                'upcomingBookings' => collect(),
                'totalBookings' => 0,
                'completedBookings' => 0,
                'pendingBookings' => 0,
                'todayCount' => 0,
            ]);
        }

        $todaySchedule = $trainer->bookings()
            ->with('user')
            ->whereDate('booking_date', today())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('start_time')
            ->get();

        $upcomingBookings = $trainer->bookings()
            ->with('user')
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereDate('booking_date', '>=', today())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(6)
            ->get();

        $totalBookings = $trainer->bookings()->count();
        $completedBookings = $trainer->bookings()->where('status', 'completed')->count();
        $pendingBookings = $trainer->bookings()->where('status', 'pending')->count();
        $todayCount = $todaySchedule->count();

        return view('trainer.dashboard', compact(
            'trainer',
            'todaySchedule',
            'upcomingBookings',
            'totalBookings',
            'completedBookings',
            'pendingBookings',
            'todayCount',
        ));
    }
}
