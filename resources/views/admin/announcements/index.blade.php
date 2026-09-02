<x-admin-layout title="Pengumuman" header="Kelola pengumuman untuk member.">
    <x-slot name="actions">
        <a href="{{ route('admin.announcements.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Buat Pengumuman
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.announcements.index') }}"
                   @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('type') ? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' : 'bg-brand-600 text-white'])>
                    Semua
                </a>
                @foreach(['info' => 'Info', 'promo' => 'Promo', 'maintenance' => 'Maintenance', 'event' => 'Event'] as $key => $label)
                    <a href="{{ route('admin.announcements.index', ['type' => $key]) }}"
                       @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('type') === $key ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'])>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <form method="GET" class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari pengumuman..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($announcements as $announcement)
                @php $colors = ['info' => 'sky', 'promo' => 'violet', 'maintenance' => 'amber', 'event' => 'emerald']; @endphp
                <div class="flex items-start gap-4 p-5 transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                        <x-icon name="megaphone" class="h-5 w-5" />
                    </span>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">{{ $announcement->title }}</h3>
                            <x-badge :color="$colors[$announcement->type] ?? 'slate'">{{ $announcement->type }}</x-badge>
                        </div>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $announcement->body }}</p>
                        <p class="mt-2 text-xs text-slate-400">Dipublikasikan {{ $announcement->published_at?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-1">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}"
                           class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 dark:hover:text-amber-400">
                            <x-icon name="pencil" class="h-4 w-4" />
                        </a>
                        <button type="button"
                                @click="$dispatch('confirm-modal', {
                                    title: 'Hapus Pengumuman',
                                    message: 'Yakin ingin menghapus pengumuman ini?',
                                    confirmText: 'Hapus',
                                    action: () => document.getElementById('delete-announcement-{{ $announcement->id }}').submit()
                                })"
                                class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                            <x-icon name="trash" class="h-4 w-4" />
                        </button>
                        <form id="delete-announcement-{{ $announcement->id }}" method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-6">
                    <x-empty-state icon="megaphone" title="Belum ada pengumuman" message="Buat pengumuman pertama untuk member." />
                </div>
            @endforelse
        </div>

        <x-pagination :items="$announcements" />
    </div>
</x-admin-layout>
