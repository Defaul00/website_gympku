<!DOCTYPE html>
<html lang="id" x-data>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Beranda member Physio Gym — pantau keanggotaan, kehadiran, dan capaianmu.">
    <title>Beranda - {{ config('app.name', 'Physio Gym') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">

    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
        Langsung ke konten
    </a>

    <!-- Navbar -->
    @include('layouts.dashboard-nav')

    <main id="main-content" class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6">
        <!-- Hero -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-violet-600 p-6 text-white shadow-xl shadow-brand-500/20 sm:p-8" data-animate>
            <span class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></span>
            <span class="pointer-events-none absolute -bottom-24 right-10 h-64 w-64 rounded-full bg-violet-300/20 blur-3xl"></span>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur-sm">
                        <span class="flex h-2 w-2 animate-pulse rounded-full bg-emerald-300"></span>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Halo, {{ strtok(auth()->user()->name, ' ') }}! <i class="fa-solid fa-dumbbell text-2xl"></i></h1>
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/85 sm:text-base">
                        Selamat datang di Physio Gym. Pantau keanggotaan, kehadiran, dan capaian kamu di sini.
                    </p>
                </div>
                <div class="grid shrink-0 grid-cols-3 gap-3">
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center backdrop-blur-sm">
                        <p class="text-2xl font-extrabold">{{ $checkInsThisMonth }}</p>
                        <p class="mt-0.5 text-[11px] font-medium text-white/75">Check-in Bulan Ini</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center backdrop-blur-sm">
                        <p class="text-2xl font-extrabold">{{ $card ? $daysLeft : 0 }}</p>
                        <p class="mt-0.5 text-[11px] font-medium text-white/75">Hari Tersisa</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center backdrop-blur-sm">
                        <p class="text-2xl font-extrabold">{{ number_format($totalSpent, 0, ',', '.') }}</p>
                        <p class="mt-0.5 text-[11px] font-medium text-white/75">Total Bayar</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status keanggotaan -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2" data-animate-on-view>
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Status Keanggotaan</h2>
                <p class="mt-0.5 text-xs text-slate-400">Informasi kartu member aktif kamu</p>

                @if($card)
                    <div class="mt-6 overflow-hidden rounded-2xl bg-gradient-to-br from-brand-600 via-brand-500 to-violet-600 p-6 text-white shadow-lg shadow-brand-500/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-white/70">Physio Gym Membership</p>
                                <p class="mt-1 text-2xl font-extrabold tracking-tight">{{ $card->membership?->name ?? 'Membership' }}</p>
                            </div>
                            <x-icon name="card" class="h-10 w-10 text-white/60" />
                        </div>
                        <div class="mt-6 flex items-end justify-between">
                            <div>
                                <p class="text-[11px] text-white/60">Nomor Kartu</p>
                                <p class="font-mono text-lg font-bold tracking-widest">{{ $card->card_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[11px] text-white/60">Berlaku hingga</p>
                                <p class="text-lg font-bold">{{ $card->end_date->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('member-card.print', $card) }}" target="_blank" rel="noopener"
                       class="mt-4 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:bg-brand-700">
                        <x-icon name="document" class="h-4 w-4" />
                        Cetak Kartu
                    </a>
                @else
                    <div class="mt-6 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 py-12 text-center dark:border-slate-700">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800">
                            <x-icon name="card" class="h-7 w-7" />
                        </span>
                        <p class="mt-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Belum ada kartu aktif</p>
                        <p class="mt-1 text-xs text-slate-400">Hubungi admin gym untuk mengaktifkan keanggotaan kamu.</p>
                    </div>
                @endif
            </div>

            <!-- Statistik singkat -->
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400">
                        <x-icon name="stopwatch" class="h-5 w-5" />
                    </span>
                    <p class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $checkInsCount }}</p>
                    <p class="text-xs text-slate-400">Total Check-in</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400">
                        <x-icon name="wallet" class="h-5 w-5" />
                    </span>
                    <p class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-400">Total Pengeluaran</p>
                </div>
                <div class="col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Pencapaian Terkunci</p>
                        <span class="rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-bold text-brand-700 dark:bg-brand-500/15 dark:text-brand-300">{{ $achievements->count() }} / {{ $achievements->count() }}</span>
                    </div>
                    @if($achievements->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($achievements as $achievement)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                    <x-icon name="trophy" class="h-3.5 w-3.5" />
                                    {{ $achievement->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-2 text-xs text-slate-400">Belum ada pencapaian. Terus berlatih!</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pengumuman & pembayaran -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengumuman</h2>
                        <p class="mt-0.5 text-xs text-slate-400">Info terbaru dari gym</p>
                    </div>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300">
                        <x-icon name="megaphone" class="h-4.5 w-4.5" />
                    </span>
                </div>
                <ul class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($announcements as $announcement)
                        <li class="py-3 first:pt-0 last:pb-0">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-2 w-2 shrink-0 rounded-full bg-brand-500"></span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $announcement->title }}</p>
                                    <p class="mt-0.5 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $announcement->body }}</p>
                                    <p class="mt-1 text-[11px] text-slate-400">{{ $announcement->published_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-slate-400">Belum ada pengumuman.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Riwayat Pembayaran</h2>
                        <p class="mt-0.5 text-xs text-slate-400">Transaksi terakhir kamu</p>
                    </div>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400">
                        <x-icon name="receipt" class="h-4.5 w-4.5" />
                    </span>
                </div>
                <ul class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($recentPayments as $payment)
                        <li class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $payment->memberCard?->membership?->name ?? 'Pembayaran' }}</p>
                                <p class="text-xs text-slate-400">{{ $payment->method }} &middot; {{ $payment->paid_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-sm font-bold text-slate-800 dark:text-slate-100">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">Lunas</span>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-slate-400">Belum ada pembayaran.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </main>

    <footer class="border-t border-slate-200 py-6 text-center text-xs text-slate-400 dark:border-slate-800">
        &copy; {{ date('Y') }} Physio Gym &mdash; Jaga kesehatan, raih performa terbaikmu.
    </footer>
</body>
</html>
