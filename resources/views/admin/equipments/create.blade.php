<x-admin-layout title="Tambah Peralatan" header="Tambahkan peralatan gym baru.">
    <x-slot name="actions">
        <a href="{{ route('admin.equipments.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.equipments.store') }}">
        @csrf
        <x-card title="Detail Peralatan" subtitle="Informasi peralatan gym.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-input name="name" label="Nama Peralatan" required placeholder="contoh: Treadmill Pro 3000" />
                <x-input name="category" label="Kategori" required placeholder="contoh: Cardio, Free Weight, Mesin" />
                <div>
                    <x-label value="Kondisi" :required="true" />
                    <select name="condition" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        @foreach(['good' => 'Layak', 'needs_maintenance' => 'Perlu Service', 'poor' => 'Rusak'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('condition') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input name="last_maintenance" label="Maintenance Terakhir" type="date" />
                <x-input name="next_maintenance" label="Maintenance Berikutnya" type="date" />
            </div>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Peralatan
            </button>
        </div>
    </form>
</x-admin-layout>
