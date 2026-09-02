<!DOCTYPE html>
<html lang="id" x-data>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Beranda pelatih Physio Gym — kelola jadwal dan sesi personal training Anda.">
    <title>Dashboard Pelatih - {{ config('app.name', 'Physio Gym') }}</title>

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
        @if($trainer)
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
                            Selamat datang kembali. Pantau jadwal sesi personal training Anda hari ini.
                        </p>
                    </div>
                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold backdrop-blur-sm">
                            <x-icon name="wrench" class="h-4 w-4 text-white/70" />
                            {{ $trainer->specialization }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold backdrop-blur-sm">
                            <x-icon name="clock" class="h-4 w-4 text-white/70" />
                            {{ $trainer->experience_years }} tahun pengalaman
                        </span>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">
                        <x-icon name="calendar" class="h-5 w-5" />
                    </span>
                    <p class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $todayCount }}</p>
                    <p class="text-xs text-slate-400">Sesi Hari Ini</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400">
                        <x-icon name="clock" class="h-5 w-5" />
                    </span>
                    <p class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalBookings }}</p>
                    <p class="text-xs text-slate-400">Total Booking</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400">
                        <x-icon name="stopwatch" class="h-5 w-5" />
                    </span>
                    <p class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $pendingBookings }}</p>
                    <p class="text-xs text-slate-400">Menunggu</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400">
                        <x-icon name="check-circle" class="h-5 w-5" />
                    </span>
                    <p class="mt-3 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $completedBookings }}</p>
                    <p class="text-xs text-slate-400">Sesi Selesai</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Jadwal hari ini -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Jadwal Hari Ini</h2>
                            <p class="mt-0.5 text-xs text-slate-400">{{ now()->translatedFormat('d F Y') }}</p>
                        </div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300">
                            <x-icon name="stopwatch" class="h-4.5 w-4.5" />
                        </span>
                    </div>
                    <ul class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($todaySchedule as $booking)
                            <li class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-xs font-bold text-white">
                                    {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $booking->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</p>
                                </div>
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                                      @class([
                                          'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' => $booking->status === 'pending',
                                          'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' => $booking->status === 'confirmed',
                                      ])>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </li>
                        @empty
                            <li class="py-8 text-center text-sm text-slate-400">Tidak ada sesi hari ini. Nikmati hari libur Anda!</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Booking mendatang -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900" data-animate-on-view>
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Booking Mendatang</h2>
                            <p class="mt-0.5 text-xs text-slate-400">Sesi yang sudah dijadwalkan</p>
                        </div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400">
                            <x-icon name="calendar" class="h-4.5 w-4.5" />
                        </span>
                    </div>
                    <ul class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($upcomingBookings as $booking)
                            <li class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-xs font-bold text-white">
                                    {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $booking->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->booking_date->translatedFormat('d M Y') }} &middot; {{ substr($booking->start_time, 0, 5) }} WIB</p>
                                </div>
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                                      @class([
                                          'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' => $booking->status === 'pending',
                                          'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' => $booking->status === 'confirmed',
                                      ])>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </li>
                        @empty
                            <li class="py-8 text-center text-sm text-slate-400">Belum ada booking mendatang.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @else
            <!-- Belum ada profil trainer -->
            <div class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white px-6 py-20 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800">
                    <x-icon name="wrench" class="h-8 w-8" />
                </span>
                <h1 class="mt-5 text-xl font-extrabold text-slate-900 dark:text-white">Profil Pelatih Belum Lengkap</h1>
                <p class="mt-2 max-w-md text-sm text-slate-500 dark:text-slate-400">Akun Anda terdaftar sebagai pelatih, namun profil pelatih belum dikonfigurasi. Hubungi admin untuk melengkapi profil dan jadwal Anda.</p>
                <a href="{{ route('profile.edit') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:bg-brand-700">
                    <x-icon name="user-circle" class="h-4 w-4" />
                    Lengkapi Profil
                </a>
            </div>
        @endif
    </main>

    <footer class="border-t border-slate-200 py-6 text-center text-xs text-slate-400 dark:border-slate-800">
        &copy; {{ date('Y') }} Physio Gym &mdash; Jaga kesehatan, raih performa terbaikmu.
    </footer>
</body>
</html>
