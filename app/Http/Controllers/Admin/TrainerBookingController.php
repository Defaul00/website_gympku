<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\TrainerBooking;
use App\Models\User;
use App\Repositories\Contracts\TrainerBookingRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainerBookingController extends Controller
{
    public function __construct(private TrainerBookingRepositoryInterface $bookings)
    {
    }

    public function index(Request $request): View
    {
        $bookings = $this->bookings->paginateWithRelations($request->query('q'), $request->query('status'), 15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create(): View
    {
        $members = User::whereHas('role', fn ($q) => $q->where('name', 'member'))->orderBy('name')->get();
        $trainers = Trainer::with('user')->where('is_available', true)->get();

        return view('admin.bookings.create', compact('members', 'trainers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'trainer_id' => ['required', 'exists:trainers,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        TrainerBooking::create([
            'user_id' => $data['user_id'],
            'trainer_id' => $data['trainer_id'],
            'booking_date' => $data['booking_date'],
            'start_time' => $data['start_time'] . ':00',
            'end_time' => \Illuminate\Support\Carbon::parse($data['start_time'])->addHour()->format('H:i:s'),
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking trainer berhasil dibuat.');
    }

    public function edit(TrainerBooking $booking): View
    {
        $booking->load('user', 'trainer.user');

        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, TrainerBooking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $booking->update($data);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(TrainerBooking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}
