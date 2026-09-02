<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-violet-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-600/25 transition duration-200 hover:from-brand-700 hover:to-violet-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:translate-y-px dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</button>
