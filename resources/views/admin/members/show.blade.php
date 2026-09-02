@php
    $activeCard = $member->activeMemberCard();
    $membershipOptions = $memberships->mapWithKeys(fn ($m) => [
        $m->id => $m->name . ' — Rp ' . number_format((float) $m->price, 0, ',', '.') . ' / ' . $m->duration_months . ' bulan',
    ]);
@endphp

<x-admin-layout :title="$member->name" header="Profil lengkap member.">
    <x-slot name="actions">
        <a href="{{ route('admin.members.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
        @if($activeCard)
            <button type="button"
                    @click="$dispatch('confirm-modal', {
                        title: 'Nonaktifkan Membership',
                        message: 'Yakin ingin menonaktifkan membership {{ $member->name }}? Seluruh kartu aktif akan ditandai expired.',
                        confirmText: 'Nonaktifkan',
                        action: () => document.getElementById('deactivate-member').submit()
                    })"
                    class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-600/25 transition hover:bg-rose-700 dark:border-rose-500/30">
                <x-icon name="x-circle" class="h-4 w-4" />
                Nonaktifkan
            </button>
        @else
            <button type="button"
                    @click="$dispatch('activate-modal')"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">
                <x-icon name="check-circle" class="h-4 w-4" />
                Aktivasi Membership
            </button>
        @endif
        <a href="{{ route('admin.members.edit', $member) }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="pencil" class="h-4 w-4" />
            Edit
        </a>
        @if($activeCard)
            <a href="{{ route('member-card.print', $activeCard) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:text-brand-400">
                <x-icon name="document" class="h-4 w-4" />
                Cetak Kartu
            </a>
        @endif
    </x-slot>

    <form id="deactivate-member" method="POST" action="{{ route('admin.members.deactivate', $member) }}" class="hidden">
        @csrf
    </form>

    @if($activeCard)
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 dark:border-emerald-500/30 dark:bg-emerald-500/10">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white">
                <x-icon name="check-circle" class="h-5 w-5" />
            </span>
            <div>
                <p class="font-semibold text-emerald-800 dark:text-emerald-300">Membership Aktif</p>
                <p class="text-sm text-emerald-700 dark:text-emerald-400">Kartu {{ $activeCard->card_number }} — paket {{ $activeCard->membership->name }}, berlaku hingga {{ $activeCard->end_date->format('d M Y') }}.</p>
            </div>
        </div>
    @else
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 dark:border-rose-500/30 dark:bg-rose-500/10">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white">
                <x-icon name="x-circle" class="h-5 w-5" />
            </span>
            <div>
                <p class="font-semibold text-rose-800 dark:text-rose-300">Member Belum Aktif</p>
                <p class="text-sm text-rose-700 dark:text-rose-400">Klik "Aktivasi Membership" untuk memilih paket dan membuat kartu member secara otomatis.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6">
            <x-card padding="false">
                <div class="h-24 rounded-t-2xl bg-gradient-to-r from-brand-600 via-violet-600 to-fuchsia-600"></div>
                <div class="-mt-10 px-6 pb-6">
                    <span class="flex h-20 w-20 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-brand-500 to-violet-600 text-2xl font-bold text-white shadow-lg dark:border-slate-900">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    <h2 class="mt-3 text-lg font-bold text-slate-800 dark:text-slate-100">{{ $member->name }}</h2>
                    <p class="text-sm text-slate-400">{{ $member->email }}</p>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-slate-400">Nomor HP</dt><dd class="font-semibold text-slate-700 dark:text-slate-200">{{ $member->phone ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Jenis Kelamin</dt><dd class="font-semibold text-slate-700 dark:text-slate-200">{{ ucfirst($member->gender ?? '-') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Tgl Lahir</dt><dd class="font-semibold text-slate-700 dark:text-slate-200">{{ $member->birth_date?->format('d M Y') ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Bergabung</dt><dd class="font-semibold text-slate-700 dark:text-slate-200">{{ $member->created_at->format('d M Y') }}</dd></div>
                    </dl>
                </div>
            </x-card>

            <x-card title="Prestasi" subtitle="Achievement yang diraih">
                @forelse($member->achievements as $achievement)
                    <div class="flex items-center gap-3 py-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600">
                            <x-icon name="trophy" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $achievement->name }}</p>
                            <p class="text-xs text-slate-400">{{ $achievement->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="py-2 text-sm text-slate-400">Belum ada achievement.</p>
                @endforelse
            </x-card>
        </div>

        <div class="space-y-6 lg:col-span-2">
            <div class="grid grid-cols-2 gap-4">
                <x-stat-card label="Total Check-in" :value="$member->attendances->count()" icon="fingerprint" color="brand" />
                <x-stat-card label="Total Pembayaran" :value="$member->payments->sum('amount')" icon="wallet" color="emerald" currency />
            </div>

            <x-card title="Membership" subtitle="Riwayat kartu & paket membership.">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-wider text-slate-400">
                            <tr><th class="pb-2 font-semibold">No. Kartu</th><th class="pb-2 font-semibold">Paket</th><th class="pb-2 font-semibold">Periode</th><th class="pb-2 font-semibold">Status</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($member->memberCards as $card)
                                <tr>
                                    <td class="py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-semibold">{{ $card->card_number }}</span>
                                            <a href="{{ route('member-card.print', $card) }}" target="_blank" rel="noopener" title="Cetak kartu"
                                               class="rounded-lg p-1.5 text-slate-400 transition hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-500/10 dark:hover:text-brand-400">
                                                <x-icon name="document" class="h-3.5 w-3.5" />
                                            </a>
                                        </div>
                                    </td>
                                    <td class="py-3 font-semibold text-slate-700 dark:text-slate-200">{{ $card->membership->name }}</td>
                                    <td class="py-3 text-slate-500 dark:text-slate-400">{{ $card->start_date->format('d M Y') }} - {{ $card->end_date->format('d M Y') }}</td>
                                    <td class="py-3"><x-badge :color="$card->status === 'active' ? 'emerald' : 'rose'">{{ $card->status }}</x-badge></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-slate-400">Belum ada membership.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <x-card title="Riwayat Pembayaran" subtitle="Semua transaksi member.">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-wider text-slate-400">
                            <tr><th class="pb-2 font-semibold">Referensi</th><th class="pb-2 font-semibold">Metode</th><th class="pb-2 font-semibold">Tanggal</th><th class="pb-2 text-right font-semibold">Nominal</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($member->payments as $payment)
                                <tr>
                                    <td class="py-3 font-mono text-xs">{{ $payment->reference }}</td>
                                    <td class="py-3 text-slate-600 dark:text-slate-300">{{ ucfirst($payment->method) }}</td>
                                    <td class="py-3 text-slate-500 dark:text-slate-400">{{ $payment->paid_at->format('d M Y H:i') }}</td>
                                    <td class="py-3 text-right font-bold text-slate-800 dark:text-slate-100">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-slate-400">Belum ada pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>

    <div x-data="{ open: @json($errors->has('membership_id') || $errors->has('payment_method')) }" x-cloak @activate-modal.window="open = true">
        <div x-show="open" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
             @keydown.escape.window="open = false">
            <div @click="open = false" class="absolute inset-0"></div>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 @click.stop
                 class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-800">
                <div class="flex items-start gap-4 p-6">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400">
                        <x-icon name="check-circle" class="h-6 w-6" />
                    </span>
                    <div class="min-w-0">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Aktivasi Membership</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih paket untuk {{ $member->name }}. Kartu member akan dibuat otomatis.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.members.activate', $member) }}" class="space-y-4 border-t border-slate-100 bg-slate-50 px-6 py-5 dark:border-slate-700 dark:bg-slate-900/50">
                    @csrf
                    <x-select name="membership_id" label="Paket Membership" :options="$membershipOptions" required />
                    <x-select name="payment_method" label="Metode Pembayaran" :options="['transfer' => 'Transfer Bank', 'qris' => 'QRIS', 'cash' => 'Tunai (Cash)', 'card' => 'Kartu']" value="transfer" required />
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="open = false"
                                class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700">
                            Batal
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <x-icon name="check-circle" class="h-4 w-4" />
                            Aktivasi & Buat Kartu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
