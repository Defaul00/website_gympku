<x-admin-layout title="Edit Member" header="Perbarui data member.">
    <x-slot name="actions">
        <a href="{{ route('admin.members.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.members.update', $member) }}">
        @csrf @method('PUT')
        <x-card title="Data Member" subtitle="Informasi pribadi member.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-input name="name" label="Nama Lengkap" required value="{{ $member->name }}" />
                <x-input name="email" label="Email" type="email" required value="{{ $member->email }}" />
                <x-input name="phone" label="Nomor HP" value="{{ $member->phone }}" />
                <div class="space-y-1">
                    <x-label for="gender" value="Jenis Kelamin" />
                    <div class="relative">
                        <select id="gender" name="gender"
                                class="block w-full appearance-none rounded-xl border-0 bg-white px-3.5 py-2.5 pr-10 text-sm text-slate-800 shadow-sm ring-1 ring-inset transition duration-200 focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 {{ $errors->has('gender') ? 'ring-rose-400 focus:ring-rose-500' : 'ring-slate-300 dark:ring-slate-700' }}">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="male" @selected(old('gender', $member->gender) === 'male')>Laki-laki</option>
                            <option value="female" @selected(old('gender', $member->gender) === 'female')>Perempuan</option>
                        </select>
                        <x-icon name="chevron-down" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
                    </div>
                    @error('gender')<p class="text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
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
