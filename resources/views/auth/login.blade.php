<x-guest-layout>
    <div>
        <p class="inline-flex items-center gap-2.5 text-xs font-bold uppercase tracking-[0.25em] text-brand-400">
            <span class="h-px w-6 bg-brand-400/70"></span>
            Member Area
        </p>
        <h1 class="mt-3 font-display text-4xl font-bold uppercase leading-none tracking-tight text-white">
            Selamat datang kembali
        </h1>
        <p class="mt-2 text-sm text-slate-400">Masuk untuk melanjutkan sesi latihanmu.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mt-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1.5" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1.5"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-md border-slate-600 bg-slate-800 text-brand-500 shadow-sm focus:ring-brand-500" name="remember">
                <span class="ms-2 text-sm text-slate-300">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="rounded-md text-sm font-semibold text-brand-400 hover:text-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:ring-offset-slate-900" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <button type="submit"
                class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-brand-600/25 transition duration-200 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:ring-offset-slate-900 active:translate-y-px">
            Masuk
            <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
        </button>
    </form>

    <div class="mt-6 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3.5 text-center text-sm text-slate-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-bold text-brand-400 hover:text-brand-300">Daftar sekarang</a>
    </div>
</x-guest-layout>
