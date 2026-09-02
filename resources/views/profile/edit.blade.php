<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-6 px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                <x-icon name="user-circle" class="h-6 w-6" />
            </span>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ __('Profil Saya') }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola informasi akun dan keamanan kamu.</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-800/50">
            <div class="p-6 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-rose-200 bg-white shadow-sm dark:border-rose-500/30 dark:bg-slate-800/50">
            <div class="p-6 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
