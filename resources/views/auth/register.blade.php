<x-guest-layout>
    <div>
        <p class="inline-flex items-center gap-2.5 text-xs font-bold uppercase tracking-[0.25em] text-brand-400">
            <span class="h-px w-6 bg-brand-400/70"></span>
            Mulai Sekarang
        </p>
        <h1 class="mt-3 font-display text-4xl font-bold uppercase leading-none tracking-tight text-white">
            Buat akun baru
        </h1>
        <p class="mt-2 text-sm text-slate-400">Gratis. Mulai perjalanan kebugaranmu hari ini.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-1.5" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1.5" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" value="Nomor HP" />
            <x-text-input id="phone" class="mt-1.5" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" placeholder="08xxxxxxxxxx" />
            <x-input-error :messages="$errors->get('phone')" class="mt-1.5" />
        </div>

        <!-- Gender -->
        <div>
            <x-input-label for="gender" value="Jenis Kelamin" />
            <div class="relative mt-1.5">
                <select id="gender" name="gender"
                        class="block w-full appearance-none rounded-xl border-0 bg-white px-3.5 py-2.5 pr-10 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition duration-200 focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="male" @selected(old('gender') === 'male')>Laki-laki</option>
                    <option value="female" @selected(old('gender') === 'female')>Perempuan</option>
                </select>
                <x-icon name="chevron-down" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1.5"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-1.5"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit"
                class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-brand-600/25 transition duration-200 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:ring-offset-slate-900 active:translate-y-px">
            Daftar
            <x-icon name="user-circle" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
        </button>
    </form>

    <div class="mt-6 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3.5 text-center text-sm text-slate-400">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-bold text-brand-400 hover:text-brand-300">Masuk di sini</a>
    </div>
</x-guest-layout>
