<x-admin-layout title="Paket Membership" header="Kelola paket-paket membership yang ditawarkan.">
    <x-slot name="actions">
        <a href="{{ route('admin.memberships.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Tambah Paket
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total <span class="font-bold text-slate-800 dark:text-slate-100">{{ $memberships->total() }}</span> paket</p>
            <form method="GET" class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama paket..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($memberships as $membership)
                <div class="group relative flex flex-col rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-brand-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand-600">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">{{ $membership->name }}</h3>
                            <p class="mt-0.5 text-xs font-medium text-slate-400">{{ $membership->duration_months }} bulan</p>
                        </div>
                        @if($membership->is_active)
                            <x-badge color="emerald">Aktif</x-badge>
                        @else
                            <x-badge color="rose">Nonaktif</x-badge>
                        @endif
                    </div>
                    <p class="mt-4 text-2xl font-extrabold text-brand-600 dark:text-brand-400">Rp {{ number_format($membership->price, 0, ',', '.') }}</p>
                    <p class="mt-2 flex-1 text-sm text-slate-500 dark:text-slate-400">{{ $membership->description ?? 'Tanpa deskripsi.' }}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-400">{{ $membership->member_cards_count }} member aktif</span>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.memberships.edit', $membership) }}"
                               class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 dark:hover:text-amber-400">
                                <x-icon name="pencil" class="h-4 w-4" />
                            </a>
                            <button type="button"
                                    @click="$dispatch('confirm-modal', {
                                        title: 'Hapus Paket',
                                        message: 'Yakin ingin menghapus paket {{ $membership->name }}?',
                                        confirmText: 'Hapus',
                                        action: () => document.getElementById('delete-membership-{{ $membership->id }}').submit()
                                    })"
                                    class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                <x-icon name="trash" class="h-4 w-4" />
                            </button>
                            <form id="delete-membership-{{ $membership->id }}" method="POST" action="{{ route('admin.memberships.destroy', $membership) }}" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <x-empty-state icon="card" title="Belum ada paket" message="Tambahkan paket membership pertama Anda." class="m-6" />
                </div>
            @endforelse
        </div>

        <x-pagination :items="$memberships" />
    </div>
</x-admin-layout>
