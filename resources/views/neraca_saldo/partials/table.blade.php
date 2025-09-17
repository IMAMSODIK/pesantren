<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Neraca Saldo</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #f2f2f2; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Laporan Neraca Saldo</h3>
    <p style="text-align: center;">Periode: {{ $bulan }}/{{ $tahun }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">Kode Akun</th>
                <th class="text-center">Nama Akun</th>
                <th class="text-center">Debit</th>
                <th class="text-center">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td class="text-center">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="text-end">
                        {{ $row['debit'] > 0 ? 'Rp. ' . number_format($row['debit'], 2, ',', '.') : '-' }}
                    </td>
                    <td class="text-end">
                        {{ $row['kredit'] > 0 ? 'Rp. ' . number_format($row['kredit'], 2, ',', '.') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada saldo pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($data) > 0)
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><b>TOTAL</b></td>
                    <td class="text-end"><b>Rp. {{ number_format(collect($data)->sum('debit'), 2, ',', '.') }}</b></td>
                    <td class="text-end"><b>Rp. {{ number_format(collect($data)->sum('kredit'), 2, ',', '.') }}</b></td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
