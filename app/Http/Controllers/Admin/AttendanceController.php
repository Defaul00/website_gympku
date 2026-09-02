<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceRepositoryInterface $attendances,
        private AttendanceService $attendanceService,
    ) {
    }

    public function index(Request $request): View
    {
        $attendances = $this->attendances->paginateWithRelations($request->query('q'), $request->query('date'), 15);

        return view('admin.attendances.index', compact('attendances'));
    }

    public function create(): View
    {
        $members = User::whereHas('role', fn ($q) => $q->where('name', 'member'))
            ->orderBy('name')
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'checked_in' => $this->attendanceService->isCurrentlyCheckedIn($user),
            ]);

        return view('admin.attendances.create', compact('members'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'action' => ['required', 'in:check_in,check_out'],
        ]);

        $user = User::findOrFail($data['user_id']);

        if ($data['action'] === 'check_in') {
            $this->attendanceService->checkIn($user);

            return redirect()->route('admin.attendances.index')
                ->with('success', "Check-in berhasil untuk {$user->name}.");
        }

        $attendance = $this->attendanceService->checkOut($user);

        if ($attendance === null) {
            return redirect()->route('admin.attendances.index')
                ->with('error', "{$user->name} belum melakukan check-in.");
        }

        return redirect()->route('admin.attendances.index')
            ->with('success', "Check-out berhasil untuk {$user->name}.");
    }
}
