<x-admin-layout title="Members" header="Kelola seluruh member gym beserta data membership-nya.">
    <x-slot name="actions">
        <a href="{{ route('admin.members.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Tambah Member
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total <span class="font-bold text-slate-800 dark:text-slate-100">{{ $members->total() }}</span> member</p>
            <form method="GET" class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau nomor HP..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Member</th>
                        <th class="px-5 py-3 font-semibold">Kontak</th>
                        <th class="px-5 py-3 font-semibold">Paket Aktif</th>
                        <th class="px-5 py-3 font-semibold">Masa Berlaku</th>
                        <th class="px-5 py-3 font-semibold">Bergabung</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($members as $member)
                        @php $activeCard = $member->memberCards->firstWhere('status', 'active'); @endphp
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-sm font-bold text-white">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $member->name }}</p>
                                        <p class="text-xs text-slate-400">#{{ $member->id }} &middot; {{ ucfirst($member->gender ?? '-') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">
                                <p>{{ $member->email }}</p>
                                <p class="text-xs text-slate-400">{{ $member->phone ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($activeCard && $activeCard->end_date >= now())
                                    <x-badge color="emerald">{{ $activeCard->membership->name }}</x-badge>
                                @else
                                    <x-badge color="rose">Tidak aktif</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">
                                @if($activeCard && $activeCard->end_date >= now())
                                    s/d {{ $activeCard->end_date->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $member->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.members.show', $member) }}" title="Detail"
                                       class="rounded-lg p-2 text-slate-400 transition hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                                        <x-icon name="identification" class="h-4.5 w-4.5" />
                                    </a>
                                    <a href="{{ route('admin.members.edit', $member) }}" title="Edit"
                                       class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 dark:hover:text-amber-400">
                                        <x-icon name="pencil" class="h-4.5 w-4.5" />
                                    </a>
                                    <button type="button" title="Hapus"
                                            @click="$dispatch('confirm-modal', {
                                                title: 'Hapus Member',
                                                message: 'Yakin ingin menghapus {{ $member->name }}? Seluruh data terkait juga akan dihapus.',
                                                confirmText: 'Hapus',
                                                action: () => document.getElementById('delete-member-{{ $member->id }}').submit()
                                            })"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                        <x-icon name="trash" class="h-4.5 w-4.5" />
                                    </button>
                                    <form id="delete-member-{{ $member->id }}" method="POST" action="{{ route('admin.members.destroy', $member) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state icon="users" title="Belum ada member" message="Tambahkan member pertama Anda dengan menekan tombol Tambah Member." class="m-6" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$members" />
    </div>
</x-admin-layout>
