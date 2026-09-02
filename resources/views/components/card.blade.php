@props(['title' => null, 'subtitle' => null, 'actions' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    @if($title || $actions)
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
            <div>
                @if($title)<h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $title }}</h3>@endif
                @if($subtitle)<p class="mt-0.5 text-xs text-slate-400">{{ $subtitle }}</p>@endif
            </div>
            @if($actions)<div class="flex items-center gap-2">{{ $actions }}</div>@endif
        </div>
    @endif
    <div @class(['px-5 py-5' => $padding])>{{ $slot }}</div>
</div>
