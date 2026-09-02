<x-admin-layout title="Buat Booking" header="Buat sesi latihan dengan trainer.">
    <x-slot name="actions">
        <a href="{{ route('admin.bookings.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.bookings.store') }}">
        @csrf
        <x-card title="Detail Booking" subtitle="Pilih member, trainer, dan jadwal sesi.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-label value="Member" :required="true" />
                    <select name="user_id" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        <option value="">-- Pilih Member --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @selected(old('user_id') == $member->id)>{{ $member->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <x-label value="Trainer" :required="true" />
                    <select name="trainer_id" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        <option value="">-- Pilih Trainer --</option>
                        @foreach($trainers as $trainer)
                            <option value="{{ $trainer->id }}" @selected(old('trainer_id') == $trainer->id)>{{ $trainer->user->name }} ({{ $trainer->specialization }})</option>
                        @endforeach
                    </select>
                    @error('trainer_id')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                </div>
                <x-input name="booking_date" label="Tanggal Booking" type="date" required value="{{ old('booking_date', now()->format('Y-m-d')) }}" />
                <x-input name="start_time" label="Jam Mulai (1 jam sesi)" type="time" required value="{{ old('start_time', '08:00') }}" />
                <div>
                    <x-label value="Status" :required="true" />
                    <select name="status" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Selesai', 'cancelled' => 'Batal'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-label value="Catatan" />
                    <textarea name="notes" rows="3" placeholder="Catatan sesi (opsional)"
                              class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">{{ old('notes') }}</textarea>
                </div>
            </div>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Booking
            </button>
        </div>
    </form>
</x-admin-layout>
