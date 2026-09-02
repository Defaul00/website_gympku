<x-admin-layout :title="$report['title']" :header="$report['description']">

    @php
        $type = $report['type'];
        $queryString = http_build_query(request()->only(['period', 'date']));
        $pdfUrl = route('admin.reports.export-pdf', $type) . ($queryString ? '?' . $queryString : '');
        $excelUrl = route('admin.reports.export-excel', $type) . ($queryString ? '?' . $queryString : '');
        $periods = ['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'];
    @endphp

    <x-slot name="actions">
        <a href="{{ $pdfUrl }}" target="_blank"
           class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-600/25 transition hover:bg-rose-700">
            <x-icon name="pdf" class="h-5 w-5" />
            Export PDF
        </a>
        <a href="{{ $excelUrl }}"
           class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">
            <x-icon name="excel" class="h-5 w-5" />
            Export Excel
        </a>
    </x-slot>

    <!-- Period filter -->
    <x-card padding="false">
        <form method="GET" action="{{ route('admin.reports.show', $type) }}" class="flex flex-col gap-4 p-5 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-label value="Periode" />
                <div class="mt-1.5 grid grid-cols-2 gap-2 sm:grid-cols-4">
                    @foreach($periods as $key => $label)
                        <a href="{{ route('admin.reports.show', [$type, 'period' => $key, 'date' => request('date')]) }}"
                           @class([
                               'rounded-xl border px-4 py-2.5 text-center text-sm font-semibold transition-all duration-200',
                               'border-brand-600 bg-brand-600 text-white shadow-md shadow-brand-600/25' => $period === $key,
                               'border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-brand-600 dark:hover:text-brand-400' => $period !== $key,
                           ])>{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div class="w-full sm:w-52">
                <x-label value="Tanggal Acuan" />
                <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                       onchange="this.form.submit()"
                       class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
            </div>
            <button type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-5 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                <x-icon name="search" class="h-4 w-4" />
                Terapkan
            </button>
        </form>
        <p class="border-t border-slate-100 px-5 py-3 text-sm font-medium text-brand-600 dark:border-slate-800 dark:text-brand-400">
            <x-icon name="calendar" class="mr-1.5 inline h-4 w-4" />
            {{ $periodData['label'] }}
        </p>
    </x-card>

    <!-- Summary stats -->
    @if(! empty($report['summary']))
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($report['summary'] as $stat)
                <x-stat-card :label="$stat['label']" :value="$stat['value']" :suffix="$stat['suffix']" :icon="$stat['icon']" :color="$stat['color']" :delta="$stat['delta'] ?? null" :currency="$stat['currency'] ?? false" />
            @endforeach
        </div>
    @endif

    <!-- Chart -->
    @if(! empty($report['chart']['datasets'][0]['data']) && collect($report['chart']['datasets'][0]['data'])->sum() > 0)
        <x-card title="Visualisasi" subtitle="Grafik data periode terpilih" class="mt-6">
            <div class="h-72">
                <canvas id="reportChart"></canvas>
            </div>
        </x-card>
    @endif

    <!-- Data table -->
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data {{ $report['title'] }}</h3>
            @if($report['rows'] instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                <span class="rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700 dark:bg-brand-500/10 dark:text-brand-300">{{ $report['rows']->total() }} baris</span>
            @endif
        </div>

        @switch($type)
            @case('attendance')
                <x-report-table :rows="$report['rows']" :columns="[
                    ['key' => 'member', 'label' => 'Member'],
                    ['key' => 'membership', 'label' => 'Membership'],
                    ['key' => 'check_in', 'label' => 'Check In'],
                    ['key' => 'check_out', 'label' => 'Check Out'],
                    ['key' => 'duration', 'label' => 'Durasi'],
                ]" :renderer="fn($a) => [
                    'member' => ['text' => $a->user->name, 'sub' => $a->user->email],
                    'membership' => $a->memberCard?->membership->name ?? '-',
                    'check_in' => $a->check_in->format('d M Y H:i'),
                    'check_out' => $a->check_out?->format('d M Y H:i') ?? 'Belum selesai',
                    'duration' => $a->duration_minutes ? $a->duration_minutes . ' menit' : '-',
                ]" />
                @break

            @case('active-memberships')
            @case('expired-memberships')
                <x-report-table :rows="$report['rows']" :columns="[
                    ['key' => 'member', 'label' => 'Member'],
                    ['key' => 'membership', 'label' => 'Paket'],
                    ['key' => 'card', 'label' => 'No. Kartu'],
                    ['key' => 'start', 'label' => 'Mulai'],
                    ['key' => 'end', 'label' => $type === 'active-memberships' ? 'Berakhir' : 'Expired'],
                    ['key' => 'status', 'label' => 'Status'],
                ]" :renderer="fn($c) => [
                    'member' => ['text' => $c->user->name, 'sub' => $c->user->email],
                    'membership' => $c->membership->name,
                    'card' => $c->card_number,
                    'start' => $c->start_date->format('d M Y'),
                    'end' => $c->end_date->format('d M Y'),
                    'status' => ['badge' => $c->status, 'color' => $c->status === 'active' ? 'emerald' : 'rose'],
                ]" />
                @break

            @case('revenue')
                <x-report-table :rows="$report['rows']" :columns="[
                    ['key' => 'member', 'label' => 'Member'],
                    ['key' => 'reference', 'label' => 'Referensi'],
                    ['key' => 'method', 'label' => 'Metode'],
                    ['key' => 'paid_at', 'label' => 'Tanggal'],
                    ['key' => 'amount', 'label' => 'Nominal'],
                ]" :renderer="fn($p) => [
                    'member' => ['text' => $p->user->name, 'sub' => $p->user->email],
                    'reference' => $p->reference,
                    'method' => ['badge' => $p->method, 'color' => 'sky'],
                    'paid_at' => $p->paid_at->format('d M Y H:i'),
                    'amount' => ['money' => $p->amount],
                ]" />
                @break

            @case('trainer-booking')
                <x-report-table :rows="$report['rows']" :columns="[
                    ['key' => 'member', 'label' => 'Member'],
                    ['key' => 'trainer', 'label' => 'Trainer'],
                    ['key' => 'date', 'label' => 'Tanggal'],
                    ['key' => 'time', 'label' => 'Jam'],
                    ['key' => 'status', 'label' => 'Status'],
                ]" :renderer="fn($b) => [
                    'member' => ['text' => $b->user->name, 'sub' => $b->user->email],
                    'trainer' => $b->trainer?->user?->name ?? '-',
                    'date' => $b->booking_date->format('d M Y'),
                    'time' => \Illuminate\Support\Carbon::parse($b->start_time)->format('H:i') . ' - ' . \Illuminate\Support\Carbon::parse($b->end_time)->format('H:i'),
                    'status' => ['badge' => $b->status, 'color' => $b->status === 'completed' ? 'emerald' : ($b->status === 'cancelled' ? 'rose' : ($b->status === 'confirmed' ? 'sky' : 'amber'))],
                ]" />
                @break

            @case('peak-hours')
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Peringkat</th>
                                <th class="px-5 py-3 font-semibold">Jam</th>
                                <th class="px-5 py-3 font-semibold">Kehadiran</th>
                                <th class="px-5 py-3 font-semibold">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($report['rows'] as $row)
                                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                                    <td class="px-5 py-3 font-bold text-brand-600 dark:text-brand-400">#{{ $row['rank'] }}</td>
                                    <td class="px-5 py-3 font-semibold">{{ sprintf('%02d:00', $row['hour']) }}</td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-2 w-32 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                                <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-violet-500" style="width: {{ min(100, $row['count']) }}%"></div>
                                            </div>
                                            <span class="text-slate-700 dark:text-slate-200">{{ $row['count'] }}x</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400">{{ $row['percentage'] }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">Tidak ada data pada periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @break

            @case('gym-activity')
                <x-report-table :rows="$report['rows']" :columns="[
                    ['key' => 'date', 'label' => 'Tanggal'],
                    ['key' => 'check_ins', 'label' => 'Check-in'],
                    ['key' => 'unique', 'label' => 'Member Unik'],
                    ['key' => 'total_minutes', 'label' => 'Total Menit'],
                    ['key' => 'avg_minutes', 'label' => 'Rata-rata'],
                ]" :renderer="fn($r) => [
                    'date' => \Illuminate\Support\Carbon::parse($r['date'])->translatedFormat('l, d M Y'),
                    'check_ins' => $r['check_ins'],
                    'unique' => $r['unique'],
                    'total_minutes' => $r['total_minutes'],
                    'avg_minutes' => $r['avg_minutes'] . ' menit',
                ]" />
                @break
        @endswitch

        @if($report['rows'] instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <x-pagination :items="$report['rows']" />
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dark = document.documentElement.classList.contains('dark');
            const chart = @json($report['chart'] ?? null);
            if (!chart || !chart.datasets || !chart.datasets.length) return;

            const canvas = document.getElementById('reportChart');
            if (!canvas) return;

            new Chart(canvas, {
                type: '{{ $type === "peak-hours" ? "bar" : "line" }}',
                data: {
                    labels: chart.labels,
                    datasets: chart.datasets.map(ds => ({
                        label: ds.label,
                        data: ds.data,
                        borderColor: ds.color,
                        backgroundColor: ds.color + (dark ? '30' : '18'),
                        fill: '{{ $type === "peak-hours" ? "false" : "origin" }}',
                        tension: 0.4,
                        borderWidth: 2.5,
                        borderRadius: 6,
                        maxBarThickness: 28,
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { labels: { boxWidth: 12, usePointStyle: true, color: dark ? '#94a3b8' : '#64748b' } },
                        tooltip: {
                            backgroundColor: dark ? '#0f172a' : '#ffffff',
                            titleColor: dark ? '#f1f5f9' : '#0f172a',
                            bodyColor: dark ? '#cbd5e1' : '#334155',
                            borderColor: dark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            callbacks: {
                                label: (ctx) => ' ' + (ctx.parsed.y !== undefined ? Number(ctx.parsed.y).toLocaleString('id-ID') : '') + (ctx.dataset.label === 'Pendapatan' || ctx.dataset.label === 'Aktivasi' ? '' : ''),
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: dark ? '#94a3b8' : '#64748b', maxTicksLimit: 12 },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: dark ? '#1e293b' : '#eef2f7' },
                            ticks: {
                                color: dark ? '#94a3b8' : '#64748b',
                                callback: (v) => v >= 1000000 ? (v/1000000) + 'jt' : v >= 1000 ? (v/1000) + 'rb' : v,
                            },
                        }
                    }
                }
            });
        });
    </script>
    @endpush

</x-admin-layout>
