<x-admin-layout title="Dashboard">

    <!-- Hero banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-indigo-600 to-violet-600 p-6 text-white shadow-xl shadow-brand-500/25 sm:p-8" data-animate>
        <!-- Decorative -->
        <span class="pointer-events-none absolute inset-0 opacity-[0.08]"
              style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 26px 26px;"></span>
        <span class="pointer-events-none absolute -left-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></span>
        <span class="pointer-events-none absolute -bottom-28 right-16 h-72 w-72 rounded-full bg-fuchsia-400/25 blur-3xl"></span>
        <span class="pointer-events-none absolute right-1/3 top-0 h-32 w-32 rounded-full bg-white/10 blur-2xl"></span>
        <span class="pointer-events-none absolute right-[8%] top-10 h-2 w-2 rounded-full bg-emerald-300"></span>
        <span class="pointer-events-none absolute right-[22%] bottom-16 h-1.5 w-1.5 rounded-full bg-white/70"></span>

        <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-xl">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur-sm">
                    <span class="flex h-2 w-2 animate-pulse rounded-full bg-emerald-300"></span>
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Halo, {{ strtok(auth()->user()->name, ' ') }}! <i class="fa-solid fa-hand text-2xl"></i></h1>
                <p class="mt-3 text-sm leading-relaxed text-white/85 sm:text-base">
                    Berikut ringkasan performa <strong>Physio Gym</strong> hari ini. Pantau check-in, pendapatan, dan aktivitas member dari satu layar.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.reports.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-700 shadow-lg shadow-black/10 transition-all hover:-translate-y-0.5 hover:shadow-xl">
                        <x-icon name="chart" class="h-4.5 w-4.5" />
                        Generate Laporan
                    </a>
                    <a href="{{ route('admin.members.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-bold text-white backdrop-blur-sm transition-all hover:-translate-y-0.5 hover:bg-white/20">
                        <x-icon name="plus" class="h-4.5 w-4.5" />
                        Daftar Member Baru
                    </a>
                </div>
            </div>

            <div class="grid w-full shrink-0 grid-cols-3 gap-px overflow-hidden rounded-2xl border border-white/15 bg-white/10 backdrop-blur-sm sm:max-w-md">
                <div class="bg-white/[0.08] px-4 py-4 text-center">
                    <p class="flex items-baseline justify-center gap-0.5 text-2xl font-extrabold">
                        {{ $todayCheckIns }}<span class="text-xs font-semibold text-white/70">kali</span>
                    </p>
                    <p class="mt-1 text-[11px] font-medium text-white/70">Check-in Hari Ini</p>
                </div>
                <div class="bg-white/[0.08] px-4 py-4 text-center">
                    <p class="flex items-baseline justify-center gap-0.5 text-2xl font-extrabold">
                        {{ number_format($activeMemberships, 0, ',', '.') }}<span class="text-xs font-semibold text-white/70">kartu</span>
                    </p>
                    <p class="mt-1 text-[11px] font-medium text-white/70">Kartu Aktif</p>
                </div>
                <div class="bg-white/[0.08] px-4 py-4 text-center">
                    <p class="flex items-baseline justify-center gap-0.5 text-2xl font-extrabold">
                        Rp{{ number_format($monthlyRevenue / 1000000, 1, ',', '.') }}<span class="text-xs font-semibold text-white/70">jt</span>
                    </p>
                    <p class="mt-1 text-[11px] font-medium text-white/70">Pendapatan Bulan Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-stat-card label="Check-in Hari Ini" :value="$todayCheckIns" :delta="$checkInsDelta" icon="stopwatch" color="indigo" suffix="member" />
        <x-stat-card label="Membership Aktif" :value="$activeMemberships" icon="card" color="emerald" suffix="kartu" />
        <x-stat-card label="Pendapatan Bulan Ini" :value="$monthlyRevenue" :delta="$revenueDelta" icon="wallet" color="amber" currency />
        <x-stat-card label="Booking Pending" :value="$pendingBookings" icon="calendar" color="rose" suffix="sesi" />
        <x-stat-card label="Expiring 7 Hari" :value="$expiringSoon" icon="alert" color="sky" suffix="kartu" />
    </div>

    <!-- Charts row -->
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-card title="Tren Pendapatan" subtitle="12 bulan terakhir" class="lg:col-span-2">
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </x-card>

        <x-card title="Distribusi Membership" subtitle="Berdasarkan paket aktif">
            <div class="relative h-72">
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($activeMemberships, 0, ',', '.') }}</p>
                    <p class="text-xs font-medium text-slate-400">Kartu Aktif</p>
                </div>
                <canvas id="membershipChart"></canvas>
            </div>
        </x-card>
    </div>

    <!-- Attendance row -->
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-card title="Kehadiran 14 Hari Terakhir" subtitle="Jumlah check-in per hari" class="lg:col-span-2">
            <div class="h-64">
                <canvas id="attendanceChart"></canvas>
            </div>
        </x-card>

        <x-card title="Kehadiran Terbaru">
            <x-slot name="actions">
                <a href="{{ route('admin.attendances.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">Lihat semua</a>
            </x-slot>
            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recentAttendances as $attendance)
                    <li class="flex items-center gap-3 py-2.5 first:pt-0 last:pb-0">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-xs font-bold text-white">
                            {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $attendance->user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $attendance->check_in->diffForHumans() }}</p>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                            {{ $attendance->duration_minutes ? $attendance->duration_minutes . ' menit' : 'Berlatih' }}
                        </span>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-slate-400">Belum ada kehadiran.</li>
                @endforelse
            </ul>
        </x-card>
    </div>

    <!-- Bottom row -->
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-card title="Transaksi Terbaru" subtitle="Pembayaran terakhir masuk">
            <x-slot name="actions">
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">Lihat semua</a>
            </x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentPayments as $payment)
                            <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="py-3">
                                    <p class="flex items-center gap-2 font-semibold text-slate-800 dark:text-slate-100">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                                            {{ strtoupper(substr($payment->user->name, 0, 1)) }}
                                        </span>
                                        <span class="truncate">{{ $payment->user->name }}</span>
                                    </p>
                                    <p class="mt-0.5 pl-10 text-xs text-slate-400">{{ $payment->method }} &middot; {{ $payment->paid_at->diffForHumans() }}</p>
                                </td>
                                <td class="py-3 text-right">
                                    <p class="font-bold text-slate-800 dark:text-slate-100">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    <p class="mt-1"><x-badge :color="$payment->status === 'paid' ? 'emerald' : 'amber'">{{ $payment->status }}</x-badge></p>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="py-4 text-sm text-slate-400">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card title="Akses Cepat" subtitle="Langsung menuju laporan yang dibutuhkan">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                @foreach(\App\Services\ReportService::definitions() as $type => $def)
                    <a href="{{ route('admin.reports.show', $type) }}" data-animate-on-view
                       class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-300 hover:bg-white hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-brand-600 dark:hover:bg-slate-800">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-100 text-brand-600 transition-colors group-hover:bg-brand-600 group-hover:text-white dark:bg-brand-500/15 dark:text-brand-300">
                            <x-icon name="{{ $type === 'attendance' ? 'check' : ($type === 'active-memberships' ? 'card' : ($type === 'expired-memberships' ? 'x-circle' : ($type === 'revenue' ? 'wallet' : ($type === 'trainer-booking' ? 'calendar' : ($type === 'peak-hours' ? 'clock' : 'fire'))))) }}" class="h-5 w-5" />
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $def['title'] }}</span>
                            <span class="block truncate text-xs text-slate-400">Lihat laporan & export</span>
                        </span>
                        <x-icon name="arrow-right" class="ml-auto h-4 w-4 shrink-0 text-slate-300 transition-transform group-hover:translate-x-0.5 group-hover:text-brand-500 dark:text-slate-600" />
                    </a>
                @endforeach
            </div>
        </x-card>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            const grid = theme === 'dark' ? '#334155' : '#e2e8f0';
            const tick = theme === 'dark' ? '#94a3b8' : '#64748b';

            Chart.defaults.color = tick;
            Chart.defaults.borderColor = grid;
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

            const gradient = document.getElementById('revenueChart').getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: @json($revenueLabels),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($revenueData),
                        borderColor: '#6366f1',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: theme === 'dark' ? '#1e293b' : '#0f172a',
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: (ctx) => ' Rp ' + Number(ctx.raw).toLocaleString('id-ID'),
                            }
                        }
                    },
                    scales: {
                        y: { grid: { color: (ctx) => ctx.tick === 0 ? 'rgba(148,163,184,0.4)' : grid }, ticks: { callback: (v) => 'Rp ' + (v >= 1000000 ? (v/1000000) + 'jt' : v >= 1000 ? (v/1000) + 'rb' : v) } },
                    }
                }
            });

            new Chart(document.getElementById('attendanceChart'), {
                type: 'bar',
                data: {
                    labels: @json($attendanceLabels),
                    datasets: [{
                        label: 'Check-in',
                        data: @json($attendanceData),
                        backgroundColor: (ctx) => {
                            const idx = ctx.dataIndex;
                            return idx === {{ count($attendanceData) - 1 }} ? 'rgba(99, 102, 241, 0.95)' : 'rgba(16, 185, 129, 0.55)';
                        },
                        borderRadius: 6,
                        maxBarThickness: 26,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } },
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });

            const membershipLabels = @json($membershipDistribution->keys());
            const membershipData = @json($membershipDistribution->values());
            const palette = ['#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#0ea5e9'];

            new Chart(document.getElementById('membershipChart'), {
                type: 'doughnut',
                data: {
                    labels: membershipLabels,
                    datasets: [{
                        data: membershipData,
                        backgroundColor: membershipLabels.map((_, i) => palette[i % palette.length]),
                        borderWidth: 3,
                        borderColor: theme === 'dark' ? '#0f172a' : '#ffffff',
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, boxHeight: 12, padding: 12, usePointStyle: true } },
                        tooltip: {
                            backgroundColor: theme === 'dark' ? '#1e293b' : '#0f172a',
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: (ctx) => {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total ? Math.round((ctx.raw / total) * 100) : 0;
                                    return ' ' + ctx.label + ': ' + ctx.raw + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush

</x-admin-layout>
