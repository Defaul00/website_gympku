<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;

class PeriodFilter
{
    public const PERIODS = ['daily', 'weekly', 'monthly', 'yearly'];

    public static function resolve(string $period, ?string $date = null): array
    {
        $anchor = $date ? CarbonImmutable::parse($date) : CarbonImmutable::today();

        return match ($period) {
            'daily' => [
                'period' => 'daily',
                'label' => "Harian — {$anchor->translatedFormat('l, d F Y')}",
                'short_label' => "Harian {$anchor->format('d M Y')}",
                'start' => $anchor->startOfDay(),
                'end' => $anchor->endOfDay(),
                'previous_start' => $anchor->subDay()->startOfDay(),
                'previous_end' => $anchor->subDay()->endOfDay(),
                'group_key' => 'd',
                'chart_label' => fn (\Carbon\CarbonInterface $d) => $d->translatedFormat('d M'),
            ],
            'weekly' => [
                'period' => 'weekly',
                'label' => "Mingguan — " . $anchor->startOfWeek()->translatedFormat('d M') . ' s/d ' . $anchor->endOfWeek()->translatedFormat('d M Y'),
                'short_label' => "Minggu {$anchor->startOfWeek()->format('d M')} - {$anchor->endOfWeek()->format('d M Y')}",
                'start' => $anchor->startOfWeek(),
                'end' => $anchor->endOfWeek(),
                'previous_start' => $anchor->subWeek()->startOfWeek(),
                'previous_end' => $anchor->subWeek()->endOfWeek(),
                'group_key' => 'd',
                'chart_label' => fn (\Carbon\CarbonInterface $d) => $d->translatedFormat('D'),
            ],
            'monthly' => [
                'period' => 'monthly',
                'label' => "Bulanan — {$anchor->translatedFormat('F Y')}",
                'short_label' => $anchor->format('M Y'),
                'start' => $anchor->startOfMonth(),
                'end' => $anchor->endOfMonth(),
                'previous_start' => $anchor->subMonth()->startOfMonth(),
                'previous_end' => $anchor->subMonth()->endOfMonth(),
                'group_key' => 'd',
                'chart_label' => fn (\Carbon\CarbonInterface $d) => $d->translatedFormat('d M'),
            ],
            'yearly' => [
                'period' => 'yearly',
                'label' => "Tahunan — {$anchor->year}",
                'short_label' => (string) $anchor->year,
                'start' => $anchor->startOfYear(),
                'end' => $anchor->endOfYear(),
                'previous_start' => $anchor->subYear()->startOfYear(),
                'previous_end' => $anchor->subYear()->endOfYear(),
                'group_key' => 'm',
                'chart_label' => fn (\Carbon\CarbonInterface $d) => $d->translatedFormat('M'),
            ],
            default => self::resolve('monthly', $date),
        };
    }

    public static function isAllowed(string $period): bool
    {
        return in_array($period, self::PERIODS, true);
    }
}
