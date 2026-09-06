<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberAttendanceController extends Controller
{
    public function scan(Request $request, AttendanceService $attendanceService): JsonResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string', 'max:128'],
        ]);

        abort_unless(hash_equals($attendanceService->qrToken(), $data['token']), 403, 'QR attendance tidak valid.');

        $result = $attendanceService->toggle($request->user());

        return response()->json([
            'action' => $result['action'],
            'message' => $result['action'] === 'check_in'
                ? 'Check-in berhasil. Selamat berlatih!'
                : 'Check-out berhasil. Sampai jumpa lagi!',
            'time' => $result['attendance']->check_out?->format('H:i')
                ?? $result['attendance']->check_in->format('H:i'),
        ]);
    }
}