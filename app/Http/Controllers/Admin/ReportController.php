<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PeriodFilter;
use App\Services\ReportExportService;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reports,
        private ReportExportService $exports,
    ) {
    }

    public function index(): View
    {
        return view('admin.reports.index', [
            'definitions' => ReportService::definitions(),
        ]);
    }

    public function show(Request $request, string $type): View
    {
        $period = $request->query('period', 'monthly');
        $periodData = PeriodFilter::resolve($period, $request->query('date'));
        $data = $this->reports->build($type, $period, $request->query('date'));

        return view('admin.reports.show', [
            'definitions' => ReportService::definitions(),
            'report' => $data,
            'period' => $period,
            'periodData' => $periodData,
        ]);
    }

    public function exportPdf(Request $request, string $type): \Illuminate\Http\Response
    {
        $period = $request->query('period', 'monthly');
        $periodData = PeriodFilter::resolve($period, $request->query('date'));

        return $this->exports->pdf($type, $periodData);
    }

    public function exportExcel(Request $request, string $type): BinaryFileResponse
    {
        $period = $request->query('period', 'monthly');
        $periodData = PeriodFilter::resolve($period, $request->query('date'));

        return $this->exports->excel($type, $periodData);
    }
}
