<x-admin-layout title="Notifikasi" header="Semua notifikasi yang masuk.">
    @php
        $typeMap = [
            'info' => 'sky', 'success' => 'emerald', 'warning' => 'amber', 'danger' => 'rose',
            'system' => 'sky', 'payment' => 'emerald', 'membership' => 'emerald', 'achievement' => 'violet',
        ];
        $colorMap = ['sky' => 'bg-sky-50 text-sky-600', 'emerald' => 'bg-emerald-50 text-emerald-600', 'amber' => 'bg-amber-50 text-amber-600', 'rose' => 'bg-rose-50 text-rose-600', 'violet' => 'bg-violet-50 text-violet-600'];
    @endphp

    @if($notifications->where('read_at', null)->count() > 0)
        <div class="mb-5 flex justify-end">
            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
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
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari notifikasi..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($notifications as $notification)
                <div class="flex items-start gap-4 p-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $colorMap[$typeMap[$notification->type] ?? 'sky'] }}">
                        <x-icon :name="in_array($notification->type, ['success', 'payment', 'membership', 'achievement']) ? 'check-circle' : ($notification->type === 'warning' ? 'alert' : 'info')" class="h-5 w-5" />
                    </span>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $notification->title }}</h3>
                            @if($notification->read_at === null)
                                <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ $notification->message }}</p>
                        <p class="mt-1.5 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if($notification->read_at === null)
                        <form method="POST" action="{{ route('admin.notifications.read', $notification) }}">
                            @csrf
                            <button type="submit" title="Tandai dibaca"
                                    class="rounded-lg p-2 text-slate-400 transition hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                                <x-icon name="check" class="h-4 w-4" />
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-6">
                    <x-empty-state icon="bell" title="Tidak ada notifikasi" message="Notifikasi yang masuk akan tampil di sini." />
                </div>
            @endforelse
        </div>

        <x-pagination :items="$notifications" />
    </div>
</x-admin-layout>
