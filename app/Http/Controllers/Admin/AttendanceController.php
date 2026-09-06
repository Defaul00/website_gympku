<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $attendanceQrToken = $this->attendanceService->qrToken();

        return view('admin.attendances.create', compact('attendanceQrToken'));
    }

}
