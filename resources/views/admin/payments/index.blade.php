<x-admin-layout title="Pembayaran" header="Kelola seluruh transaksi pembayaran member.">
    <x-slot name="actions">
        <a href="{{ route('admin.payments.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
            <x-icon name="plus" class="h-5 w-5" />
            Catat Pembayaran
        </a>
    </x-slot>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.payments.index') }}"
                   @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('status') ? 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' : 'bg-brand-600 text-white'])>
                    Semua
                </a>
                @foreach(['paid' => 'Lunas', 'pending' => 'Pending', 'failed' => 'Gagal', 'refunded' => 'Refund'] as $key => $label)
                    <a href="{{ route('admin.payments.index', ['status' => $key]) }}"
                       @class(['rounded-full px-3.5 py-1.5 text-xs font-bold transition', request('status') === $key ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'])>
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <form method="GET" class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" class="h-4 w-4" />
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari member atau referensi..."
                       class="w-full rounded-xl border-0 bg-slate-50 py-2.5 pl-9 pr-3 text-sm ring-1 ring-inset ring-slate-200 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:ring-slate-700">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Member</th>
                        <th class="px-5 py-3 font-semibold">Referensi</th>
                        <th class="px-5 py-3 font-semibold">Metode</th>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-right font-semibold">Nominal</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($payments as $payment)
                        @php
                            $colors = ['paid' => 'emerald', 'pending' => 'amber', 'failed' => 'rose', 'refunded' => 'slate'];
                            $methods = ['transfer' => 'Transfer', 'qris' => 'QRIS', 'cash' => 'Tunai', 'card' => 'Kartu'];
                        @endphp
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $payment->user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $payment->user->email }}</p>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $payment->reference }}</td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-300">{{ $methods[$payment->method] ?? ucfirst($payment->method) }}</td>
                            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $payment->paid_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-800 dark:text-slate-100">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5"><x-badge :color="$colors[$payment->status] ?? 'slate'">{{ $payment->status }}</x-badge></td>
                            <td class="px-5 py-3.5">
                                <div class="flex justify-end">
                                    <button type="button"
                                            @click="$dispatch('confirm-modal', {
                                                title: 'Hapus Pembayaran',
                                                message: 'Yakin ingin menghapus pembayaran {{ $payment->reference }}?',
                                                confirmText: 'Hapus',
                                                action: () => document.getElementById('delete-payment-{{ $payment->id }}').submit()
                                            })"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                    <form id="delete-payment-{{ $payment->id }}" method="POST" action="{{ route('admin.payments.destroy', $payment) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state icon="receipt" title="Belum ada pembayaran" message="Catat pembayaran pertama dengan menekan tombol Catat Pembayaran." class="m-6" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-pagination :items="$payments" />
    </div>
</x-admin-layout>
