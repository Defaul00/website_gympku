<?php

namespace App\Services;

use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportExportService
{
    public function excel(string $type, array $periodData): BinaryFileResponse
    {
        $data = $this->build($type, $periodData);
        $filename = Str::slug($data['title'] . '-' . $periodData['short_label']) . '.xlsx';

        return Excel::download(new ReportExport(
            $data['title'],
            $periodData['short_label'],
            $data['columns'],
            $data['rows'],
        ), $filename);
    }

    public function pdf(string $type, array $periodData): \Illuminate\Http\Response
    {
        $data = $this->build($type, $periodData);

        $pdf = Pdf::loadView('pdf.report', [
            'title' => $data['title'],
            'description' => $data['description'],
            'periodLabel' => $periodData['label'],
            'columns' => $data['columns'],
            'rows' => $data['rows'],
            'summary' => $data['summary'],
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(Str::slug($data['title'] . '-' . $periodData['short_label']) . '.pdf');
    }

    public function build(string $type, array $periodData): array
    {
        $report = app(ReportService::class)->build($type, $periodData['period'], $periodData['date'] ?? null);

        [$columns, $rows] = match ($type) {
            'attendance' => $this->attendance($report),
            'active-memberships' => $this->membershipCards($report, 'active'),
            'expired-memberships' => $this->membershipCards($report, 'expired'),
            'revenue' => $this->revenue($report),
            'trainer-booking' => $this->trainerBooking($report),
            'peak-hours' => $this->peakHours($report),
            'gym-activity' => $this->gymActivity($report),
            default => abort(404),
        };

        return [
            'title' => $report['title'],
            'description' => $report['description'],
            'columns' => $columns,
            'rows' => $rows,
            'summary' => $report['summary'],
        ];
    }

    private function attendance(array $report): array
    {
        $columns = [
            ['key' => 'member', 'label' => 'Member'],
            ['key' => 'membership', 'label' => 'Membership'],
            ['key' => 'check_in', 'label' => 'Check In'],
            ['key' => 'check_out', 'label' => 'Check Out'],
            ['key' => 'duration', 'label' => 'Durasi (menit)'],
        ];

        $rows = $report['rows']->getCollection()->map(fn ($a) => [
            'member' => $a->user->name,
            'membership' => $a->memberCard?->membership->name ?? '-',
            'check_in' => $a->check_in->format('d M Y H:i'),
            'check_out' => $a->check_out?->format('d M Y H:i') ?? '-',
            'duration' => $a->duration_minutes ?? '-',
        ]);

        return [$columns, $rows];
    }

    private function membershipCards(array $report, string $mode): array
    {
        $columns = [
            ['key' => 'member', 'label' => 'Member'],
            ['key' => 'membership', 'label' => 'Paket'],
            ['key' => 'card_number', 'label' => 'No. Kartu'],
            ['key' => 'start', 'label' => 'Mulai'],
            ['key' => 'end', 'label' => $mode === 'active' ? 'Berakhir' : 'Tanggal Expired'],
            ['key' => 'status', 'label' => 'Status'],
        ];

        $rows = $report['rows']->getCollection()->map(fn ($c) => [
            'member' => $c->user->name,
            'membership' => $c->membership->name,
            'card_number' => $c->card_number,
            'start' => $c->start_date->format('d M Y'),
            'end' => $c->end_date->format('d M Y'),
            'status' => $c->status,
        ]);

        return [$columns, $rows];
    }

    private function revenue(array $report): array
    {
        $columns = [
            ['key' => 'member', 'label' => 'Member'],
            ['key' => 'reference', 'label' => 'Referensi'],
            ['key' => 'method', 'label' => 'Metode'],
            ['key' => 'paid_at', 'label' => 'Tanggal'],
            ['key' => 'amount', 'label' => 'Nominal'],
        ];

        $rows = $report['rows']->getCollection()->map(fn ($p) => [
            'member' => $p->user->name,
            'reference' => $p->reference,
            'method' => strtoupper($p->method),
            'paid_at' => $p->paid_at->format('d M Y H:i'),
            'amount' => (int) $p->amount,
        ]);

        return [$columns, $rows];
    }

    private function trainerBooking(array $report): array
    {
        $columns = [
            ['key' => 'member', 'label' => 'Member'],
            ['key' => 'trainer', 'label' => 'Trainer'],
            ['key' => 'date', 'label' => 'Tanggal'],
            ['key' => 'time', 'label' => 'Jam'],
            ['key' => 'status', 'label' => 'Status'],
        ];

        $rows = $report['rows']->getCollection()->map(fn ($b) => [
            'member' => $b->user->name,
            'trainer' => $b->trainer->user->name,
            'date' => $b->booking_date->format('d M Y'),
            'time' => \Illuminate\Support\Carbon::parse($b->start_time)->format('H:i') . ' - ' . \Illuminate\Support\Carbon::parse($b->end_time)->format('H:i'),
            'status' => $b->status,
        ]);

        return [$columns, $rows];
    }

    private function peakHours(array $report): array
    {
        $columns = [
            ['key' => 'rank', 'label' => 'Peringkat'],
            ['key' => 'hour', 'label' => 'Jam'],
            ['key' => 'count', 'label' => 'Jumlah Kehadiran'],
            ['key' => 'percentage', 'label' => '%'],
        ];

        $rows = $report['rows']->map(fn ($r) => [
            'rank' => $r['rank'],
            'hour' => sprintf('%02d:00', $r['hour']),
            'count' => $r['count'],
            'percentage' => $r['percentage'] . '%',
        ]);

        return [$columns, $rows->values()];
    }

    private function gymActivity(array $report): array
    {
        $columns = [
            ['key' => 'date', 'label' => 'Tanggal'],
            ['key' => 'check_ins', 'label' => 'Check-in'],
            ['key' => 'unique', 'label' => 'Member Unik'],
            ['key' => 'total_minutes', 'label' => 'Total Menit'],
            ['key' => 'avg_minutes', 'label' => 'Rata-rata (menit)'],
        ];

        $rows = $report['rows']->getCollection()->map(fn ($r) => [
            'date' => $r['date'],
            'check_ins' => $r['check_ins'],
            'unique' => $r['unique'],
            'total_minutes' => $r['total_minutes'],
            'avg_minutes' => $r['avg_minutes'],
        ]);

        return [$columns, $rows];
    }
}
