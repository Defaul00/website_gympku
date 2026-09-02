<x-admin-layout title="Tambah Member" header="Tambahkan member baru ke sistem.">
    <x-slot name="actions">
        <a href="{{ route('admin.members.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.members.store') }}">
        @csrf
        <x-card title="Data Member" subtitle="Informasi pribadi member.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-input name="name" label="Nama Lengkap" required placeholder="Nama lengkap member" />
                <x-input name="email" label="Email" type="email" required placeholder="email@contoh.com" />
                <x-input name="phone" label="Nomor HP" placeholder="08xxxxxxxxxx" />
                <x-input name="gender" label="Jenis Kelamin" placeholder="male / female" />
                <x-input name="birth_date" label="Tanggal Lahir" type="date" />
                <x-input name="password" label="Password" type="password" required />
                <x-input name="password_confirmation" label="Konfirmasi Password" type="password" required />
            </div>
            <div class="mt-4">
                <x-label value="Alamat" />
                <textarea name="address" rows="3" placeholder="Alamat lengkap"
                          class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">{{ old('address') }}</textarea>
            </div>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Member
            </button>
        </div>
    </form>
</x-admin-layout>
