<x-admin-layout title="Tambah Trainer" header="Tambahkan trainer baru.">
    <x-slot name="actions">
        <a href="{{ route('admin.trainers.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.trainers.store') }}">
        @csrf
        <x-card title="Identitas" subtitle="Pilih user trainer yang sudah ada, atau buat akun baru.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-label value="User Trainer (jika sudah ada)" />
                    <select name="user_id"
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        <option value="">-- Buat akun baru di bawah --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:col-span-2 sm:grid-cols-2">
                    <x-input name="name" label="Nama (akun baru)" placeholder="Nama lengkap" />
                    <x-input name="email" label="Email (akun baru)" type="email" placeholder="trainer@contoh.com" />
                </div>
            </div>
        </x-card>

        <x-card title="Detail Profesional" subtitle="Spesialisasi dan tarif." class="mt-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-input name="specialization" label="Spesialisasi" required placeholder="contoh: Strength & Conditioning" class="sm:col-span-2" />
                <x-input name="experience_years" label="Pengalaman (tahun)" type="number" min="0" max="50" required />
                <x-input name="hourly_rate" label="Tarif per Jam (Rp)" type="number" min="0" step="any" required />
            </div>
            <div class="mt-4">
                <x-label value="Bio" />
                <textarea name="bio" rows="3" placeholder="Deskripsi singkat trainer"
                          class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">{{ old('bio') }}</textarea>
            </div>
            <label class="mt-4 inline-flex cursor-pointer items-center gap-2.5">
                <input type="checkbox" name="is_available" value="1" @checked(old('is_available', true))
                       class="h-4.5 w-4.5 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-700">
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">Tersedia untuk booking</span>
            </label>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Trainer
            </button>
        </div>
    </form>
</x-admin-layout>
