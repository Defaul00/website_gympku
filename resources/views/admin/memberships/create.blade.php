<x-admin-layout title="Tambah Paket" header="Buat paket membership baru.">
    <x-slot name="actions">
        <a href="{{ route('admin.memberships.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.memberships.store') }}">
        @csrf
        <x-card title="Detail Paket" subtitle="Informasi paket membership.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-input name="name" label="Nama Paket" required placeholder="contoh: Silver 3 Bulan" class="sm:col-span-2" />
                <x-input name="duration_months" label="Durasi (bulan)" type="number" min="1" max="60" required />
                <x-input name="price" label="Harga (Rp)" type="number" min="0" step="any" required />
            </div>
            <div class="mt-4">
                <x-label value="Deskripsi" />
                <textarea name="description" rows="3" placeholder="Deskripsi singkat paket"
                          class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">{{ old('description') }}</textarea>
            </div>
            <label class="mt-4 inline-flex cursor-pointer items-center gap-2.5">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                       class="h-4.5 w-4.5 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-700">
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">Paket aktif (dapat dipilih member)</span>
            </label>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Paket
            </button>
        </div>
    </form>
</x-admin-layout>
