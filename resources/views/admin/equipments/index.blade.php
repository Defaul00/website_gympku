<x-admin-layout title="Peralatan Gym" header="Kelola inventaris peralatan gym.">
    <x-slot name="actions">
        <a href="{{ route('admin.equipments.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Tambah Peralatan
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.equipments.index') }}"
                   @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('category') ? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' : 'bg-brand-600 text-white'])>
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('admin.equipments.index', ['category' => $category]) }}"
                       @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('category') === $category ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'])>
                        {{ $category }}
                    </a>
                @endforeach
            </div>
            <form method="GET" class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari peralatan..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Peralatan</th>
                        <th class="px-5 py-3 font-semibold">Kategori</th>
                        <th class="px-5 py-3 font-semibold">Kondisi</th>
                        <th class="px-5 py-3 font-semibold">Maintenance Terakhir</th>
                        <th class="px-5 py-3 font-semibold">Maintenance Berikut</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($equipments as $equipment)
                        @php
                            $conditions = ['good' => ['Layak', 'emerald'], 'needs_maintenance' => ['Perlu Service', 'amber'], 'poor' => ['Rusak', 'rose']];
                            $needService = $equipment->next_maintenance && $equipment->next_maintenance->lte(now()->addDays(7));
                        @endphp
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">
                                        <x-icon name="wrench" class="h-5 w-5" />
                                    </span>
                                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $equipment->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3.5"><x-badge color="indigo">{{ $equipment->category }}</x-badge></td>
                            <td class="px-5 py-3.5">
                                <x-badge :color="$conditions[$equipment->condition][1]">{{ $conditions[$equipment->condition][0] }}</x-badge>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $equipment->last_maintenance?->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                @if($needService)
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-amber-600 dark:text-amber-400">
                                        <x-icon name="alert" class="h-4 w-4" />
                                        {{ $equipment->next_maintenance->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-500 dark:text-slate-400">{{ $equipment->next_maintenance?->format('d M Y') ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.equipments.edit', $equipment) }}"
                                       class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 dark:hover:text-amber-400">
                                        <x-icon name="pencil" class="h-4 w-4" />
                                    </a>
                                    <button type="button"
                                            @click="$dispatch('confirm-modal', {
                                                title: 'Hapus Peralatan',
                                                message: 'Yakin ingin menghapus {{ $equipment->name }}?',
                                                confirmText: 'Hapus',
                                                action: () => document.getElementById('delete-equipment-{{ $equipment->id }}').submit()
                                            })"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                    <form id="delete-equipment-{{ $equipment->id }}" method="POST" action="{{ route('admin.equipments.destroy', $equipment) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state icon="wrench" title="Belum ada peralatan" message="Tambahkan peralatan gym pertama." class="m-6" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$equipments" />
    </div>
</x-admin-layout>
