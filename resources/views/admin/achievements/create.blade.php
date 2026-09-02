<x-admin-layout title="Tambah Achievement" header="Tambahkan achievement baru.">
    <x-slot name="actions">
        <a href="{{ route('admin.achievements.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.achievements.store') }}">
        @csrf
        <x-card title="Detail Achievement" subtitle="Informasi achievement.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-input name="name" label="Nama Achievement" required placeholder="contoh: Konsisten 30 Hari" />
                <x-input name="points" label="Poin" type="number" min="0" required />
                <x-input name="icon" label="Nama Icon (opsional)" placeholder="trophy, fire, clock, dll." />
                <x-input name="badge_color" label="Warna Badge (opsional)" placeholder="amber, emerald, sky, dll." />
            </div>
            <div class="mt-4">
                <x-label value="Deskripsi" />
                <textarea name="description" rows="3" placeholder="Deskripsi achievement"
                          class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">{{ old('description') }}</textarea>
            </div>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Achievement
            </button>
        </div>
    </form>
</x-admin-layout>
