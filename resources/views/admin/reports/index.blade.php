<x-admin-layout title="Generate Laporan" header="Pilih jenis laporan yang ingin di-generate, atur periode, lalu export ke PDF atau Excel.">

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3" data-animate>
        @php
            $icons = [
                'attendance' => 'check', 'active-memberships' => 'card', 'expired-memberships' => 'x-circle',
                'revenue' => 'wallet', 'trainer-booking' => 'calendar', 'peak-hours' => 'clock', 'gym-activity' => 'fire',
            ];
            $gradients = [
                'attendance' => 'from-emerald-500 to-teal-600', 'active-memberships' => 'from-brand-500 to-violet-600',
                'expired-memberships' => 'from-rose-500 to-pink-600', 'revenue' => 'from-amber-500 to-orange-600',
                'trainer-booking' => 'from-sky-500 to-blue-600', 'peak-hours' => 'from-orange-500 to-red-600',
                'gym-activity' => 'from-fuchsia-500 to-purple-600',
            ];
        @endphp
        @foreach($definitions as $type => $def)
            <a href="{{ route('admin.reports.show', $type) }}"
               class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900"
               data-animate>
                <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-slate-50 transition-transform duration-500 group-hover:scale-150 dark:bg-slate-800/60"></div>
                <div class="relative">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br text-white shadow-lg {{ $gradients[$type] }}">
                        <x-icon :name="$icons[$type]" class="h-6 w-6" />
                    </span>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">{{ $def['title'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $def['description'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 transition-colors group-hover:text-brand-700 dark:text-brand-400 dark:group-hover:text-brand-300">
                        Buka Laporan
                        <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                    </span>
                </div>
            </a>
        @endforeach
    </div>

</x-admin-layout>
