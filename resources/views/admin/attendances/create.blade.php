<x-admin-layout title="Check-in / Check-out" header="Catat kehadiran member secara manual.">
    <x-slot name="actions">
        <a href="{{ route('admin.attendances.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <x-card title="Pilih Member" subtitle="Member dengan status berwarna sedang berada di gym.">
        <form method="POST" action="{{ route('admin.attendances.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-label value="Member" :required="true" />
                    <select name="user_id" id="user_id" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        <option value="">-- Pilih Member --</option>
                        @foreach($members as $member)
                            <option value="{{ $member['id'] }}" @selected(old('user_id') == $member['id'])>
                                {{ $member['name'] }} {{ $member['checked_in'] ? '• sedang di gym' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-label value="Tindakan" :required="true" />
                    <select name="action" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        <option value="check_in">Check-in</option>
                        <option value="check_out">Check-out</option>
                    </select>
                </div>
            </div>

            <div class="mt-5 rounded-xl bg-slate-50 p-4 text-sm text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">Status member</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($members as $member)
                        <span @class([
                            'rounded-full px-2.5 py-1 text-xs font-semibold',
                            'bg-emerald-100 text-emerald-700' => $member['checked_in'],
                            'bg-slate-100 text-slate-500' => !$member['checked_in'],
                        ])>{{ $member['name'] }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                    <x-icon name="check" class="h-5 w-5" />
                    Simpan
                </button>
            </div>
        </form>
    </x-card>
</x-admin-layout>
