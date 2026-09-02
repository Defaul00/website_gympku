<x-admin-layout title="Booking Trainer" header="Kelola sesi latihan bersama trainer.">
    <x-slot name="actions">
        <a href="{{ route('admin.bookings.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Buat Booking
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.bookings.index') }}"
                   @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('status') ? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' : 'bg-brand-600 text-white'])>
                    Semua
                </a>
                @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Selesai', 'cancelled' => 'Batal'] as $key => $label)
                    <a href="{{ route('admin.bookings.index', ['status' => $key]) }}"
                       @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('status') === $key ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'])>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <form method="GET" class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari member atau trainer..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Member</th>
                        <th class="px-5 py-3 font-semibold">Trainer</th>
                        <th class="px-5 py-3 font-semibold">Jadwal</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($bookings as $booking)
                        @php
                            $colors = ['pending' => 'amber', 'confirmed' => 'sky', 'completed' => 'emerald', 'cancelled' => 'rose'];
                        @endphp
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $booking->user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->user->email }}</p>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-600 dark:text-slate-300">{{ $booking->trainer?->user?->name ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">
                                {{ $booking->booking_date->format('d M Y') }}
                                <span class="text-slate-400">{{ \Illuminate\Support\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                            </td>
                            <td class="px-5 py-3.5"><x-badge :color="$colors[$booking->status] ?? 'slate'">{{ $booking->status }}</x-badge></td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.bookings.edit', $booking) }}"
                                       class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 dark:hover:text-amber-400">
                                        <x-icon name="pencil" class="h-4 w-4" />
                                    </a>
                                    <button type="button"
                                            @click="$dispatch('confirm-modal', {
                                                title: 'Hapus Booking',
                                                message: 'Yakin ingin menghapus booking ini?',
                                                confirmText: 'Hapus',
                                                action: () => document.getElementById('delete-booking-{{ $booking->id }}').submit()
                                            })"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                    <form id="delete-booking-{{ $booking->id }}" method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="calendar" title="Belum ada booking" message="Buat booking trainer pertama." class="m-6" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$bookings" />
    </div>
</x-admin-layout>
