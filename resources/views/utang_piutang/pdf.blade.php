<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Utang & Piutang</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: right; }
        th { background: #f2f2f2; text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Utang & Piutang<br>Bulan {{ $bulan }} Tahun {{ $tahun }}</h2>

    <h4>Piutang</h4>
    <table>
        <thead>
            <tr>
                <th>Nama Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($piutang as $p)
            <tr>
                <td style="text-align:left">{{ $p->nama_akun }}</td>
                <td>Rp. {{ number_format($p->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th>Total Piutang</th>
                <th>Rp. {{ number_format($totalPiutang, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <h4>Utang</h4>
    <table>
        <thead>
            <tr>
                <th>Nama Akun</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($utang as $u)
            <tr>
                <td style="text-align:left">{{ $u->nama_akun }}</td>
                <td>Rp. {{ number_format($u->saldo, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th>Total Utang</th>
                <th>Rp. {{ number_format($totalUtang, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>
