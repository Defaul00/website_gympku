<x-admin-layout title="Kehadiran" header="Kelola check-in & check-out member.">
    <x-slot name="actions">
        <a href="{{ route('admin.attendances.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Check-in / Check-out
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total <span class="font-bold text-slate-800 dark:text-slate-100">{{ $attendances->total() }}</span> catatan kehadiran</p>
            <form method="GET" class="flex flex-col gap-2 sm:flex-row">
                <input type="date" name="date" value="{{ request('date') }}"
                       onchange="this.form.submit()"
                       class="w-full rounded-xl border-0 bg-slate-50 px-3.5 py-2.5 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
                <div class="relative sm:w-72">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                        <x-icon name="search" class="h-4 w-4" />
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama member..."
                           class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Member</th>
                        <th class="px-5 py-3 font-semibold">Check-in</th>
                        <th class="px-5 py-3 font-semibold">Check-out</th>
                        <th class="px-5 py-3 font-semibold">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($attendances as $attendance)
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $attendance->user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $attendance->user->email }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-icon name="check-circle" class="h-4 w-4 text-emerald-500" />
                                    {{ $attendance->check_in->format('d M Y H:i') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">
                                @if($attendance->check_out)
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-icon name="x-circle" class="h-4 w-4 text-rose-400" />
                                        {{ $attendance->check_out->format('d M Y H:i') }}
                                    </span>
                                @else
                                    <x-badge color="amber">Sedang di gym</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $attendance->duration_minutes ? $attendance->duration_minutes . ' menit' : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-empty-state icon="fingerprint" title="Belum ada kehadiran" message="Belum ada catatan kehadiran untuk filter ini." class="m-6" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$attendances" />
    </div>
</x-admin-layout>
