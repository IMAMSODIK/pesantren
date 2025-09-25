<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Neraca Saldo</title>
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

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Laporan Neraca Saldo</h3>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</p>

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
                    <td colspan="4" class="text-center">Tidak ada saldo pada periode ini.</td>
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
