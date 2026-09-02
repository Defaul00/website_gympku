@props(['rows', 'columns', 'renderer'])

@php
    $paginated = $rows instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $collection = $paginated ? $rows->getCollection() : $rows;
@endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
            <tr>
                @foreach($columns as $column)
                    <th class="px-5 py-3 font-semibold whitespace-nowrap">{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($collection as $row)
                @php $cells = $renderer($row); @endphp
                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40">
                    @foreach($columns as $column)
                        @php $cell = $cells[$column['key']] ?? '-'; @endphp
                        <td class="px-5 py-3.5">
                            @if(is_array($cell) && isset($cell['money']))
                                <span class="font-bold text-slate-800 dark:text-slate-100">Rp {{ number_format((float) $cell['money'], 0, ',', '.') }}</span>
                            @elseif(is_array($cell) && isset($cell['badge']))
                                @php $colors = ['active' => 'emerald', 'expired' => 'rose', 'paid' => 'emerald', 'pending' => 'amber', 'failed' => 'rose', 'refunded' => 'slate', 'completed' => 'emerald', 'confirmed' => 'sky', 'cancelled' => 'rose', 'transfer' => 'indigo', 'qris' => 'violet', 'cash' => 'amber', 'card' => 'sky']; @endphp
                                <x-badge :color="$cell['color'] ?? ($colors[strtolower((string) $cell['badge'])] ?? 'slate')">{{ $cell['badge'] }}</x-badge>
                            @elseif(is_array($cell) && isset($cell['text']))
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $cell['text'] }}</p>
                                    @if(! empty($cell['sub']))
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $cell['sub'] }}</p>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-600 dark:text-slate-300">{{ $cell }}</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-5 py-10 text-center text-slate-400">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
