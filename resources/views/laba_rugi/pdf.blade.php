<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi - {{ $bulan }}/{{ $tahun }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        table th {
            background: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background: #eaeaea;
        }
    </style>
</head>
<body>
    <h2>LAPORAN LABA RUGI</h2>
    <h4>Periode: {{ $bulan }} / {{ $tahun }}</h4>

    <h4 style="margin-top: 25px;">Pendapatan</h4>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Akun</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendapatanList as $p)
                <tr>
                    <td>{{ $p->kode }}</td>
                    <td>{{ $p->nama }}</td>
                    <td class="text-right">Rp. {{ number_format($p->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data pendapatan</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="2">Total Pendapatan</td>
                <td class="text-right">Rp. {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h4 style="margin-top: 25px;">Beban</h4>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Akun</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bebanList as $b)
                <tr>
                    <td>{{ $b->kode }}</td>
                    <td>{{ $b->nama }}</td>
                    <td class="text-right">Rp. {{ number_format($b->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data beban</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="2">Total Beban</td>
                <td class="text-right">Rp. {{ number_format($totalBeban, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin-top: 30px; text-align: right;">
        Laba Bersih: Rp. {{ number_format($labaBersih, 0, ',', '.') }}
    </h3>
</body>
</html>
