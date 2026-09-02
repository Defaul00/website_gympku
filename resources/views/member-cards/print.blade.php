@php
    $member = $card->user;
    $membership = $card->membership;
    $bars = [];
    foreach (str_split(preg_replace('/[^A-Z0-9]/', '', $card->card_number)) as $ch) {
        $bars[] = (ord($ch) % 3) + 1;
        $bars[] = 1;
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu Member - {{ $member->name }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #e2e8f0; font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        a { color: inherit; text-decoration: none; }

        .toolbar { position: sticky; top: 0; z-index: 20; display: flex; align-items: center; justify-content: center; gap: 10px; background: #0f172a; padding: 12px 16px; }
        .toolbar .btn { display: inline-flex; align-items: center; gap: 8px; border: 0; border-radius: 12px; padding: 10px 18px; font: 600 14px/1 'Plus Jakarta Sans', sans-serif; cursor: pointer; }
        .btn--primary { background: #4f46e5; color: #fff; box-shadow: 0 6px 16px rgb(79 70 229 / .35); }
        .btn--primary:hover { background: #4338ca; }
        .btn--ghost { background: transparent; color: #cbd5e1; border: 1px solid #334155 !important; }
        .btn--ghost:hover { background: #1e293b; color: #fff; }
        .toolbar .hint { color: #94a3b8; font-size: 12px; }

        .sheet { display: flex; flex-direction: column; align-items: center; gap: 14px; padding: 28px 16px; }

        .gym-card {
            position: relative; width: 85.6mm; height: 54mm; border-radius: 3.5mm;
            overflow: hidden; flex-shrink: 0;
            box-shadow: 0 16px 40px rgb(15 23 42 / .25);
            -webkit-print-color-adjust: exact; print-color-adjust: exact;
            break-inside: avoid;
        }

        .gym-card--front { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 55%, #a21caf 100%); color: #fff; }
        .gym-card--back { background: #0f172a; color: #fff; }

        .gym-card .glow { position: absolute; border-radius: 9999px; pointer-events: none; }
        .gym-card .glow--tl { top: -26mm; left: -18mm; width: 52mm; height: 52mm; background: rgb(255 255 255 / .10); }
        .gym-card .glow--br { bottom: -30mm; right: -14mm; width: 64mm; height: 64mm; background: rgb(255 255 255 / .07); }

        .card-inner { position: relative; z-index: 1; display: flex; height: 100%; padding: 5mm 6mm; flex-direction: column; justify-content: space-between; }

        .brand { display: flex; align-items: center; gap: 3mm; }
        .brand .logo { display: flex; align-items: center; justify-content: center; width: 9mm; height: 9mm; border-radius: 2.6mm; background: #fff; color: #4f46e5; font: 800 4.6mm 'Barlow Condensed', sans-serif; line-height: 1; }
        .brand .name { font: 800 6mm/1 'Barlow Condensed', sans-serif; letter-spacing: .4mm; text-transform: uppercase; }
        .brand .tag { font-size: 2.4mm; font-weight: 600; letter-spacing: 1.4mm; text-transform: uppercase; color: rgb(255 255 255 / .65); }

        .member-row { display: flex; align-items: center; justify-content: space-between; gap: 4mm; }
        .member-row .avatar { display: flex; align-items: center; justify-content: center; width: 15mm; height: 15mm; border-radius: 50%; background: rgb(255 255 255 / .16); border: .6mm solid rgb(255 255 255 / .35); font: 800 6.5mm 'Barlow Condensed', sans-serif; }
        .member-row .label { font-size: 2.2mm; font-weight: 600; letter-spacing: 1mm; text-transform: uppercase; color: rgb(255 255 255 / .6); }
        .member-row .mname { margin-top: .6mm; font: 800 6mm/1 'Barlow Condensed', sans-serif; letter-spacing: .2mm; text-transform: uppercase; }
        .member-row .pack { display: inline-flex; align-items: center; margin-top: 1.6mm; border-radius: 9999px; background: rgb(255 255 255 / .18); padding: 1.2mm 3mm; font: 700 3mm/1 'Plus Jakarta Sans', sans-serif; }

        .foot { display: flex; align-items: flex-end; justify-content: space-between; gap: 3mm; }
        .foot .num-label { font-size: 2.1mm; font-weight: 600; letter-spacing: .8mm; text-transform: uppercase; color: rgb(255 255 255 / .6); }
        .foot .num { margin-top: .6mm; font: 700 3.4mm/1 'Plus Jakarta Sans', monospace; letter-spacing: .5mm; }
        .foot .valid { text-align: right; }
        .foot .valid .d { font: 700 3.2mm/1 'Plus Jakarta Sans', sans-serif; }
        .chip { width: 8mm; height: 6mm; border-radius: 1.2mm; background: linear-gradient(135deg, #fcd34d, #f59e0b); box-shadow: inset 0 0 0 .4mm rgb(0 0 0 / .15); }

        .card--back-layout { display: flex; height: 100%; padding: 4.5mm 6mm; flex-direction: column; justify-content: space-between; }
        .back-strip { height: 7mm; margin: 0 -6mm; background: #000; }
        .back-meta { display: flex; align-items: center; justify-content: space-between; gap: 4mm; }
        .back-terms { font-size: 2.3mm; line-height: 1.45; color: rgb(255 255 255 / .62); }
        .back-terms strong { color: rgb(255 255 255 / .9); }

        .barcode { display: flex; gap: .6px; height: 7mm; align-items: stretch; margin-top: 2mm; }
        .barcode i { display: block; width: 1px; background: currentColor; }

        @media print {
            body { background: #fff; }
            .toolbar { display: none !important; }
            .sheet { padding: 0; gap: 0; }
            .gym-card { box-shadow: none; margin: 0; page-break-inside: avoid; }
            @page { size: A4; margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <button class="btn btn--ghost" onclick="window.close()">Tutup</button>
        <button class="btn btn--primary" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V3h12v6"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak Kartu
        </button>
        <span class="hint">Pilih ukuran A4, matikan "Header & Footer" (opsional).</span>
    </div>

    <main class="sheet">
        <!-- Depan kartu -->
        <div class="gym-card gym-card--front">
            <span class="glow glow--tl"></span>
            <span class="glow glow--br"></span>
            <div class="card-inner">
                <div class="brand">
                    <span class="logo">PG</span>
                    <span>
                        <span class="name" style="display:block">Physio Gym</span>
                        <span class="tag">Membership</span>
                    </span>
                </div>

                <div class="member-row">
                    <div>
                        <div class="label">Nama / Name</div>
                        <div class="mname">{{ strtoupper($member->name) }}</div>
                        <span class="pack">{{ $membership?->name ?? 'Membership' }}</span>
                    </div>
                    <span class="avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                </div>

                <div class="foot">
                    <div class="chip"></div>
                    <div>
                        <div class="num-label">No. Kartu</div>
                        <div class="num">{{ $card->card_number }}</div>
                    </div>
                    <div class="valid">
                        <div class="num-label">Berlaku s.d.</div>
                        <div class="d">{{ $card->end_date->translatedFormat('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Belakang kartu -->
        <div class="gym-card gym-card--back">
            <div class="card--back-layout">
                <div class="back-strip"></div>
                <div class="back-meta">
                    <div style="flex:1">
                        <div style="font:800 4mm 'Barlow Condensed',sans-serif;letter-spacing:.3mm;text-transform:uppercase">Physio Gym</div>
                        <div style="font-size:2.2mm;color:rgb(255 255 255/.6);margin-top:.8mm">Jl. Mangga No.10a, Pekanbaru &middot; 0853-1171-6767</div>
                    </div>
                    <div class="barcode">
                        @foreach($bars as $w)<i style="width:{{ $w }}px"></i>@endforeach
                    </div>
                </div>
                <div class="back-meta">
                    <p class="back-terms">
                        <strong>No. {{ $card->card_number }}</strong><br>
                        Kartu ini berlaku selama masa keanggotaan. Wajib dibawa setiap kali check-in. Tidak dapat dipindahtangankan. Harap beri tahu pihak gym jika kartu hilang.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 350);
        });
    </script>
</body>
</html>
