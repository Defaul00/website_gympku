@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 rounded-lg px-3 py-2 border-b-2 border-brand-500 text-sm font-bold text-brand-600 dark:text-brand-400 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center gap-2 rounded-lg px-3 py-2 border-b-2 border-transparent text-sm font-semibold text-slate-500 hover:text-slate-700 hover:border-slate-300 focus:outline-none transition duration-150 ease-in-out dark:text-slate-400 dark:hover:text-slate-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
