@props(['items', 'show' => true])

@if($items->hasPages())
    <nav class="flex items-center justify-between gap-3 border-t border-slate-200 px-5 py-4 dark:border-slate-800" aria-label="Pagination">
        <p class="hidden text-sm text-slate-500 sm:block dark:text-slate-400">
            Menampilkan <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $items->firstItem() ?? 0 }}</span> &ndash;
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $items->lastItem() ?? 0 }}</span> dari
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $items->total() }}</span> data
        </p>
        <div class="flex items-center gap-1">
            <a href="{{ $items->previousPageUrl() }}" @class(['inline-flex h-9 w-9 items-center justify-center rounded-lg text-sm transition', 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' => $items->onFirstPage(), 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => !$items->onFirstPage()])>
                <x-icon name="chevron-left" class="h-4 w-4" />
            </a>
            @foreach($items->getUrlRange(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}" @class([
                    'inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-2 text-sm font-semibold transition',
                    'bg-brand-600 text-white shadow-md shadow-brand-600/30' => $page === $items->currentPage(),
                    'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => $page !== $items->currentPage(),
                ])>{{ $page }}</a>
            @endforeach
            <a href="{{ $items->nextPageUrl() }}" @class(['inline-flex h-9 w-9 items-center justify-center rounded-lg text-sm transition', 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' => !$items->hasMorePages(), 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => $items->hasMorePages()])>
                <x-icon name="chevron-right" class="h-4 w-4" />
            </a>
        </div>
    </nav>
@endif
