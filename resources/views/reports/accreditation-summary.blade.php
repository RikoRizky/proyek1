<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .muted { color: #64748b; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #f1f5f9; }
        .unit { margin-top: 20px; page-break-inside: avoid; }
        .total { font-weight: bold; margin-top: 8px; }
    </style>
</head>
<body>
    <h1>Laporan ringkasan akreditasi</h1>
    <p class="muted">Dihasilkan: {{ $generatedAt->translatedFormat('d F Y H:i') }}</p>

    @foreach ($summaries as $block)
        <div class="unit">
            <h2 style="font-size: 14px;">{{ $block['user']->name }}</h2>
            <p class="muted">{{ $block['user']->email }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Modul</th>
                        <th>Bobot (%)</th>
                        <th>Rata skor</th>
                        <th>Kontribusi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($block['modules'] as $row)
                        <tr>
                            <td>{{ $row['module']->name }}</td>
                            <td>{{ number_format($row['weight'], 2) }}</td>
                            <td>{{ $row['average'] !== null ? number_format($row['average'], 2) : '—' }}</td>
                            <td>{{ $row['contribution'] !== null ? number_format($row['contribution'], 2) : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="total">Total tertimbang (skala 1–4): {{ number_format($block['weightedTotal'], 2) }}</p>
        </div>
    @endforeach
</body>
</html>
