<!DOCTYPE html>
<html lang="id" x-data>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifikasi - {{ config('app.name', 'Physio Gym') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">
    @include('layouts.dashboard-nav')

    <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6">
        <div class="mb-6">
            <p class="text-sm font-semibold text-brand-600 dark:text-brand-400">Akun member</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Notifikasi</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Informasi terbaru tentang membership dan aktivitas akunmu.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
        @endif

        @if($notifications->whereNull('read_at')->isNotEmpty())
            <div class="mb-5 flex justify-end">
                <form method="POST" action="{{ route('user.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                        <x-icon name="check-circle" class="h-4 w-4" />
                        Tandai semua dibaca
                    </button>
                </form>
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total <span class="font-bold text-slate-800 dark:text-slate-100">{{ $notifications->total() }}</span> notifikasi</p>
                <form method="GET" class="relative w-full sm:w-72">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari notifikasi..." class="w-full rounded-xl border-0 bg-slate-50 px-3 py-2.5 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
                </form>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($notifications as $notification)
                    <div class="flex items-start gap-4 p-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <x-icon name="{{ $notification->type === 'membership_expiring' ? 'alert' : 'info' }}" class="h-5 w-5" />
                        </span>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $notification->title }}</h2>
                                @if($notification->read_at === null)<span class="h-2 w-2 rounded-full bg-brand-500"></span>@endif
                            </div>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $notification->body }}</p>
                            <p class="mt-1.5 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if($notification->read_at === null)
                            <form method="POST" action="{{ route('user.notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" title="Tandai dibaca" class="rounded-lg p-2 text-slate-400 transition hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                                    <x-icon name="check" class="h-4 w-4" />
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada notifikasi.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4 dark:border-slate-800">
                {{ $notifications->links() }}
            </div>
        </div>
    </main>
</body>
</html>
