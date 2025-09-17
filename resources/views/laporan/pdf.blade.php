<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Umum</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h3>Laporan Jurnal Umum</h3>
    <p>Bulan: {{ $bulan }} - Tahun: {{ $tahun }}</p>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Akun</th>
                <th class="text-end">Debit</th>
                <th class="text-end">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['tanggal'] }}</td>
                    <td>{{ $row['deskripsi'] }}</td>
                    <td>{{ $row['akun'] }}</td>
                    <td class="text-end">{{ $row['debit'] }}</td>
                    <td class="text-end">{{ $row['kredit'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada transaksi di periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
