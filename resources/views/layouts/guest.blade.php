<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Masuk atau daftar ke Physio Gym — pusat kebugaran di Pekanbaru.">

        <title>{{ config('app.name', 'Physio Gym') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body data-force-dark class="min-h-screen bg-slate-950 font-sans text-slate-100 antialiased">

        <a href="#main-content"
           class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
            Langsung ke konten
        </a>

        <!-- Background -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -left-40 -top-40 h-[480px] w-[480px] rounded-full bg-brand-600/25 blur-[120px]"></div>
            <div class="absolute -bottom-40 -right-40 h-[480px] w-[480px] rounded-full bg-brand-600/15 blur-[120px]"></div>
        </div>

        <main id="main-content" class="relative flex min-h-screen flex-col items-center justify-center px-4 py-10">
            <!-- Brand -->
            <a href="/" class="mb-8 block" data-animate>
                <span class="font-display text-2xl font-bold uppercase tracking-wide text-white">
                    Physio<span class="text-brand-400">Gym</span>
                </span>
            </a>

            <!-- Card -->
            <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 p-8 shadow-2xl shadow-black/40 backdrop-blur-xl sm:p-9" data-animate>
                <span class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-brand-400/70 to-transparent"></span>
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-slate-500">
                &copy; {{ date('Y') }} Physio Gym &middot; Jaga kesehatan, raih performa terbaikmu.
            </p>
        </main>
    </body>
</html>
