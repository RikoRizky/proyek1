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
    <h1>Laporan ringkasan unggahan akreditasi</h1>
    <p class="muted">Dihasilkan: {{ $generatedAt->translatedFormat('d F Y H:i') }}</p>

    @foreach ($summaries as $block)
        <div class="unit">
            <h2 style="font-size: 14px;">{{ $block['user']->name }}</h2>
            <p class="muted">{{ $block['user']->email }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Modul</th>
                        <th>Terunggah</th>
                        <th>Total persyaratan</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($block['modules'] as $row)
                        <tr>
                            <td>{{ $row['module']->name }}</td>
                            <td>{{ $row['uploaded'] }}</td>
                            <td>{{ $row['total'] }}</td>
                            <td>{{ $row['progressPercent'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="total">Total progress: {{ $block['uploadedCount'] }} / {{ $block['totalRequirements'] }} ({{ $block['progressPercent'] }}%)</p>
        </div>
    @endforeach
</body>
</html>
