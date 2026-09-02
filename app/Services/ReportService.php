<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\MemberCard;
use App\Models\Payment;
use App\Models\TrainerBooking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    private const REPORTS = [
        'attendance' => ['title' => 'Kehadiran Member', 'description' => 'Rekap kehadiran member beserta durasi latihan.'],
        'active-memberships' => ['title' => 'Membership Aktif', 'description' => 'Member dengan membership aktif pada periode terpilih.'],
        'expired-memberships' => ['title' => 'Membership Expired', 'description' => 'Membership yang telah habis masa berlaku pada periode terpilih.'],
        'revenue' => ['title' => 'Pendapatan', 'description' => 'Total pendapatan dari pembayaran membership dan transaksi lainnya.'],
        'trainer-booking' => ['title' => 'Booking Trainer', 'description' => 'Rekap pemesanan sesi personal trainer.'],
        'peak-hours' => ['title' => 'Peak Hours', 'description' => 'Distribusi kehadiran member per jam untuk mengetahui jam tersibuk.'],
        'gym-activity' => ['title' => 'Aktivitas Gym', 'description' => 'Ringkasan aktivitas keseluruhan gym pada periode terpilih.'],
    ];

    public static function definitions(): array
    {
        return self::REPORTS;
    }

    public static function definition(string $type): array
    {
        if (! isset(self::REPORTS[$type])) {
            abort(404);
        }

        return self::REPORTS[$type];
    }

    public function build(string $type, string $period, ?string $date = null): array
    {
        $periodData = PeriodFilter::resolve($period, $date);

        return match ($type) {
            'attendance' => $this->attendanceReport($periodData),
            'active-memberships' => $this->activeMembershipsReport($periodData),
            'expired-memberships' => $this->expiredMembershipsReport($periodData),
            'revenue' => $this->revenueReport($periodData),
            'trainer-booking' => $this->trainerBookingReport($periodData),
            'peak-hours' => $this->peakHoursReport($periodData),
            'gym-activity' => $this->gymActivityReport($periodData),
            default => abort(404),
        };
    }

    private function base(string $type, array $periodData): array
    {
        $def = self::REPORTS[$type];

        return [
            'type' => $type,
            'title' => $def['title'],
            'description' => $def['description'],
            'period' => $periodData,
        ];
    }

    private function attendanceReport(array $p): array
    {
        $attendances = Attendance::query()
            ->with('user', 'memberCard.membership')
            ->whereBetween('check_in', [$p['start'], $p['end']])
            ->get();

        $total = $attendances->count();
        $unique = $attendances->pluck('user_id')->unique()->count();
        $totalMinutes = $attendances->sum('duration_minutes') ?: 0;
        $avgMinutes = $total > 0 ? (int) round($totalMinutes / $total) : 0;

        $previous = Attendance::whereBetween('check_in', [$p['previous_start'], $p['previous_end']])->count();

        $rows = Attendance::query()
            ->with('user', 'memberCard.membership')
            ->whereBetween('check_in', [$p['start'], $p['end']])
            ->latest('check_in')
            ->paginate(12)
            ->withQueryString();

        return array_merge($this->base('attendance', $p), [
            'summary' => [
                ['label' => 'Total Kehadiran', 'value' => $total, 'suffix' => 'x', 'icon' => 'check', 'color' => 'indigo', 'delta' => $this->delta($total, $previous)],
                ['label' => 'Member Aktif', 'value' => $unique, 'suffix' => 'org', 'icon' => 'users', 'color' => 'emerald'],
                ['label' => 'Total Durasi', 'value' => $this->hours($totalMinutes), 'suffix' => 'jam', 'icon' => 'clock', 'color' => 'amber'],
                ['label' => 'Rata-rata Sesi', 'value' => $this->hours($avgMinutes), 'suffix' => 'menit', 'icon' => 'stopwatch', 'color' => 'rose'],
            ],
            'rows' => $rows,
            'chart' => $this->dailySeries($p, $attendances, fn (Attendance $a) => $a->user_id, 'Kehadiran'),
        ]);
    }

    private function activeMembershipsReport(array $p): array
    {
        $cards = MemberCard::query()
            ->with('user', 'membership')
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $p['end']->toDateString())
            ->whereDate('end_date', '>=', $p['start']->toDateString())
            ->get();

        $activated = $cards->filter(fn ($c) => $c->start_date->between($p['start'], $p['end']))->count();
        $expiring = $cards->filter(fn ($c) => $c->end_date->between(today(), $p['end']))->count();

        $rows = MemberCard::query()
            ->with('user', 'membership')
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $p['end']->toDateString())
            ->whereDate('end_date', '>=', $p['start']->toDateString())
            ->latest('start_date')
            ->paginate(12)
            ->withQueryString();

        return array_merge($this->base('active-memberships', $p), [
            'summary' => [
                ['label' => 'Membership Aktif', 'value' => $cards->count(), 'suffix' => 'kartu', 'icon' => 'badge', 'color' => 'emerald'],
                ['label' => 'Aktivasi Baru', 'value' => $activated, 'suffix' => 'kartu', 'icon' => 'plus', 'color' => 'indigo'],
                ['label' => 'Segera Expired', 'value' => $expiring, 'suffix' => 'kartu', 'icon' => 'clock', 'color' => 'amber'],
                ['label' => 'Paket Terpopuler', 'value' => $cards->groupBy('membership.name')->sortDesc()->keys()->first() ?? '-', 'suffix' => '', 'icon' => 'sparkles', 'color' => 'rose'],
            ],
            'rows' => $rows,
            'chart' => $this->dailySeries($p, $cards, fn (MemberCard $c) => $c->id, 'Aktivasi', 'start_date'),
        ]);
    }

    private function expiredMembershipsReport(array $p): array
    {
        $cards = MemberCard::query()
            ->with('user', 'membership')
            ->where(function ($q) use ($p) {
                $q->where('status', 'expired')
                    ->orWhereDate('end_date', '<', today());
            })
            ->whereDate('end_date', '<=', $p['end']->toDateString())
            ->whereDate('end_date', '>=', $p['start']->toDateString())
            ->get();

        $rows = MemberCard::query()
            ->with('user', 'membership')
            ->where(function ($q) use ($p) {
                $q->where('status', 'expired')
                    ->orWhereDate('end_date', '<', today());
            })
            ->whereDate('end_date', '<=', $p['end']->toDateString())
            ->whereDate('end_date', '>=', $p['start']->toDateString())
            ->latest('end_date')
            ->paginate(12)
            ->withQueryString();

        $overdue = $cards->filter(fn ($c) => $c->end_date->isPast())->count();
        $renewed = $cards->filter(fn ($c) => $c->user->activeMemberCard() !== null && $c->user->activeMemberCard()->id !== $c->id)->count();

        return array_merge($this->base('expired-memberships', $p), [
            'summary' => [
                ['label' => 'Membership Expired', 'value' => $cards->count(), 'suffix' => 'kartu', 'icon' => 'x-circle', 'color' => 'rose'],
                ['label' => 'Telah Lewat Jatuh Tempo', 'value' => $overdue, 'suffix' => 'kartu', 'icon' => 'alert', 'color' => 'amber'],
                ['label' => 'Perpanjangan Aktif', 'value' => $renewed, 'suffix' => 'member', 'icon' => 'refresh', 'color' => 'emerald'],
                ['label' => 'Rata-rata Lewat', 'value' => $overdue > 0 ? max(0, (int) round($cards->filter(fn ($c) => $c->end_date->isPast())->avg(fn ($c) => now()->diffInDays($c->end_date)))) : 0, 'suffix' => 'hari', 'icon' => 'calendar', 'color' => 'indigo'],
            ],
            'rows' => $rows,
            'chart' => $this->dailySeries($p, $cards, fn (MemberCard $c) => $c->id, 'Expired', 'end_date'),
        ]);
    }

    private function revenueReport(array $p): array
    {
        $payments = Payment::query()
            ->with('user', 'memberCard.membership')
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$p['start'], $p['end']])
            ->get();

        $total = (float) $payments->sum('amount');
        $previousTotal = (float) Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$p['previous_start'], $p['previous_end']])
            ->sum('amount');

        $byMethod = $payments->groupBy('method')
            ->map(fn ($group) => (float) $group->sum('amount'))
            ->sortDesc();

        $rows = Payment::query()
            ->with('user', 'memberCard.membership')
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$p['start'], $p['end']])
            ->latest('paid_at')
            ->paginate(12)
            ->withQueryString();

        $chart = $this->bucketSeries($p, $payments, fn (Payment $pay) => $pay->paid_at, fn (Collection $group) => (float) $group->sum('amount'));

        return array_merge($this->base('revenue', $p), [
            'summary' => [
                ['label' => 'Total Pendapatan', 'value' => $total, 'suffix' => '', 'icon' => 'wallet', 'color' => 'emerald', 'delta' => $this->delta($total, $previousTotal), 'currency' => true],
                ['label' => 'Transaksi', 'value' => $payments->count(), 'suffix' => 'x', 'icon' => 'receipt', 'color' => 'indigo'],
                ['label' => 'Rata-rata/Transaksi', 'value' => $payments->count() ? (int) round($total / $payments->count()) : 0, 'suffix' => '', 'icon' => 'chart', 'color' => 'amber', 'currency' => true],
                ['label' => 'Metode Teratas', 'value' => $byMethod->keys()->first() ?? '-', 'suffix' => '', 'icon' => 'credit-card', 'color' => 'rose'],
            ],
            'rows' => $rows,
            'byMethod' => $byMethod,
            'chart' => $chart,
        ]);
    }

    private function trainerBookingReport(array $p): array
    {
        $bookings = TrainerBooking::query()
            ->with('user', 'trainer.user')
            ->whereBetween('booking_date', [$p['start']->toDateString(), $p['end']->toDateString()])
            ->get();

        $rows = TrainerBooking::query()
            ->with('user', 'trainer.user')
            ->whereBetween('booking_date', [$p['start']->toDateString(), $p['end']->toDateString()])
            ->latest('booking_date')
            ->paginate(12)
            ->withQueryString();

        $byTrainer = $bookings->groupBy('trainer.user.name')->map->count()->sortDesc();

        return array_merge($this->base('trainer-booking', $p), [
            'summary' => [
                ['label' => 'Total Booking', 'value' => $bookings->count(), 'suffix' => 'sesi', 'icon' => 'calendar', 'color' => 'indigo'],
                ['label' => 'Dikonfirmasi', 'value' => $bookings->where('status', 'confirmed')->count(), 'suffix' => 'sesi', 'icon' => 'check', 'color' => 'emerald'],
                ['label' => 'Selesai', 'value' => $bookings->where('status', 'completed')->count(), 'suffix' => 'sesi', 'icon' => 'flag', 'color' => 'amber'],
                ['label' => 'Dibatalkan', 'value' => $bookings->where('status', 'cancelled')->count(), 'suffix' => 'sesi', 'icon' => 'x-circle', 'color' => 'rose'],
            ],
            'rows' => $rows,
            'byTrainer' => $byTrainer,
            'chart' => $this->dailySeries($p, $bookings, fn (TrainerBooking $b) => $b->id, 'Booking', 'booking_date'),
        ]);
    }

    private function peakHoursReport(array $p): array
    {
        $attendances = Attendance::whereBetween('check_in', [$p['start'], $p['end']])->get();

        $hours = collect(range(0, 23))->map(fn ($h) => ['hour' => $h, 'count' => 0])->keyBy('hour');
        $attendances->each(function (Attendance $a) use ($hours) {
            $hours->get($a->check_in->hour)['count']++;
        });

        $distribution = $hours->values();

        $peak = $distribution->sortByDesc('count')->first();

        $rows = $distribution
            ->filter(fn ($row) => $row['count'] > 0)
            ->sortByDesc('count')
            ->values()
            ->map(fn ($row, $i) => [
                'rank' => $i + 1,
                'hour' => $row['hour'],
                'count' => $row['count'],
                'percentage' => $attendances->count() > 0 ? round(($row['count'] / $attendances->count()) * 100, 1) : 0,
            ]);

        return array_merge($this->base('peak-hours', $p), [
            'summary' => [
                ['label' => 'Jam Tersibuk', 'value' => $peak['count'] > 0 ? sprintf('%02d:00', $peak['hour']) : '-', 'suffix' => '', 'icon' => 'fire', 'color' => 'rose'],
                ['label' => 'Total Kehadiran', 'value' => $attendances->count(), 'suffix' => 'x', 'icon' => 'check', 'color' => 'indigo'],
                ['label' => 'Jam Sepi', 'value' => $distribution->filter(fn ($r) => $r['count'] === 0)->count(), 'suffix' => 'jam', 'icon' => 'moon', 'color' => 'slate'],
                ['label' => 'Rata-rata/Jam', 'value' => $attendances->count() > 0 ? round($attendances->count() / 24, 1) : 0, 'suffix' => 'org', 'icon' => 'chart', 'color' => 'amber'],
            ],
            'rows' => $rows,
            'chart' => [
                'labels' => $distribution->map(fn ($r) => sprintf('%02d:00', $r['hour']))->values(),
                'datasets' => [[
                    'label' => 'Kehadiran',
                    'data' => $distribution->map(fn ($r) => $r['count'])->values(),
                    'color' => '#f43f5e',
                ]],
            ],
        ]);
    }

    private function gymActivityReport(array $p): array
    {
        $attendances = Attendance::query()
            ->with('user')
            ->whereBetween('check_in', [$p['start'], $p['end']])
            ->get();

        $total = $attendances->count();
        $unique = $attendances->pluck('user_id')->unique()->count();
        $totalMinutes = $attendances->sum('duration_minutes') ?: 0;
        $avgMinutes = $total > 0 ? (int) round($totalMinutes / $total) : 0;

        $busiestDay = $attendances->groupBy(fn ($a) => $a->check_in->toDateString())
            ->map->count()->sortDesc()->keys()->first();
        $busiestHour = $attendances->groupBy(fn ($a) => $a->check_in->hour)
            ->map->count()->sortDesc()->keys()->first();

        $daily = $attendances->groupBy(fn ($a) => $a->check_in->toDateString())
            ->map(fn ($group) => [
                'date' => $group->first()->check_in->toDateString(),
                'check_ins' => $group->count(),
                'unique' => $group->pluck('user_id')->unique()->count(),
                'total_minutes' => (int) $group->sum('duration_minutes'),
                'avg_minutes' => (int) round($group->avg('duration_minutes') ?: 0),
            ])
            ->sortKeys()
            ->values();

        $rows = new \Illuminate\Pagination\LengthAwarePaginator(
            $daily->slice($daily->count() > 15 ? ($daily->count() - 15) : 0, 15)->values(),
            $daily->count(),
            15,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $chart = $this->bucketSeries(
            $p,
            $attendances,
            fn (Attendance $a) => $a->check_in,
            fn (Collection $group) => $group->count()
        );

        return array_merge($this->base('gym-activity', $p), [
            'summary' => [
                ['label' => 'Total Check-in', 'value' => $total, 'suffix' => 'x', 'icon' => 'check', 'color' => 'indigo'],
                ['label' => 'Member Unik', 'value' => $unique, 'suffix' => 'org', 'icon' => 'users', 'color' => 'emerald'],
                ['label' => 'Total Jam Latihan', 'value' => $this->hours($totalMinutes), 'suffix' => 'jam', 'icon' => 'clock', 'color' => 'amber'],
                ['label' => 'Rata-rata Sesi', 'value' => $this->hours($avgMinutes), 'suffix' => 'menit', 'icon' => 'stopwatch', 'color' => 'rose'],
            ],
            'rows' => $rows,
            'busiestDay' => $busiestDay ? Carbon::parse($busiestDay)->translatedFormat('l, d M Y') : '-',
            'busiestHour' => $busiestHour !== null ? sprintf('%02d:00', $busiestHour) : '-',
            'chart' => $chart,
        ]);
    }

    private function dailySeries(array $p, Collection $items, callable $uniqueKey, string $label, string $dateField = 'check_in'): array
    {
        $series = $items->unique($uniqueKey);

        return $this->bucketSeries($p, $series, fn ($item) => $item->{$dateField}, fn (Collection $group) => $group->count(), $label);
    }

    private function bucketSeries(array $p, Collection $items, callable $dateGetter, callable $value, ?string $label = null): array
    {
        $buckets = $this->makeBuckets($p);

        $items->each(function ($item) use (&$buckets, $dateGetter, $p) {
            $date = Carbon::parse($dateGetter($item));
            $key = $p['group_key'] === 'd' ? $date->toDateString() : $date->format('Y-m');
            if (isset($buckets[$key])) {
                $buckets[$key]['items']->push($item);
            }
        });

        $labels = collect($buckets)->map(fn ($b) => $b['label'])->values();
        $data = collect($buckets)->map(fn ($b) => $value($b['items']))->values();

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => $label ?? 'Jumlah',
                'data' => $data,
                'color' => '#6366f1',
            ]],
        ];
    }

    private function makeBuckets(array $p): array
    {
        $buckets = [];
        $cursor = $p['start']->copy();

        while ($cursor <= $p['end']) {
            $key = $p['group_key'] === 'd' ? $cursor->toDateString() : $cursor->format('Y-m');
            $buckets[$key] = ['label' => $p['chart_label']($cursor), 'items' => collect()];
            $cursor = $p['group_key'] === 'd' ? $cursor->addDay() : $cursor->addMonth();
        }

        return $buckets;
    }

    private function delta(float|int $current, float|int $previous): ?float
    {
        if ($previous <= 0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function hours(int $minutes): string
    {
        if ($minutes <= 0) {
            return '0';
        }

        return number_format($minutes / 60, 1);
    }
}
