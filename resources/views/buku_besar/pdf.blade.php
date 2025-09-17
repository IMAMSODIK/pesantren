<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Besar {{ $bulan }}/{{ $tahun }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .bg-light { background: #f8f8f8; }
    </style>
</head>
<body>
    <h3>Laporan Buku Besar <br> Periode: {{ $bulan }}/{{ $tahun }}</h3>

    @forelse($bukuBesar as $akun => $data)
        <h4>{{ $akun }}</h4>
        <table>
            <thead>
                <tr>
                    <th width="80">Tanggal</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Debit</th>
                    <th class="text-center">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['rows'] as $row)
                    <tr>
                        <td class="text-center">{{ $row['tanggal'] }}</td>
                        <td>{{ $row['deskripsi'] }}</td>
                        <td class="text-end">{{ $row['debit'] }}</td>
                        <td class="text-end">{{ $row['kredit'] }}</td>
                    </tr>
                @endforeach
                <tr class="fw-bold bg-light">
                    <td colspan="2" class="text-center">TOTAL</td>
                    <td class="text-end">Rp. {{ number_format($data['totalDebit'], 2, ',', '.') }}</td>
                    <td class="text-end">Rp. {{ number_format($data['totalKredit'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @empty
        <p class="text-center">Tidak ada transaksi pada periode ini.</p>
    @endforelse
</body>
</html>
