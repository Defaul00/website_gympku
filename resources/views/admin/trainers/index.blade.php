<x-admin-layout title="Trainer" header="Kelola data trainer dan ketersediaannya.">
    <x-slot name="actions">
        <a href="{{ route('admin.trainers.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Tambah Trainer
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total <span class="font-bold text-slate-800 dark:text-slate-100">{{ $trainers->total() }}</span> trainer</p>
            <form method="GET" class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama trainer..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($trainers as $trainer)
                <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-brand-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand-600">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-600 text-lg font-bold text-white">{{ strtoupper(substr($trainer->user->name, 0, 1)) }}</span>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">{{ $trainer->user->name }}</h3>
                            <p class="text-xs font-medium text-brand-600 dark:text-brand-400">{{ $trainer->specialization }}</p>
                        </div>
                        @if($trainer->is_available)
                            <x-badge color="emerald" class="ml-auto">Tersedia</x-badge>
                        @else
                            <x-badge color="slate" class="ml-auto">Tidak</x-badge>
                        @endif
                    </div>
                    <p class="mt-3 flex-1 text-sm text-slate-500 dark:text-slate-400">{{ $trainer->bio ?? 'Tanpa bio.' }}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 dark:border-slate-800">
                        <div class="text-sm">
                            <p class="font-bold text-slate-800 dark:text-slate-100">Rp {{ number_format($trainer->hourly_rate, 0, ',', '.') }}<span class="text-xs font-normal text-slate-400">/jam</span></p>
                            <p class="text-xs text-slate-400">{{ $trainer->experience_years }} tahun pengalaman</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.trainers.edit', $trainer) }}"
                               class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 dark:hover:text-amber-400">
                                <x-icon name="pencil" class="h-4 w-4" />
                            </a>
                            <button type="button"
                                    @click="$dispatch('confirm-modal', {
                                        title: 'Hapus Trainer',
                                        message: 'Yakin ingin menghapus trainer {{ $trainer->user->name }}?',
                                        confirmText: 'Hapus',
                                        action: () => document.getElementById('delete-trainer-{{ $trainer->id }}').submit()
                                    })"
                                    class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                <x-icon name="trash" class="h-4 w-4" />
                            </button>
                            <form id="delete-trainer-{{ $trainer->id }}" method="POST" action="{{ route('admin.trainers.destroy', $trainer) }}" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <x-empty-state icon="briefcase" title="Belum ada trainer" message="Tambahkan trainer pertama Anda." class="m-6" />
                </div>
            @endforelse
        </div>

        <x-pagination :items="$trainers" />
    </div>
</x-admin-layout>
