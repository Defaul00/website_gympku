@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 w-full ps-3 pe-4 py-2 border-l-4 border-brand-500 text-start text-sm font-bold text-brand-600 bg-brand-50 focus:outline-none transition duration-150 ease-in-out dark:bg-brand-500/10 dark:text-brand-400'
            : 'flex items-center gap-3 w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 hover:border-slate-300 focus:outline-none transition duration-150 ease-in-out dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
