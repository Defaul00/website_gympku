@props(['label', 'value', 'icon' => 'info', 'color' => 'indigo', 'suffix' => '', 'delta' => null, 'currency' => false])

@php
    $colors = [
        'indigo' => ['from-brand-500 to-violet-600', 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400', 'ring-brand-100 dark:ring-brand-500/20'],
        'emerald' => ['from-emerald-500 to-teal-600', 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400', 'ring-emerald-100 dark:ring-emerald-500/20'],
        'amber' => ['from-amber-500 to-orange-600', 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400', 'ring-amber-100 dark:ring-amber-500/20'],
        'rose' => ['from-rose-500 to-pink-600', 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400', 'ring-rose-100 dark:ring-rose-500/20'],
        'slate' => ['from-slate-500 to-slate-700', 'bg-slate-100 text-slate-600 dark:bg-slate-700/40 dark:text-slate-300', 'ring-slate-200 dark:ring-slate-700'],
        'sky' => ['from-sky-500 to-blue-600', 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400', 'ring-sky-100 dark:ring-sky-500/20'],
    ];
    [$gradient, $tint, $ring] = $colors[$color] ?? $colors['indigo'];
    $isUp = $delta >= 0;
@endphp

<div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-lg hover:shadow-slate-900/5 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700 dark:hover:shadow-black/30"
     data-animate>
    <span class="pointer-events-none absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br opacity-[0.05] blur-2xl transition-opacity duration-500 group-hover:opacity-15 {{ $gradient }}"></span>

    <div class="relative flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-[13px] font-semibold text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2.5 flex items-baseline gap-1">
                @if($currency)<span class="text-sm font-bold text-slate-400 dark:text-slate-500">Rp</span>@endif
                <span class="text-[26px] font-extrabold leading-none tracking-tight text-slate-900 dark:text-white">
                    {{ number_format((float) $value, 0, ',', '.') }}
                </span>
                @if($suffix)<span class="text-[13px] font-medium text-slate-400">{{ $suffix }}</span>@endif
            </p>
        </div>
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 transition-transform duration-300 group-hover:scale-110 {{ $tint }} {{ $ring }}">
            <x-icon :name="$icon" class="h-5.5 w-5.5" />
        </span>
    </div>

    @if($delta !== null)
        <div class="relative mt-3.5 flex items-center gap-2 border-t border-slate-100 pt-3 dark:border-slate-800">
            <span @class([
                'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-bold',
                'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' => $isUp,
                'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' => !$isUp,
            ])>
                <x-icon name="trending-up" :class="$isUp ? '' : '-scale-y-100'" class="h-3.5 w-3.5" />
                {{ abs($delta) }}%
            </span>
            <span class="text-[11px] text-slate-400">vs periode lalu</span>
        </div>
    @endif
</div>
