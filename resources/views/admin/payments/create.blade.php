<x-admin-layout title="Catat Pembayaran" header="Catat pembayaran baru dari member.">
    <x-slot name="actions">
        <a href="{{ route('admin.payments.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </a>
    </x-slot>

    <form method="POST" action="{{ route('admin.payments.store') }}">
        @csrf
        <x-card title="Detail Pembayaran" subtitle="Lengkapi informasi transaksi.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-label value="Member" :required="true" />
                    <select name="user_id" id="user_id" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        <option value="">-- Pilih Member --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @selected(old('user_id') == $member->id)>{{ $member->name }} ({{ $member->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="mt-1 text-xs font-medium text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <x-label value="Kartu Member (opsional)" />
                    <select name="member_card_id" id="member_card_id"
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        <option value="">-- Tidak terkait kartu --</option>
                        @foreach($cards as $card)
                            <option value="{{ $card->id }}" @selected(old('member_card_id') == $card->id)>{{ $card->user->name }} - {{ $card->membership->name }} (#{{ $card->card_number }})</option>
                        @endforeach
                    </select>
                </div>
                <x-input name="amount" label="Nominal (Rp)" type="number" min="0" step="any" required />
                <div>
                    <x-label value="Metode Pembayaran" :required="true" />
                    <select name="method" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        @foreach(['transfer' => 'Transfer Bank', 'qris' => 'QRIS', 'cash' => 'Tunai', 'card' => 'Kartu Debit/Kredit'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('method') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-label value="Status" :required="true" />
                    <select name="status" required
                            class="mt-1.5 block w-full rounded-xl border-0 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm ring-1 ring-inset ring-slate-300 transition focus:ring-2 focus:ring-brand-500 dark:bg-slate-800 dark:text-slate-100 dark:ring-slate-700">
                        @foreach(['paid' => 'Lunas (Paid)', 'pending' => 'Pending', 'failed' => 'Gagal', 'refunded' => 'Refund'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-card>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-brand-600/25 transition hover:bg-brand-700">
                <x-icon name="check" class="h-5 w-5" />
                Simpan Pembayaran
            </button>
        </div>
    </form>
</x-admin-layout>
