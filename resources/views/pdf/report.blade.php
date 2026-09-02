<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 12px; margin-bottom: 16px; }
        .header h1 { font-size: 20px; color: #312e81; margin-bottom: 4px; }
        .header .sub { color: #64748b; font-size: 10px; }
        .meta { width: 100%; margin-bottom: 16px; }
        .meta td { font-size: 10px; padding: 2px 0; color: #475569; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .summary td { border: 1px solid #e2e8f0; padding: 8px; text-align: center; }
        .summary .num { font-size: 15px; font-weight: bold; color: #4f46e5; display: block; }
        .summary .lbl { font-size: 9px; color: #64748b; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #4f46e5; color: #fff; padding: 6px 8px; font-size: 10px; text-align: left; }
        table.data td { border: 1px solid #e2e8f0; padding: 5px 8px; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f8fafc; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 6px; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 9px; }
        .text-right { text-align: right !important; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Physio Gym</h1>
        <div class="sub">Jl. Mangga No.10a Kel. Jadirejo Kec. Sukajadi Kota Pekanbaru &bull; 085311716767</div>
    </div>

    <h2>{{ $title }}</h2>
    <table class="meta">
        <tr>
            <td><b>Periode:</b> {{ $periodLabel }}</td>
            <td class="text-right"><b>Dicetak:</b> {{ $generatedAt->format('d M Y H:i') }}</td>
        </tr>
    </table>

    @if(! empty($summary))
    <table class="summary">
        <tr>
            @foreach($summary as $item)
            <td>
                <span class="num">
                    @if(! empty($item['currency']))Rp {{ number_format($item['value'], 0, ',', '.') }}
                    @else{{ $item['value'] }} {{ $item['suffix'] }}@endif
                </span>
                <span class="lbl">{{ $item['label'] }}</span>
            </td>
            @endforeach
        </tr>
    </table>
    @endif

    <table class="data">
        <thead>
            <tr>
                @foreach($columns as $col)
                <th>{{ $col['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                @foreach($columns as $col)
                <td>{{ $row[$col['key']] }}</td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($columns) }}" style="text-align:center; color:#94a3b8;">Tidak ada data pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dokumen ini dihasilkan otomatis oleh Physio Gym Management System &copy; {{ now()->year }}</div>
</body>
</html>
