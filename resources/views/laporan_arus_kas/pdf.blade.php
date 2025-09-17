<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $pageTitle }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f0f0f0; text-align: left; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">{{ $pageTitle }}</h2>
    <p style="text-align: center;">Periode: {{ $bulan }}/{{ $tahun }}</p>

    <table>
        <thead>
            <tr>
                <th>Aktivitas</th>
                <th class="text-end">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Arus Kas dari Aktivitas Operasi</strong></td>
                <td class="text-end">Rp. {{ number_format($kasDariOperasi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Arus Kas dari Aktivitas Investasi</strong></td>
                <td class="text-end">Rp. {{ number_format($kasDariInvestasi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Arus Kas dari Aktivitas Pendanaan</strong></td>
                <td class="text-end">Rp. {{ number_format($kasDariPendanaan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Kas Bersih</strong></td>
                <td class="text-end"><strong>Rp. {{ number_format($totalKasBersih, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
