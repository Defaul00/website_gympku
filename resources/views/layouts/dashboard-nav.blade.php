@php
    $isDashboard = request()->routeIs('user.dashboard', 'trainer.dashboard', 'admin.dashboard');
    $isProfile = request()->routeIs('profile.edit');
    $linkBase = 'rounded-xl px-3 py-2 text-sm transition';
    $linkActive = 'bg-brand-50 font-semibold text-brand-700 dark:bg-brand-500/10 dark:text-brand-300';
    $linkIdle = 'font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100';
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/80">
    <div class="mx-auto flex h-16 w-full max-w-6xl items-center gap-4 px-4 sm:px-6">
        <a href="{{ route(Auth::user()->homeRoute()) }}" class="flex items-center">
            <span class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">
                Physio<span class="text-brand-600 dark:text-brand-400">Gym</span>
            </span>
        </a>

        <nav class="ml-8 hidden items-center gap-1 md:flex">
            <a href="{{ route(Auth::user()->homeRoute()) }}" class="{{ $linkBase }} {{ $isDashboard ? $linkActive : $linkIdle }}">Beranda</a>
            <a href="{{ route('profile.edit') }}" class="{{ $linkBase }} {{ $isProfile ? $linkActive : $linkIdle }}">Profil</a>
            <a href="/" class="{{ $linkBase }} {{ $linkIdle }}">Lihat Website</a>
        </nav>

        <div class="ml-auto flex items-center gap-3">
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

            <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                @csrf
                <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                    <x-icon name="logout" class="h-4 w-4" />
                    Keluar
                </button>
            </form>

            <button @click="open = !open" class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 md:hidden dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100" aria-label="Menu" aria-expanded="false" :aria-expanded="open.toString()">
                <template x-if="!open">
                    <x-icon name="menu" class="h-6 w-6" />
                </template>
                <template x-if="open">
                    <x-icon name="x" class="h-6 w-6" />
                </template>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak x-transition @click.outside="open = false" class="border-t border-slate-200 px-4 py-3 md:hidden dark:border-slate-800">
        <nav class="flex flex-col gap-1">
            <a href="{{ route(Auth::user()->homeRoute()) }}" class="{{ $linkBase }} {{ $isDashboard ? $linkActive : $linkIdle }}">Beranda</a>
            <a href="{{ route('profile.edit') }}" class="{{ $linkBase }} {{ $isProfile ? $linkActive : $linkIdle }}">Profil</a>
            <a href="/" class="{{ $linkBase }} {{ $linkIdle }}">Lihat Website</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-3 dark:border-slate-800">
                @csrf
                <button class="inline-flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10">
                    <x-icon name="logout" class="h-4 w-4" />
                    Keluar
                </button>
            </form>
        </nav>
    </div>
</header>
