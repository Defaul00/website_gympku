@props(['title' => 'Dashboard', 'header' => null, 'actions' => null])

@php
    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
    $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
@endphp

<!DOCTYPE html>
<html lang="id" x-data>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Panel manajemen Physio Gym — kelola member, pembayaran, kehadiran, dan laporan.">
    <title>{{ $title }} - {{ config('app.name', 'Physio Gym') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ notificationsOpen: false, userMenuOpen: false }"
      class="min-h-screen bg-slate-100 text-slate-800 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">

    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[70] focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
        Langsung ke konten
    </a>

    <!-- Toast container -->
    <div x-data x-cloak
         class="pointer-events-none fixed inset-x-0 top-4 z-[60] flex flex-col items-center gap-2 px-4 sm:items-end sm:pr-6"
         @toast.window="$store.toast.push($event.detail.message, $event.detail.type)">
        <template x-for="toast in $store.toast.items" :key="toast.id">
            <div x-show="true" x-transition:enter="transition duration-300 ease-out"
                 x-transition:enter-start="opacity-0 translate-y-[-8px] scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-xl dark:border-slate-700 dark:bg-slate-800"
                 :class="toast.type === 'error' ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-emerald-500'">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                      :class="toast.type === 'error' ? 'bg-rose-100 text-rose-600 dark:bg-rose-500/15 dark:text-rose-400' : 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400'">
                    <template x-if="toast.type === 'error'"><x-icon name="x-circle" class="h-5 w-5" /></template>
                    <template x-if="toast.type !== 'error'"><x-icon name="check-circle" class="h-5 w-5" /></template>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100"
                       x-text="toast.type === 'error' ? 'Terjadi Kesalahan' : 'Berhasil'"></p>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400" x-text="toast.message"></p>
                </div>
                <button @click="$store.toast.items = $store.toast.items.filter(i => i.id !== toast.id)"
                        class="shrink-0 rounded-md p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <x-icon name="x" class="h-4 w-4" />
                </button>
            </div>
        </template>
    </div>

    @if(session('success'))
        <div x-data x-init="$store.toast.push('{{ session('success') }}', 'success')" class="hidden"></div>
    @endif
    @if(session('error'))
        <div x-data x-init="$store.toast.push('{{ session('error') }}', 'error')" class="hidden"></div>
    @endif
    @if($errors->any())
        <div x-data x-init="$store.toast.push('{{ $errors->first() }}', 'error')" class="hidden"></div>
    @endif

    <!-- Main column -->
    <div class="flex min-h-screen flex-col">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/80 px-4 backdrop-blur-xl sm:px-6 dark:border-slate-800 dark:bg-slate-900/80">
            <a href="{{ route('admin.dashboard') }}" class="flex shrink-0 items-center">
                <span class="hidden text-lg font-extrabold tracking-tight text-slate-900 sm:block dark:text-white">
                    Physio<span class="text-brand-600 dark:text-brand-400">Gym</span>
                </span>
            </a>

            <form action="{{ route('admin.members.index') }}" method="GET" class="relative ml-2 hidden lg:block">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4.5 w-4.5" />
                </span>
                <input type="text" name="q" placeholder="Cari member, email, nomor HP..." value="{{ request('q') }}"
                       class="w-64 rounded-xl border-0 bg-slate-100 py-2 pl-9 pr-3 text-sm text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500 lg:w-80 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500">
            </form>

            <div class="ml-auto flex items-center gap-1.5">
                <!-- Theme toggle -->
                <button @click="$store.theme.toggle()"
                        class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-amber-500 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-amber-400"
                        title="Ganti tema">
                    <template x-if="$store.theme.dark">
                        <x-icon name="sun" class="h-5 w-5" />
                    </template>
                    <template x-if="!$store.theme.dark">
                        <x-icon name="moon" class="h-5 w-5" />
                    </template>
                </button>

                <!-- Notifications -->
                <div class="relative">
                    <button @click="notificationsOpen = !notificationsOpen; userMenuOpen = false"
                            class="relative rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800">
                        <x-icon name="bell" class="h-5 w-5" />
                        @if($unreadCount > 0)
                            <span class="absolute right-1.5 top-1.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <div x-show="notificationsOpen" x-cloak x-transition @click.outside="notificationsOpen = false"
                         class="absolute right-0 mt-2 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 dark:border-slate-700">
                            <p class="text-sm font-semibold">Notifikasi</p>
                            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                                @csrf
                                <button class="text-xs font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">Tandai semua dibaca</button>
                            </form>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($recentNotifications as $notification)
                                <div class="flex gap-3 border-b border-slate-50 px-4 py-3 transition hover:bg-slate-50 dark:border-slate-700/50 dark:hover:bg-slate-700/30">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                                          @class([
                                              'bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300' => $notification->type === 'info' || $notification->type === 'system',
                                              'bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-300' => $notification->type === 'warning',
                                              'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-300' => in_array($notification->type, ['payment', 'membership', 'achievement']),
                                          ])>
                                        <x-icon name="{{ $notification->type === 'warning' ? 'alert' : 'info' }}" class="h-4 w-4" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $notification->title }}</p>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $notification->body }}</p>
                                        <p class="mt-0.5 text-[11px] text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="px-4 py-8 text-center text-sm text-slate-400">Belum ada notifikasi.</p>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.notifications.index') }}" class="block border-t border-slate-100 px-4 py-2.5 text-center text-xs font-semibold text-brand-600 hover:bg-slate-50 dark:border-slate-700 dark:text-brand-400 dark:hover:bg-slate-700/30">Lihat semua notifikasi</a>
                    </div>
                </div>

                <!-- User menu -->
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen; notificationsOpen = false"
                            class="flex items-center gap-2 rounded-xl p-1.5 pr-2 transition hover:bg-slate-100 dark:hover:bg-slate-800">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block max-w-[120px] truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ auth()->user()->name }}</span>
                            <span class="block text-[11px] capitalize text-slate-400">{{ auth()->user()->role?->display_name ?? 'Member' }}</span>
                        </span>
                        <x-icon name="chevron-down" class="h-4 w-4 text-slate-400" />
                    </button>

                    <div x-show="userMenuOpen" x-cloak x-transition @click.outside="userMenuOpen = false"
                         class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-800">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700/30">
                            <x-icon name="user-circle" class="h-5 w-5" />
                            Profil Saya
                        </a>
                        <a href="/" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-700/30">
                            <x-icon name="document" class="h-5 w-5" />
                            Lihat Website
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10">
                                <x-icon name="logout" class="h-5 w-5" />
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main id="main-content" class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-[1600px]">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div data-animate>
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h1>
                        @isset($header)
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $header }}</p>
                        @endisset
                    </div>
                    @isset($actions)
                        <div class="flex flex-wrap items-center gap-2" data-animate>{{ $actions }}</div>
                    @endisset
                </div>

                <div data-animate-on-view>
                    {{ $slot }}
                </div>
            </div>
        </main>

        <footer class="border-t border-slate-200 px-4 py-4 text-center text-xs text-slate-400 sm:px-6 dark:border-slate-800">
            &copy; {{ date('Y') }} Physio Gym Management System &mdash; Dibuat dengan hati di Pekanbaru.
        </footer>
    </div>

    <!-- Confirmation modal -->
    <x-confirm-modal />

    @stack('scripts')
</body>
</html>
