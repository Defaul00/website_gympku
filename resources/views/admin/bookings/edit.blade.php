<x-admin-layout title="Edit Booking" header="Perbarui status atau catatan booking.">
    <x-slot name="actions">
        <a href="{{ route('admin.bookings.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
        @csrf @method('PUT')
        <x-card title="Detail Booking" subtitle="Informasi sesi saat ini.">
            <dl class="grid grid-cols-1 gap-3 rounded-xl bg-slate-50 p-4 text-sm sm:grid-cols-2 dark:bg-slate-800">
                <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Member</dt><dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $booking->user->name }}</dd></div>
                <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Trainer</dt><dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $booking->trainer?->user?->name ?? '-' }}</dd></div>
                <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal</dt><dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $booking->booking_date->format('d M Y') }}</dd></div>
                <div><dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Jam</dt><dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ \Illuminate\Support\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($booking->end_time)->format('H:i') }}</dd></div>
            </dl>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-label value="Status" :required="true" />
                    <select name="status" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Selesai', 'cancelled' => 'Batal'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $booking->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-label value="Catatan" />
                    <textarea name="notes" rows="4" placeholder="Catatan sesi"
                              class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">{{ old('notes', $booking->notes) }}</textarea>
                </div>
            </div>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-admin-layout>
