<?php

use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GymEquipmentController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TrainerBookingController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerDashboardController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{type}', [ReportController::class, 'show'])->name('show');
        Route::get('/{type}/export/pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{type}/export/excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });

    Route::resource('members', MemberController::class);
    Route::post('members/{member}/activate', [MemberController::class, 'activate'])->name('members.activate');
    Route::post('members/{member}/deactivate', [MemberController::class, 'deactivate'])->name('members.deactivate');
    Route::resource('memberships', MembershipController::class);
    Route::resource('payments', PaymentController::class)->except(['edit', 'update']);
    Route::resource('attendances', AttendanceController::class)->except(['edit', 'update', 'show', 'destroy']);
    Route::resource('trainers', TrainerController::class);
    Route::resource('bookings', TrainerBookingController::class);
    Route::resource('equipments', GymEquipmentController::class)->except(['show']);
    Route::resource('announcements', AnnouncementController::class)->except(['show']);
    Route::resource('achievements', AchievementController::class);

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
});

Route::middleware(['auth', 'verified', 'member'])->group(function () {
    Route::get('/home', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth', 'verified', 'trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/', [TrainerDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/member-cards/{card}/print', [MemberCardController::class, 'print'])->name('member-card.print');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
