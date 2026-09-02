@props(['rows' => 5, 'cols' => 4])

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
    <div class="space-y-4 p-5">
        @foreach(range(1, $rows) as $i)
            <div class="flex gap-4">
                @foreach(range(1, $cols) as $j)
                    <div class="skeleton h-4 flex-1 rounded"></div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
